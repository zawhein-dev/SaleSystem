<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$productName = $productNameErr = "";
$price = $priceErr = "";
$description = $descriptionErr = "";
$category = $categoryErr = "";
$photo = $photoErr = "";
$invalid = true;
if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $productList = get_product_with_id($mysqli,$product_id);
    $productName = $productList['product_name'];
    $price = $productList['price'];
    $description = $productList['description'];
    $oldPhotoName = $productList['photo'];
    $category = $productList['category_id'];
}
if(isset($_POST['submit'])){
    $productName = test_input($_POST['productName']);
    $price = test_input($_POST['price']);
    $description = test_input($_POST['description']);
    $category = test_input($_POST['category']);

$maxSize = 5 * 1024 * 1024; 
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];
    $photoTmpPath = $file['tmp_name'];
    $photoName = $file['name'];
    $photoSize = $file['size'];
    $photoType = $file['type'];
    $imageInfo = getimagesize($photoTmpPath);
    if ($imageInfo === false) {
        $photoErr = "Error: The file is not a valid image.";
    }
    elseif (!in_array($fileType, $allowedTypes)) {
        $photoErr =  "Error: Only JPEG, PNG, or GIF files are allowed.";
    }
    elseif ($fileSize > $maxSize) {
        $photoErr =  "Error: File size exceeds the 5MB limit.";
    }
} else {
    $photoErr = "No file uploaded.";
}

    if($productName == ""){
        $productNameErr = "Please enter product name..";
        $invalid = false;
    } else
        if (strlen($productName) < 3 || strlen($productName) > 30) {
            $productNameErr = "Product name must be between 3 and 30 characters.";
            $invalid = false;
        }
        elseif (!preg_match('/^[A-Z]/', $productName)) {
            $productNameErr = "Product name must start with a capital letter.";
            $invalid = false;
        }
        elseif (!preg_match('/^[A-Z][a-zA-Z ]{2,29}$/', $productName)) {
            $productNameErr = "Product name can only contain letters and spaces.";
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
    } else 
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
                if (!is_numeric($price)) {
                    $priceErr = "Error: Price must be a numeric value.";
                    $invalid = false;
                } elseif (strpos($price, '.') !== false && preg_match('/\.\d{3,}$/', $price)) {
                    $priceErr = "Error: Price can have at most two decimal places.";
                    $invalid = false;
                } elseif (strpos($price, '.') !== false && preg_match('/\.$/', $price)) {
                    $priceErr = "Error: Price cannot end with a decimal point.";
                    $invalid = false;
                } else {
                    $priceErr = "Error: Invalid price format.";
                    $invalid = false;
                }
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
            if ($_FILES['photo']['name'] == "") {
                $status = update_product($mysqli,$productName,$oldPhotoName,$price,$description,$category,$product_id);
                if ($status) {
                        echo "<script>location.replace('./product_list.php')</script>";
                } else {
                    $fail_query = $status;
                }
            }else{
                $targetDir = '../assets/product/';
                if($oldPhotoName !== "product.png"){
                    $filePath = '../assets/product/' . $oldPhotoName;
                }
                $newFileName = uniqid('img_') . '.' . pathinfo($photoName, PATHINFO_EXTENSION);
                $targetFilePath = $targetDir . $newFileName;
                $status = update_product($mysqli,$productName,$newFileName,$price,$description,$category,$product_id);
                if ($status === true) {
                    if (file_exists($filePath)) {
                        if (unlink($filePath)) {
                            echo "The file 'profile.png' was deleted successfully.";
                        }
                    }
                    if (move_uploaded_file($photoTmpPath, $targetFilePath)) {
                        echo "<script>location.replace('./product_list.php')</script>";
                    }
                } else {
                    $fail_query = $status;
                }
            }
        }else{
            if ($_FILES['photo']['name'] == "") {
                // var_dump("hello");
            $status = save_product($mysqli,$productName,"product.png",$price,$description,$category);
            }else{
                $targetDir = '../assets/product/';
                $newFileName = uniqid('img_') . '.' . pathinfo($photoName, PATHINFO_EXTENSION);  // Generate a unique file name
                $targetFilePath = $targetDir . $newFileName;
                $status = save_product($mysqli,$productName,$newFileName,$price,$description,$category);
               
               if($status){
                   if (move_uploaded_file($photoTmpPath, $targetFilePath)) {
                        echo "<script>location.replace('./product_list.php')</script>";
                    }
               } 
            }
            if ($status) {
                echo "<script>location.replace('./product_list.php')</script>";
                } else {
                    $fail_query = $status;
                }
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
        <form method="POST" class="form-group" enctype="multipart/form-data">
            <label for="productName" class="form-label mb-1">Name</label>
            <input type="text" name="productName" value="<?= $productName ?>" id="productName" class="form-control form-control-sm" placeholder="Eneter product name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $productNameErr ?></i></div>

            <label for="price" class="form-label mb-1">Price</label>
            <input type="text" name="price" value="<?= $price ?>" id="price" class="form-control form-control-sm" placeholder="Eneter product price....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $priceErr ?></i></div>
            
            <label for="photo" class="form-label  mb-1">Photo</label>
            <input type="file" name="photo" id="photo" class="form-control form-control-sm">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $photoErr ?></i></div>
            
            <label for="category" class="form-label mb-1 mt-3">Category</label>
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