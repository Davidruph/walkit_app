<?php
//db connection included
require 'dbconn.php';
$conn = mysqli_connect("localhost", "root", "", "walkit_app");

$errors = array();
$success = array();
$id = $_SESSION['user'];

//if submit button is clicked and inputs are not empty
if (isset ($_POST['submit']) && (isset ($_POST['name']))){

  $name = $_POST['name'];
  $email = $_POST['email'];
  $reg_date = date("Y-m-d H:i:s", time());
  $company_name = $_POST['company_name'];
  $address = $_POST['address'];
  $url = $_POST['url'];
  $country = $_POST['country'];
  $mobile_code = $_POST['mobile_code'];
  $mobile_no = $_POST['mobile'];
  $id = $_SESSION['user'];



    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }

    else{

      if($_FILES['logo']['name'] == "" || $_FILES['logo']['size'] == 0) {
      // No file was selected for upload, your (re)action goes here
       $query = mysqli_query($conn, "SELECT url FROM users WHERE url='$url' AND user_id!=$id");
            if(mysqli_num_rows($query) > 0){
               $errors['pass'] = "Pls this Url has been used";
            }else{

           $sql = 'UPDATE users SET names=:name, country=:country, mobile_code=:mobile_code, mobile_no=:mobile_no, email=:email, company_name=:company_name, address=:address, url=:url, registered_on=:reg_date WHERE user_id=:id';
          $statement = $connection->prepare($sql);
          if ($statement->execute([':name' => $name, ':country' => $country, ':mobile_code' => $mobile_code, ':mobile_no' => $mobile_no, ':email' => $email, ':company_name' => $company_name, ':address' => $address, ':url' => $url, ':reg_date' => $reg_date, ':id' => $id])) {
            $success['data'] = "your details has been updated successfully";
          }else{
            $errors['data'] = 'Ooops, an error occured';
          }
       }
      }else{

          $imgfile = $_FILES["logo"]["name"];
          $extension = substr($imgfile,strlen($imgfile)-4,strlen($imgfile));
          // allowed extensions
          $allowed_extensions = array(".jpg","jpeg",".png",".gif");
          // Validation for allowed extensions .in_array() function searches an array for a specific value.
          if(!in_array($extension, $allowed_extensions))
          {
          echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
          }
          else
          {
          //rename the image file
          $imgnewfile = md5($imgfile).$extension;
          $temp_name = $_FILES['logo']['tmp_name'];

            $query = mysqli_query($conn, "SELECT url FROM users WHERE url='$url' AND user_id!=$id");
            if(mysqli_num_rows($query) > 0){
               $errors['pass'] = "Pls this Url has been used";
            }else{

            $sql = 'UPDATE users SET names=:name, country=:country, mobile_code=:mobile_code, mobile_no=:mobile_no, email=:email, company_name=:company_name, logo=:imgnewfile, address=:address, url=:url, registered_on=:reg_date WHERE user_id=:id';
          $statement = $connection->prepare($sql);
          if ($statement->execute([':name' => $name, ':country' => $country, ':mobile_code' => $mobile_code, ':mobile_no' => $mobile_no, ':email' => $email, ':company_name' => $company_name, ':imgnewfile' => $imgnewfile, ':address' => $address, ':url' => $url, ':reg_date' => $reg_date, ':id' => $id])) {
            move_uploaded_file($temp_name,"uploads/".$imgnewfile);
            $success['data'] = "your details has been updated successfully";
          }else{
            $errors['data'] = 'Ooops, an error occured';
          }

    }

  }
}
 }
 }

  if (isset($_POST['password_change'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $id = $_SESSION['user'];

        $query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            $id = $row['user_id'];
            $old_pwd = $row['password'];

             if(password_verify($old_password, $old_pwd)){
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE user_id = '$id'");
                if ($sql) {
                    session_destroy();
                    header('location: ../signin.php');
                }
             }else{
                $errors['password'] = "Incorrect password or user does not exist";
             }
         }
    }

?>