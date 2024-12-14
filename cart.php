<?php session_start();
 $item_array =  $_SESSION["item_list"];
 if(isset($_GET['order'])){
    if(!isset($_COOKIE['user'])){
        header("Location:./register.php");
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>
<body>
   <div class="container w-75 mx-auto mt-3">
   <table class="table table_striped">
        <thead>
            <th>Branch Name</th>
            <th>Product Name</th>
            <th>Branch Product Id</th>
            <th>Price</th>
            <th>qyt</th>
        </thead>
        <tbody>
        <?php
    foreach ($item_array as $index => $item) { 
       ?>
        <tr>
            <td><?= $item['branch_name'] ?></td>
            <td><?= $item['product_name'] ?></td>
            <td><?= $item['branch_product_id'] ?></td> 
            <td><?= $item['price'] * $item['qty']  ?></td>
            <td><?= $item['qty'] ?></td>
        </tr>
        <?php }?>
        </tbody>
    </table>
    <a href="?order" class="btn btn-success">Order</a>
   </div>
</body>
</html>