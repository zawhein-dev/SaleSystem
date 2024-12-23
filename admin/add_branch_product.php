<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$product = $productErr =  "";
$branch = $branchErr = "";
$qty = $qtyErr = "";
$invalid = true;
if(isset($_GET['branch_product_id'])){
    $branch_product_id = $_GET['branch_product_id'];
    $branch_product_list = get_branch_product_with_id($mysqli,$branch_product_id);
    // var_dump($branch_product_list);
    $branch = $branch_product_list['branch_id'];
    $product = $branch_product_list['product_id'];
    $qty = $branch_product_list['qty'];
}

if(isset($_POST['submit'])){
    $branch = $_POST['branch'];
    $product = $_POST['product'];
    $qty = $_POST['qty'];
    if($branch == ""){
        $branchErr = "Please choose branch..";
        $invalid = false;
    }
    if($product == ""){
        $productErr = "Please choose product..";
        $invalid = false;
    }
    if($qty == ""){
        $qtyErr = "Please enter qty ..";
        $invalid = false;
    }
    if($invalid){
        if(isset($_GET['branch_product_id'])){
                    $status = update_branch_product($mysqli,$branch,$product,$qty,$branch_product_id);
                }else{
                    $branch_product = check_prouct_in_branch_exist($mysqli,$product,$branch);
                    // var_dump($branch_product['branch_product_id']);
                    // $branch_product_id = $branch_product['branch_product_id'];
                    if(!isset($branch_product['branch_product_id'])){
                        $status = save_branch_product($mysqli,$branch,$product,$qty);
                    }
                    else
                    {
                        $branch_product_id = $branch_product['branch_product_id'];
                        echo "<script>location.replace('./add_branch_product.php?duplicate&branch_product_id= $branch_product_id')</script>";
                    }
                }

        if ($status) {
            echo "<script>location.replace('./branch_product_list.php')</script>";
        } else {
            $fail_query = $status;
        }
    }
}
?>
<div class="main  w-100 bg-white">
    <div class="main-content w-50 mx-auto">
   <?php  if(isset($_GET['branch_product_id'])){?>
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update Product in Branch</div>
        <?php } else { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add Product to Branch</div>
            <?php } ?>
            <?php if (isset($_GET['duplicate'])) { ?>
                    <div id="autoCloseAlert" class="alert alert-sm mt-3 alert-warning alert-dismissible fade show" role="alert">
                        <strong>Product in Branch already exists. So please update only qty in update section</strong>
                        <button type="button" class="btn-sm btn-close close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                <?php } ?>
        <form method="POST" class="form-group">
            <label for="product" class="form-label mb-1">Product</label>
            <select name="product" id="product" class="form-select form-select-sm">
                <option value="">Select Product</option>
                <?php $products = get_product($mysqli);
                while($product_data = $products->fetch_assoc()){?>
                <option value="<?= $product_data['product_id'] ?>" <?php if($product== $product_data['product_id']) echo "selected"; ?>><?= $product_data['product_name'] ?></option>
                <?php } ?>
            </select>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $productErr ?></i></div>

            <label for="branch" class="form-label mb-1">Branch</label>
            <select name="branch" id="branch" class="form-select form-select-sm">
                <option value="">Select Branch</option>
                <?php $branches = get_branch($mysqli);
                while($branch_data = $branches->fetch_assoc()){?>
                <option value="<?= $branch_data['branch_id'] ?>" <?php if($branch== $branch_data['branch_id']) echo "selected"; ?> ><?= $branch_data['branch_name'] ?></option>
                <?php } ?>
            </select>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $branchErr ?></i></div>
                
            
            <label for="qty" class="form-label mb-1">Qty</label>
            <input type="text" name="qty" value="<?= $qty ?>" id="qty" class="form-control form-control-sm" placeholder="Eneter qty....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $qtyErr ?></i></div>
            
            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
                <?php if(isset($_GET['duplicate']) && isset($_GET['branch_product_id'])) {
                    $returnToUpdateWithBranchProductId = $_GET['branch_product_id'];
                    ?>
                    <a href="./add_branch_product.php?branch_product_id=<?= $returnToUpdateWithBranchProductId ?>" class="btn btn-success">Updete here</a>
                <?php } ?> 
            </div>
        </form>
    </div>
</div>
<script>
    setTimeout(function() {
            var alertBox = document.getElementById('autoCloseAlert');
            if (alertBox) {
                alertBox.classList.remove('show'); 
                setTimeout(function() {
                    alertBox.remove();
                }, 150);
            }
        }, 4000); 
</script>
<?php require_once("../layout/footer.php") ?>