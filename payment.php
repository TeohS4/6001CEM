<?php
include 'connect.php';
session_start();

if (isset($_POST['complete_payment'])) {
    $user_id = $_POST['user_id'];
    $total_price = $_POST['total_price'];
    $customer_address = mysqli_real_escape_string($db, $_POST['customer_address']);
    $payment_method = $_POST['payment_method'];
    $packaging_options = $_POST['packaging'];

    // Fetch the list of items ordered
    $items_query = "SELECT p.product_name, c.quantity FROM cart c
                    JOIN products p ON c.product_id = p.product_id
                    WHERE c.user_id = '$user_id'";

    $items_result = mysqli_query($db, $items_query);

    $ordered_items = array();

    if ($items_result && mysqli_num_rows($items_result) > 0) {
        while ($item_data = mysqli_fetch_assoc($items_result)) {
            $product_name = $item_data['product_name'];
            $quantity = $item_data['quantity'];
            $ordered_items[] = "$product_name, Quantity: $quantity";
        }
    }

    // Serialize the ordered items to save in the order_items column
    $order_items = json_encode($ordered_items);

    // Insert the order into the orders table
    $insert_sql = "INSERT INTO orders (user_id, address, payment_method, amount, order_date, packaging_options, order_items)
                   VALUES ('$user_id', '$customer_address', '$payment_method', '$total_price', NOW(), '$packaging_options', '$order_items')";

    if (mysqli_query($db, $insert_sql)) {
        // Successfully inserted
        $order_id = mysqli_insert_id($db);

        // EMPTY THE USER CART
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
        if (mysqli_query($db, $clear_cart_sql)) {
            // Cart Deleted
        } else {
            echo "Error clearing the cart: " . mysqli_error($db);
        }
        header('location: receipt.php?order_id=' . $order_id);
        exit();
    } else {
        echo "Error inserting order: " . mysqli_error($db);
    }

    mysqli_close($db);
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Products</title>
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
</head>
<style>
    .form-group {
        margin-bottom: 15px;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
    }

    label {
        font-size: 18px;
    }

    /* Custom styles for the image options */
    .image-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .image-option input[type="radio"],
    .image-option input[type="checkbox"] {
        margin-right: 10px;
    }

    .image-option img {
        max-width: 50px;
        /* Adjust the image size as needed */
    }
</style>

<body>
    <?php include 'header.php'; ?>

    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Check Out</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <!--::chefs_part start::-->
    <!-- food_menu start-->
    <br>
    <?php
    $user_id = $_POST['user_id'];
    $cart_id = $_POST['cart_id'];
    $total_price = $_POST['total_price'];
    ?>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="section_tittle">
                    <h2>Check Out</h2>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <form method="POST" action="">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                    
                    <h3>Enter Billing Details</h3>
                    <div class="form-group">
                        <label for="customer_address">Address:</label>
                        <input type="text" class="form-control" id="customer_address" name="customer_address" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal">TouchNGo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="packaging">Choose Your Sustainable Packaging:</label>
                        <div class="image-option">
                            <input type="radio" id="bamboo" name="packaging" value="Bamboo Packaging">
                            <label for="bamboo">
                                <img src="img/bamboo.jpg" alt="Bamboo Packaging">
                                Bamboo Packaging
                            </label>
                        </div>
                        <div class="image-option">
                            <input type="radio" id="mushroom" name="packaging" value="Mushroom Packaging">
                            <label for="mushroom">
                                <img src="img/mushroom.jpg" alt="Mushroom Packaging">
                                Mushroom Packaging
                            </label>
                        </div>
                        <div class="image-option">
                            <input type="radio" id="compostable" name="packaging" value="Compostable Packaging">
                            <label for="compostable">
                                <img src="img/compostable.jpg" alt="Compostable Packaging">
                                Compostable Packaging
                            </label>
                        </div>
                    </div>

                    <button type="submit" name="complete_payment" class="btn_4" style="border: none; width:100%;">Complete Payment</button>
                </form>
            </div>

            <div class="col-lg-4 col-md-6">
                <?php
                $user_id = $_POST['user_id'];
                $cart_id = $_POST['cart_id'];
                $total_price = $_POST['total_price'];

                // Retrieve order details from the database based on user_id and cart_id
                $order_sql = "SELECT products.product_name, products.product_price, cart.quantity
                                FROM cart
                                INNER JOIN products ON cart.product_id = products.product_id
                                WHERE cart.user_id = '$user_id'";

                $order_result = mysqli_query($db, $order_sql);

                if (!$order_result) {
                    // Handle the database query error
                    echo "Error fetching order details: " . mysqli_error($db);
                } else {
                    // Initialize subtotal
                    $subtotal = 0;

                    // Display order information
                    echo '<div class="container mb-4">';
                    echo '<div style="background-color: #f7f7f7; padding: 20px; border-radius: 10px;">'; // Added this div
                    echo '<h2 class="mb-4">Your Order Details</h2>';
                    echo '<table class="table">';
                    echo '<thead><tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr></thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_assoc($order_result)) {
                        echo '<tr>';
                        echo '<td>' . $row['product_name'] . '</td>';
                        echo '<td>RM ' . number_format($row['product_price'], 2) . '</td>';
                        echo '<td>' . $row['quantity'] . '</td>';
                        echo '</tr>';
                        // Calculate subtotal
                        $subtotal += ($row['product_price'] * $row['quantity']);
                    }
                    echo '</tbody>';
                    echo '</table>';

                    // Display the subtotal
                    echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Subtotal:</strong> <span style="float: right;">RM ' . number_format($subtotal, 2) . '</span></p>';

                    // Shipping fee
                    $shipping_fee = 5.00;

                    // Calculate total
                    $total_price = $subtotal + $shipping_fee;

                    // Display the shipping fee
                    echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Shipping Fee:</strong> <span style="float: right;">RM ' . number_format($shipping_fee, 2) . '</span></p>';

                    echo '<hr style="border-color: #ccc;">';
                    // Display the total price with shipping fee in bold
                    echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Total Price:</strong> <span style="float: right;">RM ' . number_format($total_price, 2) . '</span></p>';

                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <!-- end row -->
    </div>

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