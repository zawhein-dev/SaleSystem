<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php"); 
 require_once("./storage/order_product_crud.php");
 require_once("./storage/order_detail_crud.php");  
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_COOKIE['user'])) {
    header("location:./home.php");
}
$userName = $userNameErr = "";
$userEmail = $userEmailErr = "";
$password = $passwordErr = "";
$confirmPassword = $confirmPasswordErr = "";
$fileName = $fileNameErr = "";
$invalid = true;

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $fileTmpPath = $_FILES['userProfile']['tmp_name'];
    $fileName = $_FILES['userProfile']['name'];
    $fileSize = $_FILES['userProfile']['size'];
    $fileType = $_FILES['userProfile']['type'];

    if($userName == ""){
        $userNameErr = "User name cann't be blank!";
        $invalid = false;
    }
    if($userEmail == ""){
        $userEmailErr = "User email cann't be blank!";
        $invalid = false;
    }
    if($fileName == ""){
        $fileNameErr = "Please choose photo for profile picture!";
        $invalid = false;
    }
    if($password == ""){
        $passwordErr = "Password cann't be blank!";
        $invalid = false;
    }
    if($confirmPassword == ""){
        $confirmPasswordErr = "Confirm Password cann't be blank!";
        $invalid = false;
    }
    if($confirmPassword != $password){
        $confirmPasswordErr = "Confirm password does not match to password!";
        $invalid = false;
    }
    
if($invalid){
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $targetDir = './assets/userProfile/';
    $newFileName = uniqid('img_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION); 
    $targetFilePath = $targetDir . $newFileName;
    $status = save_user($mysqli, $userName, $userEmail, $hashedPassword,$newFileName);
    if ($status) {
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            $user = get_user_with_email($mysqli, $userEmail);
            setcookie("user", json_encode($user), time() + 60 * 60 * 24 * 30, "/");
            if(isset($_GET['order'])){
             
              if(save_order_product($mysqli,$user['user_id']))
              {
                $order_product_id = get_last_order_product_id($mysqli);
                // var_dump($order_product_id['order_product_id']);
                // var_dump($user['user_id']);
                // var_dump($item_array);
                
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                        save_order_detail($mysqli,$order_product_id['order_product_id'], $item['branch_product_id'],$item['qty'],$total);
                }
                $_SESSION["item_list"] = [];
                header("Location:./home.php");
              }
            }else{
                header("Location:./index.php?user_id =".$user_id['user_id']);
            }
            } 
    }else 
    {
            $fail_query = $status;
    }
    
}
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>

    <div class="card mx-auto w-50 mt-4">
        <div class="card-body">
            <h2 class="text-center">Register Form</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-input-group mx-auto w-75">
                    <label for="userName" class="mb-2">User Name</label>
                    <input name="userName" value="<?= $userName ?>" type="text" class="form-control" id="userName" placeholder="enter user name">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $userNameErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <label for="userEmail" class="mb-2">Email address</label>
                    <input name="userEmail"  value="<?= $userEmail ?>" type="text" class="form-control" id="userEmail" placeholder="useremail@gmail.com">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $userEmailErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <label for="userProfile" class="form-label">Profile</label>
                    <input type="file" value="<?= $fileName ?>" name="userProfile" id="userProfile" class="form-control">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $fileNameErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <label for="password" class="mb-2">Password</label>
                    <input name="password" value="<?= $password ?>" type="password" class="form-control" id="password" placeholder="password">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $passwordErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <input type="checkbox" id="showPassword" class="form-check-input">
                    <label class="form-check-label" for="show">
                        Show Password
                    </label>
                    <div style="height: 20px; line-height: 20px;"></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <label for="confirmPassword" class="mb-2">Confirm Password</label>
                    <input name="confirmPassword" value="<?= $confirmPassword ?>" type="password" class="form-control" id="confirmPassword"  placeholder="confirm password">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $confirmPasswordErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <input type="checkbox" id="showConfirmPassword" class="form-check-input">
                    <label class="form-check-label" for="show">
                        Show Password
                    </label>
                    <div style="height: 20px; line-height: 20px;"></div>
                </div>
                <div class=" text-center">
                    <button class="btn btn-success w-25" name="submit">Submit</button>
                </div>
                <div class="text-center">
                    <a>Do you have account?</a>
                    <a href="./login.php" class="btn fs-6 text-primary">Login Here</a>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
        $(() => {
            let password = $("#password");
            let confirmPassword = $("#confirmPassword");
            let showPassword = $("#showPassword");
            let showConfirmPassword = $("#showConfirmPassword");
            showPassword.on("click", () => {
                if (showPassword.is(":checked")) {
                    password.get(0).type = "text";
                } else {
                    password.get(0).type = "password";
                }
            })
            showConfirmPassword.on("click", () => {
                if (showConfirmPassword.is(":checked")) {
                    confirmPassword.get(0).type = "text";
                } else {
                    confirmPassword.get(0).type = "password";
                }
            })
        })
    </script>
</html>