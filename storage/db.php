<?php
// try {
    $mysqli = new mysqli("localhost", "root", "");
    $sql = "CREATE DATABASE IF NOT EXISTS `sale_and_stock_management_system`";
    if ($mysqli->query($sql)) {
        if ($mysqli->select_db("sale_and_stock_management_system")) {
            create_table($mysqli);
        }
    }
// } catch (\Throwable $th) {
//     echo "Can not connect to Database!";
//     die();
// }

// auto create all table when our index page is loaded
function create_table($mysqli)
{
    $sql = "CREATE TABLE IF NOT EXISTS `user`(`user_id` INT AUTO_INCREMENT,`user_name` VARCHAR(70) NOT NULL,`user_email` VARCHAR(70) UNIQUE,`password` VARCHAR(220) NOT NULL,`profile` LONGTEXT NOT NULL,`role` INT NOT NULL ,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(`user_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS `branch`(`branch_id` INT AUTO_INCREMENT,`branch_name` VARCHAR(70) NOT NULL,`address` VARCHAR(225),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(`branch_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    $sql = "CREATE TABLE IF NOT EXISTS `category`(`category_id` INT AUTO_INCREMENT,`category_name` VARCHAR(70) UNIQUE NOT NULL,`description` VARCHAR(225),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(`category_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS `product`(`product_id` INT AUTO_INCREMENT,`product_name` VARCHAR(70) UNIQUE NOT NULL,price INT NOT NULL,`description` VARCHAR(225),category_id INT NOT NULL,PRIMARY KEY(`product_id`),FOREIGN KEY(`category_id`) REFERENCES `category`(`category_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    $sql = "CREATE TABLE IF NOT EXISTS `branch_product`(`branch_product_id` INT AUTO_INCREMENT,`branch_id` INT NOT NULL,`product_id` int NOT NULL,`qty` int NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(`branch_product_id`),FOREIGN KEY(`branch_id`) REFERENCES `branch`(`branch_id`),FOREIGN KEY(`product_id`) REFERENCES `product`(`product_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    $sql = "CREATE TABLE IF NOT EXISTS `order_product`(`order_product_id` INT AUTO_INCREMENT,`user_id` INT NOT NULL,`status` int NOT NULL,order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(`order_product_id`),FOREIGN KEY(`user_id`) REFERENCES `user`(`user_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    $sql = "CREATE TABLE IF NOT EXISTS `order_detail`(`order_detail_id` INT AUTO_INCREMENT,`order_product_id` INT NOT NULL,`branch_product_id` INT NOT NULL,`qty` int NOT NULL,`price` int not null,PRIMARY KEY(`order_detail_id`),FOREIGN KEY(`order_product_id`) REFERENCES `order_product`(`order_product_id`),FOREIGN KEY(`branch_product_id`) REFERENCES `branch_product`(`branch_product_id`))";
    if (!$mysqli->query($sql)) {
        return false;
    }
    return true;
}
