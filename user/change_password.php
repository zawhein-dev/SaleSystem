<?php
require_once("../storage/db.php");
require_once("../auth/isLogin.php");
require_once("../storage/user_crud.php");
require_once("../storage/order_detail_crud.php");
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $users =  get_user_with_id($mysqli, $user_id);
    $userName = $users['user_name'];
    $userEmail = $users['user_email'];
    $profileName = $users['profile'];
    $role = $users['role'];
    $old_user_password = $users['password'];
}
$oldPassword =  $oldPasswordErr = "";
$password = $passwordErr = "";
$confirmPassword = $confirmPasswordErr = "";
$invalid = true;
if (isset($_POST['changePassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $password = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    if ($oldPassword == "") {
        $oldPasswordErr = "Please enter  old password";
        $invalid = false;
    }
    if ($password == "") {
        $passwordErr = "Please enter new password";
        $invalid = false;
    }
    if ($confirmPassword == "") {
        $confirmPasswordErr = "Please enter confirm password";
        $invalid = false;
    }
    if ($password != $confirmPassword) {
        $confirmPasswordErr = "Confirm password does not match password";
        $invalid = false;
    }
    if ($invalid) {
        // var_dump("Hello");
        if (password_verify($oldPassword,  $old_user_password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $status = update_user($mysqli, $userName, $userEmail, $hashedPassword, $profileName, $role, $user_id);
            if ($status == true) {
                echo "<script>location.replace('./profile.php')</script>";
            } else {
                $fail_query = $status;
            }
        } else {
            $oldPasswordErr = "Old password does not match";
        }
    }
}

?>
<?php require_once("../userLayout/header.php");
require_once("../auth/isLogin.php"); ?>
<?php require_once("../userLayout/navbar.php") ?>
<div class="main bg-white w-75 mx-auto mt-4">
    <div class="main-content w-50 mx-auto">
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Change Password</div>
        <form action="" method="POST" class="form-group">
            <label for="oldPassword" class="form-label  mb-1">Old Password</label>
            <input type="password" name="oldPassword" id="oldPassword" class="form-control form-control-sm" placeholder="Eneter old password.....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $oldPasswordErr ?></i></div>

            <label for="newPassword" class="form-label  mb-1">New Password</label>
            <input type="password" name="newPassword" id="newPassword" class="form-control form-control-sm" placeholder="Eneter user password.....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $passwordErr ?></i></div>

            <label for="confirmPassword" class="form-label mb-1">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-sm" placeholder="Eneter user same password.....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $confirmPasswordErr ?></i></div>

            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="changePassword" id="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>

<?php require_once("../userLayout/footer.php") ?>