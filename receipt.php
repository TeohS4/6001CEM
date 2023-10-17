<?php
include 'connect.php';
session_start();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query to retrieve order details
    $order_query = "SELECT * FROM orders WHERE order_id = $order_id";

    $result = mysqli_query($db, $order_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $order_data = mysqli_fetch_assoc($result);
        $user_id = $order_data['user_id'];
        $customer_name = $order_data['customer_name'];
        $order_date = $order_data['order_date'];
        $total_price = $order_data['amount'];
        $payment_method = $order_data['payment_method'];
        $packaging_options = $order_data['packaging_options'];
    } else {
        // Handle if order not found
        echo "Order not found.";
    }
} else {
    // Handle the case if order id is missing in URL
    echo "Order ID is missing.";
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
    <style>
        .card {
            text-align: center;
            max-width: 300px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }


        .card-img img {
            width: 80%;
            display: block;
            margin: 0 auto;
        }

        .btn {
            display: block;
            margin: 0 auto;
        }

        /* Receipt */
        .receipt {
            border: 2px solid #000;
            border-radius: 10px;
            padding: 20px;
            width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .receipt h1 {
            font-size: 24px;
        }

        .receipt p {
            font-size: 16px;
        }

        .receipt .info {
            text-align: left;
        }
    </style>
</head>

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
    <div class="modal fade" id="paymentModal" role="dialog">
        <div class="modal-dialog">
            <div class="card">
                <div class="card-img">
                    <img src="img/parcel.gif">
                </div>
                <div class="card-title">
                    <h2>Payment Successful</h2>
                </div>
                <div class="card-text">
                    <h4>Your Order Is On Its Way!</h4>
                </div>
                <a href="" class="btn btn-success">View Order Details</a>
            </div>
        </div>
    </div>
    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Receipt</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <section class="food_menu gray_bg">
        <div class="container">

            <div class="receipt">
                <h1>Order Receipt</h1>
                <p>Thank you for your shopping at EcoPack!</p>

                <div class="info">
                    <p><strong>Name:</strong> <?php echo $customer_name; ?></p>
                    <p><strong>Date Purchased:</strong> <?php echo $order_date; ?></p>
                    <p><strong>Order Number:</strong> <?php echo $order_id; ?></p>
                </div>
                <hr>

                <?php
                // Fetch the list of items ordered based on the order_id
                $items_query = "SELECT order_items FROM orders WHERE order_id = $order_id";
                $items_result = mysqli_query($db, $items_query);

                if ($items_result && mysqli_num_rows($items_result) > 0) {
                    $order_data = mysqli_fetch_assoc($items_result);
                    $order_items = $order_data['order_items'];

                    echo '<p><strong>Items Ordered:</strong></p>';

                    // Parse the JSON array
                    $order_items = json_decode($order_items);

                    echo '<ul>';
                    foreach ($order_items as $item) {
                        $formatted_item = str_replace(['"', '[', ']'], '', $item);
                        echo "<li>$formatted_item</li>";
                    }
                    echo '</ul>';
                }

                ?>
                <p><strong>Total Price:</strong> <?php echo $total_price; ?></p>
                <p><strong>Payment Method:</strong> <?php echo $payment_method; ?></p>
                <p><strong>Packaging Type:</strong> <?php echo $packaging_options; ?></p>
                <p>For any inquiries, contact our <a href='contact.php'>customer support</a>.</p>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <script>
        $(document).ready(function() {
            $('#paymentModal').modal('show');
        });
    </script>
</body>

</html>