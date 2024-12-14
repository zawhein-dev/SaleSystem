<?php require_once("../storage/db.php");
require_once("../auth/isLogin.php");
?>

<?php require_once("../storage/user_crud.php");?>
<?php require_once("../storage/branch_crud.php");?>
<?php require_once("../storage/category_crud.php");?>
<?php require_once("../storage/product_crud.php");?>
<?php require_once("../storage/branch_product_crud.php");?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <style>
/* Remove the dropdown arrow for the profile dropdown */
#profileDropdown::after {
    display: none;
}
</style>
</head>

<body class="" style="overflow: hidden;">

    <div class="main-content row d-flex bg-light">