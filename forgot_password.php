<?php 
session_start();
$errors = array();
$success = array();
$conn = mysqli_connect("localhost", "root", "", "walkit_app");
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'admin/vendor/autoload.php';
require_once 'admin/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'admin/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once 'admin/vendor/phpmailer/phpmailer/src/Exception.php';


if($_SERVER["REQUEST_METHOD"] == "POST"){
  $email = $_POST['email'];
   $str = '0089773bcghucjdJFGJDNDTEMNVgdhdhjabcdef0987654321';
    $token = str_shuffle($str);
    
    if($email === "") {
         $errors['fields'] = "Email is Required";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }
  else{
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
        
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            $email = $row['email'];
            $_SESSION['email'] = $email;
            $email =  $_SESSION['email'];
            $id = $row['user_id'];
            $link="https://fiwithbitcoin.com/walkit/reset_password.php?reset_code=$token";

            $to = '$email';
            $subject = 'Password Reset link';
            // ****HTML Elements for Email Body
            $message='
            <div>
                <p>Please follow this link to reset your password</p>
                <p><a href="'.$link.'">Click to reset</a></p>
            </div>
            ';

             try{
             $mail = new PHPMailer;
            $mail->Host = 'smtp.gmail.com';
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Username = 'paytest432@gmail.com'; // Gmail address which you want to use as SMTP server
            $mail->Password = 'qwerty1234!@#$%'; // Gmail address Password // Gmail address Password
            $mail->Port = 465; //587
            $mail->SMTPSecure = 'ssl'; //tls
              $mail->addAddress($email); // Email address where you want to receive emails (you can use any of your gmail address including the gmail address which you used as SMTP server)
              $mail->setFrom('fiwithbitcoin@gmail.com', 'Reset Password'); // Gmail address which you used as SMTP server
              //$mail->SMTPDebug = 2;
              $mail->isHTML(true);
              $mail->Subject = 'password reset link';
              $mail->Body = $message;
              if ($mail->send()){
                  $success['testt'] = "Password Reset link has been sent your email";
                   $query = mysqli_query($conn, "UPDATE users SET verification_code = '$token' WHERE email = '$email'");
              }else{
                $errors['data'] = 'An error occured!';
            }
         } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
 }else{
    $errors['mail'] = 'Email not found';
    }

 }

 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-light">

  <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4">
          <h1 class="text-center text-dark mt-5 mb-4">Forgot Password</h1>
           <?php if (count($errors) > 0): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach($errors as $error): ?> 
                <li style="color: red"><?php echo $error; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <?php if (count($success) > 0): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach($success as $succes): ?> 
                <li style="color: green"><?php echo $succes; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
          <div class="w-100 shadow trans card">
           
            <form method="POST" action="forgot_password.php">
              <div class=" form-group mt-4 mr-4 ml-4">
                <label for="Username">Enter Email address</label>
                <input type="text" class="form-control" name="email" value="" required placeholder="jane@domain.com">
              </div>

              <div class="form-group mr-4 ml-4">
                <input type="submit" class="btn btn-success login w-100 mt-2 mb-3" name="submit" value="Get Link">
              </div>
            </form>
          </div>
          <div class="trans card w-100 mt-3 shadow">
            <p class="text-center mt-2"><a href="signin.php">Go Back</a></p>
          </div>
        </div>
    </div>
  </div>





<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>