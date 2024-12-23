<?php
require_once("../storage/db.php");
require_once("../auth/isLogin.php");
require_once("../storage/user_crud.php");
require_once("../storage/order_detail_crud.php");


if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
        $current_user_id = $currentUser['user_id'];
    }
}
// var_dump($currentUser['user_id']);
?>
<div class="main bg-white">
    <div class="content w-100">
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
                        <?php $user_buying =   user_order_detail($mysqli,$current_user_id);
                        $i= 1;
                        // var_dump($user_buying->fetch_assoc());
                            while($order_detail =$user_buying->fetch_assoc()){
                        ?>
                        <tr>
                        <td><?= $i ?></td>
                            <td><?= $order_detail['branch_name'] ?></td>
                            <td><?= $order_detail['product_name'] ?></td>
                            <td><?= $order_detail['price'] ?></td>
                            <td><?= $order_detail['userName'] ?></td>
                            <td><?= $order_detail['qty'] ?></td>
                            <td><?php if($order_detail['status'] == 0) {echo 'Ordering';}else{echo 'taken';} ?></td>
                            </tr>
                        <?php $i++;}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>