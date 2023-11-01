<?php
include '../../../connect.php';

$message = '';

// Check if the form is submitted to update order status
if (isset($_POST['updateStatus'])) {
    $order_id = $_POST['order_id'];
    $newStatus = $_POST['newStatus'];

    // Update the order status in the database
    $updateQuery = "UPDATE orders SET status='$newStatus' WHERE order_id=$order_id";
    if (mysqli_query($db, $updateQuery)) {
        $message = '<div class="alert alert-success alert-dismissible">
            Status updated successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible">
            Error updating status: ' . mysqli_error($db) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Nice lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Nice admin lite design, Nice admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="Nice Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Manage Orders</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/niceadmin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../pictures/admin logo.png">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">
        <?php
        include 'header.php';
        ?>
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Manage Orders</h4>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <h5 class="card-subtitle">Manage Customer Order</h5>
                            <?php

                            // Fetch and display orders
                            $query = "SELECT o.order_id, u.username, o.address, o.amount, o.order_date, o.status FROM orders o
                            INNER JOIN user u ON o.user_id = u.user_id";
                            $result = mysqli_query($db, $query);

                            echo $message;

                            if ($result && mysqli_num_rows($result) > 0) {
                                echo '<form method="post" action="">';
                                echo '<table class="table table-striped">';
                                echo '<thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Username</th>
                                    <th>Address</th>
                                    <th>Amount (RM)</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>';
                                echo '<tbody>';
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                    echo '<td>' . $row['order_id'] . '</td>';
                                    echo '<td>' . $row['username'] . '</td>';
                                    echo '<td>' . $row['address'] . '</td>';
                                    echo '<td>' . $row['amount'] . '</td>';
                                    echo '<td>' . $row['order_date'] . '</td>';
                                    echo '<td>
                <select class="form-select" name="newStatus">
                    <option value="Delivered" ' . ($row['status'] == 'Delivered' ? 'selected' : '') . '>Delivered</option>
                    <option value="In Transit" ' . ($row['status'] == 'In Transit' ? 'selected' : '') . '>In Transit</option>
                    <option value="Out for Delivery" ' . ($row['status'] == 'Out for Delivery' ? 'selected' : '') . '>Out for Delivery</option>
                </select>
            </td>';
                                    echo '<td>
                <input type="hidden" name="order_id" value="' . $row['order_id'] . '">
                <button type="submit" name="updateStatus" class="btn btn-primary">Save Changes</button>
            </td>';
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';
                                echo '</form>';
                            } else {
                                echo 'No orders found.';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <footer class="footer text-center">
                    Designed and Developed by
                    <a href="">Teoh</a>.
                </footer>
            </div>
        </div><!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- End Wrapper -->
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>