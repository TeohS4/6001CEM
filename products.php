<?php
include 'connect.php';
session_start();

function getProductCountForCategory($category)
{
    // Define the SQL query to count products in the given category
    $category = mysqli_real_escape_string(mysqli_connect('localhost', 'root', '', 'ecopack'), $category); // Sanitize input
    $sql = "SELECT COUNT(*) AS product_count FROM products WHERE category = '$category'";

    // Execute the query
    $result = mysqli_query(mysqli_connect('localhost', 'root', '', 'ecopack'), $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['product_count'];
    } else {
        return 0;
    }
}

function getAllProductCount($db)
{
    $sql = "SELECT COUNT(*) AS product_count FROM products";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['product_count'];
    } else {
        return 0;
    }
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
        .categories ul {
            list-style: none;
            padding: 0;
        }

        .categories li {
            margin-bottom: 10px;
        }

        .categories a {
            text-decoration: none;
            color: black;
            font-weight: bold;
            font-size: 18px;
        }

        .categories span {
            color: #888;
            margin-left: 5px;
        }

        .orange-btn {
            background-color: #ff6a00;
            border-color: #ff6a00;
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
                            <h2>Our Products</h2>
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
        <div class="custom_container p-5">
            <div class="row">
                <div class="col-md-3">
                    <div class="categories">
                        <h3 style="font-family: Arial; font-weight:bold">Browse Categories</h3>
                        <ul class="categories">
                            <li style="font-family: Arial;">
                                <a href="products.php">All</a>
                                <span>(<?php echo getAllProductCount($db); ?> products)</span>
                            </li>
                            <li style="font-family: Arial;">
                                <a href="products.php?category=<?php echo urlencode('Cutlery & Cups'); ?>">Cutlery & Cups</a>
                                <span>(<?php echo getProductCountForCategory('Cutlery & Cups'); ?> products)</span>
                            </li>
                            <li style="font-family: Arial;">
                                <a href="products.php?category=Bags">Bags</a>
                                <span>(<?php echo getProductCountForCategory('Bags'); ?> products)</span>
                            </li>
                            <li style="font-family: Arial;">
                                <a href="products.php?category=Plates">Plates</a>
                                <span>(<?php echo getProductCountForCategory('Plates'); ?> products)</span>
                            </li>
                            <li style="font-family: Arial;">
                                <a href="products.php?category=Food+Containers">Food Containers</a>
                                <span>(<?php echo getProductCountForCategory('Food Containers'); ?> products)</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col">
                            <div class="section_tittle">
                                <h2>Products</h2>
                                <div class="d-flex align-items-center">
                                    <!-- search -->
                                    <form class="input-group" method="GET" action="products.php">
                                        <input type="text" name="keywords" class="form-control rounded-0" placeholder="Search Keywords">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary orange-btn" type="submit">
                                                <i class="fa fa-search text-white"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <!-- end search -->
                                    <div style="margin-left: 10px;"></div>
                                    <!-- Sort -->
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Sort by Price
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="sortDropdown">
                                        <a class="dropdown-item" href="#">Price: Low to High</a>
                                        <a class="dropdown-item" href="#">Price: High to Low</a>
                                    </div>
                                    <!-- end sort -->
                                    <div style="margin-left: 10px;"></div>
                                </div>
                                <!-- end d-flex -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active single-member" id="Special" role="tabpanel" aria-labelledby="Special-tab">
                                <?php
                                // Retrieve success and error messages from query parameters
                                $successMessage = isset($_GET['success']) ? urldecode($_GET['success']) : '';
                                $errorMessage = isset($_GET['error']) ? urldecode($_GET['error']) : '';

                                if (!empty($successMessage)) {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                                    echo $successMessage;
                                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                    echo '<span aria-hidden="true">&times;</span>';
                                    echo '</button>';
                                    echo '</div>';
                                }

                                if (!empty($errorMessage)) {
                                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                    echo $errorMessage;
                                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                    echo '<span aria-hidden="true">&times;</span>';
                                    echo '</button>';
                                    echo '</div>';
                                }
                                ?>

                                <div class="row">
                                    <?php
                                    if (isset($_GET['keywords'])) {
                                        $keywords = mysqli_real_escape_string($db, $_GET['keywords']);

                                        if (isset($_GET['category']) && $_GET['category'] != 'All') {
                                            $selected_category = mysqli_real_escape_string($db, $_GET['category']);
                                            $sql = "SELECT product_id, product_image, product_name, category, product_price FROM products WHERE category = '$selected_category' AND product_name LIKE '%$keywords%'";
                                        } else {
                                            $sql = "SELECT product_id, product_image, product_name, category, product_price FROM products WHERE product_name LIKE '%$keywords%'";
                                        }
                                    } else {
                                        if (isset($_GET['category']) && $_GET['category'] != 'All') {
                                            $selected_category = mysqli_real_escape_string($db, $_GET['category']);
                                            $sql = "SELECT product_id, product_image, product_name, category, product_price FROM products WHERE category = '$selected_category'";
                                        } else {
                                            $sql = "SELECT product_id, product_image, product_name, category, product_price FROM products";
                                        }
                                    }

                                    $result = mysqli_query($db, $sql);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Display Product
                                            $product_id = $row['product_id'];
                                            $product_image = $row["product_image"];
                                            $product_name = $row["product_name"];
                                            $product_category = $row["category"];
                                            $product_price = $row["product_price"];
                                            // Display Product
                                            echo '<div class="col-sm-6 col-lg-6">';
                                            echo '<div class="single_food_item media bg-white shadow">';
                                            echo '<a href="product_info.php?product_id=' . $product_id . '">
                                                <img src="uploads/' . $product_image . '" class="mr-3" alt="' . $product_image . '" width="180" height="180" style="border-radius: 20px;">
                                                </a>';
                                            echo '<div class="media-body align-self-center">';
                                            echo '<a href="product_info.php?product_id=' . $product_id . '">
                                                  <h3 class="mt-2">' . $product_name . '</h3></a>';
                                            echo '<p>' . $product_category . '</p>';
                                            echo '<h5>RM ' . $product_price . '</h5>';
                                            echo '<div class="menu_btn">';
                                            // Form button
                                            echo '<form action="add_cart.php" method="POST">';
                                            echo '<input type="hidden" name="product_image" value="' . $product_image . '">';
                                            echo '<input type="hidden" name="product_name" value="' . $product_name . '">';
                                            echo '<input type="hidden" name="product_price" value="' . $product_price . '">';
                                            echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                                            echo '<button type="submit" class="single_page_btn d-none d-sm-block mb-2" style="width: 160px;">';
                                            echo '<i class="fa-solid fa-cart-shopping"></i> Add To Cart</button>';
                                            echo '</form>';

                                            echo '</div>'; // Menu_btn div
                                            echo '</div>'; // Media-body div
                                            echo '</div>'; // Single_food_item div
                                            echo '</div>'; // Column div
                                        }
                                    } else {
                                        echo "Error: " . mysqli_error($db);
                                    }
                                    // Close the database connection
                                    mysqli_close($db);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- food_menu part end-->
    <!--::chefs_part end::-->
    <!-- intro_video_bg start-->
    <section class="intro_video_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro_video_iner text-center">
                        <h2>How Sustainable Packaging Helps Environment</h2>
                        <div class="intro_video_icon">
                            <a id="play-video_1" class="video-play-button popup-youtube" href="https://www.youtube.com/watch?v=lwYwKQcmXhY">
                                <span class="ti-control-play"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- intro_video_bg part start-->

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
    <!-- custom js -->
    <script src="js/custom.js"></script>



</body>

</html>