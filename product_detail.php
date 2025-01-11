<?php
require_once("./storage/db.php");
require_once("./storage/user_crud.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");

session_start();
if(isset($_SESSION['item_list'])){
    $item_array = $_SESSION['item_list'];
}
$show = false;
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);
  
    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
      $user_id = $userData['user_id'];
      $currentUser = get_user_with_id($mysqli, $user_id);
    }
  }
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
}
if (isset($_POST['checkout'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_post_id == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }

        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
        }

        $_SESSION["item_list"] = $item_array;
        if (!isset($_COOKIE['user'])) {
            header("Location:./register.php?order");
        } else if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], associative: true);
            $user_id = $userData['user_id'];
            //     // $currentUser =   get_user_with_id($mysqli, $user_id);
            if (save_order_product($mysqli, $user_id)) {
                $order_product_id = get_last_order_product_id($mysqli);
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                    save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                    update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
                unset($_SESSION["item_list"]);
                session_destroy();
                header("Location:./home.php?user_id =" . $user_id);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $products = get_product_with_id($mysqli, $product_post_id);
        $product_id_from_db = $products['product_id'];
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_id_from_db == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }
        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
            $_SESSION["item_list"] = $item_array;
            header("location:cart.php?branch_id=" . $branch_post_id);
        } else {
            $show = true;
            $_SESSION["item_list"] = $item_array;
            // header("location:cart.php?branch_id=".$branch_post_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
    <style>
        #profileDropdown::after {
        display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container justify-content-between">
        <div>
      <a class="navbar-brand mx-5 fs-4 fw-bold" href="./index.php">Electronic Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      </div>
      <div>
      <div class="collapse navbar-collapse ms-5" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item position-relative mt-2 me-3">
                              <a class="nav-link" href="./cart.php?branch_id=<?= $_GET['branch_id'] ?>">
                                <i class="bi bi-cart fs-4"></i>
                                <span class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger">
                                  <?= count($item_array) ?>
                                </span>
                    </a>
                    </li>   
                    <li class="nav-item dropdown align-content-center">
                    <?php if(isset($currentUser)){ ?>
                      <?= $currentUser['user_name']?>
                       <?php  } ?>
                    </li>
                    
                    <li class="nav-item dropdown align-content-center ms-2">
                    <form method="post">
                    <div class="dropdown">
                      <?php if(isset($currentUser)){ ?>
                        <a class="navbar-brand dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="./assets/userProfile/<?= $currentUser['profile'] ?>" style="width: 60px; height: 60px; border-radius: 50%;" id="profileImage" alt="userImage">
                        </a>
                        <?php } ?>
                          <!-- <a class="navbar-brand dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="./assets/userProfile/userlogo.jpg" style="width: 60px; height: 60px; border-radius: 50%;" id="profileImage" alt="userImage">
                        </a>-->
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <?php if($currentUser['role'] == 1){ ?>
                                <li><a class="dropdown-item" href="./admin/index.php">Profile</a></li>
                                <?php } else { ?>
                                <li><a class="dropdown-item" href="./user/index.php">Profile</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    </form>
                    </li>
                    <li class="nav-item dropdown align-content-center">
                    <?php if(!isset($currentUser)){ 
                        echo "<a href='./login.php' class='btn border-info me-1'>Login</a>";
                        echo "<a href='./register.php' class='btn border-info ms-1'>Register</a>";
                    }?>
                    </li>
                </ul>
      </div>
      </div>
    </div>
  </nav>
        
    <?php if ($show) { ?>
        <div class="text-center align-content-center">
            <div class="mx-auto w-50">
                <p class="text-dark"><a href="./cart.php?branch_id=<?= $branch_post_id ?>">View Cart!</a> The product you choose is already exist in cart.</p>
            </div>
        </div>
    <?php  } ?>
            <div class="container-fluid mt-2">
            <div class="row mb-3">
                <div class="left-side col-lg-6 col-sm-12 bg-white">
                    <div class="w-75 mx-auto align-content-center bg-light " style="height: 80vh;">
                        <img src="./assets/product/<?= $product['photo'] ?>" class="img-fluid mt-2 mb-2" style="height: 400px; width: 90%;margin-left: 5%; margin-right: 5%;" alt="product">
                    </div>
                </div>
                <div class="right-side col-lg-6 col-sm-12  bg-light">
                    <div class="mt-4 ms-3">
                        <h4><?= $product['product_name'] ?></h4>
                        <h5><?= $product['category_name'] ?> <small class="text-secondary">category</small></h5>
                        <div class="mb-5 me-5 " style="width: 300px; height: 200px; text-align:justify;">
                            <p><?= $product['description'] ?></p>
                        </div>
                        <div class="mt-4">
                                <span class="mt-5 mb-4 text-reset fw-bolder fs-6 me-3">Instock: <?= $branch_product['qty'] ?></span>
                                <span class="mt-5 mb-4 text-reset fw-bolder"><?= number_format($product['price'],  0, ".", ",") ?> MMK</span>
                            <form action="" method="POST" class="mt-3">
                                <input type="hidden" name="currentBranchProductQty" id="currentBranchProductQty" value="<?= $branch_product['qty'] ?>">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                                <button type="button" id="minus" class="btn btn-sm btn-primary">-</i></button>
                                <input type="text" style="width: 50px;" readonly name="qty" id="qty" class="btn btn-sm bg-info rounded" value="1">
                                <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
                                <button type="submit" name="submit" class="btn btn-success mx-3" <?php if($branch_product['qty'] <= 0) {echo "disabled";} else{ echo "";} ?>>Add to cart</button>
                                <button name="checkout" class="btn btn-success" <?php if($branch_product['qty'] <= 0) {echo "disabled";} else{ echo "";} ?>>Check Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script>
        // qty_in_branch = 6;
        $currentBranchProductQty = document.getElementById('currentBranchProductQty');
        plus = document.getElementById('plus');
        qty = document.getElementById("qty");
        minus = document.getElementById('minus');
        plus.addEventListener("click", () => {
            // if (qty_in_branch != qty.value) {
            if ($currentBranchProductQty.value > qty.value) {
                qty.value++;
            }
        })
        minus.addEventListener("click", () => {
            if (qty.value != 1) {
                qty.value--;
            }
        })
    </script>
</body>

</html>