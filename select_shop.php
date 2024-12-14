<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
 require_once("./storage/branch_crud.php");
 require_once("./storage/branch_product_crud.php");
 require_once("./server_cart.php");
if (isset($_COOKIE['user'])) {
    header("location:./home.php");
}
if(isset($_GET['branch_id'])){
    $branch_id = $_GET['branch_id'];
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
    <div class="text-end me-4 mt-4">
    <a href="./cart.php?" class="btn btn-success">Cart</a>
        <a href="./login.php" class="btn btn-success">Login</a>
        <a href="./register.php" class="btn btn-info">Register</a>
    </div>
    <?php if(isset($_GET['branch_id'])){
         $branch_id = $_GET['branch_id'];
     $branch = get_branch_with_id($mysqli,$branch_id);
    ?>
    <div class="text-center">
            <span class="fs-2"> <?php echo $branch['branch_name']." Branch" ?></span>
            <span class=" fs-4 ms-3"> <?= $branch['address']?></span>
    </div>
        <?php 
         $product_in_branch = get_product_in_branch($mysqli,$branch_id);?>
         <div class="container_fluid d-flex row">
    <?php while($product = $product_in_branch->fetch_assoc()){ ?>
        <div class="text-center col-3 mt-2">
            <div class=" card mx-2 bg-light">
                <div class="">
                <a href="?product_id=<?= $product['product_id'] ?>&branch_id= <?= $branch_id ?>" style="width: 15rem; text-decoration: none;" >
                    <div style="font-size: 25px;"><?= $product['product_name'] ?></div>
                    <div><img src="./assets/image/img_6757e7d06917d.jpg" alt="" style="width: 400px; height: 200px;"></div>
                   <div class="justify-content-evenly d-flex">
                  <div style="font-size: 19px;"> <?php echo "In Stock ". $product['qty'] ?> </div>
                   <div style="font-size: 19px;"> <?php echo $product['price']." MMK" ?> </div>
    </a>
                   </div>
                </div>
            </div>
        </div>
       <?php }} ?>
    </div>
    
</body>
</html>