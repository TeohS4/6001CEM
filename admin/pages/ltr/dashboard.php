<?php
include '../../../connect.php';

// Get total user count
$totalUsers = 0;
$userCountQuery = "SELECT COUNT(*) AS totalUsers FROM user";
$userCountResult = mysqli_query($db, $userCountQuery);
$userCountRow = mysqli_fetch_assoc($userCountResult);
$totalUsers = $userCountRow['totalUsers'];

// Get total order count
$totalOrders = 0;
$orderCountQuery = "SELECT COUNT(*) AS totalOrders FROM orders";
$orderCountResult = mysqli_query($db, $orderCountQuery);
$orderCountRow = mysqli_fetch_assoc($orderCountResult);
$totalOrders = $orderCountRow['totalOrders'];

// Get total revenue
$totalRevenue = 0;
$totalRevenueQuery = "SELECT SUM(amount) AS totalRevenue FROM orders";
$totalRevenueResult = mysqli_query($db, $totalRevenueQuery);
$totalRevenueRow = mysqli_fetch_assoc($totalRevenueResult);
$totalRevenue = $totalRevenueRow['totalRevenue'];

// Monthly Sales
$monthlyQuery = "SELECT MONTH(order_date) AS month, SUM(amount) AS total_sales
          FROM orders
          WHERE YEAR(order_date) = YEAR(CURDATE())
          GROUP BY MONTH(order_date)
          ORDER BY MONTH(order_date)";

$monthlyResult = mysqli_query($db, $monthlyQuery);

// Fetch the data into a monthly data array
$monthlyData = array();
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthlyData[] = array($row['month'], (int)$row['total_sales']);
}

// Weekly Sales
$weeklyQuery = "SELECT DATE(order_date) AS order_date, SUM(amount) AS total_sales
              FROM orders
              WHERE WEEK(order_date) = WEEK(CURDATE())
              AND YEAR(order_date) = YEAR(CURDATE())
              GROUP BY DATE(order_date)
              ORDER BY DATE(order_date)";
$weeklyResult = mysqli_query($db, $weeklyQuery);
$weeklyData = array();
while ($row = mysqli_fetch_assoc($weeklyResult)) {
    $weeklyData[] = array($row['order_date'], (int)$row['total_sales']);
}

