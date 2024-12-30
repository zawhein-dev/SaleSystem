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
<?php require_once("../userLayout/header.php");
require_once("../auth/isLogin.php"); ?>
<?php require_once("../userLayout/navbar.php") ?>
<div class="content mt-4 mx-auto w-75">
    <div class="card w-90 mt-2 mx-1">
        <div class="card-title fs-3 text-center">User Order Detail</div>
        <div class="card-body">
            <table class="table table-striped  w-100 mx-auto">
            <thead>
                    <tr>
                        <th>Order_Code</th>
                        <th>User Name</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $user_buying =   get_order_cancel_with_current_user($mysqli,$current_user_id);
                    // $i = 1;
                    // var_dump($user_buying->fetch_assoc());
                    while ($order_detail = $user_buying->fetch_assoc()) {
                    ?> <tr>
                    <td><?= "order_code_".$order_detail['order_product_id'] ?></td>
                    <td><?= $order_detail['user_name']?></td>
                    <td><?= $order_detail['order_date']?></td>
                    <td class="text-danger fw-bolder" style="width: 100px;">
                            <?php if ($order_detail['status'] == 0) {
                                echo 'On Hold';
                            } else if ($order_detail['status'] == 1){
                                echo 'Accepted';
                            } else if ($order_detail['status'] == 2){
                            echo 'Ready'; 
                            } else if ($order_detail['status'] == 3){
                                echo "Cancel";
                            }
                            ?>
                            </td>
                            <td>
                                <a href="./user_order_list.php?order_product_id=<?= $order_detail['order_product_id'] ?>" class="btn btn-sm btn-primary">Order Detail</a>
                            </td>
                 </tr>
                    <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once("../userLayout/footer.php") ?>