<?php
include '../connect.php';
session_start();

$error_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if any of the fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = '<div class="alert alert-danger" role="alert">Please fill in all the details</div>';
    } else {
        // Check if the email is already registered
        $check_query = "SELECT * FROM user WHERE email='$email'";
        $check_result = mysqli_query($db, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error_message = '<div class="alert alert-danger" role="alert">Email already in use</div>';
        } else {
            // Continue with password validation and registration
            $number = preg_match('@[0-9]@', $password);
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
                $error_message = '<div class="alert alert-danger" role="alert">Password must be at least 8 characters, contain at least one number, one upper case letter, one lower case letter, and one special character</div>';
            } else {
                // Use bcrypt to hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO user (username,email,password) VALUES('$username','$email','$hashed_password')";
                if (mysqli_query($db, $sql)) {
                    // Registration was successful
                    $_SESSION['register_success'] = true;
                    $_SESSION['showEmail'] = $email;
                    $_SESSION['user_info'] = "SELECT * FROM register WHERE email ='$email'";
                    header('Location: login.php');
                    exit();
                } else {
                    $error_message = '<div class="alert alert-danger" role="alert">Registration failed</div>';
                }
            }
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
    <title>Register - EcoPack</title>
    <link rel="icon" href="../pictures/admin logo.png">
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Icons -->
    <script src="https://kit.fontawesome.com/a84d485a7a.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .registration-box {
            width: 280px;
            background-color: #f2f2f2;
            border-radius: 10px;
            padding: 12px;
            margin: 0 auto;
            text-align: center;
        }
        .registration-text {
            font-size: 13px;
            color: #333;
        }
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
        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Create an Account</h2>
                        <form method="POST" class="register-form" id="register-form">
                            <?php echo $error_message; ?>
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username" id="name" placeholder="Username" />
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Email" />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" placeholder="Password">
                                    <span class="toggle-password" id="togglePassword">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password"/>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                            </div> -->
                            <div class="registration-box">
                                <p class="registration-text">
                                    Password must be at least 8 characters, contain at least one number, one upper case letter, one lower case letter, and one special character
                                </p>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="submit" id="signup" class="form-submit" value="Register" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/machine.gif" alt="sing up image"></figure>
                        <a href="login.php" class="signup-image-link">Login Now</a>
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