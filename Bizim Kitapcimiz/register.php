<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'Kullanıcı E-postası Zaten Var!';
   }else{
      if($pass != $cpass){
         $message[] = 'Şifreler Eşleşmedi!';
      }else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image, status) VALUES(?,?,?,?,?)");
         $insert->execute([$name, $email, $pass, $image,'pending']);
         $message[]= 'Üyeliğiniz admin onayı bekliyor!';

         if($insert){
            if($image_size > 2000000){
               $message[] = 'Resim Boyutu Çok Büyük!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kayıt Ol</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Kayıt Ol</h3>
      <input type="text" name="name" class="box" placeholder="İsminizi Giriniz" required>
      <input type="email" name="email" class="box" placeholder="E-posta Adresinizi Giriniz" required>
      <input type="password" name="pass" class="box" placeholder="Şifrenizi Giriniz" required>
      <input type="password" name="cpass" class="box" placeholder="Şifrenizi Tekrar Giriniz" required>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Kayıt Ol" class="btn" name="submit">
      <p>Bir Hesabınmız varmı? <a href="login.php">Giriş Yap</a></p>
   </form>

</section>


</body>
</html>