// Delete Reviews
$message = '';
if (isset($_POST['delete'])) {
    $review_id = $_POST['delete']; // Corrected from $_GET

    $deleteQuery = "DELETE FROM reviews WHERE review_id = $review_id";

    if (mysqli_query($db, $deleteQuery)) {
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Comment Deleted Successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    } else {
        // Handle the error if the review deletion fails
        $message = "Error deleting the review: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Dashboard</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/niceadmin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../pictures/admin logo.png">
    <!-- Custom CSS -->
    <link href="../../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        .custom-rounded {
            border-radius: 20px;

        }
    </style>
</head>

<body>
    <!-- Preloader - style you can find in spinners.css -->
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
    <!-- Main wrapper -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">
        <?php
        include 'header.php';
        ?>
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container fluid  -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <h4 class="card-title">Monthly Sales</h4>

                                <!-- Display monthly sales -->
                                <div id="monthlySales" style="width: 100%; height: 300px;"></div>
                                <script type="text/javascript">
                                    google.charts.load('current', {
                                        'packages': ['corechart']
                                    });
                                    google.charts.setOnLoadCallback(drawMonthlyChart);

                                    function drawMonthlyChart() {
                                        // Create an array to hold the sales data for each month
                                        var salesData = [
                                            ['Month', 'Sales'],
                                            <?php
                                            // Create an array to store the sales data
                                            $salesArray = array(
                                                'January' => 0,
                                                'February' => 0,
                                                'March' => 0,
                                                'April' => 0,
                                                'May' => 0,
                                                'June' => 0,
                                                'July' => 0,
                                                'August' => 0,
                                                'September' => 0,
                                                'October' => 0,
                                                'November' => 0,
                                                'December' => 0
                                            );
                                            // Populate the sales data from your database
                                            foreach ($monthlyData as $row) {
                                                $month = date("F", mktime(0, 0, 0, $row[0], 1));
                                                $salesArray[$month] = $row[1];
                                            }
                                            // Generate the data points for each month
                                            foreach ($salesArray as $month => $sales) {
                                                echo "['$month', $sales],";
                                            }
                                            ?>
                                        ];
                                        var data = google.visualization.arrayToDataTable(salesData);
                                        var options = {
                                            title: 'Amount (RM)',
                                            curveType: 'function',
                                            legend: {
                                                position: 'bottom'
                                            },
                                        };
                                        var chart = new google.visualization.LineChart(document.getElementById('monthlySales'));
                                        chart.draw(data, options);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <h4 class="card-title">Weekly Sales</h4>
                                <!-- Display Weekly -->
                                <div id="weeklySales" style="width: 100%; height: 300px;"></div>
                                <?php
                                $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                ?>
                                <script type="text/javascript">
                                    google.charts.load('current', {
                                        'packages': ['corechart']
                                    });
                                    google.charts.setOnLoadCallback(drawWeeklyChart);

                                    function drawWeeklyChart() {
                                        // Create an array to hold the sales data for each day of the week
                                        var salesData = [
                                            ['Day', 'Sales'],
                                            <?php
                                            // Create an associative array to store sales data for each day of the week
                                            $salesByDay = array();
                                            foreach ($daysOfWeek as $day) {
                                                $salesByDay[$day] = 0;
                                            }

                                            // Populate the sales data from your database
                                            foreach ($weeklyData as $row) {
                                                $dayOfWeek = date("l", strtotime($row[0]));
                                                $salesByDay[$dayOfWeek] = $row[1];
                                            }

                                            // Generate the data points for each day of the week
                                            foreach ($daysOfWeek as $day) {
                                                echo "['$day', " . $salesByDay[$day] . "],";
                                            }
                                            ?>
                                        ];

                                        var data = google.visualization.arrayToDataTable(salesData);

                                        var options = {
                                            title: 'Amount (RM)',
                                            curveType: 'function',
                                            legend: {
                                                position: 'bottom'
                                            },
                                        };

                                        var chart = new google.visualization.LineChart(document.getElementById('weeklySales'));

                                        chart.draw(data, options);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <div class="text-center bg-dark rounded-circle d-flex justify-content-center align-items-center p-2 shadow" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-dollar-sign text-light" style="font-size: 1.5rem;"></i>
                                </div>
                                <h5 class="card-title mt-3 mb-1">Total Revenue</h5>
                                <h3><?php echo 'RM ' . $totalRevenue; ?></h3>
                            </div>
                        </div>

                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <div class="text-center bg-dark rounded-circle d-flex justify-content-center align-items-center p-2 shadow" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-user text-light" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title mt-3 mb-0">Total Users</h4>
                                <h2><?php echo $totalUsers; ?></h2>
                            </div>
                        </div>

                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <div class="text-center bg-dark rounded-circle d-flex justify-content-center align-items-center p-2 shadow" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-truck text-light" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="card-title mt-3 mb-0">Total Orders</h4>
                                <h2><?php echo $totalOrders; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Email campaign chart -->
                <!-- ============================================================== -->
                <!-- Ravenue - page-view-bounce rate -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-------- Best Selling ---------->
                    <div class="col-12">
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <h4 class="card-title">Top 3 Best Selling Products</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Product</th>
                                            <th class="border-top-0">Total Number of Sales</th>
                                            <th class="border-top-0">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query to get the best-selling products
                                        $bestSellingProductsSQL = "SELECT p.product_name, SUM(oi.qty) as total_sales, p.product_price
                                                FROM order_items oi
                                                INNER JOIN products p ON oi.product_id = p.product_id
                                                GROUP BY p.product_id
                                                ORDER BY total_sales DESC
                                                LIMIT 3";

                                        $bestSellingProductsResult = mysqli_query($db, $bestSellingProductsSQL);

                                        while ($product = mysqli_fetch_assoc($bestSellingProductsResult)) {
                                            $product_name = $product['product_name'];
                                            $total_sales = $product['total_sales'];
                                            $product_price = $product['product_price'];

                                            echo '<tr>';
                                            echo '<td class="txt-oflo">' . $product_name . '</td>';
                                            echo '<td class="txt-oflo">' . $total_sales . '</td>';
                                            echo '<td><span class="font-medium">RM ' . number_format($product_price, 2) . '</span></td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-------- Recent Sales ---------->
                    </div>
                    <div class="col-12">
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <h4 class="card-title">Recent Sales</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Product</th>
                                            <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Date</th>
                                            <th class="border-top-0">Customer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query to get recent sales
                                        $recentSalesSQL = "SELECT p.product_name, o.status, o.order_date, u.username
                                                FROM orders o
                                                INNER JOIN user u ON o.user_id = u.user_id
                                                INNER JOIN order_items oi ON o.order_id = oi.orders_id
                                                INNER JOIN products p ON oi.product_id = p.product_id
                                                ORDER BY o.order_date DESC
                                                LIMIT 3";

                                        $recentSalesResult = mysqli_query($db, $recentSalesSQL);

                                        while ($sale = mysqli_fetch_assoc($recentSalesResult)) {
                                            $product_name = $sale['product_name'];
                                            $status = $sale['status'];
                                            $order_date = $sale['order_date'];
                                            $username = $sale['username'];

                                            echo '<tr>';
                                            echo '<td class="txt-oflo">' . $product_name . '</td>';
                                            echo '<td><span class="label label-success label-rounded">' . $status . '</span></td>';
                                            echo '<td class="txt-oflo">' . $order_date . '</td>';
                                            echo '<td><span class="font-medium">' . $username . '</span></td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- end card -->
                    </div>
                </div>
                <!-------- Recent Reviews ---------->
                <div class="row">
                    <!-- column -->
                    <div class="col-lg-6">
                        <div class="card shadow custom-rounded">
                            <div class="card-body">
                                <h4 class="card-title">Recent Reviews</h4>
                                <?php echo $message; ?>
                            </div>
                            <div class="comment-widgets" style="height:300px;">
                                <!-- Comment Row -->
                                <?php
                                // Query to get user reviews
                                $userReviewsSQL = "SELECT r.review_id, r.product_id, r.user_id, r.rating, r.comment, r.date, p.product_name, u.username, p.product_image
                                        FROM reviews r
                                        INNER JOIN products p ON r.product_id = p.product_id
                                        INNER JOIN user u ON r.user_id = u.user_id";

                                $userReviewsResult = mysqli_query($db, $userReviewsSQL);

                                if (mysqli_num_rows($userReviewsResult) > 0) {
                                    while ($review = mysqli_fetch_assoc($userReviewsResult)) {
                                        $review_id = $review['review_id'];
                                        $username = $review['username'];
                                        $comment = $review['comment'];
                                        $date = $review['date'];
                                        $product_name = $review['product_name'];
                                        $image = $review['product_image'];

                                        echo '<div class="d-flex flex-row comment-row mt-0">';
                                        echo '<div class="p-2">';
                                        echo '<img src="../../../uploads/' . $image . '" alt="user" width="50" class="rounded-circle">';
                                        echo '</div>';
                                        echo '<div class="comment-text w-100">';
                                        echo '<h6 class="font-medium">' . $username . '</h6>';
                                        echo '<span class="mb-3 d-block">' . $comment . '</span>';
                                        echo '<div class="comment-footer">';
                                        echo '<span class="text-muted float-end">' . $date . '</span>';
                                        echo '<span class="label label-rounded label-primary" style="font-size: 14px;">Product : ' . $product_name . '</span>';

                                        echo '<span class="action-icons">';
                                        echo '<form method="post">';
                                        echo '<button type="submit" style="border:none;color:red;" class="delete-button mt-2" name="delete" value="' . $review_id . '" onclick="return confirm(\'Are you sure you want to delete this review?\');">';
                                        echo '<i class="fa fa-trash"></i>';
                                        echo '</button>';
                                        echo '</form>';
                                        echo '</span>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p class="text-center">No comments found</p>';
                                }
                                ?>
                                <!-- end comment row -->
                            </div>
                        </div>
                    </div>
                    <!-- column -->
                </div>
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                Designed and Developed by
                <a href="">Teoh</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="../../assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../../dist/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>
