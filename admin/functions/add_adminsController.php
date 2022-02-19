<?php
//db connection included
require 'dbconn.php';
$conn = mysqli_connect("localhost", "root", "", "walkit_app");

$errors = array();
$success = array();

//if submit button is clicked and inputs are not empty
if (isset ($_POST['submit']) && (isset ($_POST['admin']))){

  $admin = $_POST['admin'];
  $email = $_POST['email'];
  $code = $_POST['password'];
  $reg_date = date("Y-m-d H:i:s", time());
  $password = password_hash($code, PASSWORD_DEFAULT);
  $role = $_POST['role'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }
    elseif(strlen($code) < 8) {
        $errors['password'] = "Password too short";
    }else{
         $query = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
        if(mysqli_num_rows($query) > 0){
           $errors['email'] = "Email Exists";
        }else{
             $sql = 'INSERT INTO users(names, password, email, role, registered_on) VALUES(:admin, :password, :email, :role, :reg_date)';
            $statement = $connection->prepare($sql);

            if ($statement->execute([':admin' => $admin, ':password' => $password, ':email' => $email, ':role' => $role, ':reg_date' => $reg_date])) {
            $success['data'] = 'Admin registered successfully';
          
            }else{
                $errors['data'] = 'Ooops, an error occured';
            }
    }

    }


  }

?>