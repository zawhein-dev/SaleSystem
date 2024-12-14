<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
 require_once("./storage/branch_crud.php");

// if (isset($_COOKIE['user'])) {
//     header("location:./home.php");
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>
<body>
    <?php if(have_admin($mysqli)) {
         $hashedPassword = password_hash("123456", PASSWORD_DEFAULT);
            //  $newFileName = uniqid('img_') . '.' . pathinfo("admin.jpg", PATHINFO_EXTENSION); 
             $status = save_user($mysqli, 'admin','admin@gmail.com',$hashedPassword, "admin.jpg",1);
             if ($status === true) {
                 
             } else {
                 $fail_query = $status;
             }
    }?>
    <div class="d-flex row container-fluid mt-4">
    <?php $branches = get_branch($mysqli);
   while($branch = $branches->fetch_assoc()){?> 
    <div class="text-center col-4">
        <a href="./select_shop.php?branch_id=<?= $branch['branch_id'] ?>" class="btn btn-info mb-2 w-100"><?= $branch['branch_name'] ?></a>
    </div>
    <?php } ?>
    </div>
   
</body>
</html>