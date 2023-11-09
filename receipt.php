<?php
include 'connect.php';
session_start();

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
        .modal-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            text-align: center;
            max-width: 350px;
            padding: 25px;
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
    <?php include 'header.php'; ?>

    <!-- Order Success Message -->
    <div class="modal fade" id="paymentModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-container">
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

    <?php
    if (isset($_GET['order_id'])) {
        // Get order_id from the URL
        $order_id = $_GET['order_id'];

        // Get all the order data from orders table
        $orderSQL = "SELECT * FROM orders WHERE order_id = $order_id";
        $orderResult = mysqli_query($db, $orderSQL);

        if ($orderResult && mysqli_num_rows($orderResult) > 0) {
            // Fetch order details
            $orderData = mysqli_fetch_assoc($orderResult);

            // Fetch the 'username' from the 'user' table based on 'user_id'
            $user_id = $orderData['user_id'];
            $userSQL = "SELECT username FROM user WHERE user_id = $user_id";
            $userResult = mysqli_query($db, $userSQL);
            $userData = mysqli_fetch_assoc($userResult);
            $customer_name = $userData['username']; // Fetch 'username' from the 'user' table

            $order_date = $orderData['order_date'];
            $total_price = $orderData['amount'];
            $payment_method = $orderData['payment_method'];
            $packaging_options = $orderData['packaging_options'];

            // Fetch products ordered (including quantity)
            $productSQL = "SELECT products.product_name, order_items.qty
                           FROM order_items
                           INNER JOIN products ON order_items.product_id = products.product_id
                           WHERE order_items.orders_id = $order_id";
            $productResult = mysqli_query($db, $productSQL);

            $productData = array();
            while ($product = mysqli_fetch_assoc($productResult)) {
                $productData[] = $product['product_name'] . ' (Qty: ' . $product['qty'] . ')';
            }

            // Output the order receipt
    ?>
            <!-- Your HTML code for the receipt -->
            <section class="food_menu gray_bg">
                <!-- Your receipt HTML code goes here -->
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
                        <p><strong>Products Ordered:</strong></p>
                        <ul>
                            <?php
                            foreach ($productData as $product) {
                                echo "<li>$product</li>";
                            }
                            ?>
                        </ul>

                        <p><strong>Total Price: RM</strong> <?php echo $total_price; ?></p>
                        <p><strong>Payment Method:</strong> <?php echo $payment_method; ?></p>
                        <p><strong>Packaging Type:</strong> <?php echo $packaging_options; ?></p>
                        <p>For any inquiries, contact our <a href='contact.php'>customer support</a>.</p>
                        <br>
                        <button class="btn btn-primary" onclick="window.print();">Print Receipt</button>
                    </div>
                </div>
            </section>
            <!-- Your HTML code for the receipt ends here -->
    <?php
        } else {
            echo "Order not found.";
        }
    } else {
        echo "Order ID not provided.";
    }
    ?>

    <?php
    include 'footer.html';
    ?>

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