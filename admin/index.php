
<?php require_once("../layout/header.php");
require_once("../auth/isLogin.php"); ?>
<?php require_once("../layout/navbar.php") ?>
</div>
</div>
<?php 
if (isset($_COOKIE['user'])) {
    $userData = json_decode($_COOKIE['user'], associative: true);
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser = get_user_with_id($mysqli, $user_id);
        $current_user_id = $currentUser['user_id'];
    }
}
if (isset($_GET['accept'])) {
    $order_product_id = $_GET['accept'];
    // var_dump($order_product_id);
    $order_product_status = get_order_product_status($mysqli, $order_product_id);
    if ($order_product_status['status'] == 3) {
        $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
        $should_execute_queries = true; 
        while ($result = $branch_product_qty->fetch_assoc()) {
            $update_branch_product_data = $result['branch_product_id'];
            $update_branch_product_qty = $result['qty'];
            $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
            if ($instock['qty'] == 0) {
                $should_execute_queries = false; 
                break; 
            }
        }
        if ($should_execute_queries) {
            $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
            while ($result = $branch_product_qty->fetch_assoc()) {
                $update_branch_product_data = $result['branch_product_id'];
                $update_branch_product_qty = $result['qty'];
                $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
                accept_status($mysqli, $order_product_id);
                update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
            }
        }
        // echo "hello";
        // $status =  accept_status($mysqli, $order_product_id);
        // if ($status == true) {
        //     $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
        //     while ($result = $branch_product_qty->fetch_assoc()) {
        //         $update_branch_product_data = $result['branch_product_id'];
        //         $update_branch_product_qty = $result['qty'];

        //         update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
        //     }
        // }
    } else{
        accept_status($mysqli, $order_product_id);
    }
}
if (isset($_GET['ready'])) {
    $order_product_id = $_GET['ready'];
    // var_dump($order_product_id);
    $order_product_status = get_order_product_status($mysqli, $order_product_id);
    if ($order_product_status['status'] == 3) {
        $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
        $should_execute_queries = true; // A flag to control query execution
        
        while ($result = $branch_product_qty->fetch_assoc()) {
            $update_branch_product_data = $result['branch_product_id'];
            $update_branch_product_qty = $result['qty'];
            $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
        
            // Check if any $instock['qty'] equals 0
            if ($instock['qty'] == 0) {
                $should_execute_queries = false; // Set the flag to false
                break; // Exit the loop as we no longer need to process further
            }
        }
        if ($should_execute_queries) {
            $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
            while ($result = $branch_product_qty->fetch_assoc()) {
                $update_branch_product_data = $result['branch_product_id'];
                $update_branch_product_qty = $result['qty'];
                $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
                ready_status($mysqli, $order_product_id);
                update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
            }
        }

        // echo "hello";
        // $status = ready_status($mysqli, $order_product_id);
        // if ($status == true) {
        //     $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
        //     while ($result = $branch_product_qty->fetch_assoc()) {
        //         $update_branch_product_data = $result['branch_product_id'];
        //         $update_branch_product_qty = $result['qty'];
        //         update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
        //     }
        // }
    } else{
        ready_status($mysqli, $order_product_id);
    }

}

if (isset($_GET['pending'])) {
    $order_product_id = $_GET['pending'];
    // var_dump($order_product_id);
    $order_product_status = get_order_product_status($mysqli, $order_product_id);
    if ($order_product_status['status'] == 3) {

        $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
        $should_execute_queries = true; // A flag to control query execution
        
        while ($result = $branch_product_qty->fetch_assoc()) {
            $update_branch_product_data = $result['branch_product_id'];
            $update_branch_product_qty = $result['qty'];
            $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
        
            // Check if any $instock['qty'] equals 0
            if ($instock['qty'] == 0) {
                $should_execute_queries = false; // Set the flag to false
                break; // Exit the loop as we no longer need to process further
            }
        }
        if ($should_execute_queries) {
            $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
            while ($result = $branch_product_qty->fetch_assoc()) {
                $update_branch_product_data = $result['branch_product_id'];
                $update_branch_product_qty = $result['qty'];
                $instock = get_qty_with_branch_product_id($mysqli, $update_branch_product_data);
                pending_status($mysqli, $order_product_id);
                update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
            }
        }
        // echo "hello";
        // $status = pending_status($mysqli, $order_product_id);
        // if ($status == true) {
            // $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
            // while ($result = $branch_product_qty->fetch_assoc()) {
            //     $update_branch_product_data = $result['branch_product_id'];
            //     $update_branch_product_qty = $result['qty'];
            //     $instock = get_qty_with_branch_product_id($mysqli,$update_branch_product_data);
            //     // var_dump($instock);
            //     if($instock['qty'] != 0){
            //         var_dump($instock);
            //     pending_status($mysqli, $order_product_id);
            //     update_branch_product_when_order_cancel_with_minus($mysqli, $update_branch_product_data, $update_branch_product_qty);
            //     }
            // }
        // }
    } else{
        pending_status($mysqli, $order_product_id);
    }
    
}

