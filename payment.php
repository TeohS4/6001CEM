<?php
include 'connect.php';
session_start();

if (isset($_POST['complete_payment'])) {
    $user_id = $_POST['user_id'];
    $total_price = $_POST['total_price'];
    $customer_name = mysqli_real_escape_string($db, $_POST['customer_name']);
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
    $insert_sql = "INSERT INTO orders (user_id, customer_name, address, payment_method, amount, order_date, packaging_options, order_items)
                   VALUES ('$user_id', '$customer_name', '$customer_address', '$payment_method', '$total_price', NOW(), '$packaging_options', '$order_items')";

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
    /* Style the form container */
    .form-container {
        max-width: 400px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        background-color: #fff;
    }

    /* Add some spacing between form elements */
    .form-group {
        margin-bottom: 15px;
    }
</style>

<body>
    <!--::header part start::-->
    <header class="main_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.php"> <img src="pictures/logo.png" alt="logo" style="height: 120px; width:120px;"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about.php">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="products.php">Our Products</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.html" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Blog
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="blog.html">Blog</a>
                                        <a class="dropdown-item" href="single-blog.html">Single blog</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact</a>
                                </li>
                            </ul>
                        </div>
                        <div class="menu_btn">
                            <a href="cart.php" class="single_page_btn d-none d-sm-block"><i class="fa-solid fa-cart-shopping"></i>
                                Shopping Cart</a>
                        </div>
                        <?php
                        // Check if user is logged in
                        if (isset($_SESSION['user_id'])) {
                            // Display logout button if logged in
                            echo '<a href="logout.php" class="single_page_btn d-none d-sm-block ml-2"">';
                            echo '<i class="fas fa-sign-in-alt"></i> Logout';
                            echo '</a>';
                        } else {
                            // Display login button if user not logged in
                            echo '<a href="login/login.php" class="single_page_btn d-none d-sm-block ml-2">';
                            echo '<i class="fas fa-sign-in-alt"></i> Login';
                            echo '</a>';
                        }
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end-->

    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Payment</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <!--::chefs_part start::-->
    <!-- food_menu start-->
    <section class="food_menu gray_bg">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="section_tittle">
                        <p>Enter Your Payment Details</p>
                        <h2>Payment</h2>
                    </div>
                </div>

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
                    echo '<div class="container mt-5">';
                    echo '<h2 class="mb-4">Your Order Details</h2>';
                    echo '<table class="table table-striped table-bordered">';
                    echo '<thead class="thead-dark"><tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr></thead>';
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
                    echo '<p class="mb-2"><strong>Subtotal:</strong> RM ' . number_format($subtotal, 2) . '</p>';

                    // Shipping fee
                    $shipping_fee = 5.00;

                    // Calculate total
                    $total_price = $subtotal + $shipping_fee;

                    // Display the shipping fee
                    echo '<p class="mb-2"><strong>Shipping Fee:</strong> RM ' . number_format($shipping_fee, 2) . '</p>';

                    // Display the total price with shipping fee in bold
                    echo '<h3 class="mt-4"><strong>Total Price (Including Shipping Fee): RM ' . number_format($total_price, 2) . '</strong></h3>';

                    echo '</div>';
                }
                ?>

            </div>
            <!-- end row -->
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 form-container">
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <h3 align='center'>Customer Details</h3>
                        <div class="form-group">
                            <label for="customer_name">Name:</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>

                        <div class="form-group">
                            <label for="customer_address">Address:</label>
                            <input type="text" class="form-control" id="customer_address" name="customer_address" required>
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Payment Method:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="packaging">Choose Your Sustainable Packaging:</label>
                            <select class="form-control" id="packaging" name="packaging" required>
                                <option value="Bamboo Packaging">Bamboo Packaging</option>
                                <option value="Biodegradable Plastic">Biodegradable Plastic</option>
                                <option value="Compostable Packaging">Compostable Packaging</option>
                            </select>
                        </div>

                        <button type="submit" name="complete_payment" class="btn btn-primary btn-block">Complete Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_1">
                        <h4>About Us</h4>
                        <p>Heaven fruitful doesn't over for these theheaven fruitful doe over days
                            appear creeping seasons sad behold beari ath of it fly signs bearing
                            be one blessed after.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Important Link</h4>
                        <div class="contact_info">
                            <ul>
                                <li><a href="#">WHMCS-bridge</a></li>
                                <li><a href="#"> Search Domain</a></li>
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">Shopping Cart</a></li>
                                <li><a href="#"> Our Shop</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Contact us</h4>
                        <div class="contact_info">
                            <p><span> Address :</span>Hath of it fly signs bear be one blessed after </p>
                            <p><span> Phone :</span> +2 36 265 (8060)</p>
                            <p><span> Email : </span>info@colorlib.com </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-8 col-md-6">
                    <div class="single-footer-widget footer_3">
                        <h4>Newsletter</h4>
                        <p>Heaven fruitful doesn't over lesser in days. Appear creeping seas</p>
                        <form action="#">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder='Email Address' onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'">
                                    <div class="input-group-append">
                                        <button class="btn" type="button"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="copyright_part_text">
                <div class="row">
                    <div class="col-lg-8">
                        <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This template is made with <i class="ti-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                    <div class="col-lg-4">
                        <div class="copyright_social_icon text-right">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="ti-dribbble"></i></a>
                            <a href="#"><i class="fab fa-behance"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer part end-->

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