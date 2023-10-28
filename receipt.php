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

        // Query to retrieve name from user table
        $user_query = "SELECT username FROM user WHERE user_id = $user_id";

        $user_result = mysqli_query($db, $user_query);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_data = mysqli_fetch_assoc($user_result);
            $customer_name = $user_data['username'];
            $order_date = $order_data['order_date'];
            $total_price = $order_data['amount'];
            $payment_method = $order_data['payment_method'];
            $packaging_options = $order_data['packaging_options'];
        } else {
            // Handle if user not found
            echo "User not found.";
        }
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
                <br>
                <button class="btn btn-primary" onclick="window.print();">Print Receipt</button>
            </div>
        </div>
    </section>

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