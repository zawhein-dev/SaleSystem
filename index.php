<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
require_once("./storage/branch_crud.php");
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
    }
}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>
    <?php if (have_admin($mysqli)) {
        $hashedPassword = password_hash("123456", PASSWORD_DEFAULT);
        //  $newFileName = uniqid('img_') . '.' . pathinfo("admin.jpg", PATHINFO_EXTENSION); 
        $status = save_user($mysqli, 'admin', 'admin@gmail.com', $hashedPassword, "user.jpg", 1);
        if ($status === true) {
        } else {
            $fail_query = $status;
        }
    } ?>
    <?php if (!isset($currentUser['user_id'])) { ?>
        <div class="text-end me-4 mt-4">
            <a href="./login.php" class="btn btn-success">Login</a>
            <a href="./register.php" class="btn btn-info">Register</a>
        </div>
    <?php } ?>
    <div class="d-flex row container-fluid mt-4">
        <?php $branches = get_branch($mysqli);
        while ($branch = $branches->fetch_assoc()) { ?>
            <div class="text-center col-4">
                <a href="./select_shop.php?branch_id=<?= $branch['branch_id'] ?>" class="btn btn-info mb-2 w-100"><?= $branch['branch_name'] ?></a>
            </div>
        <?php } ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>