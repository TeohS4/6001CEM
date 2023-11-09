<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home</title>
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

<body>
    <?php include 'header.php'; ?>

    <!-- banner part start-->
    <section class="banner_part">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h5>Make the world a greener place!</h5>
                            <h1>Affordable Sustainable Product</h1>
                            <p>Welcome to EcoPack, We offer a variety of eco-friendly products to help
                                you make greener choices in everyday life. Join us to create a
                                cleaner, healthier world through sustainable shopping!</p>
                            <div class="banner_btn">
                                <div class="banner_btn_iner">
                                    <a href="products.php" class="btn_2">Browse Products <img src="img/icon/left_1.svg" alt=""></a>
                                </div>
                                <a href="https://www.youtube.com/watch?v=lwYwKQcmXhY" class="popup-youtube video_popup">
                                    <span><img src="img/icon/play.svg" alt=""></span> How it helps the environment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner part start-->

    <?php
    // Fetch products
    $sql = "SELECT product_id, product_image, product_name, category, product_price FROM products LIMIT 3"; // Limit to three products
    $result = mysqli_query($db, $sql);

    // Check if products were found
    if ($result && mysqli_num_rows($result) > 0) {
    ?>
        <!--::exclusive_item_part start::-->
        <section class="exclusive_item_part blog_item_section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="section_tittle">
                            <p>Sustainable Products</p>
                            <h2>Our Products</h2>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_id = $row['product_id'];
                        $product_name = $row['product_name'];
                        $category = $row['category'];
                        $product_image = $row['product_image'];
                    ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="single_blog_item">
                                <div class="single_blog_img">
                                    <img src="uploads/<?php echo $product_image; ?>" style="width:302.5px;height:300px;" alt="<?php echo $product_name; ?>">
                                </div>
                                <div class="single_blog_text">
                                    <h3><?php echo $product_name; ?></h3>
                                    <p><?php echo $category; ?></p>
                                    <a href="product_info.php?product_id=<?php echo $product_id; ?>" class="btn_3">Read More <img src="img/icon/left_2.svg" alt=""></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php
    } else {
        echo "No products found.";
    }
    ?>

    <!--::exclusive_item_part end::-->

    <!--::review_part start::-->
    <section class="review_part gray_bg section_padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="section_tittle">
                        <h2>User Reviews</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11">
                    <div class="client_review_part owl-carousel">
                        <?php
                        // Fetch user feedback data from the reviews table
                        $sql = "SELECT r.comment, r.rating, r.product_id, p.product_image, u.username
                            FROM reviews r
                            JOIN products p ON r.product_id = p.product_id
                            JOIN user u ON r.user_id = u.user_id
                            LIMIT 3"; // Adjust the query as needed

                        $result = $db->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $image = $row['product_image'];
                                $feedback = $row['comment'];
                                $rating = $row['rating'];
                                $username = $row['username'];

                                // Output the feedback data
                        ?>
                                <div class="client_review_single media">
                                    <div class="client_img align-self-center">
                                        <img src="uploads/<?php echo $image; ?>" alt="">
                                    </div>
                                    <div class="client_review_text media-body">
                                        <p><?php echo $feedback; ?></p>
                                        <h4>Rating: <?php echo $rating; ?></h4>
                                        <a><?php echo $username; ?></a>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!--::review_part end::-->

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