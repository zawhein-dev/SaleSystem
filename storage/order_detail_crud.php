<?php 
function save_order_detail($mysqli,$order_product_id, $branch_product_id,$qty,$price){
    $sql = "INSERT INTO `order_detail`(`order_product_id`,`branch_product_id`,`qty`,`price`) VALUES ($order_product_id, $branch_product_id, $qty ,$price)";
    return $mysqli->query($sql);

} 
function user_order_detail($mysqli,$current_user_id){
   $sql =  "SELECT branch.branch_name,order_product.status, product.product_name,order_detail.price,(SELECT user_name FROM user WHERE user_id = $current_user_id) as userName,order_detail.qty  FROM `order_detail` inner join order_product on order_detail.order_product_id = order_product.order_product_id inner join branch_product on order_detail.branch_product_id =branch_product.branch_product_id INNER JOIN branch on branch_product.branch_id =branch.branch_id INNER join product on product.product_id = branch_product.product_id WHERE order_product.user_id = $current_user_id";
    return $mysqli->query($sql);
}