if (isset($_GET['cancel'])) {
    $order_product_id = $_GET['cancel'];
    // var_dump($order_product_id);

    $order_product_status = get_order_product_status($mysqli, $order_product_id);
    if ($order_product_status['status'] != 3) {
        // echo "hello";
        // $status = cancel_status($mysqli, $order_product_id);
        // if ($status == true) {
            $branch_product_qty = get_branch_product_id_when_order_cancel($mysqli, $order_product_id);
            while ($result = $branch_product_qty->fetch_assoc()) {
                $update_branch_product_data = $result['branch_product_id'];
                $update_branch_product_qty = $result['qty'];
                $instock = get_qty_with_branch_product_id($mysqli,$update_branch_product_data);
                // var_dump($instock);
                // var_dump($update_branch_product_qty);
                if($instock > 0){
                $status = cancel_status($mysqli, $order_product_id);
                update_branch_product_when_order_cancel($mysqli, $update_branch_product_data, $update_branch_product_qty);
                }
            }
        // }
    }

}
$limit = 5;
$page = isset($_GET['pageNo']) ? intval($_GET['pageNo']) : 1;
$offset = ($page - 1) * $limit;
$numberTitle = ($page * $limit) - $limit;
    if (isset($_GET['search_data'])) {
            $searchData = $_GET['search_data'];
            $row =  get_order_product_with_search_data($mysqli, $searchData);
            $row_count = COUNT($row->fetch_all());
            $pagination_link = ceil($row_count / 5); 
            $order_product = get_order_product_with_offset($mysqli, $offset, $limit,$searchData);
    }else{
        $row = get_order_detail($mysqli);
        $row_count  = COUNT($row->fetch_all()); //get number of users
        $pagination_link = ceil($row_count / 5);
        $order_product = get_search_order_product_with_offset($mysqli, $offset, $limit);
    }

?>

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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $order_product = get_order_detail($mysqli);
                    while ($orders = $order_product->fetch_assoc()) { ?>
                        <tr>
                            <td><?= "#order_code_" . $orders['order_product_id'] ?></td>
                            <td><?= $orders['user_name'] ?></td>
                            <td><?= $orders['order_date'] ?></td>
                            <td class="text-danger fw-bolder" style="width: 100px;"><?php if ($orders['status'] == 0) {
                                echo 'On Hold';
                            } else if ($orders['status'] == 1) {
                                echo 'Accepted';
                            } else if ($orders['status'] == 2) {
                                echo 'Ready';
                            } else if ($orders['status'] == 3) {
                                echo "Cancel";
                            }
                            ?>
                            </td>
                            <td>
                                <a href="?pending=<?= $orders['order_product_id'] ?>&pageNo=<?php if(isset($_GET['pageNo'])){ echo $_GET['pageNo'];} else{ echo "1";}?>" class="btn btn-sm btn-primary">on
                                    hold</a>
                                <a href="?accept=<?= $orders['order_product_id'] ?>&pageNo=<?php if(isset($_GET['pageNo'])){ echo $_GET['pageNo'];} else{ echo "1";}?>"
                                    class="btn btn-sm btn-primary">accept</a>
                                <a href="?ready=<?= $orders['order_product_id'] ?>&pageNo=<?php if(isset($_GET['pageNo'])){ echo $_GET['pageNo'];} else{ echo "1";}?>" class="btn btn-sm btn-primary">ready</a>
                                <a href="?cancel=<?= $orders['order_product_id'] ?>&pageNo=<?php if(isset($_GET['pageNo'])){ echo $_GET['pageNo'];} else{ echo "1";}?>"
                                    class="btn btn-sm btn-primary">cancel</a>
                            </td>
                            <td>
                                <a href="./user_detail.php?user_id=<?= $orders['user_id'] ?>"
                                    class="btn btn-sm btn-primary">User Info</a>
                                <a href="./order_list.php?order_product_id=<?= $orders['order_product_id'] ?>"
                                    class="btn btn-sm btn-primary">Order Detail</a>
                            </td>
                        </tr>
                    <?php } ?>
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
<?php require_once("../layout/footer.php") ?>