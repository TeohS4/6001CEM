<!--::header part start::-->
<header class="main_menu">
    <div class="container-fluid" style="max-width: 1300px;">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="index.php"> <img src="pictures/logo.png" alt="logo" style="height: 120px; width:120px;"> </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="products.php">Products</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="blog.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    News
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="news.php">Latest News</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contact</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="profile.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Profile
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="profile.php">View Profile</a>
                                    <a class="dropdown-item" href="order_history.php">Orders History</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="menu_btn">
                        <a href="cart.php" class="single_page_btn d-none d-sm-block"><i class="fa-solid fa-cart-shopping"></i>
                            Cart</a>
                    </div>
                    <?php
                    include 'connect.php';
                    // Check if user is logged in
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];

                        $query = "SELECT * FROM user WHERE user_id = $user_id";

                        if ($r = mysqli_query($db, $query)) {
                            $row = mysqli_fetch_array($r);
                            // Get the username
                            $username = $row['username'];
                        }
                        // Display logout button and welcome msg when logged in
                        echo '<a href="logout.php" class="signout_btn d-none d-sm-block ml-2"">';
                        echo '<i class="fas fa-sign-in-alt"></i> Logout';
                        echo '</a>';
                        echo "<b class='ml-3'>Welcome, $username</b>";
                    } else {
                        // Display login button when user not logged in
                        echo '<a href="login/login.php" class="single_page_btn d-none d-sm-block ml-2">';
                        echo '<i class="fas fa-sign-in-alt"></i> Login';
                        echo '</a>';
                    }
                    ?>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- Header part end-->