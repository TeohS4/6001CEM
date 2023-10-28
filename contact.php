<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);
$alert = '';

if (isset($_POST['submit'])) {
  $name = ($_POST['name']);
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $okay = true;

  if (empty($name) && empty($email) && empty($subject) && empty($message)) {
    $alert = '<div class="alert alert-danger" role="alert">Please fill out all the fields</div>';
    $okay = false;
  } else {
    if (empty($name)) {
      $alert = '<div class="alert alert-danger" role="alert">Fill in your name</div>';
      $okay = false;
    } else if (ctype_alpha(str_replace(' ', '', $name)) == false) {
      $alert = '<div class="alert alert-danger" role="alert">Only letters and spaces are allowed in the Name field</div>';
      $okay = false;
    }

    if (empty($email)) {
      $alert = '<div class="alert alert-danger" role="alert">Fill in email</div>';
      $okay = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $alertMessages .= '<div class="alert alert-danger" role="alert">Invalid E-mail address.</div>';
      $okay = false;
    }

    if (empty($subject)) {
      $alert = '<div class="alert alert-danger" role="alert">Fill in subject.</div>';
      $okay = false;
    }

    if (empty($message)) {
      $alert = '<div class="alert alert-danger" role="alert">Fill in message.</div>';
      $okay = false;
    }
  }

  if ($okay) {
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'weijieteoh26@gmail.com'; // Email used as SMTP server
      $mail->Password = 'icnigwfanwuydwap'; // Secret Gmail pasword
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = '587';

      $mail->setFrom($email);
      $mail->addAddress('weijieteoh26@gmail.com'); // Receiver

      $mail->isHTML(true);
      $mail->Subject = 'Message Received';
      $mail->Body = '<h3>Name: ' . $name . '<br>Subject: ' . $subject . '<br>Message: ' . $message . '</h3>';

      $mail->send();
      $alert = '<div class="alert alert-success" role="alert">
              Your Message has been sent, Thank you for contacting us
            </div>';
    } catch (Exception $e) {
      $alert = '<div class="alert-error">
              ' . $e->getMessage() . '
            </div>';
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Contact Us</title>
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

  <!-- breadcrumb start-->
  <section class="breadcrumb breadcrumb_bg">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb_iner text-center">
            <div class="breadcrumb_iner_item">
              <h2>Contact Us</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- breadcrumb start-->

  <!-- ================ contact section start ================= -->
  <section class="contact-section section_padding">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h2 class="contact-title">Get in Touch With Us</h2>
        </div>
        <div class="col-lg-8">
          <form class="form-contact contact_form" action="contact.php" method="post" id="contactForm" novalidate="novalidate">
            <!-- Alert Message -->
            <?php echo $alert; ?>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="name" id="name" type="text" placeholder='Enter your name'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="email" id="email" type="email" placeholder='Enter email address'>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input class="form-control" name="subject" id="subject" type="text" placeholder='Enter Subject'>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" placeholder='Enter Message'></textarea>
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <input type="submit" name="submit" class="button button-contactForm btn_4">
            </div>
          </form>
        </div>
        <!-- End Form -->
        <div class="col-lg-4">
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-home"></i></span>
            <div class="media-body">
              <h3>Straits Rd</h3>
              <p>8076436</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
            <div class="media-body">
              <h3>+60 124567890</h3>
              <p>Mon to Fri 10am to 5pm</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-email"></i></span>
            <div class="media-body">
              <h3>weijieteoh26@gmail.com</h3>
              <p>Send us your query anytime!</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="fa-solid fa-location-dot"></i></span>
            <div class="media-body">
              <h3>Our Shop Location</h3>
              <p>Visit Us!</p>
              <iframe src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=The Bright Beaver
                  &amp;t=h&amp;z=15&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" frameborder="0" style="border:0; width: 130%; 
                  height: 350px; border-radius: 12px; box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);" allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ contact section end ================= -->

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