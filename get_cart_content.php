<?php
session_start();
if (isset($_SESSION["item_list"])) {
    $item_array = $_SESSION["item_list"];
    if (count($item_array) > 0) {
        $net_total = 0;
        foreach ($item_array as $index => $item) {
            $subtotal = $item['price'] * $item['qty'];
            $net_total += $subtotal;
            echo '<div class="row border-bottom py-2">';
            echo '<div class="col-2">' . $item['branch_name'] . '</div>';
            echo '<div class="col-3">' . $item['product_name'] . '</div>';
            echo '<div class="col-3 d-flex align-items-center">';
            echo '<input type="number" class="form-control form-control-sm me-2" style="width: 4rem;" value="' . $item['qty'] . '" readonly>';
            // echo '<a href="?remove=' . $index . '&branch_id=' . $branch_id . '" class="btn btn-sm btn-danger">X</a>';
            echo '</div>';
            echo '<div class="col-2">' . $item['price'] . '</div>';
            echo '<div class="col-2">' . $subtotal . '</div>';
            echo '</div>';
        }
        echo '<div class="row mt-3"><div class="col text-end"><strong>Net Total: ' . $net_total . '</strong></div></div>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
} else {
    echo '<p>Your cart is empty.</p>';
}
