<?php

@include 'config.php';
 
include "baglan.php";
 
$sql ="SELECT * FROM products";
$sorgu = $baglan->prepare($sql);
$sorgu->execute();
 
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   //$price = $_POST['price'];
   //$price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Kitap Adı Zaten Var!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, image) VALUES(?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $image]);

      if($insert_products){
         if($image_size > 2000000){
            $message[] = 'Resim Boyutu Çok Büyük!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Yeni Kayıt Eklendi!';
         }

      }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kitaplar</title>

   <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Yeni Kitap EKle</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Kitap İsmini Griiniz">
         <select name="category" class="box" required>
            <option value="" selected disabled>Kategori Seç</option>
               <option value="Şiir Kitapları">Şiir Kitapları</option>
               <option value="Bilim Kurgu Kitapları">Bilim Kurgu Kitapları </option>
               <option value="Çocuk Kitapları">Çocuk Kitapları </option>
               <option value="Gizem Kitapları">Gizem Kitapları</option>
         </select>
         </div>
         <div class="inputBox">
        <!input type="number" min="0" name="price" class="box" required placeholder="enter product price">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Kitabın Özetini Giriniz" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Kitabı EKle" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Eklenmiş Kitaplar</h1>
   
   <table class="table table-hover table-blue table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Ad</th>
                                <th>Kategori</th>
                                <th>Özet</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($satir = $sorgu->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?=$satir['id']?></td>
                                <td><?=$satir['name']?></td>
                                <td><?=$satir['category']?></td>
                                <td><?=$satir['details']?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="admin_update_product.php?update=<?=$satir['id']?>" class="btn btn-secondary">Güncelle</a>
                                        <a href="admin_products.php?delete=<?=$satir['id']?>" onclick="return confirm('Silinsin mi?')" class="btn btn-danger">Kaldır</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
</section>

<script src="js/script.js"></script>

</body>
</html>