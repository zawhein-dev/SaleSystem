<?php 
function save_category($mysqli,$categoryName,$description){
    $sql = "INSERT INTO `category`(`category_name`,`description`) VALUES ('$categoryName','$description')";
    return $mysqli->query($sql);
}

function get_category_with_id($mysqli,$category_id){
    $sql = "SELECT * FROM `category` WHERE `category_id`  = $category_id";
    $category = $mysqli->query($sql);
    return $category->fetch_assoc();
}
function update_category($mysqli,$categoryName,$description,$category_id){
    $sql = "UPDATE `category` SET `category_name` = '$categoryName',
                                    `description` = '$description'
                                    WHERE `category_id` = $category_id ";
    return $mysqli->query($sql);
}
function get_category($mysqli){
    $sql = "SELECT * FROM `category`";
    return $mysqli->query($sql);
}
function delete_category($mysqli, $deleteId){
        $sql = "DELETE FROM `category` WHERE `category_id`= $deleteId";
        return $mysqli->query($sql);
}
function get_category_with_name_and_id($mysqli,$categoryName,$category_id){
    $sql = "SELECT * FROM `category` WHERE `category_name` = '$categoryName' AND `category_id` != $category_id";
    $category = $mysqli->query($sql);
    return $category->fetch_assoc();
}
function get_category_with_name($mysqli,$categoryName){
    $sql = "SELECT * FROM `category` WHERE `category_name` = '$categoryName'";
    $category = $mysqli->query($sql);
    return $category->fetch_assoc();
}