<?php
include 'connect.php';
session_start();
$user_id = $_SESSION['user_id'];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order History</title>
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
        .receipt-container {
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 20px;
            width: auto;
        }

        .receipt-content {
            width: 100%;
            /* Ensure the content fills the container width */
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
                            <h2>Orders Details</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <div class="row">
        <div class="col m-5">
            <div class="section_tittle">
                <h2>Order Details</h2>
            </div>
            <?php
            if (isset($_GET['order_id'])) {
                $order_id = $_GET['order_id'];
                // Fetch order details based on the provided order_id
                $orderDetailsSQL = "SELECT o.order_id, u.username, o.amount, o.order_date, o.status, o.payment_method
                        FROM orders o
                        INNER JOIN user u ON o.user_id = u.user_id
                        WHERE o.order_id = $order_id";

                $orderDetailsResult = mysqli_query($db, $orderDetailsSQL);

                if ($orderDetailsResult && mysqli_num_rows($orderDetailsResult) > 0) {
                    $orderDetails = mysqli_fetch_assoc($orderDetailsResult);

                    $order_id = $orderDetails['order_id'];
                    $customer_name = $orderDetails['username'];
                    $total_price = $orderDetails['amount'];
                    $order_date = $orderDetails['order_date'];
                    $delivery_status = $orderDetails['status'];
                    $payment_method = $orderDetails['payment_method'];

                    // Fetch order items
                    $orderItemsSQL = "SELECT p.product_name, oi.qty
                        FROM order_items oi
                        INNER JOIN products p ON oi.product_id = p.product_id
                        WHERE oi.orders_id = $order_id";

                    $orderItemsResult = mysqli_query($db, $orderItemsSQL);

                    $orderItems = array();
                    while ($item = mysqli_fetch_assoc($orderItemsResult)) {
                        $orderItems[] = $item['product_name'] . " (Qty: " . $item['qty'] . ")";
                    }

                    // Output the order details
                    echo '<div class="receipt-container" style="font-size: 18px;">';
                    echo '<div class="receipt-content text-center">';
                    echo '<h4 style="font-size: 24px;">Order Details</h4>';
                    echo '<hr>';
                    echo "<p><strong style='font-size: 16px;'>Order ID:</strong> $order_id</p>";
                    echo "<p><strong style='font-size: 16px;'>Customer Name:</strong> $customer_name</p>";
                    echo "<p><strong style='font-size: 16px;'>Order Items:</strong><br>";
                    echo "<ul>";
                    foreach ($orderItems as $item) {
                        echo "<li style='font-size: 16px;'>$item</li>";
                    }
                    echo "</ul></p>";
                    echo '<hr>';
                    echo "<p><strong style='font-size: 16px;'>Amount:</strong> RM $total_price</p>";
                    echo "<p><strong style='font-size: 16px;'>Payment Method:</strong> $payment_method</p>";
                    echo "<p><strong style='font-size: 16px;'>Order Date:</strong> $order_date</p>";
                    echo "<p><strong style='font-size: 16px;'>Delivery Status:</strong></p>";
                    echo "<p class='text-success font-weight-bold' style='font-size: 16px;'>$delivery_status</p>";
                    echo '</div>';
                    echo '<hr>';
                    echo '<div class="text-center">';
                    echo '<button class="btn btn-primary" onclick="window.print();">Print</button>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "Order not found.";
                }
            } else {
                echo "Order ID not provided.";
            }
            ?>
        </div>
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
    <!-- ajaxchimp js -->
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <!-- validate js -->
    <script src="js/jquery.validate.min.js"></script>
    <!-- form js -->
    <script src="js/jquery.form.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</body>

</html>