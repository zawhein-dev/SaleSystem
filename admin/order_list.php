<?php require_once("../layout/header.php");
if (isset($_COOKIE['user'])) {

    $userData = json_decode($_COOKIE['user'], associative: true);

    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser = get_user_with_id($mysqli, $user_id);
        $current_user_id = $currentUser['user_id'];
    }
}
if (isset($_GET['order_product_id'])) {
    $order_product_id = $_GET['order_product_id'];
    // var_dump($order_detail_id);
}
// if(isset($_GET['pending'])){
//     $order_detail_id = $_GET['pending'];
//     // var_dump($order_detail_id);
//     pending_order_status($mysqli,$order_detail_id);
// }
// if(isset($_GET['accept'])){
//     $order_detail_id = $_GET['accept'];
//     // var_dump($order_detail_id);
//     accept_order_status($mysqli,$order_detail_id);
// }
// if(isset($_GET['ready'])){
//     $order_detail_id = $_GET['ready'];
//     // var_dump($order_detail_id);
//     ready_order_status($mysqli,$order_detail_id);
// }
// if(isset($_GET['cancel'])){
//     $order_detail_id = $_GET['cancel'];
//     // var_dump($order_detail_id);
//     cancel_order_status($mysqli,$order_detail_id);
// }
?>
<?php require_once("../layout/navbar.php");
?>
</div>
</div>

<div class="content mt-4 mx-auto w-75">
    <div class="card w-90 mt-2 mx-1">
        <div class="card-title fs-3 text-center">User Order Detail</div>
        <div class="card-body">
            <table class="table table-striped  w-100 mx-auto">
                <thead>
                    <tr>
                        <th>Order_Code</th>
                        <th>Product Name</th>
                        <th>Category Name</th>
                        <th>Branch Name</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                        <!-- <th>Status</th>
                        <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                     $orders = get_product_with_order_product_id($mysqli, $order_product_id);
                    // var_dump($orders->fetch_assoc());
                    while ($order_product = $orders->fetch_assoc()) {
                        $total += $order_product['unit_price'];
                        ?>
                        <tr>
                            <td><?= "order_code_" . $order_product['order_product_id'] ?></td>
                            <td><?= $order_product['product_name'] ?></td>
                            <td><?= $order_product['category_name'] ?></td>
                            <td><?= $order_product['branch_name'] ?></td>
                            <td><?= $order_product['unit_price'] ?></td>
                            <td><?= $order_product['order_product_qty'] ?></td>
                            <td><?= $order_product['order_product_price'] ?></td>
                            <!-- <td class="text-danger fw-bolder" style="width: 100px;">
                                <?php if ($order_product['order_product_status'] == 0) {
                                    echo 'On Hold';
                                } else if ($order_product['order_product_status'] == 1){
                                    echo 'Accepted';
                                } else if ($order_product['order_product_status'] == 2){
                                echo 'Ready'; 
                                } else if ($order_product['order_product_status'] == 3){
                                    echo "Cancel";
                                }
                                ?>
                                </td>
                            <td>
                                <a href="?pending=<?= $order_product['order_detail_id'] ?>&order_product_id=<?= $order_product['order_product_id'] ?>" class="btn btn-sm btn-primary">on hold</a>
                                <a href="?accept=<?= $order_product['order_detail_id'] ?>&order_product_id=<?= $order_product['order_product_id'] ?>" class="btn btn-sm btn-primary">accept</a>
                                <a href="?ready=<?= $order_product['order_detail_id'] ?>&order_product_id=<?= $order_product['order_product_id'] ?>" class="btn btn-sm btn-primary">ready</a>
                                <a href="?cancel=<?= $order_product['order_detail_id'] ?>&order_product_id=<?= $order_product['order_product_id'] ?>" class="btn btn-sm btn-primary">cancel</a>
                            </td> -->
                        </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-center fw-bolder fs-6">Total</td>
                        <td class="fw-bolder fs-6"><?= $total ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>