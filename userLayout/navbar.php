<!-- #region -->
<?php
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
       
    }
}
?>
<div class="container_fluid bg-white">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid ">
            <a class="navbar-brand mx-5 fw-bold" href="#">Anycall Mobile</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse ms-5" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown mx-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           Order
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../user/index.php">Order Details</a></li>
                            <li><a class="dropdown-item" href="../user/order_onhold.php">Order OnHold</a></li>
                            <li><a class="dropdown-item" href="../user/order_accepted.php">Order Accepted</a></li>
                            <li><a class="dropdown-item" href="../user/order_ready.php">Order Ready</a></li>
                            <li><a class="dropdown-item" href="../user/order_cancel.php">Order Cancel</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown  mx-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account Details
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../user/edit.php">Edit account</a></li>
                            <li><a class="dropdown-item" href="../user/change_password.php">Change Password</a></li>
                        </ul>
                    </li>
                </ul>
                <form method="post">
                <div class="dropdown">
                    <a class="navbar-brand dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/userProfile/<?= $currentUser['profile'] ?>" style="width: 60px; height: 60px; border-radius: 50%;" id="profileImage" alt="Image" class="ms-2">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="../user/profile.php">Profile</a></li>
                        <li>
                            <button class="dropdown-item btn"  type="submit" name="logout">Logout</button>
                           
                        </li>
                    </ul>
                </div>
                </form>
            </div>
        </div>
    </nav>
<?php
if (isset($_POST['logout'])) {
    setcookie("user", '', -1, "/");
    header('Location:../index.php');
}
?>