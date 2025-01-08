<?php
require_once("./storage/db.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");

session_start();
$show = false;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
}
if (isset($_POST['checkout'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_post_id == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }

        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
        }

        $_SESSION["item_list"] = $item_array;
        if (!isset($_COOKIE['user'])) {
            header("Location:./register.php?order");
        } else if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], associative: true);
            $user_id = $userData['user_id'];
            //     // $currentUser =   get_user_with_id($mysqli, $user_id);
            if (save_order_product($mysqli, $user_id)) {
                $order_product_id = get_last_order_product_id($mysqli);
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                    save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                    update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
                unset($_SESSION["item_list"]);
                session_destroy();
                header("Location:./home.php?user_id =" . $user_id);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $products = get_product_with_id($mysqli, $product_post_id);
        $product_id_from_db = $products['product_id'];
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_id_from_db == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }
        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
            $_SESSION["item_list"] = $item_array;
            header("location:cart.php?branch_id=" . $branch_post_id);
        } else {
            $show = true;
            $_SESSION["item_list"] = $item_array;
            // header("location:cart.php?branch_id=".$branch_post_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>
    <?php if ($show) { ?>
        <div>
            <a href="./cart.php?branch_id=<?= $branch_post_id ?>" </a> View Cart!</a> The product you choose is already exist in cart.
        </div>
    <?php  } ?>
    <table class="table table-striped">
        <thead>
            <th>Product Name</th>
            <th>Photo</th>
            <th>Price</th>
            <th>Description</th>
            <th>Category Name</th>
        </thead>
        <tbody>
            <tr>
                <td><?= $product['product_name'] ?></td>
                <td><img src="./assets/product/<?= $product['photo'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="currentBranchProductQty" id="currentBranchProductQty" value="<?= $branch_product['qty'] ?>">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <button type="button" id="minus" class="btn btn-sm btn-success">-</i></button>
                        <input type="text" readonly name="qty" id="qty" class="btn btn-sm bg-light w-25" value="1">
                        <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
                </td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['category_name'] ?></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" name="submit" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Add to cart</button>
    <button name="checkout" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Check Out</button>
    </form>
    <script>
        // qty_in_branch = 6;
        $currentBranchProductQty = document.getElementById('currentBranchProductQty');
        plus = document.getElementById('plus');
        qty = document.getElementById("qty");
        minus = document.getElementById('minus');
        plus.addEventListener("click", () => {
            // if (qty_in_branch != qty.value) {
            if ($currentBranchProductQty.value > qty.value) {
                qty.value++;
            }
            // }
        })
        minus.addEventListener("click", () => {
            if (qty.value != 1) {
                qty.value--;
            }
        })
    </script>
</body>

</html>

<?php
require_once("./storage/db.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");

session_start();
$show = false;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
}
if (isset($_POST['checkout'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_post_id == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }

        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
        }

        $_SESSION["item_list"] = $item_array;
        if (!isset($_COOKIE['user'])) {
            header("Location:./register.php?order");
        } else if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], associative: true);
            $user_id = $userData['user_id'];
            //     // $currentUser =   get_user_with_id($mysqli, $user_id);
            if (save_order_product($mysqli, $user_id)) {
                $order_product_id = get_last_order_product_id($mysqli);
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                    save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                    update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
                unset($_SESSION["item_list"]);
                session_destroy();
                header("Location:./home.php?user_id =" . $user_id);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $products = get_product_with_id($mysqli, $product_post_id);
        $product_id_from_db = $products['product_id'];
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_id_from_db == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }
        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
            $_SESSION["item_list"] = $item_array;
            header("location:cart.php?branch_id=" . $branch_post_id);
        } else {
            $show = true;
            $_SESSION["item_list"] = $item_array;
            // header("location:cart.php?branch_id=".$branch_post_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>
    <?php if ($show) { ?>
        <div>
            <a href="./cart.php?branch_id=<?= $branch_post_id ?>" </a> View Cart!</a> The product you choose is already exist in cart.
        </div>
    <?php  } ?>
    <table class="table table-striped">
        <thead>
            <th>Product Name</th>
            <th>Photo</th>
            <th>Price</th>
            <th>Description</th>
            <th>Category Name</th>
        </thead>
        <tbody>
            <tr>
                <td><?= $product['product_name'] ?></td>
                <td><img src="./assets/product/<?= $product['photo'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="currentBranchProductQty" id="currentBranchProductQty" value="<?= $branch_product['qty'] ?>">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <button type="button" id="minus" class="btn btn-sm btn-success">-</i></button>
                        <input type="text" readonly name="qty" id="qty" class="btn btn-sm bg-light w-25" value="1">
                        <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
                </td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['category_name'] ?></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" name="submit" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Add to cart</button>
    <button name="checkout" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Check Out</button>
    </form>
    <script>
        // qty_in_branch = 6;
        $currentBranchProductQty = document.getElementById('currentBranchProductQty');
        plus = document.getElementById('plus');
        qty = document.getElementById("qty");
        minus = document.getElementById('minus');
        plus.addEventListener("click", () => {
            // if (qty_in_branch != qty.value) {
            if ($currentBranchProductQty.value > qty.value) {
                qty.value++;
            }
            // }
        })
        minus.addEventListener("click", () => {
            if (qty.value != 1) {
                qty.value--;
            }
        })
    </script>
</body>

</html>

<?php
require_once("./storage/db.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");

session_start();
$show = false;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
}
if (isset($_POST['checkout'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_post_id == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }

        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
        }

        $_SESSION["item_list"] = $item_array;
        if (!isset($_COOKIE['user'])) {
            header("Location:./register.php?order");
        } else if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], associative: true);
            $user_id = $userData['user_id'];
            //     // $currentUser =   get_user_with_id($mysqli, $user_id);
            if (save_order_product($mysqli, $user_id)) {
                $order_product_id = get_last_order_product_id($mysqli);
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                    save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                    update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
                unset($_SESSION["item_list"]);
                session_destroy();
                header("Location:./home.php?user_id =" . $user_id);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $products = get_product_with_id($mysqli, $product_post_id);
        $product_id_from_db = $products['product_id'];
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_id_from_db == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }
        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
            $_SESSION["item_list"] = $item_array;
            header("location:cart.php?branch_id=" . $branch_post_id);
        } else {
            $show = true;
            $_SESSION["item_list"] = $item_array;
            // header("location:cart.php?branch_id=".$branch_post_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>
    <?php if ($show) { ?>
        <div>
            <a href="./cart.php?branch_id=<?= $branch_post_id ?>" </a> View Cart!</a> The product you choose is already exist in cart.
        </div>
    <?php  } ?>
    <table class="table table-striped">
        <thead>
            <th>Product Name</th>
            <th>Photo</th>
            <th>Price</th>
            <th>Description</th>
            <th>Category Name</th>
        </thead>
        <tbody>
            <tr>
                <td><?= $product['product_name'] ?></td>
                <td><img src="./assets/product/<?= $product['photo'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="currentBranchProductQty" id="currentBranchProductQty" value="<?= $branch_product['qty'] ?>">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <button type="button" id="minus" class="btn btn-sm btn-success">-</i></button>
                        <input type="text" readonly name="qty" id="qty" class="btn btn-sm bg-light w-25" value="1">
                        <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
                </td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['category_name'] ?></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" name="submit" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Add to cart</button>
    <button name="checkout" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Check Out</button>
    </form>
    <script>
        // qty_in_branch = 6;
        $currentBranchProductQty = document.getElementById('currentBranchProductQty');
        plus = document.getElementById('plus');
        qty = document.getElementById("qty");
        minus = document.getElementById('minus');
        plus.addEventListener("click", () => {
            // if (qty_in_branch != qty.value) {
            if ($currentBranchProductQty.value > qty.value) {
                qty.value++;
            }
            // }
        })
        minus.addEventListener("click", () => {
            if (qty.value != 1) {
                qty.value--;
            }
        })
    </script>
</body>

</html>

<?php
require_once("./storage/db.php");
require_once("./storage/product_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./storage/order_product_crud.php");
require_once("./storage/order_detail_crud.php");

session_start();
$show = false;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = $_GET['branch_id'];
    $product = get_product_with_id($mysqli, $product_id);
    $branch_product = get_branch_product_for_order_detail($mysqli, $product_id, $branch_id);
}
if (isset($_POST['checkout'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_post_id == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }

        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
        }

        $_SESSION["item_list"] = $item_array;
        if (!isset($_COOKIE['user'])) {
            header("Location:./register.php?order");
        } else if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], associative: true);
            $user_id = $userData['user_id'];
            //     // $currentUser =   get_user_with_id($mysqli, $user_id);
            if (save_order_product($mysqli, $user_id)) {
                $order_product_id = get_last_order_product_id($mysqli);
                $item_array =  $_SESSION["item_list"];
                foreach ($item_array as $index => $item) {
                    $total = $item['qty'] * $item['price'];
                    save_order_detail($mysqli, $order_product_id['order_product_id'], $item['branch_product_id'], $item['qty'], $total);
                    update_qty_when_order_success($mysqli, $item['qty'], $item['branch_product_id']);
                }
                unset($_SESSION["item_list"]);
                session_destroy();
                header("Location:./home.php?user_id =" . $user_id);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    if ($_POST['currentBranchProductQty'] != 0) {
        $qty = $_POST['qty'];
        $product_post_id = $_POST['product_id'];
        $branch_post_id = $_POST['branch_id'];
        $item_array = [];
        if (isset($_SESSION["item_list"])) {
            $item_array = $_SESSION['item_list'];
        }
        $branch_product = get_branch_product_for_order_detail($mysqli, $product_post_id, $branch_post_id);
        $products = get_product_with_id($mysqli, $product_post_id);
        $product_id_from_db = $products['product_id'];
        $isHave = true;
        for ($i = 0; $i < count($item_array); $i++) {
            if ($product_id_from_db == $item_array[$i]['product_id']) {
                $isHave = false;
                // $item_array[$i]['qty']++;
            }
        }
        if ($isHave) {
            array_push($item_array, ['product_id' => $branch_product['product_id'], 'branch_product_id' => $branch_product['branch_product_id'], 'product_name' => $branch_product['product_name'], 'price' => $branch_product['price'], 'branch_name' => $branch_product['branch_name'], 'qty' => $qty]);
            $_SESSION["item_list"] = $item_array;
            header("location:cart.php?branch_id=" . $branch_post_id);
        } else {
            $show = true;
            $_SESSION["item_list"] = $item_array;
            // header("location:cart.php?branch_id=".$branch_post_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>

<body>
    <?php if ($show) { ?>
        <div>
            <a href="./cart.php?branch_id=<?= $branch_post_id ?>" </a> View Cart!</a> The product you choose is already exist in cart.
        </div>
    <?php  } ?>
    <table class="table table-striped">
        <thead>
            <th>Product Name</th>
            <th>Photo</th>
            <th>Price</th>
            <th>Description</th>
            <th>Category Name</th>
        </thead>
        <tbody>
            <tr>
                <td><?= $product['product_name'] ?></td>
                <td><img src="./assets/product/<?= $product['photo'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="currentBranchProductQty" id="currentBranchProductQty" value="<?= $branch_product['qty'] ?>">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <button type="button" id="minus" class="btn btn-sm btn-success">-</i></button>
                        <input type="text" readonly name="qty" id="qty" class="btn btn-sm bg-light w-25" value="1">
                        <button type="button" id="plus" class="btn btn-sm btn-primary">+</i></button>
                </td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['category_name'] ?></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" name="submit" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Add to cart</button>
    <button name="checkout" class="btn btn-success" <?php if($branch_product['qty'] == 0) {echo "disabled";} else{ echo "";} ?>>Check Out</button>
    </form>
    <script>
        // qty_in_branch = 6;
        $currentBranchProductQty = document.getElementById('currentBranchProductQty');
        plus = document.getElementById('plus');
        qty = document.getElementById("qty");
        minus = document.getElementById('minus');
        plus.addEventListener("click", () => {
            // if (qty_in_branch != qty.value) {
            if ($currentBranchProductQty.value > qty.value) {
                qty.value++;
            }
            // }
        })
        minus.addEventListener("click", () => {
            if (qty.value != 1) {
                qty.value--;
            }
        })
    </script>
</body>

</html>