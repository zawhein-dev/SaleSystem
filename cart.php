<?php
require_once("./storage/db.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");
require_once("./storage/product_crud.php");

session_start();
if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
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

<body class="bg-light">
    <div class="container-fluid row  mx-auto mt-3">
        <?php if (isset($_SESSION["item_list"])) {
            $item_array =  $_SESSION["item_list"];
            if (count($item_array) > 0) { ?>
                <div class="col-8 mt-5 border-rounded">
                    <table class="table table_striped">
                        <thead>
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
                                <tr>
                                    <td><?= $item['branch_name'] ?></td>
                                    <td><?= $item['product_name'] ?></td>
                                    <td>
                                        <a href="?minus=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-success">-</i></a>
                                        <input type="text" class="btn btn-sm bg-light w-25" value="<?= $item['qty'] ?>">
                                        <a href="?add=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-primary">+</i></a>
                                        <a href="?remove=<?= $index ?>&branch_id=<?= $branch_id ?>" class="btn btn-sm btn-danger">X</a>
                                    </td>
                                    <td><?= $item['price'] ?></td>
                                    <td><?= $subtotal ?></td>
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
                                <div class="card-text fw-medium "><?= $net_total . " MMK" ?></div>
                            </div>
                            <div class="d-flex justify-content-between p-3 mt-2 mb-2 border-bottom">
                                <div class="card-text fw-bold">Shipping</div>
                                <div class="card-text fw-medium"><?= "1000 MMK" ?></div>
                            </div>
                            <div class="d-flex justify-content-between p-3 mt-2 mb-2">
                                <div class="card-text mb-0 fw-bold fs-5">Total</div>
                                <div class="card-text fw-medium "><?= $net_total . " MMK" ?></div>
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
        } ?>
    </div>
</body>

</html>