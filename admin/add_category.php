<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$categoryName = $categoryNameErr = "";
$description = $descriptionErr = "";
$invalid = true;
if(isset($_GET['category_id'])){
    $category_id = $_GET['category_id'];
    $categories = get_category_with_id($mysqli,$category_id);
    $categoryName = $categories['category_name'];
    $description = $categories['description'];
}
if(isset($_POST['submit'])){
    $categoryName = $_POST['categoryName'];
    $description = $_POST['description'];

    if($categoryName == ""){
        $categoryNameErr = "Enter category name...";
        $invalid = false;
    }
    if(isset($_GET['category_id'])){
    $uniqueCategoryName = get_category_with_name_and_id($mysqli,$categoryName,$category_id);
    if(isset($uniqueCategoryName['category_name'])){
        if($categoryName == $uniqueCategoryName['category_name'] ){
            $categoryNameErr = "Category name already exist...";
            $invalid = false;
        }
    }
    } else{
        $CategoryName = get_category_with_name($mysqli,$categoryName);
        if(isset($CategoryName['category_name'] )){
            $categoryNameErr = "Category name already exist...";
            $invalid = false;
        }
    }
    if($description == ""){
        $descriptionErr = "Enter description...";
        $invalid = false;
    }

   if($invalid){
    if(isset($_GET['category_id'])){
        $status = update_category($mysqli,$categoryName,$description,$category_id);
        if ($status) {
            echo "<script>location.replace('./category_list.php')</script>";
            } else {
                $fail_query = $status;
            }
       }
    else {
        $status = save_category($mysqli,$categoryName,$description);
        if ($status) {
            echo "<script>location.replace('./category_list.php')</script>";
            } else {
                $fail_query = $status;
            }
       }
    }
}
    

?>
<div class="main  w-100 bg-white">
    <div class="main-content w-50 mx-auto">
   <?php  if(isset($_GET['category_id'])){?>
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update Category</div>
        <?php } else { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add Category</div>
            <?php } ?>
        <form method="POST" class="form-group">
            <label for="categoryName" class="form-label mb-1">Name</label>
            <input type="text" name="categoryName" value="<?= $categoryName ?>" id="categoryName" class="form-control form-control-sm" placeholder="Eneter branch name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $categoryNameErr ?></i></div>

            <label for="description" class="form-label mb-1">Description</label>
            <textarea class="form-control" style="height: 100px; width: 100%;" placeholder="Enter description..." name="description" id="description"><?= $description?></textarea>
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $descriptionErr ?></i></div>
           
            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php require_once("../layout/footer.php"); ?>