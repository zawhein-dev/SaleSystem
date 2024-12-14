<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$productName = $productNameErr = "";
$price = $priceErr = "";
$description = $descriptionErr = "";
$category = $categoryErr = "";
$invalid = true;
if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $productList = get_product_with_id($mysqli,$product_id);
    $productName = $productList['product_name'];
    $price = $productList['price'];
    $description = $productList['description'];
    $category = $productList['category_id'];
}
if(isset($_POST['submit'])){
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    if($productName == ""){
        $productNameErr = "Please enter product name..";
        $invalid = false;
    }
    if(isset($_GET['product_id'])){
        $uniqueProductName = get_product_with_name_and_id($mysqli,$productName,$product_id);
       if(isset($uniqueProductName['product_name'])){
           if($productName == $uniqueProductName['product_name'] ){
               $productNameErr = "Product name already exist...";
               $invalid = false;
           }
       }
    } else{
        $ProductName = get_product_with_name($mysqli,$productName);
        if(isset($ProductName['product_name'] )){
            $productNameErr = "Product name already exist...";
            $invalid = false;
        }
    }
    if($price == ""){
        $priceErr = "Please enter product price..";
        $invalid = false;
    }
    if($description == ""){
        $descriptionErr = "Please enter description....";
        $invalid = false;
    }
    if($category == ""){
        $categoryErr = "Please choose category name....";
        $invalid = false;
    }
    if($invalid){
        if(isset($_GET['product_id'])){
            $status = update_product($mysqli,$productName,$price,$description,$category,$product_id);
        }else{
            $status = save_product($mysqli,$productName,$price,$description,$category);
        }
            if ($status) {
             echo "<script>location.replace('./product_list.php')</script>";
             } else {
                 $fail_query = $status;
             }
        
    }
}
?>
<div class="main  bg-white w-100">
    <div class="main-content w-50 mx-auto">
   <?php  if(isset($_GET['product_id'])){?>
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update Product</div>
        <?php } else { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add Product</div>
            <?php } ?>
        <form method="POST" class="form-group">
            <label for="productName" class="form-label mb-1">Name</label>
            <input type="text" name="productName" value="<?= $productName ?>" id="productName" class="form-control form-control-sm" placeholder="Eneter product name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $productNameErr ?></i></div>

            <label for="price" class="form-label mb-1">Price</label>
            <input type="text" name="price" value="<?= $price ?>" id="price" class="form-control form-control-sm" placeholder="Eneter product price....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $priceErr ?></i></div>

            <label for="category" class="form-label mb-1">Category</label>
            <select name="category" id="category" class="form-select form-select-sm">
                <option value="">Select Category</option>
                <?php $categories = get_category($mysqli);
                while($categoryList = $categories->fetch_assoc()){?>
                <option value="<?= $categoryList['category_id'] ?>" <?php if($category == $categoryList['category_id']) echo "selected"; ?>><?= $categoryList['category_name'] ?></option>
                <?php } ?>
            </select>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $categoryErr ?></i></div>

            <label for="description" class="form-label mb-1">Description</label>
            <textarea class="form-control" style="height: 100px; width: 100%;" placeholder="Enter product description..." name="description" id="description"><?= $description?></textarea>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $descriptionErr ?></i></div>
            
            
            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>