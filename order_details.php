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
                <h2>Orders</h2>
            </div>
            <?php
            // Receive the order_id from the URL
            $order_id = $_GET['order_id'];

            $query = "SELECT o.order_id, u.username, o.order_items, o.amount, o.order_date
            FROM orders o
            JOIN user u ON o.user_id = u.user_id
            WHERE o.order_id = $order_id";

            $result = mysqli_query($db, $query);

            // Check if the order was found
            if ($result && mysqli_num_rows($result) > 0) {
                $order = mysqli_fetch_assoc($result);

                echo '<div class="receipt-container">';
                echo '<div class="receipt-content text-center">';
                echo '<h4>Order Receipt</h4>';
                echo '<hr>';
                echo '<p><strong>Order ID:</strong> ' . $order['order_id'] . '</p>';
                echo '<p><strong>Customer Name:</strong> ' . $order['username'] . '</p>'; // Use 'username' here
                echo '<p><strong>Order Items:</strong><br>' . formatOrderItems($order['order_items']) . '</p>';
                echo '<p><strong>Amount:</strong> RM ' . $order['amount'] . '</p>';
                echo '<p><strong>Order Date:</strong> ' . $order['order_date'] . '</p>';
                echo '</div>';
                echo '<hr>';
                echo '<div class="text-center">';
                echo '<button class="btn btn-primary" onclick="window.print();">Print Receipt</button>';
                echo '</div>';
                echo '</div>';
            } else {
                echo 'Order not found.';
            }
            function formatOrderItems($order_items)
            {
                $order_items = json_decode($order_items);
                if ($order_items !== null) {
                    $formatted_items = [];

                    foreach ($order_items as $item) {
                        $formatted_item = str_replace(['"', '[', ']'], '', $item);
                        $formatted_items[] = $formatted_item;
                    }
                    return implode("<br>", $formatted_items);
                }
                return "Invalid order items";
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