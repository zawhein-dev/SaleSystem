<?php
session_start();
if (isset($_COOKIE['user'])) {
    $userData = json_decode($_COOKIE['user'], associative: true);
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
    }
}
$item_array = [];
if (isset($_SESSION["item_list"])) {
    $item_array = $_SESSION['item_list'];
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $branch_id = $_POST['b_id'];
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
    $isHave = true;
    $addtocart = true;
    if ($branch_product['qty'] == 0) {
        $addtocart = false;
    }
    for ($i = 0; $i < count($item_array); $i++) {
        if ($product_id == $item_array[$i]['product_id']) {
            $isHave = false;

            if ($branch_product['qty'] > $item_array[$i]['qty']) {
                $item_array[$i]['qty']++;
            }
        }
    }
    if ($isHave && $addtocart) {
        array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => 1,'branch_id' => $branch_product['branch_id']]);    
    }
    $_SESSION["item_list"] = $item_array;
    // header("Location:?branch_id=$branch_id");
    header("Location: " . $_SERVER['PHP_SELF'] . "?branch_id=$branch_id&show_offcanvas=true");
// exit;

}
// if(isset($_POST['filter'])){
//     var_dump($_POST);
// }
if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
    $branch = get_branch_with_id($mysqli, $branch_id);
  }