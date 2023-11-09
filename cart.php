<?php
include 'connect.php';
session_start();

// Check if the user is logged in and get their user_id from session
if (!isset($_SESSION['user_id'])) {
    echo '<script>
    alert("Please login to continue");
    window.location.href = "index.php"; 
    </script>';
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart data for the user from the database
$sql = "SELECT c.cart_id, p.product_id, p.product_name, p.product_price, p.product_image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0;

// Quantity Adjustment
if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    // Sanitize and update the quantity in the database
    $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $new_quantity, $cart_id);

    if ($stmt->execute()) {
        // Update successful

        // Calculate and update the stock in the database
        $product_id_sql = "SELECT product_id FROM cart WHERE cart_id = '$cart_id'";
        $product_id_result = mysqli_query($db, $product_id_sql);

        if ($product_id_result) {
            $product_id_row = mysqli_fetch_assoc($product_id_result);
            $product_id = $product_id_row['product_id'];

            // Calculate the change in stock
            $stock_change = $new_quantity - $quantity; // Assuming $quantity is the old quantity

            // Update product stock by subtracting the change in stock
            $update_stock_sql = "UPDATE products SET stock = stock - $stock_change WHERE product_id = '$product_id'";
            mysqli_query($db, $update_stock_sql);
        }

        // Redirect back to the cart page
        header('Location: cart.php');
        exit();
    } else {
        // Handle update error
        echo 'Error updating quantity: ' . $db->error;
    }

    $stmt->close();
    $db->close();
}

// Delete Btn
if (isset($_GET['delete']) && isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];

    // Retrieve the cart item details
    $cart_sql = "SELECT product_id, quantity FROM cart WHERE cart_id = ?";
    $stmt = $db->prepare($cart_sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->bind_result($product_id, $quantity);

    if ($stmt->fetch()) {
        $stmt->close();
        // Delete the item from the cart
        $delete_sql = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $db->prepare($delete_sql);
        $stmt->bind_param("i", $cart_id);

        if ($stmt->execute()) {
            // Adjust the stock by adding the quantity back to the product
            $update_stock_sql = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
            $stmt = $db->prepare($update_stock_sql);
            $stmt->bind_param("ii", $quantity, $product_id);

            if ($stmt->execute()) {
                // Redirect to cart.php with a success message
                header('Location: cart.php?delete_success=true');
                exit();
            } else {
                echo "Error updating product stock";
            }
        } else {
            echo "Error deleting from cart";
        }
    } else {
        echo "Error retrieving cart item details";
    }

    $stmt->close();
    $db->close();
}

// Clear button
if (isset($_POST['clear_cart']) && $_POST['clear_cart'] === 'true') {
    // Handle the "Clear All" button click here
    if (isset($user_id)) {
        // Code to reset stock values
        $reset_stock_sql = "UPDATE products p
            JOIN cart c ON p.product_id = c.product_id
            SET p.stock = p.stock + c.quantity
            WHERE c.user_id = ?";
        $reset_stock_stmt = $db->prepare($reset_stock_sql);
        $reset_stock_stmt->bind_param("i", $user_id);
        if ($reset_stock_stmt->execute()) {
            // Clear the cart
            $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
            $clear_cart_stmt = $db->prepare($clear_cart_sql);
            $clear_cart_stmt->bind_param("i", $user_id);
            if ($clear_cart_stmt->execute()) {
                // Redirect back to cart.php or display a success message
                header('Location: cart.php');
                exit();
            } else {
                echo "Error clearing the cart: " . $db->error;
            }
        } else {
            echo "Error resetting product stock: " . $reset_stock_stmt->error;
        }
        $reset_stock_stmt->close();
    } else {
        echo "User ID is not set.";
    }
}
?>

