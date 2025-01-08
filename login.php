<?php require_once("./storage/db.php"); ?>


<?php require_once("./storage/user_crud.php"); ?>

<?php
if (isset($_COOKIE['user'])) {
    header("location:./home.php");
}
$userEmail = $userEmailErr = "";
$password = $passwordErr = "";
$invalid = true;

if (isset($_POST['submit'])) {
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password'];

    if ($userEmail == "") {
        $userEmailErr = "Email cann't be blank!";
        $invalid = false;
    }
    if ($password == "") {
        $passwordErr = "Password cann't be blank!";
        $invalid = false;
    }
    if ($invalid == true) {
        $user = get_user_with_email($mysqli, $userEmail);
        if (!$user) {
            $userEmailErr = "Email does not match";
        } else {
            if (password_verify($password, $user['password'])) {
                setcookie("user", json_encode($user), time() + 60 * 60 * 24 * 30, "/");
                header("Location:./home.php");
                // var_dump("Hello");
            } else {
                $passwordErr = "Password does not match!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoginForm</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>

    <div class="card mx-auto w-50 mt-4">
        <div class="card-body">
            <h2 class="text-center">Login Form</h2>
            <?php if (isset($_GET['invalid'])) { ?>
                    <div class="alert alert-warning alert-dismissible fade mx-auto show w-75" role="alert">
                        <strong><?= $_GET['invalid'] ?></strong>
                        <button type="button" class=" btn-close close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    <?php } ?>
            <form method="post">
                <div class="form-input-group mx-auto w-75">
                    <label for="userEmail" class="mb-2">Email address</label>
                    <input name="userEmail" type="text" class="form-control" id="userEmail" value="" placeholder="name@gmail.com">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $userEmailErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <label for="password" class="mb-2">Password</label>
                    <input name="password" type="password" class="form-control" id="password" value="" placeholder="password">
                    <div style="height: 20px; line-height: 20px;"><i class="text-danger text-sm-start"><?= $passwordErr ?></i></div>
                </div>
                <div class="form-input-group mx-auto w-75">
                    <input type="checkbox" id="showPassword" class="form-check-input">
                    <label class="form-check-label" for="show">
                        Show Password
                    </label>
                    <div style="height: 20px; line-height: 20px;"></div>
                </div>
                <div class=" text-center">
                    <button class="btn btn-success w-25" name="submit">LOGIN</button>
                </div>
                <div class="text-center">
                    <a>Don't have account?</a>
                    <a href="./register.php" class="btn fs-6 text-primary">Sign Up Here</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(() => {
            let password = $("#password");
            let showPassword = $("#showPassword");
            showPassword.on("click", () => {
                if (showPassword.is(":checked")) {
                    password.get(0).type = "text";
                } else {
                    password.get(0).type = "password";
                }
            })
        })
    </script>
</body>

</html>