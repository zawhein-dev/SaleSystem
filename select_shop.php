<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
 require_once("./storage/branch_crud.php");
 require_once("./storage/branch_product_crud.php");
 require_once("./server_cart.php");
if(isset($_GET['branch_id'])){
    $branch_id = $_GET['branch_id'];
    $branch = get_branch_with_id($mysqli,$branch_id);
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
<body class="bg-light overflow-auto">
<div class="container_fluid bg-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?php echo $branch['branch_name']." Branch" ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Category
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_category.php">Add Category</a></li>
                            <li><a class="dropdown-item" href="../admin/category_list.php">Category List</a></li>
                        </ul>
                    </li>
                </ul>
                    <a class="navbar-brand " href="#" id="profileDropdown" >
                        <img src="./assets/userProfile/user.png" style="width: 60px; height: 60px; border-radius: 50%;" id="profileImage" alt="Image" class="ms-2">
                    </a>
            </div>
        </div>
    </nav>
    <div class="main d-flex">
        <div class="sidebar bg-info col-2 " style="height: 100vh;">
            j
        </div>
        <div class="main-content text-center mx-auto col-9 d-flex gap-2 mt-2 h-100">
    <?php 
    $product_in_branch = get_product_in_branch($mysqli, $branch_id);
    while ($product = $product_in_branch->fetch_assoc()) { ?>
        <div class="card w-25 position-relative">
            <div class="image-container position-relative">
            <a href="./product_detail.php?product_id=<?= $product['product_id'] ?>&branch_id=<?= $branch_id ?>" style="text-decoration: none;">
                <img class="card-img-top" src="./assets/product/<?= $product['photo'] ?>" style="width: 100%; height: 200px; object-fit: cover;" alt="Card image cap">
            </a>
            </div>
            <div class="card-body bg-light">
                <div class="card-text"><?= $product['product_name'] ?></div>
                <div class="d-flex">
                    <span>In stock: <?= $product['qty'] ?></span>
                    <span class="ms-auto"><?= $product['price'] ?> MMK</span>
                </div>
                <div>
                <a class="btn btn-sm" href="?product_id=<?= $product['product_id'] ?>&branch_id=<?= $branch_id ?>" style="width: 15rem; text-decoration: none;">Add to Cart</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

    </div>
</div>
</body>
</html>