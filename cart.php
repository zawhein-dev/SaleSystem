<?php
require_once("./storage/db.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_crud.php");
require_once("./storage/user_crud.php");

session_start();
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);
  
    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
      $user_id = $userData['user_id'];
      $currentUser = get_user_with_id($mysqli, $user_id);
    }
  }
if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
    $branch = get_branch_with_id($mysqli, $branch_id);
  }
if (isset($_GET['add'])) {
    $index = $_GET['add'];
    $item_array = $_SESSION["item_list"];

    for ($i = 0; $i < count($item_array); $i++) {
        if ($i == $index) {
            $product_name = $item_array[$i]['product_name'];
            $productWithName = get_product_id_with_name($mysqli, $product_name);
            $product_id = $productWithName['product_id'];
            $branch_id = $_GET['branch_id'];
            $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
            if ($branch_product['qty'] > $item_array[$i]['qty']) {
                $item_array[$i]['qty']++;
            }
        }
        $_SESSION["item_list"] = $item_array;
        header("Location:?branch_id=" . $branch_id);
    }
}
if (isset($_GET['remove'])) {
    $item_array = $_SESSION["item_list"];
    array_splice($item_array, $_GET['remove'], 1);
    $_SESSION["item_list"] = $item_array;
    header("Location:?branch_id=" . $branch_id);
}
if (isset($_GET['minus'])) {
    $index = $_GET['minus'];
    $item_array = $_SESSION["item_list"];
    for ($i = 0; $i < count($item_array); $i++) {
        if ($i == $index) {
            if ($item_array[$i]['qty'] != 1) {
                --$item_array[$i]['qty'];
            }
        }
        $_SESSION["item_list"] = $item_array;
        header("Location:?branch_id=" . $branch_id);
    }
}
if (isset($_GET['order'])) {
    if (!isset($_COOKIE['user'])) {
        header("Location:./register.php?order");
    } else if (isset($_COOKIE['user'])) {
        // Decode the JSON string into a PHP associative array
        $userData = json_decode($_COOKIE['user'], associative: true);
        $user_id = $userData['user_id'];
        // var_dump($user_id);
        // $currentUser =   get_user_with_id($mysqli, $user_id);
        
        if (save_order_product($mysqli, $user_id)) {
            $order_product_id = get_last_order_product_id($mysqli);
            $item_array =  $_SESSION["item_list"];
            foreach ($item_array as $index => $item) {
                $current_branch_id = $item['branch_id'];
                $current_product_id = $item['product_id'];
                $current_branch_product = get_branch_product_for_order_detail($mysqli, $current_product_id, $current_branch_id);
                if($current_branch_product['qty'] == 0){
                  array_splice($item_array, $index, 1);
                }else{
                  $total = $item['qty'] * $item['price'];
                  save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                  update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
            }
            unset($_SESSION["item_list"]);
            session_destroy();
            header("Location:./home.php?user_id =" . $user_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <!--  -->
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
      <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        #profileDropdown::after {
        display: none;
        }
    </style>
</head>
<body class="bg-white">
    <nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
      <a class="navbar-brand fw-bolder" href="./index.php">Electronic Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
        <!-- <form class="d-flex mx-auto w-50" role="search" method="get">
          <input type="hidden" value=" _GET['branch_id'] " name = "branch_id">
          <!-- <input type="hidden" value="category_id from php ?>" name="search_category_id"> 
           <input class="form-control me-2" name="search_data" type="text" placeholder="Search" aria-label="Search" /> 
          <button class="btn btn-outline-success" name="search">
            <i class="bi bi-search"></i>
          </button>
        </form>  -->
        <div class="d-flex align-items-center">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown align-content-center">
                    <?php if(isset($currentUser)){ ?>
                      <?= $currentUser['user_name']?>
                       <?php  } ?>
                    </li>
                    <li class="nav-item dropdown align-content-center ms-3">
                <form method="post">
                    <div class="dropdown">
                        <?php 
                        if(isset($currentUser)){ ?>
                            <a class="navbar-brand dropdown-toggle" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="./assets/userProfile/<?= $currentUser['profile'] ?>" 
                                     style="width: 60px; height: 60px; border-radius: 50%;" 
                                     id="profileImage" 
                                     alt="userImage">
                            </a>
                        <?php } ?>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <?php if($currentUser['role'] == 1){ ?>
                                <li><a class="dropdown-item" href="./admin/index.php">Admin Profile</a></li>
                            <?php } else { ?>
                                <li><a class="dropdown-item" href="./user/index.php">User Profile</a></li>
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
    <div class="container-fluid row  mx-auto mt-1">
        <?php if (isset($_SESSION["item_list"])) {
            $item_array =  $_SESSION["item_list"];
            if (count($item_array) > 0) { ?>
                <div class="col-8 mt-5 border-rounded">
                    <table class="table table_striped">
                        <thead class="text-center">
                            <th>Branch Name</th>
                            <th>Product Name</th>
                            <th>qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                            $net_total = 0;
                            foreach ($item_array as $index => $item) {
                                $subtotal = $item['price'] * $item['qty'];
                                $net_total += $subtotal;
                            ?>
                                <tr class="text-center" >
                                    <td><?= $item['branch_name'] ?></td>
                                    <td><?= $item['product_name'] ?></td>
                                    <td>
                                        <a href="?minus=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-success">-</i></a>
                                        <input type="number" readonly class="btn btn-sm bg-light w-25" value="<?= $item['qty'] ?>">
                                        <a href="?add=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-primary">+</i></a>
                                        <a href="?remove=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-danger">X</a>
                                    </td>
                                    <td class="text-end"><?=  number_format($item['price'],  0, ".", ",") ?></td>
                                    <td class="text-end"><?=  number_format($subtotal, 0, ".", ",") ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-4 mt-5">
                    <div class="card">
                        <div class="card-header"> 
                            <div class="card-title fs-4">Cart Total</div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-3 border-bottom mt-2 mb-2">
                                <div class="card-text fw-bold ">Subtotal</div>
                                <div class="card-text fw-medium "><?= number_format($net_total, 0, ".", ",") . " MMK" ?></div>
                            </div>
                            <!-- <div class="d-flex justify-content-between p-3 mt-2 mb-2 border-bottom">
                                <div class="card-text fw-bold">Shipping</div>
                                <div class="card-text fw-medium"><?= "1000 MMK" ?></div>
                            </div> -->
                            <div class="d-flex justify-content-between p-3 mt-2 mb-2">
                                <div class="card-text mb-0 fw-bold fs-5">Total</div>
                                <div class="card-text fw-medium "><?= number_format($net_total, 0, ".", ",") . " MMK" ?></div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="?order" class="btn w-75 btn-success">Order</a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div>There is no product in cart</div>
        <?php }
        }  else { ?>
                <div>There is no product in cart</div>
        <?php } ?>
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
      integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
      <script src="./assets/js/bootstrap.min.js"></script>
      <script src="./assets/js/jquery.min.js"></script>
</body>

</html>

            