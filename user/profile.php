<?php
require_once("../storage/db.php");
require_once("../auth/isLogin.php");
require_once("../storage/user_crud.php");
require_once("../storage/order_detail_crud.php");

if (isset($_COOKIE['user'])) {

    $userData = json_decode($_COOKIE['user'], associative: true);

    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
        $current_user_id = $currentUser['user_id'];
        $user  = get_user_with_id($mysqli, $current_user_id);
    }
}
?>
<?php require_once("../userLayout/header.php");
require_once("../auth/isLogin.php"); ?>
<?php require_once("../userLayout/navbar.php") ?>
<div class="content mt-4 mx-auto w-75 d-flex">
    <div class="w-50  align-content-center" style="height: 500px;">
        <div class="text-center">
            <img src="../assets/userProfile/<?= $user['profile']?>" style="border-radius: 50%; height: 500px; width: 500px;" alt="">
        </div>    
    </div>
    <div class="w-50 align-content-center">
        <div class="w-80 justify-content-center">
            <div class="p-3  text-center fs-4 mt-4 border border-light rounded">
             <?= $user['user_name'] ?>
            </div>
            <div class="p-3 text-center  fs-4 border border-light mt-5 rounded">
            <?= $user['user_email'] ?>
            </div>
            <div class="p-3 text-center  fs-4 border border-light mt-5 rounded">
         <?php if($user['role'] == 1) {echo "Admin";} else if($user['role'] == 2){ echo "Staff";} else if($user['role'] == 3){
                echo "User";
             }  ?>
            </div>
            <div class="p-3 d-flex my-3 text-center">
            <div class="w-50 text-center">
                <a href="./edit_user.php?user_id=<?= $user['user_id']?>" class="btn btn-lg btn-success">Edit Acc Info</a>  
            </div>
            <div class="w-50 text-center">
                <a href="./change_password.php?user_id=<?= $user['user_id']?>" class="btn btn-lg btn-success">Edit Acc Password</a>  
            </div>
            </div>
        </div>
    </div>
</div>
<?php require_once("../userLayout/footer.php") ?>