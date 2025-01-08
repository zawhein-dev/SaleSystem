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
$limit = 6;
$page = isset($_GET['pageNo']) ? intval($_GET['pageNo']) : 1;
$offset = ($page - 1) * $limit;
$numberTitle = ($page * $limit) - $limit;
    if (isset($_GET['search_data'])) {
            $searchData = $_GET['search_data'];
            $row =  get_order_detail_with_current_user_with_search_data($mysqli, $searchData,$current_user_id);
            $row_count = COUNT($row->fetch_all());
            $pagination_link = ceil($row_count / 6); 
            $order_product = get_order_detail_with_current_user_with_offset($mysqli, $offset, $limit,$searchData,$current_user_id);
    }else{
        // $row = get_user($mysqli);
        $row = get_order_detail_with_current_user($mysqli,$current_user_id);
        $row_count  = COUNT($row->fetch_all()); //get number of users
        $pagination_link = ceil($row_count / 6);
        $order_product = get_search_order_detail_with_current_user_with_offset($mysqli, $offset, $limit,$current_user_id);
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
                        <th>Order_date</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //  $order_product = get_order_detail_with_current_user($mysqli,$current_user_id);
                    //  var_dump($order_product->fetch_assoc());
                     while($user_order = $order_product->fetch_assoc()){
                     ?>
                     <tr>
                        <td><?= "order_code_".$user_order['order_product_id'] ?></td>
                        <td><?= $user_order['user_name']?></td>
                        <td><?= $user_order['order_date']?></td>
                        <td class="text-danger fw-bolder" style="width: 100px;">
                                <?php if ($user_order['status'] == 0) {
                                    echo 'On Hold';
                                } else if ($user_order['status'] == 1){
                                    echo 'Accepted';
                                } else if ($user_order['status'] == 2){
                                echo 'Ready'; 
                                } else if ($user_order['status'] == 3){
                                    echo "Cancel";
                                }
                                ?>
                                </td>
                                <td>
                                    <a href="./user_order_list.php?order_product_id=<?= $user_order['order_product_id'] ?>" class="btn btn-sm btn-primary">Order Detail</a>
                                </td>
                     </tr>
                     <?php  } ?>
                </tbody>
            </table>
            <?php if (!($row_count <= $limit)) { ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) echo 'disabled' ?>">
                                <a class="page-link" href="?pageNo=<?= $page - 1; ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>">Previous</a>
                            </li>
                            <?php $j = 1;
                            while ($pagination_link >= $j) { ?>
                                <li class="page-item">
                                    <a class="page-link <?php if ($page == $j) echo 'active' ?>" href="?pageNo=<?= $j ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>"><?php echo $j; ?></a>
                                </li>
                            <?php $j++;
                            } ?>
                            <li class="page-item <?php if ($pagination_link == $page) echo 'disabled' ?>">
                                <a class="page-link"" href=" ?pageNo=<?= $page + 1; ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php } ?> 
        </div>
    </div>
</div>
<?php require_once("../userLayout/footer.php") ?>