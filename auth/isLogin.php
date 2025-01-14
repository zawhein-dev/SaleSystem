<?php
$user = json_decode($_COOKIE["user"], true);
// var_dump($user);
if (!$user) {
    header("Location:../login.php?invalid=Please login first!");
} else {
    $url = $_SERVER['REQUEST_URI'];
    $arr = explode('/', $url);
    // var_dump($arr);
    $code = 0;
    if ($arr[count($arr) - 2] !== "SaleSystem") {
        $role_name = $arr[count($arr) - 2];
        switch ($role_name) {
            case 'admin':
                $code = 1;
                break;
            case 'staff':
                $code = 2;
                break;
            case 'user':
                $code = 3;
                break; 
        }
    }
    // var_dump($code);
    // var_dump($user['role']);
    if ($code != $user['role']) {
        header('Location:../401.php');
    }
}
if (isset($_POST['logout'])) {
    setcookie("user", '', -1, "/");
    header('Location:../index.php');
}


// function isKitchen($user){
//     if($user['role'] != 3){
//         header("Location:../401.php");
//     }
// }