<!Doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Shopping Cart</title>
    <link rel="icon" href="pictures/admin logo.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="css/flaticon.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/gijgo.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/all.css">
    <!-- Icons -->
    <script src="https://kit.fontawesome.com/a84d485a7a.js" crossorigin="anonymous"></script>
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .button-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        td {
            font-size: 18px;
        }

        body {
            font-family: Arial, sans-serif !important;
        }

        /* Table Styles */
        .table-container {
            padding-left: 20px;
            padding-right: 20px;
        }

        .table.table-bordered {
            border-left: none;
            border-right: none;
        }

        .table-bordered th,
        .table-bordered td {
            border: none;
            padding: 20px;
        }

        .rounded-row {
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-left: none;
            border-right: none;
            background-color: white;
        }

        .table-bordered tr {
            margin-bottom: 20px;
        }

        thead tr {
            background-color: white;
            font-size: 18px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Shopping Cart</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->
    <!-- Cart Start -->
    <section>
        <div class="container mt-3">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="section_tittle">
                        <h2>My Cart</h2>
                    </div>
                </div>

                <div class="table-responsive mb-2">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                    ?>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <?php
                                        $cart_id = $row['cart_id'];
                                        $product_id = $row['product_id'];
                                        $product_name = $row['product_name'];
                                        $product_image = $row['product_image'];
                                        $product_price = $row['product_price'];
                                        $quantity = $row['quantity'];
                                        $totalProductPrice = $product_price * $quantity;
                                        $totalPrice += $totalProductPrice;
                                        ?>

                                        <tr class="rounded-row">
                                            <td><img src="uploads/<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" width="100"></td>
                                            <td><?php echo $product_name; ?></td>
                                            <td style="font-weight: bold;">RM <?php echo number_format($product_price, 2); ?></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="hidden" id="stock-<?php echo $cart_id; ?>" value="<?php echo $product_stock; ?>">
                                                    <button class="btn btn-outline-secondary" onclick="adjustQuantity(<?php echo $cart_id; ?>, -1, <?php echo $product_price; ?>)">-</button>
                                                    <span class="input-group-text" id="quantity-<?php echo $cart_id; ?>"><?php echo $quantity; ?></span>
                                                    <button class="btn btn-outline-secondary" onclick="adjustQuantity(<?php echo $cart_id; ?>, 1, <?php echo $product_price; ?>)">+</button>
                                                </div>
                                            </td>
                                            <td id="total-price-<?php echo $cart_id; ?>" style="font-weight: bold;">RM <?php echo number_format($totalProductPrice, 2); ?></td>
                                            <td>
                                                <a href="cart.php?delete=true&cart_id=<?php echo $cart_id; ?>" class="btn btn-danger rounded-circle" onclick="return confirmDelete();">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>


                            <div class="row">
                                <div class="col-lg-7">
                                    <!-- Clear All button -->
                                    <form method="POST" action="" style="float: left;">
                                        <input type="hidden" name="clear_cart" value="true">
                                        <button type="submit" class="btn_4" style="border: none;">
                                            <i class="fas fa-trash"></i> Clear All
                                        </button>
                                    </form>
                                    <!-- Continue Shop button -->
                                    <a href="products.php" class="btn_4" style="float: left; margin-left: 10px;">
                                        <i class="fa-solid fa-circle-chevron-left"></i> Continue Shopping
                                    </a>
                                </div>
                                <div class="col-lg-5">
                                    <!-- Light Grey Box -->
                                    <div style="background-color: #ededed; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                                        <!-- Total Price -->
                                        <h3 style="font-family: Arial; font-weight:bold">Cart Total</h3>
                                        <br>
                                        <h4 style="font-family: Arial; font-weight: bold;">Total Price: <span class="fw-bold text-primary" id="total-price" style="color: black;float:right;">RM <?php echo number_format($totalPrice, 2); ?></span></span></h3>
                                            <!-- Check Out button -->
                                            <div style="text-align: center;">
                                                <hr style="border: 1px solid #ccc; margin: 0 0 20px;">
                                                <form action="payment.php" method="POST">
                                                    <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
                                                    <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                                    <button type="submit" class="btn_4" style="border: none;width: 100%;">
                                                        <i class="fas fa-shopping-cart"></i> Check Out
                                                    </button>
                                                </form>
                                            </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- End of table-container -->
                    <?php } else {
                        echo ' <div class="text-center">
                    <img src="img/speech.gif" alt="Empty Cart" width="360px" height="360px">
                    <h3>Your shopping cart is empty! <a href="products.php">Add Products Now</a></h3>
                    <br>
                </div>';
                    } ?>
                </div>
                <!-- Cart End -->
                <div class="col-lg-12">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active single-member" id="Special" role="tabpanel" aria-labelledby="Special-tab">
                            <div class="row">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Adjust QUANTITY FUNCTION -->
    <script>
        function adjustQuantity(cartId, change, productPrice) {
            const quantityElement = document.getElementById(`quantity-${cartId}`);
            const totalPriceElement = document.getElementById(`total-price-${cartId}`);
            const totalElement = document.getElementById('total-price');
            let quantity = parseInt(quantityElement.textContent);
            let totalPrice = parseFloat(totalPriceElement.textContent.replace('RM ', '').replace(',', ''));
            let total = parseFloat(totalElement.textContent.replace('RM ', '').replace(',', ''));

            quantity += change;
            if (quantity >= 1) {
                quantityElement.textContent = quantity;
                totalPrice = quantity * productPrice; // Calculate the new total price
                totalPriceElement.textContent = 'RM ' + totalPrice.toFixed(2);
                total += change * productPrice; // Update the total price
                totalElement.textContent = 'RM ' + total.toFixed(2);

                // Send the updated quantity to the server using a form submission
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = ''; // Replace with your PHP script URL

                const cartIdInput = document.createElement('input');
                cartIdInput.type = 'hidden';
                cartIdInput.name = 'cart_id';
                cartIdInput.value = cartId;
                form.appendChild(cartIdInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = 'quantity';
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                document.body.appendChild(form);
                form.submit();
            }
        }


        function confirmDelete() {
            if (confirm("Are you sure you want to delete this item?")) {
                return true;
            } else {
                return false;
            }
        }
    </script>

    <?php 
    include 'footer.html';
    ?>

    <!-- jquery plugins here-->
    <!-- jquery -->
    <script src="js/jquery-1.12.1.min.js"></script>
    <!-- popper js -->
    <script src="js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- easing js -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- swiper js -->
    <script src="js/swiper.min.js"></script>
    <!-- swiper js -->
    <script src="js/masonry.pkgd.js"></script>
    <!-- particles js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- swiper js -->
    <script src="js/slick.min.js"></script>
    <script src="js/gijgo.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</body>

</html>