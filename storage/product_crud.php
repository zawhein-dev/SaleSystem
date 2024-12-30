<?php
function save_product($mysqli, $productName, $photo, $price, $description, $category)
{
    $sql = "INSERT INTO `product` (`product_name`,`photo`,`price`,`description`,`category_id`) VALUES ('$productName','$photo','$price','$description',$category)";
    return $mysqli->query($sql);
}
function  get_product($mysqli)
{
    $sql = "SELECT * FROM `product` INNER JOIN `category` ON `product`.`category_id` = `category`.`category_id`";
    return $mysqli->query($sql);
}
function delete_product($mysqli, $deleteId)
{
    $sql = "DELETE FROM `product` WHERE `product_id` = $deleteId";
    return $mysqli->query($sql);
}
function get_product_with_id($mysqli, $product_id)
{
    $sql = "SELECT * FROM `product` INNER JOIN `category` ON `product`.`category_id` = `category`.`category_id` WHERE `product_id` = $product_id";
    $product = $mysqli->query($sql);
    return $product->fetch_assoc();
}
function  update_product($mysqli, $productName, $photo, $price, $description, $category_id, $product_id)
{
    $sql = "UPDATE `product` SET `product_name` = '$productName',
                                `price` = $price,
                                `photo` = '$photo',
                                `description` = '$description',
                                `category_id` = $category_id
                            WHERE `product_id`= $product_id";
    return $mysqli->query($sql);
}
function get_product_with_name_and_id($mysqli, $productName, $product_id)
{
    $sql = "SELECT * FROM `product` WHERE `product_name` = '$productName' AND `product_id` !=  $product_id";
    $category = $mysqli->query($sql);
    return $category->fetch_assoc();
}
function get_product_with_name($mysqli, $productName)
{
    $sql = "SELECT * FROM `product` WHERE `product_name` = '$productName'";
    $category = $mysqli->query($sql);
    return $category->fetch_assoc();
}
function get_product_id_with_name($mysqli, $product_name)
{
    $sql = "SELECT product_id FROM product WHERE product.product_name = '$product_name' ";
    $product = $mysqli->query($sql);
    return $product->fetch_assoc();
}
