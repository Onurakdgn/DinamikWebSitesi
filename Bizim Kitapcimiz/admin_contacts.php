<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:admin_contacts.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>İletişim</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">İletişim</h1>

   <div class="box-container">

   <?php
      $select_message = $conn->prepare("SELECT * FROM `message`");
      $select_message->execute();
      if($select_message->rowCount() > 0){
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Kullanıcı idisi : <span><?= $fetch_message['user_id']; ?></span> </p>
      <p> Kullanıcı İsmi : <span><?= $fetch_message['name']; ?></span> </p>
      <p> Kullanıcı Numarası : <span><?= $fetch_message['number']; ?></span> </p>
      <p> Kullanıcı E-maili : <span><?= $fetch_message['email']; ?></span> </p>
      <p> Kullanıcı Mesajı : <span><?= $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Mesajı Silmek İstiyormusunuz?');" class="delete-btn">Sil</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Hiç Bir Mesaj Yok!</p>';
      }
   ?>

   </div>

</section>













<script src="js/script.js"></script>

</body>
</html>