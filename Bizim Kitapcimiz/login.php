<?php
// PHP program to pop an alert
// message box on the screen
  
// Function definition
function function_alert($message) {
      
    // Display the alert box 
    echo "<script>alert('$message');</script>";
}
  
?>
<?php

include 'config.php';

session_start();



if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   //$status = md5($_POST['status']);
   //$status = filter_var($status, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  
   
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   

   $sql2 = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt2 = $conn->prepare($sql2);
   $stmt2->execute([$email, $pass]);
   $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    $status = $row2['status'] ?? 'pending';
   

   if($rowCount > 0  ){

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }
      else if($row['user_type'] == 'user'){
         $_SESSION['status']=$row2['status'];
         $_SESSION['user_id'] = $row['id'];
      
         if($status=='approved'){
        
         $message[]='Giriş Başarılı!';
         header('location:home.php');
         function_alert("giriş yapıldı");
      }  
      else if($status=='pending'){
         
        
         function_alert("Hesabınız onay bekliyor!");
         //header('location:login.php');
      }
         
      else{
         $message[] = 'Kullanıcı bulunamadı!';
      }

   }else{
      $message[] = 'Yanlış Eposta veya şifre!';
   }}


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giriş Yap</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>


   
<section class="form-container">

   <form action="" method="POST">
      <h3>Giriş Yap</h3>
      <input type="email" name="email" class="box" placeholder="E-mailini Giriniz" required>
      <input type="password" name="pass" class="box" placeholder="Şifrenizi Giriniz" required>
      <input type="submit" value="Giriş Yap" class="btn" name="submit">
      <p>Hesabın Yok Mu?<a href="register.php">Şimdi Kayıt Ol</a></p>
   </form>

</section>


</body>
</html>