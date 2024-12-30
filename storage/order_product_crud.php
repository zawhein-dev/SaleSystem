<?php 
function save_order_product($mysqli,$user_id){
    $sql = "INSERT INTO `order_product`(`user_id`,`status`) VALUES($user_id,0)";
    return $mysqli->query($sql);
}
function  get_last_order_product_id($mysqli){
    $sql = "SELECT `order_product_id` FROM  `order_product` ORDER BY order_product_id DESC LIMIT 1";
    $data = $mysqli->query($sql);
    return $data->fetch_assoc();
}
function pending_status($mysqli,$order_product_id){
    $sql = "UPDATE `order_product` SET `status`= 0  WHERE `order_product_id` = $order_product_id";
    return $mysqli->query($sql);
}
function accept_status($mysqli,$order_product_id){
    $sql = "UPDATE `order_product` SET `status`= 1  WHERE `order_product_id` = $order_product_id";
    return $mysqli->query($sql);
}
function ready_status($mysqli,$order_product_id){
    $sql = "UPDATE `order_product` SET `status`= 2  WHERE `order_product_id` = $order_product_id";
    return $mysqli->query($sql);
}
function cancel_status($mysqli,$order_product_id){
    $sql = "UPDATE `order_product` SET `status`= 3  WHERE `order_product_id` = $order_product_id";
    return $mysqli->query($sql);
}
function get_product_with_order_product_id($mysqli,$order_product_id){
     $sql = "SELECT *,product.price as unit_price,order_detail.status as order_product_status,order_detail.price as order_product_price,order_detail.qty as order_product_qty FROM order_detail
INNER JOIN branch_product ON order_detail.branch_product_id  = branch_product.branch_product_id
INNER JOIN product ON branch_product.product_id = product.product_id
INNER JOIN branch ON branch_product.branch_id = branch.branch_id
INNER JOIN category ON product.category_id = category.category_id
WHERE order_detail.order_product_id = $order_product_id";
return $mysqli->query($sql);
}