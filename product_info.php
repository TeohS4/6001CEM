<?php
session_start();
include 'connect.php';

$product_id = $_GET['product_id'];
$user_id = null; // Initialize the variable to null

// Check if the user is logged in (adjust the condition as needed)
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   // Now $user_id contains the user's ID
} else {
   // Handle the case when the user is not logged in
}



$sql = "SELECT * FROM products WHERE product_id = $product_id";

$result = mysqli_query($db, $sql);

if ($result) {
   // Fetch the product details
   $row = mysqli_fetch_assoc($result);

   // define product attributes
   $product_name = $row['product_name'];
   $image = $row['product_image'];
   $category = $row['category'];
   //$reviews = $row['reviews'];
   $description = $row['product_des'];
   $price = $row['product_price'];
   $stock = $row['stock'];

   // Close the result set 
   mysqli_free_result($result);
} else {
   echo "Error: " . mysqli_error($db);
}


$message = '';
$hasReviewed = false;

if (isset($_POST['submit_review'])) {
   // Check if the user has already reviewed the product
   $sqlCheckReviewed = "SELECT * FROM reviews WHERE product_id = '$product_id' AND user_id = '$user_id'";
   $resultCheckReviewed = $db->query($sqlCheckReviewed);

   if ($resultCheckReviewed->num_rows > 0) {
      // User has already reviewed the product
      $hasReviewed = true;
      $message = '<div class="alert alert-warning">You have already reviewed this product.</div>';
   } else {
      // Get the user's rating and comment
      $rating = $_POST['rating'];
      $comment = $_POST['comment'];

      if (empty($comment) || empty($rating)) {
         $message = '<div class="alert alert-info alert-dismissible fade show" role="alert">Please enter a comment and select a star rating.
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>';
      } else {
         // Get the current date and time
         $date = date('Y-m-d H:i:s');

         // Insert the review into the database
         $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, date) VALUES ('$product_id', '$user_id', '$rating', '$comment', '$date')";

         if ($db->query($sql) === TRUE) {
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Review posted!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>';
         } else {
            $message = '<div class="alert alert-warning">Error: ' . $sql . '<br>' . $db->error . '</div>';
         }
      }
   }
   mysqli_close($db);
}

?>

