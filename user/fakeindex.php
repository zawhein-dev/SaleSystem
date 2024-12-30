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
    }
}
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
   
    <div class="main d-flex bg-white">
        <div class="col-2 bg-info" style="height:100vh;">
            <div>
                My Account
            </div>
        </div>
        <div class="content w-100 mx-3">
            <div class="card w-90 mt-2 mx-1">
                <div class="card-title fs-3 text-center">User Order Detail</div>
                <div class="card-body">
                    <table class="table table-striped  w-100 mx-auto">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Branch Name</th>
                                <th>Product Name</th>
                                <th>Total Price</th>
                                <th>User Name</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $user_buying =   user_order_detail($mysqli, $current_user_id);
                            $i = 1;
                            while ($order_detail = $user_buying->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $order_detail['branch_name'] ?></td>
                                    <td><?= $order_detail['product_name'] ?></td>
                                    <td><?= $order_detail['price'] ?></td>
                                    <td><?= $order_detail['userName'] ?></td>
                                    <td><?= $order_detail['qty'] ?></td>
                                    <td><?php if ($order_detail['status'] == 0) {
                                            echo 'Ordering';
                                        } else {
                                            echo 'taken';
                                        } ?></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>