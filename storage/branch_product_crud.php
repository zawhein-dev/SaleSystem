<?php

function save_branch_product($mysqli, $branch, $product, $qty): mixed
{
    try {
        $sql = "INSERT INTO `branch_product`(`branch_id`,`product_id`,`qty`) VALUES('$branch','$product','$qty')";
        return $mysqli->query($sql);
    } catch (\Throwable $th) { {
            return "Internal server error!";
        }
    }
}
function check_prouct_in_branch_exist($mysqli, $product_id, $branch_id)
{
    $sql = "SELECT * FROM  `branch_product` WHERE `product_id` = $product_id AND `branch_id` = $branch_id";
    $branch_product = $mysqli->query($sql);
    return $branch_product->fetch_assoc();
}

function get_branch_product($mysqli)
{
    $sql = "SELECT * FROM `branch_product` INNER JOIN  `branch` ON `branch_product`.`branch_id` = `branch`.branch_id  INNER JOIN `product` ON `branch_product`.`product_id` = `product`.`product_id`";
    return $mysqli->query($sql);
}
function delete_branch_product($mysqli, $deleteId)
{
    $sql = "DELETE FROM `branch_product` WHERE `branch_product_id` = $deleteId";
    return $mysqli->query($sql);
}
function get_branch_product_with_id($mysqli, $branch_product_id)
{
    $sql = "SELECT * FROM `branch_product` WHERE `branch_product_id` = $branch_product_id";
    $branch = $mysqli->query($sql);
    return $branch->fetch_assoc();
}
function  update_branch_product($mysqli, $branch, $product, $qty, $branch_product_id)
{
    $sql = "UPDATE `branch_product` SET `branch_id` = $branch,
                                        `product_id` = $product,
                                        `qty` = $qty
                                    WHERE `branch_product_id` = $branch_product_id ";
    return $mysqli->query($sql);
}
function get_branch_product_for_order_detail($mysqli, $product_id, $branch_id)
{
    $sql = "SELECT * FROM `branch_product` INNER JOIN `branch` ON `branch_product`.`branch_id` = `branch`.`branch_id` INNER JOIN `product` ON `branch_product`.`product_id` = `product`.`product_id`  WHERE `branch_product`.`product_id` = $product_id AND `branch_product`.`branch_id` = $branch_id";
    $branch_product = $mysqli->query($sql);
    return $branch_product->fetch_assoc();
}

function  save_branch_product_with_order($mysqli, $order_product_id, $branch_product_id, $price, $qty)
{
    $sql = "INSERT INTO `order_detail`(`order_product_id`,`branch_product_id`,`qty`,`price`) VALUES($order_product_id,$branch_product_id,$price,$qty)";
    return $mysqli->query($sql);
}

function get_branch_product_with_category_id($mysqli, $category_id, $branch_id)
{
    $sql  = "SELECT *,product.* FROM `branch_product` inner join product on branch_product.product_id = product.product_id WHERE product.category_id = $category_id AND branch_product.branch_id = $branch_id ";
    return $mysqli->query($sql);
}

function update_qty_when_order_success($mysqli, $qty, $branch_product_id)
{
    $sql = "UPDATE `branch_product` SET `qty` = `qty` - $qty WHERE `branch_product_id` = $branch_product_id";
    return $mysqli->query($sql);
}

function get_qty_with_branch_product_id($mysqli,$branch_product_id){
    $sql = "SELECT `qty` FROM `branch_product` WHERE `branch_product_id` = $branch_product_id";
    $qty = $mysqli->query($sql);
    return $qty->fetch_assoc();
}