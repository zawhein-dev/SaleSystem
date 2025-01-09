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
    if(isset($_GET['order_product_id'])){
        $order_product_id = $_GET['order_product_id'];
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
                        <th>Product Name</th>
                        <th>Branch Name</th>
                        <th>Category Name</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                     $product_orders = get_order_product_with_current_user($mysqli,$current_user_id,$order_product_id);
                    // var_dump($product_orders->fetch_assoc());
                    while($order_detail = $product_orders->fetch_assoc() ){
                        $total += $order_detail['total_price'];
                    ?>
                    <tr>
                        <td><?= $order_detail['product_name']?></td>
                        <td><?= $order_detail['branch_name']?></td>
                        <td><?= $order_detail['category_name']?></td>
                        <td><?= number_format($order_detail['product_price'], 0, ".", ",")?></td>
                        <td><?= number_format($order_detail['product_qty'], 0, ".", ",")?></td>
                        <td><?= number_format($order_detail['total_price'], 0, ".", ",")?></td>
                    </tr>
                <?php }  ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bolder">Total</td>
                   <td class="fw-bolder"><?= number_format($total, 0, ".", ",") ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const fullUrl = window.location.href;
    const parts = fullUrl.split("/");
    const latestUrl = parts[parts.length - 1];
    // console.log(latestUrl);
    const searchBars = document.getElementsByClassName("search-bar");
    if (latestUrl.includes("user_order_list.php")) {
        // for (let i = 0; i < searchBars.length; i++) {
            searchBars[0].style.display = "none"; 
        // }
    }
    // console.log(searchBars);
</script>

<!-- <script>
    const fullUrl = window.location.href;
    const parts = fullUrl.split("/");
    latestUrl = parts[parts.length - 1];
    console.log(latestUrl);
    searchBar = document.getElementsByClassName("search-bar");
    if (latestUrl.includes("user_order_list.php")) searchBar.css("display", "none")
    console.log(searchBar);
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php require_once("../userLayout/footer.php") ?>