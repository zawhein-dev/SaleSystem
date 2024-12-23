<?php

require_once("./storage/db.php");
// require_once("./storage/user_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");
session_start();
if (isset($_GET['add'])) {
    $index = $_GET['add'];
    $item_array = $_SESSION["item_list"];
    for ($i = 0; $i < count($item_array); $i++) {
        if ($i == $index) {
            $item_array[$i]['qty']++;
        }
            $_SESSION["item_list"] = $item_array;
            header("Location:?");
    }
}
if (isset($_GET['remove'])) {
    $item_array = $_SESSION["item_list"];
    array_splice($item_array, $_GET['remove'], 1);
    $_SESSION["item_list"] = $item_array;
    header("Location:?");
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
        header("Location:?");
    // var_dump($item_array);
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
                $total = $item['qty'] * $item['price'];
                save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
            }
            // $_SESSION["item_list"] =[];
            // session_destroy();
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
                <!-- <th>Branch Product Id</th> -->
                <th>qty</th>
                <th>Price</th>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION["item_list"])) {
                    $item_array =  $_SESSION["item_list"];
                    foreach ($item_array as $index => $item) {
                ?>
                        <tr>
                            <td><?= $item['branch_name'] ?></td>
                            <td><?= $item['product_name'] ?></td>
                            <td>
                                <a href="?minus=<?= $index ?>" class="btn btn-sm btn-success">-</i></a>
                                <input type="text" class="btn btn-sm bg-light w-25" value="<?= $item['qty'] ?>">
                                <a href="?add=<?= $index ?>" class="btn btn-sm btn-primary">+</i></a>
                                <a href="?remove=<?= $index ?>"  class="btn btn-sm btn-danger">X</a>
                            </td>
                            <td><?= $item['price'] * $item['qty'] ?></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <a href="?order" class="btn btn-success">Order</a>
    </div>
    <script>

    </script>
</body>

</html>