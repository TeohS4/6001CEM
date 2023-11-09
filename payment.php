<?php
include 'connect.php';
session_start();
$user_id = $_SESSION['user_id'];

// Insert new card details to database
if (isset($_POST['card_name'], $_POST['card_num'], $_POST['CVV'], $_POST['expiry'])) {

    $card_name = mysqli_real_escape_string($db, $_POST['card_name']);
    $card_num = mysqli_real_escape_string($db, $_POST['card_num']);
    $cvv = mysqli_real_escape_string($db, $_POST['CVV']);
    $expiry = mysqli_real_escape_string($db, $_POST['expiry']);

    // Insert the new card information into the database
    $query = "INSERT INTO cards (user_id, card_name, card_num, CVV, expiry) VALUES ($user_id, '$card_name', '$card_num', '$cvv', '$expiry')";
    if (mysqli_query($db, $query)) {
        // Card added successfully
        header('Location: payment.php');
        exit();
    } else {
        // Handle database error
        echo "Error: " . mysqli_error($db);
    }
}

if (isset($_POST['save_card'])) {
    // Get the form data
    $card_name = $_POST['card_name'];
    $card_num = $_POST['card_num'];
    $cvv = $_POST['cvv'];
    $expiry = $_POST['expiry'];

    // Hash card number (except the last 4 characters) using BCRYPT
    $hashed_card_num = substr($card_num, 0, -4) . password_hash(substr($card_num, -4), PASSWORD_BCRYPT);

    // Hash CVV using BCRYPT
    $hashed_cvv = password_hash($cvv, PASSWORD_BCRYPT);

    $insert_card = "INSERT INTO cards (user_id, card_name, card_num, cvv, expiry) VALUES ('$user_id', '$card_name', '$hashed_card_num', '$hashed_cvv', '$expiry')";

    if (mysqli_query($db, $insert_card)) {
        $successMessage = "Card successfully saved!";
    } else {
        $errorMessage = "Error saving the card: " . mysqli_error($db);
    }
}

if (isset($_POST['delete_card'])) {
    $card_id_to_delete = $_POST['delete_card'];

    // Perform SQL deletion using $card_id_to_delete
    $delete_query = "DELETE FROM cards WHERE card_id = $card_id_to_delete";

    // Execute the deletion query
    if (mysqli_query($db, $delete_query)) {
        $message = 'Card deleted';
    } else {
        $message = 'Error deleting';
    }
}

