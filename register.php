<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");
require_once("./storage/branch_product_crud.php");
session_start();
$item_array = $_SESSION["item_list"];
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

    if ($userName == "") {
        $userNameErr = "User name cann't be blank!";
        $invalid = false;
    }
    if ($userEmail == "") {
        $userEmailErr = "User email cann't be blank!";
        $invalid = false;
    }
    if ($fileName == "") {
        $fileNameErr = "Please choose photo for profile picture!";
        $invalid = false;
    }
    if ($password == "") {
        $passwordErr = "Password cann't be blank!";
        $invalid = false;
    }
    if ($confirmPassword == "") {
        $confirmPasswordErr = "Confirm Password cann't be blank!";
        $invalid = false;
    }
    if ($confirmPassword != $password) {
        $confirmPasswordErr = "Confirm password does not match to password!";
        $invalid = false;
    }

    if ($invalid) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $targetDir = './assets/userProfile/';
        $newFileName = uniqid('img_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $targetFilePath = $targetDir . $newFileName;
        $status = save_user($mysqli, $userName, $userEmail, $hashedPassword, $newFileName);
        if ($status) {
            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                $user = get_user_with_email($mysqli, $userEmail);
                setcookie("user", json_encode($user), time() + 60 * 60 * 24 * 30, "/");
                if (isset($_GET['order'])) {
                    if (save_order_product($mysqli, $user['user_id'])) {
                        $order_product_id = get_last_order_product_id($mysqli);
                        $item_array =  $_SESSION["item_list"];
                        foreach ($item_array as $index => $item) {
                            $total = $item['qty'] * $item['price'];
                            save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                            update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);

                        }
                        $_SESSION["item_list"] = [];
                        header("Location:./home.php");
                    }
                } else {
                    header("Location:./index.php?user_id =" . $user_id['user_id']);
                }
            }
        } else {
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

    <body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card mx-auto w-50">
            <div class="card-body">
                <h4 class="text-center">Register Form</h4>
                <form method="post" enctype="multipart/form-data">
                    <label for="userName" class="">User Name</label>
                    <input name="userName" class="form-control <?php echo !empty($userNameErr) ? 'is-invalid' : ''; ?>" value="<?= $userName ?>" type="text" id="userName" placeholder="Enter user name">
                    <div class="invalid-feedback"><?php echo $userNameErr; ?></div>

                    <label for="userEmail" class="">Email address</label>
                    <input name="userEmail" class="form-control <?php echo !empty($userEmailErr) ? 'is-invalid' : ''; ?>" value="<?= $userEmail ?>" type="text" id="userEmail" placeholder="useremail@gmail.com">
                    <div class="invalid-feedback"><?php echo $userEmailErr; ?></div>

                    <label for="userProfile" class="form-label">Profile</label>
                    <input type="file" class="form-control <?php echo !empty($fileNameErr) ? 'is-invalid' : ''; ?>" value="<?= $fileName ?>" name="userProfile" id="userProfile">
                    <div class="invalid-feedback"><?php echo $fileNameErr; ?></div>

                    <label for="password" class="">Password</label>
                    <input name="password" class="form-control <?php echo !empty($passwordErr) ? 'is-invalid' : ''; ?>" value="<?= $password ?>" type="password" id="password" placeholder="Password">
                    <div class="invalid-feedback"><?php echo $passwordErr; ?></div>

                    <input type="checkbox" id="showPassword" class="form-check-input">
                    <label class="form-check-label" for="showPassword">Show Password</label>

                    <label for="confirmPassword" class="">Confirm Password</label>
                    <input name="confirmPassword" class="form-control <?php echo !empty($confirmPasswordErr) ? 'is-invalid' : ''; ?>" value="<?= $confirmPassword ?>" type="password" id="confirmPassword" placeholder="Confirm password">
                    <div class="invalid-feedback"><?php echo $confirmPasswordErr; ?></div>

                    <input type="checkbox" id="showConfirmPassword" class="form-check-input">
                    <label class="form-check-label" for="showConfirmPassword">Show Confirm Password</label>

                    <div class="text-center mt-3">
                        <button class="btn btn-success w-25" name="submit">Submit</button>
                    </div>

                    <div class="text-center mt-3">
                        <a>Do you have an account?</a>
                        <a href="./login.php" class="btn fs-6 text-primary">Login Here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

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