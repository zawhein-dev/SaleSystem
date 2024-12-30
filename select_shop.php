<?php require_once("./storage/db.php"); ?>

<?php require_once("./storage/user_crud.php");
require_once("./storage/branch_crud.php");
require_once("./storage/category_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./server_cart.php");

if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
    }
}
if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
    $branch = get_branch_with_id($mysqli, $branch_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light overflow-auto">
    <div class="container_fluid bg-white">
        <nav class="navbar navbar-expand-lg w-100 navbar-dark bg-dark  align-content-center">
            <div class="container-fluid">
                <!-- Navbar Brand -->
                <a class="navbar-brand fs-3" href="#"><?php echo $branch['branch_name'] . " Branch" ?></a>
                <!-- Navbar Toggle for Mobile View -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Center: Search Bar -->
                    <form class="d-flex mx-auto " style="width: 50%;">
                        <input class="form-control me-2 bg-body-secondary" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-info" type="submit">Search</button>
                    </form>
                    <!-- Right: User and Cart -->
                    <ul class="navbar-nav ms-auto  mb-2 mb-lg-0">
                        
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="./cart.php?branch_id=<?= $_GET['branch_id'] ?>">
                                <span class="text-light"> <i class="bi bi-cart"></i> Cart</span>
                                <span class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= count($item_array) ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item ms-3">
                            <span class="d-flex ">
                                <span class="text-light nav-link">
                                    <?php if (isset($currentUser)) {
                                                                        echo  $currentUser['user_name'];
                                                                    } else {
                                                                        echo "User";
                                                                    } ?>
                                                                    </span>
                                                                    <span>
                                <?php if (isset($currentUser)) { ?>
                                    <img src="./assets/userProfile/<?= $currentUser['profile'] ?>" style="width: 40px; height: 40px; border-radius: 50%;" id="profileImage" alt="Image">
                                <?php } else { ?>
                                    <img src="./assets/userProfile/user.png" style="width: 40px; height: 40px; border-radius: 50%;" id="profileImage" alt="Image">
                                <?php } ?>
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid row">
            <div class="bg-light col-2" style="height: 100vh;">
                <div class="bg-info text-center p-4 mt-2 rounded fs-4 fw-bold">
                    Category
                </div>
                <?php $categories = get_category($mysqli);
                while ($category = $categories->fetch_assoc()) {
                ?>
                    <div class="bg-white text-center p-2 mt-2 rounded fs-4 fw-bold">
                        <a href="?category_id=<?= $category['category_id'] ?>&branch_id=<?= $branch_id ?>" class="text-decoration-none text-black"><?= $category['category_name'] ?></a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-10">
                <div class="row">
                    <?php
                    $product_in_branch = get_product_in_branch($mysqli, $branch_id);
                    if (isset($_GET['category_id'])) {
                        $product_in_branch = get_branch_product_with_category_id($mysqli, $_GET['category_id'], $_GET['branch_id']);
                    }
                    while ($product = $product_in_branch->fetch_assoc()) { ?>
                        <div class="col-4 my-3">
                            <div class="card ">
                                <div class="image-container">
                                    <a href="./product_detail.php?product_id=<?= $product['product_id'] ?>&branch_id=<?= $branch_id ?>" style="text-decoration: none;">
                                        <img class="card-img-top" src="./assets/product/<?= $product['photo'] ?>" style="width: 100%; height: 200px; object-fit: cover;" alt="Card image cap">
                                    </a>
                                </div>
                                <div class="card-body bg-light">
                                    <div class="card-text text-center text-dark"><strong><?= $product['product_name'] ?></strong></div>
                                    <div class="d-flex">
                                        <span class="fw-bold"><?php if ($product['qty'] == 0) {
                                                                    echo "Out of Stock";
                                                                } else {
                                                                    echo "In stock: " . $product['qty'];
                                                                } ?></span>
                                        <span class="ms-auto"><?= $product['price'] ?> MMK</span>
                                    </div>
                                    <div class="text-center bg-success rounded mt-2">
                                        <a class="btn btn-sm" href="?product_id=<?= $product['product_id'] ?>&branch_id=<?= $branch_id ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" style="width: 15rem; text-decoration: none;">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Enable body scrolling</button> -->

<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Offcanvas with body scrolling</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <p>Try scrolling the rest of the page to see this option in action.</p>
  </div>
</div>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</body>

</html>