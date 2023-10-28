<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <link rel="icon" href="../pictures/admin logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .text-center {
            text-align: center;
        }

        .cu-center-h1,
        .cu-center-h3 {
            text-align: center;
        }

        .cu-hr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
        }

        .cu-custom-hr {
            width: 5%;
            border-color: black;
            border-width: 2px;
        }

        .cu-section {
            background-color: #d3f1f6;
            padding: 20px;
            width: 65%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .cu-section h5,
        .cu-section p {
            text-align: center;
        }

        .contact-underlined-input {
            border: none;
            border-bottom: 2px solid green;
            background-color: transparent;
            border-radius: 0;
            padding: 5px;
        }

        .btn.btn-outline-primary.btn-block {
            border-color: green;
            color: green;
            background-color: transparent;
            width: 500px; 
            height: 50px;    
        }

        .btn.btn-outline-primary.btn-block:hover {
            background-color: green; 
            color: white; 
            width: 500px;
            height: 50px;     
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <br /><br />
        <div class="container d-flex align-items-center justify-content-center h-100">
                <img src="../pictures/logo.png" alt="EcoPack Logo" class="logo" style="width:11rem;height:11rem;">
        </div>
        <br /><br />

        <div class="cu-section">
            <h5>Rest Password</h5>
            <p>Enter a new password for login</p>

                <?php
                    session_start();
                    if(isset($_SESSION['status']))
                    {
                        ?>
                        <div class="alert alert-success">
                            <h5>
                                <?= $_SESSION['status']; ?>
                            </h5>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }        
                ?>
            
                <section class="mb-4">
                <div class="row justify-content-center align-items-center">
                    <!--Grid column-->
                    <div class="col-md-9 mb-md-0 mb-5">
                        <form action="reset-password-code.php" method="POST">
                            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>">

                            <!--Grid row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="md-form mb-0">
                                        <label>Email Address</label>
                                        <input type="text" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" class="form-control" placeholder="Enter Email Address">                                   
                                    </div>
                                </div>
                            </div>
                            <!--Grid row-->

                            <br />

                            <!--Grid row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="md-form mb-0">
                                        <label>New Password</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="New Password">                                   
                                    </div>
                                </div>
                            </div>
                            <!--Grid row-->

                            <br />

                            <!--Grid row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="md-form mb-0">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">                                   
                                    </div>
                                </div>
                            </div>
                            <!--Grid row-->

                            
                            <br /><br />

                            <div class="text-center text-md-left">
                                <button type="submit" name="password_update" class="btn btn-outline-primary btn-block">Send</button>
                            </div>
                            <div class="status"></div>
                        <!--Grid column-->
                        </form>
                    </div>
                </div>
            </section>

        </div>
        <br /><br />
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>

</html>