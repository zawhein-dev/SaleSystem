<?php
require_once("./storage/db.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
session_start();
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
}
if(isset($_POST['submit'])){
    $qty = $_POST['qty'];
    $product_post_id = $_POST['product_id'];
    $branch_post_id = $_POST['branch_id'];
    // var_dump($product_post);
    // var_dump($branch_post);
    $item_array = [];
    if (isset($_SESSION["item_list"])) {
        $item_array = $_SESSION['item_list'];
    }
        $branch_product = get_branch_product_for_order_detail($mysqli,$product_post_id,$branch_post_id);
    //    var_dump($branch_product);
        // $isHave = true;
        // for ($i = 0; $i < count($item_array); $i++) {
        //     if ($product_id == $item_array[$i]['product_id']) {
        //             $isHave = false;
        //             $item_array[$i]['qty']++;
        //     }
        // }
        // if($isHave){
            array_push($item_array,['product_id'=> $branch_product['product_id'],'branch_product_id' => $branch_product['branch_product_id'],'product_name' => $branch_product['product_name'],'price' => $branch_product['price'],'branch_name' => $branch_product['branch_name'],'qty' => $qty]);
        // }
        $_SESSION["item_list"] = $item_array;
        // header("Location:?branch_id=$branch_id");
    header("location:cart.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>
<body>
    <table class="table table-striped">
    <thead>
        <th>Product Name</th>
        <th>Photo</th>
        <th>Price</th>
        <th>Description</th>
        <th>Category Name</th>
    </thead>
    <tbody>
        <tr>
            <td><?= $product['product_name'] ?></td>
            <td><img src="./assets/product/<?= $product['photo'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
            <td><?= $product['price'] ?></td>
            <td>
                <form action="" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                <button type="button" id="minus" class="btn btn-sm btn-success">-</i></button>
                <input type="text" name="qty" id="qty" class="btn btn-sm bg-light w-25" value="1">
                <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
            </td>
            <td><?= $product['description'] ?></td>
            <td><?= $product['category_name'] ?></td>
        </tr>
    </tbody>
    </table>
    <button type="submit" name="submit" class="btn btn-success" >Add to cart</>
    <div class="btn btn-success">Check Out</div>
    </form>
<script>
    qty_in_branch = 6;
    plus = document.getElementById('plus');
    qty = document.getElementById("qty");
    minus =document.getElementById('minus');
    plus.addEventListener("click",()=>{
        if(qty_in_branch != qty.value){
            qty.value ++;
        }
        // qty.innerHTML = qty.value;
    })
    minus.addEventListener("click",()=>{
        if(qty.value != 1){
            qty.value--;
            // qty.innerHTML = qty.value;
        }
    })
    
</script>
</body>
</html>