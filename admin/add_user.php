<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$userName = $userNameErr = "";
$userEmail = $userEmailErr = "";
$role = $roleErr = "";
$profileName = $profileNameErr = "";
$password = $passwordErr = "";
$confirmPassword = $confirmPasswordErr = "";
$invalid = true;
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $users  = get_user_with_id($mysqli, $user_id);
    $userName = $users['user_name'];
    $userEmail = $users['user_email'];
    $oldPassword = $users['password'];
    $oldProfileName = $users['profile'];
    $role = $users['role'];
}
if (isset($_POST['submit'])) {
    $userName = $_POST["userName"];
    $userEmail = $_POST["userEmail"];
    $role = $_POST['role'];
    $profileTmpPath = $_FILES['profile']['tmp_name'];
    $profileName = $_FILES['profile']['name'];
    $profileSize = $_FILES['profile']['size'];
    $profileType = $_FILES['profile']['type'];
    if (!isset($_GET['user_id'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        if ($password == "") {
            $passwordErr = "Please enter user password";
            $invalid = false;
        }
        if ($confirmPassword == "") {
            $confirmPasswordErr = "Please enter user confirm password";
            $invalid = false;
        }
        if ($password != $confirmPassword) {
            $confirmPasswordErr = "Confirm password does not match password";
            $invalid = false;
        }
    }

    if ($userName == "") {
        $userNameErr = "Please enter user name";
        $invalid = false;
    }
    if ($userEmail == "") {
        $userEmailErr = "Please enter user email";
        $invalid = false;
    }
    if ($role == "") {
        $roleErr = "Please select user role";
        $invalid = false;
    }

    if (!isset($_GET['user_id'])) {
        if ($profileName == "") {
            $profileNameErr = "Please choose user profile";
            $invalid = false;
        }
    }
    if ($invalid) {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            if ($_FILES['profile']['name'] == "") {
                $status = update_user($mysqli, $userName, $userEmail, $oldPassword, $oldProfileName, $role, $user_id);
                if ($status == true) {
                    echo "<script>location.replace('./user_list.php')</script>";
                } else {
                    $fail_query = $status;
                }
            } else {
                $targetDir = '../assets/userProfile/';
                $filePath = '../assets/userProfile/' . $oldProfileName;
                $newFileName = uniqid('img_') . '.' . pathinfo($profileName, PATHINFO_EXTENSION);  // Generate a unique file name
                $targetFilePath = $targetDir . $newFileName;
                $status = update_user($mysqli, $userName, $userEmail, $oldPassword, $newFileName, $role, $user_id);
                if ($status === true) {
                    if (file_exists($filePath)) {
                        if (unlink($filePath)) {
                            echo "The file 'profile.png' was deleted successfully.";
                        }
                    }
                    if (move_uploaded_file($profileTmpPath, $targetFilePath)) {
                        echo "<script>location.replace('./user_list.php')</script>";
                    }
                } else {
                    $fail_query = $status;
                }
            }
        } else {
            $targetDir = '../assets/userProfile/';
            $newFileName = uniqid('img_') . '.' . pathinfo($profileName, PATHINFO_EXTENSION);  // Generate a unique file name
            $targetFilePath = $targetDir . $newFileName;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $status = save_user($mysqli, $userName, $userEmail,  $hashedPassword, $newFileName,$role);
            if ($status === true) {
                if (move_uploaded_file($profileTmpPath, $targetFilePath)) {
                    echo "<script>location.replace('./user_list.php')</script>";
                }
            } else {
                $fail_query = $status;
            }
        }
    }
}

?>
<div class="main bg-white w-100">
    <div class="main-content w-50 mx-auto">
   <?php if (isset($_GET['user_id'])) { ?>
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update User</div>
        <?php } else {?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add User</div>
            <?php } ?>
        <form action="" method="POST" class="form-group" enctype="multipart/form-data">
            <label for="userName" class="form-label mb-1">Name</label>
            <input type="text" name="userName" value="<?= $userName ?>" id="userName" class="form-control form-control-sm" placeholder="Eneter user name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $userNameErr ?></i></div>

            <label for="userEmail" class="form-label  mb-1">Email</label>
            <input type="email" name="userEmail" value="<?= $userEmail ?>" id="userEmail" class="form-control form-control-sm" placeholder="Eneter user email.....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $userEmailErr ?></i></div>

            <label for="role" class="form-label mb-1">Role</label>
            <select name="role" id="role" class="form-select form-select-sm">
                <option value="" selected>Select Role</option>
                <option value="1" <?php if ($role == "1") {
                                        echo "selected";
                                    } ?>>Admin</option>
                <option value="2" <?php if ($role == "2") {
                                        echo "selected";
                                    } ?>>Staff</option>
                <option value="3" <?php if ($role == "3") {
                                        echo "selected";
                                    } ?>>User</option>
            </select>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $roleErr ?></i></div>

            <label for="profile" class="form-label  mb-1">Photo</label>
            <input type="file" name="profile" id="profile" class="form-control form-control-sm">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $profileNameErr ?></i></div>
            <?php if (!isset($_GET['user_id'])) { ?>
                <label for="password" class="form-label  mb-1">Password</label>
                <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="Eneter user password.....">
                <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $passwordErr ?></i></div>

                <label for="confirmPassword" class="form-label  mb-1">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-sm" placeholder="Eneter user same password.....">
                <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $confirmPasswordErr ?></i></div>
            <?php } ?>
            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
            </div>
        </form>
        <!-- </div> -->

        <!-- </div> -->
        <!-- </div> -->
    </div>
</div>
<?php require_once("../layout/footer.php") ?>