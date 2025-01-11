<?php
require_once("./storage/db.php");
require_once("./storage/branch_product_crud.php");

session_start();
if (isset($_SESSION["item_list"])) {
    $item_array = $_SESSION["item_list"];
    // $branch_id_session =$_SESSION['branch_id_session'];
    if (count($item_array) > 0) {
        $net_total = 0;
        foreach ($item_array as $index => $item) {
            $current_branch_id = $item['branch_id'];
            $current_product_id = $item['product_id'];
            $branch_product = get_branch_product_for_order_detail($mysqli, $current_product_id, $current_branch_id);
            if($branch_product['qty'] == 0){
                array_splice($item_array, $index, 1);
                $_SESSION["item_list"] = $item_array;
            }else{
            $subtotal = $item['price'] * $item['qty'];
            $net_total += $subtotal;
            // echo '<div style="height: 300px;">';
            echo '<div class="row border-bottom py-2">';
            // echo '<div class="col-2">' . $item['branch_name'] . '</div>';
            echo '<div class="col-4">' . $item['product_name'] .  '</div>';
            echo '<div class="col-2 d-flex align-items-center">';
            echo '<div class="">' . $item['qty'] . '</div>';
            // echo '<input type="number" class="form-control form-control-sm me-2" style="width: 4rem;" value="' . $item['qty'] . '" readonly>';
            // echo '<a href="?remove=' . $index . '&branch_id=' . $branch_id . '" class="btn btn-sm btn-danger">X</a>';
            echo '</div>';
            echo '<div class="col-2 text-end">' . $item['price'] . '</div>';
            echo '<div class="col-2 text-end">' .number_format($subtotal, 0, ".", ",") . '</div>';
            // echo '</div>';
            echo '</div>'; 
            }
        
        } 
        echo '<div class="row mt-3 mb-2"><div class="col text-end"><strong>Net Total: ' . number_format($net_total, 0, ".", ",") . '</strong></div></div>';
        echo "<div class='d-flex mt-4 justify-content-center'>
        <div class='me-3'><a href='cart.php?branch_id=$current_branch_id' class='btn btn-success'>View Cart</a>
        </div>
        <form action='cart.php' method='get'>
        <input type='submit' class='btn btn-success' name='order' value='Order'>
        </form>
        </div>";
    } else {
        echo '<p>Your cart is empty.</p>';
    }
} else {
    echo '<p>Your cart is empty.</p>';
}
