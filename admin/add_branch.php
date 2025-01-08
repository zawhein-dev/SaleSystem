<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php") ?>
<?php
$branchName = $branchNameErr = "";
$address = $addressErr = "";
$invalid = true;
if(isset($_GET['branch_id'])){
    $branch_id = $_GET['branch_id'];
    $branches = get_branch_with_id($mysqli,$branch_id);
    $branchName = $branches['branch_name'];
    $address = $branches['address'];
}
if(isset($_POST['submit'])){
    $address = test_input($_POST['address']);
    $branchName = test_input($_POST['branchName']);
    
    if($branchName == ""){
        $branchNameErr = "Please enter branch name..";
        $invalid = false;
    } else
        if (strlen($branchName) < 3 || strlen($branchName) > 30) {
            $branchNameErr = "Branch name must be between 3 and 30 characters.";
            $invalid = false;
        }
        elseif (!preg_match('/^[A-Z]/', $branchName)) {
            $branchNameErr = "Branch name must start with a capital letter.";
            $invalid = false;
        }
        elseif (!preg_match('/^[A-Z][a-zA-Z ]{2,29}$/', $branchName)) {
            $branchNameErr = "Branch name can only contain letters and spaces.";
            $invalid = false;
        } 

    if($address == ""){
        $addressErr = "Please enter address..";
        $invalid = false;
    } else
    if (strlen($address) < 5 || strlen($address) > 50) {
        $addressErr = "Address must be between 5 and 100 characters.";
        $invalid = false;
    } else 
    if (!preg_match('/^[a-zA-Z0-9 ,.\-\/]+$/', $address)) {
        $addressErr = "Address can only contain letters, numbers, spaces, commas, periods, hyphens, and slashes.";
        $invalid = false;
    } else
    if (!preg_match('/[a-zA-Z]/', $address)) {
        $addressErr = "Address must contain letter.";
        $invalid = false;
    }
    if($invalid){
        if(isset($_GET['branch_id'])){
            $status = update_branch($mysqli,$branchName,$address,$branch_id);
            if($status){
                echo "<script>location.replace('./branch_list.php')</script>";
            }else {
                $fail_query = $status;
            }
        }else{
            $status = save_branch($mysqli,$branchName,$address);
            if ($status === true) {
                echo "<script>location.replace('./branch_list.php')</script>";
        } else {
            $fail_query = $status;
        }
        }
    }
}
?>
<div class="main  w-100 bg-white">
    <div class="main-content w-50 mx-auto">
   <?php  if(isset($_GET['branch_id'])){?>
        <div class=" mx-auto mb-0 fs-4 my-1 text-center">Update Branch</div>
        <?php } else { ?>
            <div class=" mx-auto mb-0 fs-4 my-1 text-center">Add Branch</div>
            <?php } ?>
        <form method="POST" class="form-group">
            <label for="branchName" class="form-label mb-1">Name</label>
            <input type="text" name="branchName" value="<?= $branchName ?>" id="branchName" class="form-control form-control-sm" placeholder="Eneter branch name....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $branchNameErr ?></i></div>

            <label for="address" class="form-label mb-1">Address</label>
            <input type="text" name="address" value="<?= $address ?>" id="address" class="form-control form-control-sm" placeholder="Eneter branch address....">
            <div style="height: 13px; line-height: 13px; font-size: 14px; margin-top: 3px;"><i class="text-danger text-sm-start"><?= $addressErr ?></i></div>
           
            <div class="form-input-group-sm text-center">
                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>