// if user click complete payment insert it to orders and check for empty fields
if (isset($_POST['complete_payment'])) {
    $user_id = $_POST['user_id'];
    $cart_id = $_POST['cart_id'];
    $total_price = $_POST['total_price'];
    $address = $_POST['customer_address'];
    $payment_method = $_POST['payment_method'];

    $errorMessage = "";

    if (empty($address)) {
        $errorMessage = "Address is required.";
    }

    if (empty($payment_method)) {
        $errorMessage = "Payment Method is required.";
    }

    // Check if "packaging" is set and not empty
    if (isset($_POST['packaging']) && !empty($_POST['packaging'])) {
        $packaging = $_POST['packaging'];
    } else {
        $errorMessage = "Packaging Option is required.";
    }

    if ($payment_method === 'Credit Card') {
        // Check if a credit card is selected
        if (empty($_POST['selected_card'])) {
            $errorMessage = "Please select a credit card for payment.";
        }
    }

    if (empty($errorMessage)) {
        // Insert order information into the 'orders' table
        $insertOrderSQL = "INSERT INTO orders (user_id, address, payment_method, amount, order_date, packaging_options) 
                            VALUES ('$user_id', '$address', '$payment_method', '$total_price', NOW(), '$packaging')";

        if (mysqli_query($db, $insertOrderSQL)) {
            $order_id = mysqli_insert_id($db);

            $cartItemsSQL = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
            $cartItemsResult = mysqli_query($db, $cartItemsSQL);

            if ($cartItemsResult) {
                while ($cartItem = mysqli_fetch_assoc($cartItemsResult)) {
                    $product_id = $cartItem['product_id'];
                    $qty = $cartItem['quantity'];

                    $insertOrderItemSQL = "INSERT INTO order_items (orders_id, product_id, qty) 
                                          VALUES ('$order_id', '$product_id', '$qty')";

                    mysqli_query($db, $insertOrderItemSQL);
                }

                $clearCartSQL = "DELETE FROM cart WHERE user_id = '$user_id'";
                mysqli_query($db, $clearCartSQL);

                header('Location: receipt.php?order_id=' . $order_id);
                exit();
            } else {
                echo "Error retrieving cart items: " . mysqli_error($db);
            }
        } else {
            echo "Error placing the order: " . mysqli_error($db);
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payment</title>
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
    <!-- Modal for adding a new card -->
    <div class="modal fade" id="addCardModal" tabindex="-1" role="dialog" aria-labelledby="addCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="top: 10%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addCardModalLabel" style="font-family: 'Arial', sans-serif;">Add New Card</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding a new card -->
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="card_name">Card Name</label>
                            <input type="text" class="form-control" id="card_name" name="card_name" placeholder="Enter Card Name">
                        </div>
                        <div class="form-group">
                            <label for="card_num">Card Number</label>
                            <input type="text" class="form-control" id="card_num" name="card_num" placeholder="Enter 16 Digit Number" maxlength="16">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="3 Digit" maxlength="3">
                        </div>
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" class="form-control" id="expiry" name="expiry" placeholder="(MM/YY)">
                        </div>
                        <button type="submit" name="save_card" class="btn btn-primary">Save Card</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end form -->
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="section_tittle">
                    <h2>Check Out</h2>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <!-- If theres error message display it -->
                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                    <h3>Enter Billing Details</h3>
                    <div class="form-group">
                        <label for="customer_address">Address:</label>
                        <input type="text" class="form-control" id="customer_address" name="customer_address">
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select class="form-control" id="payment_method" name="payment_method">
                            <option value="Credit Card">Credit Card</option>
                            <option value="TouchNGo">TouchN'GO</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    <div id="card_section">
                        <?php
                        // Display all card
                        $query = "SELECT card_id, card_name, card_num, expiry FROM cards WHERE user_id = $user_id";
                        $result = mysqli_query($db, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Display saved card details
                                echo '<label for="card' . $row['card_id'] . '">';
                                echo '<div class="card rounded mb-2">';
                                echo '<div class="card-body">';
                                echo '<div class="d-flex align-items-center">';

                                echo '<input class="form-check-input m-3" type="radio" name="selected_card" value="' . $row['card_id'] . '" id="card' . $row['card_id'] . '">';
                                echo '<i class="fas fa-credit-card ml-5"></i>';

                                echo '<div class="flex-grow-1 pl-3">';
                                echo '<h5 class="card-title" style="font-family: Arial;">Card Name: ' . $row['card_name'] . '</h5>';

                                $hashed_card_num = $row['card_num'];
                                $masked_card_num = str_repeat('*', strlen($hashed_card_num));
                                echo '<p class="card-text" style="font-family: Arial;">Card Number: ' . $masked_card_num . '</p>';

                                echo '<p class="card-text" style="font-family: Arial;">Expiry Date: ' . $row['expiry'] . '</p>';
                                echo '</div>'; // End flex-grow-1

                                echo '<button type="submit" name="delete_card" value="' . $row['card_id'] . '" class="btn btn-danger ml-3" onclick="return confirm(\'Are you sure you want to delete this card?\')"><i class="fas fa-trash"></i></button>';

                                echo '</div>'; // End d-flex
                                echo '</div>';
                                echo '</div>';
                                echo '</label>';
                            }
                        }
                        ?>
                        <br>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCardModal">
                            Add New Card
                        </button>
                    </div>
                    <!-- end card section -->
                    <br><br>

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

                    // If order is above 50, exclude shipping fee
                    if ($subtotal >= 50) {
                        echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Shipping Fee:</strong> <span style="color: green; float: right;">Free</span></p>';
                    } else {
                        $total_price = $subtotal + $shipping_fee;
                        echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Shipping Fee:</strong> <span style="float: right;">RM ' . number_format($shipping_fee, 2) . '</span></p>';
                    }

                    echo '<hr style="border-color: #ccc;">';
                    // Display the total price with shipping fee in bold
                    echo '<p class="mb-2" style="font-family: Arial; font-size: 18px;"><strong>Total Price:</strong> <span style="float: right;">RM ' . number_format($total_price, 2) . '</span></p>';

                    echo '</div>';
                    echo '</div>';
                }
                ?>
                <button type="submit" name="complete_payment" class="btn_4" style="border: none; width:100%;">Complete Payment</button>
                </form>
            </div>
        </div>
        <!-- end row -->
    </div>

    <?php
    include 'footer.html';
    ?>
    <!-- Script to hide credit card detail if credit card option not selected -->
    <script>
        const paymentMethodSelect = document.getElementById('payment_method');
        const creditCardSection = document.getElementById('card_section');

        paymentMethodSelect.addEventListener('change', function() {
            const selectedPaymentMethod = paymentMethodSelect.value;

            if (selectedPaymentMethod === 'Credit Card') {
                creditCardSection.style.display = 'block';
            } else {
                creditCardSection.style.display = 'none';
            }
        });
    </script>
    
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