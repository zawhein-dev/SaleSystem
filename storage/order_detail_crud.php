<?php 
function save_order_detail($mysqli,$order_product_id, $branch_product_id,$qty,$price){
    $sql = "INSERT INTO `order_detail`(`order_product_id`,`branch_product_id`,`qty`,`price`,`status`) VALUES ($order_product_id, $branch_product_id, $qty ,$price,0)";
    return $mysqli->query($sql);

} 
function count_order($mysqli){
    $sql = "SELECT COUNT(order_product_id) as numberOfOrderPending FROM order_product WHERE status = 0";
    $count = $mysqli->query($sql);
    return $count->fetch_assoc();
}
function user_order_detail($mysqli){
    $sql =  "SELECT order_detail_id,order_detail.qty as order_qty,order_product.status as order_status, order_detail.price as order_price,order_product.order_product_id,user.user_name as user_name ,product.product_name as product_name,branch.branch_name as branch_name FROM `order_detail` 
INNER JOIN order_product ON order_detail.order_product_id = order_product.order_product_id
INNER JOIN user on order_product.user_id = user.user_id 
INNER JOIN branch_product ON order_detail.branch_product_id = branch_product.branch_product_id
INNER JOIN product ON branch_product.product_id = product.product_id
INNER JOIN category ON product.category_id = category.category_id
INNER JOIN branch ON branch_product.branch_id = branch.branch_id
Order BY order_product.order_date DESC";
    return $mysqli->query($sql);
 }
 
 function get_order_detail($mysqli){
    $sql = "SELECT * FROM `order_product` INNER JOIN `user` ON `order_product`.`user_id` = `user`.`user_id`";
    return $mysqli->query($sql);
 }
 function get_order_product_with_search_data($mysqli, $search)
{
    $sql = "SELECT * FROM `order_product` INNER JOIN `user` ON `order_product`.`user_id` = `user`.`user_id` WHERE `user`.`user_name`  LIKE '%$search%'";
    return $mysqli->query($sql);
}

function get_search_order_product_with_offset($mysqli, $offset, $limit)
{
    $sql = "SELECT * FROM order_product INNER JOIN `user` ON `order_product`.`user_id` = `user`.`user_id` ORDER BY order_product.order_product_id DESC   LIMIT $limit OFFSET $offset";
    return $mysqli->query($sql);

}
function get_order_product_with_offset($mysqli, $offset, $limit,$search)
{
    $sql = "SELECT * FROM `order_product` INNER JOIN `user` ON `order_product`.`user_id` = `user`.`user_id`  WHERE `user`.`user_name` LIKE '%$search%' ORDER BY order_product.order_product_id DESC LIMIT $limit OFFSET $offset";
    return $mysqli->query($sql);
}
 function pending_order_status($mysqli,$order_detail_id){
    $sql = "UPDATE `order_detail` SET `status`= 0  WHERE `order_detail_id` = $order_detail_id";
    return $mysqli->query($sql);
}
function accept_order_status($mysqli,$order_detail_id){
    $sql = "UPDATE `order_detail` SET `status`= 1  WHERE `order_detail_id` = $order_detail_id";
    return $mysqli->query($sql);
}
function ready_order_status($mysqli,$order_detail_id){
    $sql = "UPDATE `order_detail` SET `status`= 2  WHERE `order_detail_id` = $order_detail_id";
    return $mysqli->query($sql);
}
function cancel_order_status($mysqli,$order_detail_id){
    $sql = "UPDATE `order_detail` SET `status`= 3  WHERE `order_detail_id` = $order_detail_id";
    return $mysqli->query($sql);
}
    //  function user_last_order($mysqli,$current_user_id){
    //     $sql =  "SELECT order_product.order_product_id,branch.branch_name,order_product.status,product.product_name,order_detail.price,(SELECT user_name FROM user WHERE user_id = $current_user_id) as userName,order_detail.qty  FROM `order_detail` inner join order_product on order_detail.order_product_id = order_product.order_product_id inner join branch_product on order_detail.branch_product_id =branch_product.branch_product_id INNER JOIN branch on branch_product.branch_id =branch.branch_id INNER join product on product.product_id = branch_product.product_id WHERE  order_product.user_id = $current_user_id AND order_detail.order_product_id = (SELECT `order_product_id` FROM  `order_product` ORDER BY order_product_id DESC LIMIT 1)";
    //      return $mysqli->query($sql);
    //  }
function user_last_order($mysqli,$current_user_id){
    $sql = "SELECT order_detail.status as detail_status,order_detail.qty as order_qty,order_detail.price as total_price,product.price as unit_price,branch.branch_name as branch_name,product.product_name as product_name,product.description as description,(SELECT `order_product_id` FROM `order_product` WHERE `user_id` = $current_user_id ORDER BY `order_product_id` DESC LIMIT 1) as ORDER_product_id
FROM `order_detail`
INNER JOIN branch_product ON order_detail.branch_product_id = branch_product.branch_product_id
INNER JOIN branch ON branch_product.branch_id = branch.branch_id
INNER JOIN product ON branch_product.product_id = product.product_id
INNER JOIN category ON product.category_id = category.category_id   
WHERE `order_detail`.`order_product_id` =(SELECT `order_product_id` FROM `order_product` WHERE `user_id` = 16 ORDER BY `order_product_id` DESC LIMIT 1)";
    return $mysqli->query($sql);
}

