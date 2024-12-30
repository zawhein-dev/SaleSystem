<?php
function save_branch($mysqli, $branchName, $address)
{
    try {
        $sql = "INSERT INTO `branch`(`branch_name`,`address`) VALUES ('$branchName','$address')";
        return $mysqli->query($sql);
    } catch (\Throwable $th) {
        return "Internal server error!";
    }
}
function get_branch($mysqli)
{
    $sql = "SELECT * FROM `branch`";
    return $mysqli->query($sql);
}
function get_branch_with_id($mysqli, $branch_id)
{
    $sql = "SELECT * FROM `branch` WHERE `branch_id` = $branch_id";
    $branch = $mysqli->query($sql);
    return $branch->fetch_assoc();
}
function update_branch($mysqli, $branchName, $address, $branch_id)
{
    $sql = "UPDATE `branch` 
        SET `branch_name` = '$branchName',
            `address` = '$address'
            WHERE `branch_id` = $branch_id";
    return $mysqli->query($sql);
}
function  delete_branch($mysqli, $deleteId)
{
    $sql = "DELETE FROM `branch` WHERE  `branch_id` = '$deleteId'";
    return $mysqli->query($sql);
}
function  get_product_in_branch($mysqli, $branch_id)
{
    $sql = "SELECT bp.branch_product_id,p.product_id,`p`.`photo`,`p`.`price`, `bp`.`qty`, `p`.`product_name`, `b`.`branch_name`, `b`.`address` FROM `branch_product` bp INNER JOIN `branch` b ON `bp`.`branch_id` = `b`.`branch_id` INNER JOIN `product` p ON  `bp`.`product_id` = `p`.`product_id` WHERE bp.`branch_id` = $branch_id";
    return $mysqli->query($sql);
}
