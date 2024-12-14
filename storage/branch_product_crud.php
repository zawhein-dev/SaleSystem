<?php 
function save_branch_product($mysqli,$branch,$product,$qty): mixed{
    try {
        $sql = "INSERT INTO `branch_product`(`branch_id`,`product_id`,`qty`) VALUES('$branch','$product','$qty')";
        return $mysqli->query($sql);
    } catch (\Throwable $th) { {
            return "Internal server error!";
        }
    }
}

function get_branch_product($mysqli){
    $sql = "SELECT * FROM `branch_product` INNER JOIN  `branch` ON `branch_product`.`branch_id` = `branch`.branch_id  INNER JOIN `product` ON `branch_product`.`product_id` = `product`.`product_id`";
    return $mysqli->query($sql);
}
function delete_branch_product($mysqli, $deleteId){
    $sql = "DELETE FROM `branch_product` WHERE `branch_product_id` = $deleteId";
    return $mysqli->query($sql);
}
function get_branch_product_with_id($mysqli,$branch_product_id){
    $sql = "SELECT * FROM `branch_product` WHERE `branch_product_id` = $branch_product_id";
    $branch = $mysqli->query($sql);
    return $branch->fetch_assoc();
}
function  update_branch_product($mysqli,$branch,$product,$qty,$branch_product_id){
    $sql = "UPDATE `branch_product` SET `branch_id` = $branch,
                                        `product_id` = $product,
                                        `qty` = $qty
                                    WHERE `branch_product_id` = $branch_product_id ";
    return $mysqli->query($sql);
}
function get_branch_product_for_order_detail($mysqli,$product_id,$branch_id){
    $sql = "SELECT * FROM `branch_product` INNER JOIN `branch` ON `branch_product`.`branch_id` = `branch`.`branch_id` INNER JOIN `product` ON `branch_product`.`product_id` = `product`.`product_id`  WHERE `branch_product`.`product_id` = $product_id AND `branch_product`.`branch_id` = $branch_id";
    $branch_product = $mysqli->query($sql);
    return $branch_product->fetch_assoc();
}