function get_order_product_with_current_user($mysqli,$current_user_id,$order_product_id){
    $sql = "SELECT order_detail.qty as product_qty,branch.branch_name,order_detail.order_product_id,product.product_name,order_detail.branch_product_id,product.description,category.category_name as category_name,order_detail.price as total_price,product.price as product_price,order_product.order_product_id as order_code FROM `order_detail` 
INNER JOIN order_product ON order_detail.order_product_id = order_product.order_product_id 
INNER JOIN branch_product ON order_detail.branch_product_id = branch_product.branch_product_id
INNER JOIN branch ON branch_product.branch_id = branch.branch_id
INNER JOIN product ON branch_product.product_id = product.product_id
INNER JOIN category ON product.category_id = category.category_id
WHERE order_product.user_id = $current_user_id AND order_product.order_product_id = $order_product_id";
return $mysqli->query($sql);}

function get_order_onhold_with_current_user($mysqli,$current_user_id){
    $sql = "SELECT * FROM order_product INNER JOIN user ON order_product.user_id = user.user_id WHERE user.user_id = $current_user_id AND order_product.status = 0";
    return $mysqli->query($sql);
}
function get_order_accept_with_current_user($mysqli,$current_user_id){
    $sql = "SELECT * FROM order_product INNER JOIN user ON order_product.user_id = user.user_id WHERE user.user_id = $current_user_id AND order_product.status = 1";
    return $mysqli->query($sql);
}
function get_order_ready_with_current_user($mysqli,$current_user_id){
    $sql = "SELECT * FROM order_product INNER JOIN user ON order_product.user_id = user.user_id WHERE user.user_id = $current_user_id AND order_product.status = 2";
    return $mysqli->query($sql);
}
function get_order_cancel_with_current_user($mysqli,$current_user_id){
    $sql = "SELECT * FROM order_product INNER JOIN user ON order_product.user_id = user.user_id WHERE user.user_id = $current_user_id AND order_product.status = 3";
    return $mysqli->query($sql);
}

function get_branch_product_id_when_order_cancel($mysqli,$order_product_id){
    $sql = "SELECT branch_product_id,qty FROM `order_detail` WHERE order_product_id = $order_product_id";
    return $mysqli->query($sql);
}
function get_order_product_status($mysqli,$order_product_id){
    $sql = "SELECT status FROM order_product WHERE order_product_id = $order_product_id";
    $status =$mysqli->query($sql);
    return $status->fetch_assoc();
}
function update_branch_product_when_order_cancel($mysqli,$update_branch_product_data,$update_branch_product_qty){
    $sql = "UPDATE branch_product SET qty = qty + $update_branch_product_qty WHERE branch_product_id = $update_branch_product_data";
    return $mysqli->query($sql);
}
function update_branch_product_when_order_cancel_with_minus($mysqli,$update_branch_product_data,$update_branch_product_qty){
    $sql = "UPDATE branch_product SET qty = qty - $update_branch_product_qty WHERE branch_product_id = $update_branch_product_data";
    return $mysqli->query($sql);
}

function get_order_detail_with_current_user($mysqli,$current_user_id){
    $sql = "SELECT * FROM order_product INNER JOIN user ON order_product.user_id = user.user_id WHERE user.user_id = $current_user_id";
    return $mysqli->query($sql);
}
function get_order_detail_with_current_user_with_search_data($mysqli, $search,$current_user_id)
{
    $sql = "SELECT * FROM `order_product` 
    INNER JOIN user ON order_product.user_id = user.user_id
    WHERE order_product.`order_product_id` LIKE '%$search%' AND user.user_id = $current_user_id";
    return $mysqli->query($sql);
}
function get_order_detail_with_current_user_with_offset($mysqli, $offset, $limit,$search,$current_user_id)
{
    $sql = "SELECT * FROM `order_product`  
    INNER JOIN user ON order_product.user_id = user.user_id
    
    WHERE order_product.`order_product_id` LIKE '%$search%' AND user.user_id = $current_user_id  ORDER BY order_product.order_product_id DESC
    LIMIT $limit OFFSET $offset";
    return $mysqli->query($sql);
}
function get_search_order_detail_with_current_user_with_offset($mysqli, $offset, $limit,$current_user_id)
{
    $sql = "SELECT * FROM order_product
    INNER JOIN user ON order_product.user_id = user.user_id
    WHERE user.user_id = $current_user_id 
    ORDER BY order_product.order_product_id DESC
     LIMIT $limit OFFSET $offset ";
    return $mysqli->query($sql);
}