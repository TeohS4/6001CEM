<?php
include 'connect.php';
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_SESSION['user_id'])) {

    $query = "SELECT * FROM user WHERE user_id = $user_id";

    if ($r = mysqli_query($db, $query)) {
        $row = mysqli_fetch_array($r);

        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
    }
} else {
    echo '
        <script>
        alert("Please login to your account before editing profile.");
        window.location.href = "index.php";
        </script>';
}

// Update Profile
if (isset($_POST['update'])) {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        $AlertMessage[] = 'Please fill in all the details';
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash the password using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $update = "UPDATE user SET username='$username', email='$email', password='$hashedPassword' 
                   WHERE user_id ='$user_id'";
        mysqli_query($db, $update);

        $message[] = 'User Data Successfully Updated';
    }
}

//   Delete Profile
if (isset($_POST['delete'])) {
    mysqli_query($db, "DELETE FROM user WHERE user_id = '$user_id'");
    session_destroy();
    echo "<script>alert('Your account has been deleted!');window.location='index.php';</script>";
    header('location:index.php');
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Profile</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">
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
                            <h2>Profile</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <div class="row">
        <div class="container mt-4 mb-4">
            <div class="section_tittle">
                <h2>User Profile</h2>
            </div>
            <?php
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                       ' . $message .
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                }
            }

            if (isset($AlertMessage)) {
                foreach ($AlertMessage as $AlertMessage) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       ' . $AlertMessage .
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                }
            }
            ?>
            <form action="" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="">
                </div>
                <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?');">Delete My Account</button>
            </form>
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