<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Onaylama</title>

    

</head>
<body>

<a href='admin_page.php'>Geri Gel</a>
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
    

<div class="center">
    <h1>Kullanıcı Kayıt Onaylama</h1>
    
    <table table border="1" cellpadding="10"> 

        <caption> Kullanıcı Kayıt Onaylama </caption> 
    <tr>
        <th>Id</th>
        <th>Ad</th>
        <th>E-mail</th>
        <th>Eylem</th>
    </tr>

    <?php
    $query = "SELECT * FROM users WHERE status = 'pending' ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
    ?>

    <tr>
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td>
         <form  action="admin-approval.php" method ="POST">
            <input type ="hidden" name="id" value = "<?php echo $row['id'];?>">
            <input type ="submit" name="approve" value = "Onayla">
            <input type ="submit" name="deny" value = "Onaylama">
        </td>
    </tr>
</table>

<?php
        }
 ?>        

</div>

<?php
if(isset($_POST['approve'])){
$id = $_POST['id'];

$select = "UPDATE users SET status = 'approved' WHERE id = '$id'";
$result = mysqli_query($conn , $select);

$message[]='Üyelik onaylandı';
header('location:admin-approval.php');
}

if(isset($_POST['deny'])){
    $id = $_POST['id'];
    
    $select = "DELETE FROM users WHERE id = '$id'";
    $result = mysqli_query($conn , $select);
    
    $message[]='Üyelik onaylanmadı';
    header('location:admin-approval.php');
    }
?>

</body>
</html>