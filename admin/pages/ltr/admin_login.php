<?php
include '../../../connect.php';

session_start(); // Start the session

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message[] = 'Please fill in all the details';
    } else {
        // Fetch the hashed password from the database based on the username
        $query = "SELECT password FROM admin WHERE username = 'admin'";
        $result = mysqli_query($db, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION['admin_username'] = $username; 
                header('location: dashboard.php'); 
            } else {
                // Password is incorrect
                $message[] = 'Wrong username or password';
            }
        } else {
            // Admin username not found
            $message[] = 'Wrong username or password';
        }

        mysqli_free_result($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin Login</title>
  <link rel="icon" href="../pictures/admin logo.png">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
  <link rel="stylesheet" href="style.css" type="text/css">
  <script src="https://kit.fontawesome.com/a84d485a7a.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <style>
    .rounded-box {
      border-radius: 20px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.4);
      padding: 10px;
    }
    body {
        background-image: url('../../../img/green.jpg');
        background-size: cover;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-8 col-lg-4">
        <div class="card rounded-box shadow">
          <div class="card-body">
            <h3 class="card-title text-center">Admin Login</h3>
            <img src="../../../pictures/logo.png" width="200px" height="200px" class="rounded mx-auto d-block mb-3">

            <form action="" method="post">
              <div class="form-floating mb-3">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-user"></i></span>
                  <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
              </div>
              <div class="form-floating mb-3">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-lock"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                  
                </div>
              </div>

              <?php
              if (isset($message)) {
                foreach ($message as $message) {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="fa-solid fa-circle-info"></i> ' . $message .
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                };
              };
              ?>
              <input type="submit" class="w-100 btn btn-dark fs-6" name="submit" value="Login">
            </form>
            <br>
            <a href="../../../login/login.php" class="text-center d-block text-dark">Login as User</a>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>