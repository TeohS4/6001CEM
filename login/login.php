<?php
include '../connect.php';
session_start();

$error_message = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($db, $sql);

    if (!$result) {
        $error_message = '<div class="alert alert-danger" role="alert">Database query error</div>';
    } else {
        $row = mysqli_fetch_array($result);
        if ($row && password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $row['user_id'];
            header('location: ../index.php');
            exit();
        } else {
            $error_message = '<div class="alert alert-danger" role="alert">Wrong email or password</div>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - EcoPack</title>
    <link rel="icon" href="../pictures/admin logo.png">
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Icons -->
    <script src="https://kit.fontawesome.com/a84d485a7a.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        input[type="password"] {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="main">
        <!-- Sing in  Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="images/laptop.gif" alt="sing up image"></figure>
                        <a href="register.php" class="signup-image-link">Create an account</a>
                    </div>

                    <?php
                    if (isset($_SESSION['cart_error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        echo $_SESSION['cart_error'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        unset($_SESSION['cart_error']); // Clear error message
                    }

                    ?>
                    <div class="signin-form">
                        <h2 class="form-title">Login</h2>
                        <form method="POST" class="register-form" id="login-form">
                            <?php echo $error_message;
                            if (isset($_SESSION['register_success']) && $_SESSION['register_success']) {
                                echo '<div class="alert alert-success" role="alert">Your Account Has Been Successfully Created</div>';
                                // Clear success message from session
                                unset($_SESSION['register_success']);
                            }

                            ?>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="email" id="email" placeholder="Email" />
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="Password" />
                                <span class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="submit" id="signin" class="form-submit" value="Log in" />
                            </div>
                        </form>
                        <br>
                        <a href="../index.php" class="signup-image-link">Continue as Guest</a>
                        <a href="../admin/admin_login.php" class="signup-image-link">Login as Admin</a>
                        <!-- <div class="social-login">
                            <span class="social-label">Or login with</span>
                            <ul class="socials">
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const togglePasswordButton = document.getElementById("togglePassword");
        const toggleIcon = document.getElementById("toggleIcon");

        togglePasswordButton.addEventListener("click", () => {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        });
    </script>
    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>