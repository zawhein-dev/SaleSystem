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
    $users = get_user_with_id($mysqli, $user_id);
    $userName = $users['user_name'];
    $userEmail = $users['user_email'];
    $oldPassword = $users['password'];
    $oldProfileName = $users['profile'];
    $role = $users['role'];
}
if (isset($_POST['submit'])) {
    $userName = $_POST["userName"];
    $userEmail = $_POST["userEmail"];
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    $file = $_FILES['profile'];

    $profileName = $file['name'];
    $profileTmpPath = $file['tmp_name'];
    $profileSize = $file['size'];
    $profileType = $file['type'];
    $profileError = $file['error'];

    if (!isset($_GET['user_id'])) {
        if ($profileName == "") {
            $profileNameErr = "Please choose user profile";
            $invalid = false;
        }
    }
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {

        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxFileSize = 3 * 1024 * 1024; // 3 MB

        if (!in_array($profileType, $allowedMimeTypes)) {
            $profileNameErr = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
            $invalid = false;
        }

        if ($profileSize > $maxFileSize) {
            $profileNameErr = "File size exceeds the 2 MB limit.";
            $invalid = false;
        }

    }
    // var_dump($role);
    // $profileTmpPath = $_FILES['profile']['tmp_name'];
    // $profileName = $_FILES['profile']['name'];
    // $profileSize = $_FILES['profile']['size'];
    // $profileType = $_FILES['profile']['type'];
    if (!isset($_GET['user_id'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        if ($password == "") {
            $passwordErr = "Please enter user password";
            $invalid = false;
        } else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $password)) {
            $passwordErr = "Enter strong password (eg.Hein@123)";
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
    } else if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_\. ]{2,19}$/', $userName)) {
        //John.Doe is a valid username.
        // user_123 is a valid username.
        // 1st_user is not a valid username.
        // ab is not a valid username.
        // valid_username123 is a valid username.
        $userNameErr = "Invalid user name";
        $invalid = false;
    }
    if ($userEmail == "") {
        $userEmailErr = "Please enter user email";
        $invalid = false;
    } else if (filter_var($userEmail, FILTER_VALIDATE_EMAIL) === false) {
        $userEmailErr = "Invalid email format";
        $invalid = false;
    }
    if ($role == '') {
        $roleErr = "Please select user role";
        $invalid = false;
    } else if (!preg_match('/^[123]$/', $role)) {
        $roleErr = "Role must be number";
        $invalid = false;
    }
    if ($invalid) {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            if ($_FILES['profile']['name'] == "") {
                $status = update_user($mysqli, $userName, $userEmail, $oldPassword, $oldProfileName, $role, $user_id);
                if ($status == 1) {
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
                if ($status == 1) {
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
                    // echo $fail_query;
                }
            }
        } else {
            $targetDir = '../assets/userProfile/';
            $newFileName = uniqid('img_') . '.' . pathinfo($profileName, PATHINFO_EXTENSION);  // Generate a unique file name
            $targetFilePath = $targetDir . $newFileName;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $status = save_user($mysqli, $userName, $userEmail, $hashedPassword, $newFileName, $role);
            if (is_numeric($status)) {
                if (move_uploaded_file($profileTmpPath, $targetFilePath)) {
                    echo "<script>location.replace('./user_list.php')</script>";
                } else {
                    echo "File upload failed.";
                }
            } else {
                   $fail_query =  $status; 
            }

        }
    }
}

?>
<div class="main bg-white w-100">
    <div class="main-content w-50 mx-auto">
        <?php if (isset($_GET['user_id'])) { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update User</div>
        <?php } else { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add User</div>
        <?php } ?>
        <?php if (isset($fail_query)) { ?>
            <div id="autoCloseAlert" class="alert alert-warning alert-dismissible fade mx-auto show w-75 mt-2" role="alert">
                <strong><?= $fail_query ?></strong>
                <button type="button" class=" btn-close close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        <?php } ?>
        <form action="" method="POST" class="form-group" enctype="multipart/form-data">
            <label for="userName" class="form-label mb-1">Name</label>
            <input type="text" name="userName" value="<?= $userName ?>" id="userName"
                class="form-control form-control-sm" placeholder="Eneter user name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                    class="text-danger text-sm-start"><?= $userNameErr ?></i></div>

            <label for="userEmail" class="form-label  mb-1">Email</label>
            <input type="email" name="userEmail" value="<?= $userEmail ?>" id="userEmail"
                class="form-control form-control-sm" placeholder="Eneter user email.....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                    class="text-danger text-sm-start"><?= $userEmailErr ?></i></div>

            <label for="role" class="form-label mb-1">Role</label>

            <select name="role" id="role" class="form-select form-select-sm" <?php if ($role == "1") {
                echo "disabled";
            } ?> >
                <option value="" disabled <?php if (empty($role)) {
                    echo "selected";
                } ?>>Select Role</option>
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

            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                    class="text-danger text-sm-start"><?= $roleErr ?></i></div>

            <label for="profile" class="form-label  mb-1">Photo</label>
            <input type="file" name="profile" id="profile" class="form-control form-control-sm">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                    class="text-danger text-sm-start"><?= $profileNameErr ?></i></div>
            <?php if (!isset($_GET['user_id'])) { ?>
                <label for="password" class="form-label  mb-1">Password</label>
                <input type="password" name="password" id="password" class="form-control form-control-sm"
                    placeholder="Eneter user password.....">
                <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                        class="text-danger text-sm-start"><?= $passwordErr ?></i></div>

                <label for="confirmPassword" class="form-label  mb-1">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-sm"
                    placeholder="Eneter user same password.....">
                <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i
                        class="text-danger text-sm-start"><?= $confirmPasswordErr ?></i></div>
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