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