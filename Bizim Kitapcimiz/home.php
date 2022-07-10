<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'Beğenilenlerde Zaten Var!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'Sepette Zaten Var!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, image) VALUES(?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_image]);
      $message[] = 'Beğenilenlere Eklendi!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'Sepete Zaten Eklendi!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Sepete Eklendi!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ana Sayfa</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Bizim Kitapcımız</span>
         <h3>Kitaplar Hakkında Bilgi İçin Bu Siteyi İnceleyebilirsiniz.</h3>
         <p>Daha Fazla Bilgi Almak İçin Hakkımızda Kısmına Gidebilirsiniz...</p>
         <a href="about.php" class="btn">Hakkımızda</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title"> Kategoriler</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Şiir Kitapları</h3>
         <p>Şiir Kitaplarını Görmek İçin Aşşağıdaki Butona Tıklayınız..</p>
         <a href="category.php?category=Şiir Kitapları" class="btn">Şiir Kitapları</a>
      </div>

      <div class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Bilim Kurgu Kitapları</h3>
         <p>Bilim Kurgu Kitaplarını Görmek İçin Aşşağıdaki Butona Tıklayınız..</p>
         <a href="category.php?category=Bilim Kurgu Kitapları" class="btn">Bilim Kurgu Kitapları</a>
      </div>

      <div class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Çocuk Kitapları</h3>
         <p>Çocuk Kitaplarını Görmek İçin Aşşağıdaki Butona Tıklayınız..</p>
         <a href="category.php?category=Çocuk Kitapları" class="btn">Çocuk Kitapları</a>
      </div>

      <div class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Gizem Kitapları</h3>
         <p>Gizem Kitaplarını Görmek İçin Aşşağıdaki Butona Tıklayınız..</p>
         <a href="category.php?category=Gizem Kitapları" class="btn">Gizem Kitapları</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">En Son Eklenen</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      
      <input type="submit" value="Beğenilenlere Ekle" class="option-btn" name="add_to_wishlist">
      
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Hiç Bir Ürün Eklenmedi!</p>';
   }
   ?>

   </div>

</section>







<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>