<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?php echo $product_name; ?></title>
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
      .rounded-image {
         border-radius: 20px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      }

      .centered-box {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100%;
      }

      .rounded-box {
         background-color: #f2f2f2;
         border-radius: 30px;
         padding: 15px;
         max-width: auto;
      }

      .rounded-box ul li {
         font-size: 18px;
      }

      /* comment section */
      .comment-card {
         background-color: white;
         border: 1px solid #c7c7c7;
         border-radius: 10px;
         margin-bottom: 10px;
         padding: 20px;
      }

      .comment-card .thumb {
         max-width: 50px;
      }

      .comment-card .comment {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 10px 0;
         font-size: 20px;
      }

      .comment-card .user-info {
         margin-right: 20px;
      }

      .comment-card .user-info h5 {
         font-family: Arial, sans-serif;
         font-weight: bold;
         margin: 0;
      }

      .comment-card .date {
         font-family: Arial, sans-serif;
         color: #777;
         margin: 0;
      }

      .comment-card .rating {
         font-size: 18px;
         color: #ffac33;
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
                     <h2><?php echo $product_name; ?></h2>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb start-->
   <!--================Blog Area =================-->
   <section class="blog_area single-post-area section_padding">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 posts-list">
               <div class="single-post">
                  <div class="feature-img">
                     <img class="img-fluid rounded-image" src="uploads/<?php echo $image; ?>" alt="<?php echo $product_name; ?>">
                  </div>
                  <div class="blog_details">
                     <h2><?php echo $product_name; ?>
                     </h2>
                     <!-- INFO -->
                     <div class="centered-box">
                        <div class="rounded-box">
                           <ul class="blog-info-link mt-3 mb-4">
                              <li><i class="fa-solid fa-circle-info"></i> <?php echo $category; ?></li>
                              <li><i class="fa-solid fa-money-bill-wave"></i> RM <?php echo number_format($price, 2); ?></li>
                              <li>
                                 <?php if ($stock < 10) : ?>
                                    <span style="color: red; font-size: 18px;">
                                       <i class="fa-solid fa-box"></i> <?php echo $stock; ?> items left (Low Stock)
                                    </span>
                                 <?php else : ?>
                                    <i class="fa-solid fa-box"></i> <?php echo $stock; ?> items left
                                 <?php endif; ?>
                              </li>

                           </ul>
                        </div>
                     </div>
                     <br>
                     <h4><b>Product Description:</b></h4>
                     <?php echo "<p style='font-size: 18px;'>$description </p>"; ?>
                     <br>
                     <?php
                     echo '<form action="add_cart.php" method="POST">';
                     echo '<input type="hidden" name="product_image" value="' . $image . '">';
                     echo '<input type="hidden" name="product_name" value="' . $product_name . '">';
                     echo '<input type="hidden" name="product_price" value="' . $price . '">';
                     echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                     echo '<button type="submit" class="btn_4" style="border: none;">';
                     echo '<i class="fa-solid fa-cart-shopping"></i> Add To Cart</button>';
                     echo '</form>';
                     ?>
                     <br>
                  </div>
               </div>
               <div class="navigation-top">
                  <div class="d-sm-flex justify-content-between text-center">
                     <div class="col-sm-4 text-center my-2 my-sm-0">
                        <!-- <p class="comment-count"><span class="align-middle"><i class="far fa-comment"></i></span> 06 Comments</p> -->
                     </div>
                     <ul class="social-icons">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                     </ul>
                  </div>
               </div>
               <!-- <div class="blog-author">
                  <div class="media align-items-center">
                     <img src="img/blog/author.png" alt="">
                     <div class="media-body">
                        <a href="#">
                           <h4>Harvard milan</h4>
                        </a>
                        <p>Second divided from form fish beast made. Every of seas all gathered use saying you're, he
                           our dominion twon Second divided from</p>
                     </div>
                  </div>
               </div> -->

               <!-- Comment Section -->
               <h3>User Reviews:</h3>
               <?php
               // Get average ratings
               $sql = "SELECT r.*, u.username FROM reviews r
                        LEFT JOIN user u ON r.user_id = u.user_id
                        WHERE r.product_id = $product_id";
               $result = $db->query($sql);

               $totalRatings = 0;
               $averageRating = 0;

               if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                     $rating = $row['rating'];
                     $ratingValue = (int)filter_var($rating, FILTER_SANITIZE_NUMBER_INT);
                     $totalRatings += $ratingValue;
                  }
                  $averageRating = $totalRatings / $result->num_rows;

                  $averageStars = '';
                  for ($i = 1; $i <= 5; $i++) {
                     if ($i <= round($averageRating)) {
                        $averageStars .= '<i class="fas fa-star"></i>'; // Full star for average rating
                     } else {
                        $averageStars .= '<i class="far fa-star"></i>'; // Empty star for remaining
                     }
                  }
                  // Display the average rating with stars
                  echo '<p>Average Rating: ' . $averageStars . ' (' . number_format($averageRating, 2) . ' stars)</p>';
               }

               ?>
               <br>
               <?php
               $sql = "SELECT r.*, u.username FROM reviews r
               LEFT JOIN user u ON r.user_id = u.user_id
               WHERE r.product_id = $product_id";
               $result = $db->query($sql);

               if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                     $rating = $row['rating']; // Get the textual rating (e.g., '5 stars', '4 stars')
                     $comment = $row['comment'];
                     $username = $row['username']; // Get the username from the user table
                     $date = $row['date'];

                     // Convert the textual rating to star icons
                     $stars = '';
                     $ratingValue = (int)filter_var($rating, FILTER_SANITIZE_NUMBER_INT);
                     for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $ratingValue) {
                           $stars .= '<i class="fas fa-star"></i>';
                        } else {
                           $stars .= '<i class="far fa-star"></i>';
                        }
                     }

                     // Output the review with star rating
                     echo '<div class="comment-card">
                           <div class="single-comment justify-content-between d-flex">
                              <div class="user justify-content-between d-flex">
                                 <div class="thumb">
                                    <i class="fa fa-user"></i>
                                 </div>
                                 <div class="desc ml-3">
                                 <h4 style="font-family: Arial; color: blue;">
                                    <a href="">' . $username . '</a>
                                 </h4>
                                       <div class="rating">' . $stars . '</div>
                                       <div class="d-flex justify-content-between align-items-center">
                                          <div class="user-info">
                                          <p class="comment">' . $comment . '</p>
                                             <p class="date">' . $date . '</p>
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>';
                  }
               } else {
                  echo 'There are no reviews about this product.';
               }
               ?>
               <!-- <div class="reply-btn">
                                    <a href="#" class="btn-reply text-uppercase">reply</a>
                                 </div> -->
               <!-- End Comment Section -->
               <!-- Comment Form -->
               <div class="comment-form">

                  <?php echo $message; ?>

                  <?php
                  if (isset($_SESSION['user_id'])) {
                     $user_id = $_SESSION['user_id'];
                     echo '
                        <h4>Leave a Review</h4>
                  <form class="form-contact comment_form" method="POST" action="" id="commentForm">
                     <div class="row">
                        <div class="col-12">
                           <div class="form-group">
                              <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="form-group">
                              <label for="rating">Rate:</label><br>
                              <div id="star-rating" name="rating"></div>
                              <div id="rating-hint"></div>

                           </div>
                        </div>
                     </div>
                        <div class="form-group">
                        <button type="submit" name="submit_review" class="button button-contactForm">Post Review</button>
                        </div>';
                  } else {
                     echo '<div class="alert alert-warning">
                              <h4 class="m-3"><i class="fa-solid fa-triangle-exclamation"></i> You must be logged in to post reviews</h4>
                           </div>';
                  }
                  ?>
                  </form>
               </div>
               <!-- END COMMENT FORM -->
            </div>
            <div class="col-lg-4">
               <div class="blog_right_sidebar">
                  <aside class="single_sidebar_widget post_category_widget">
                     <h4 class="widget_title">Browse Category</h4>
                     <ul class="list cat-list">
                        <li>
                           <a href="http://localhost/ecopack/products.php?category=Cutlery+%26+Cups" class="d-flex">
                              <p>Cutlery & Cups</p>
                           </a>
                        </li>
                        <li>
                           <a href="http://localhost/ecopack/products.php?category=Bags" class="d-flex">
                              <p>Bags</p>
                           </a>
                        </li>
                        <li>
                           <a href="http://localhost/ecopack/products.php?category=Plates" class="d-flex">
                              <p>Plates</p>
                           </a>
                        </li>
                        <li>
                           <a href="http://localhost/ecopack/products.php?category=Food+Containers" class="d-flex">
                              <p>Food Containers</p>
                           </a>
                        </li>
                     </ul>
                  </aside>

               </div>
            </div>
         </div>
      </div>
   </section>
   <!--================Blog Area end =================-->

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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/raty/2.9.0/jquery.raty.min.js"></script>
   <!-- Ratings -->
   <script>
      $(document).ready(function() {
         $('#star-rating').raty({
            number: 5, // Number of stars
            scoreName: 'rating',
            target: '#rating-hint', // Display the rating hint
            targetText: '',
            click: function(score, event) {},
            starType: 'i',
            starOn: 'fa fa-star',
            starOff: 'fa fa-star-o'
         });
      });
   </script>
</body>

</html>