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
        /* Custom style for the entire table */
        table {
            font-family: Arial, sans-serif;
            font-size: 16px;
            /* Adjust the text size as needed */
        }

        /* Style for the header row */
        table thead tr th {
            font-weight: bold;
            /* Make the header text bold */
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
                            <h2>Orders History</h2>
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
                <h2>My Orders</h2>
            </div>
            <?php
            // Retrieve orders for the user with customer name
            $query = "SELECT o.order_id, o.amount, o.order_date, o.status
            FROM orders o
            JOIN user u ON o.user_id = u.user_id
            WHERE o.user_id = $user_id
            ORDER BY o.order_date DESC";

            $result = mysqli_query($db, $query);
            
            $counter = 1;
            // Check if there are any orders
            if ($result && mysqli_num_rows($result) > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>Order No</th><th>Total Amount</th><th>Date / Time</th><th>Status</th><th>Details</th></tr></thead>';
                echo '<tbody';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $counter++ . '</td>';
                    echo '<td><strong>RM ' . $row['amount'] . '</strong></td>';
                    echo '<td>' . $row['order_date'] . '</td>';
                    echo '<td class="text-success font-weight-bold">' . $row['status'] . '</td>';
                    echo '<td><a href="order_details.php?order_id=' . $row['order_id'] . '"><i class="fas fa-info-circle"></i></a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="font-size: 20px;">No Orders Found.</p>';
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