<?php require_once("./storage/db.php"); ?>
<?php require_once("./storage/user_crud.php");
require_once("./storage/branch_crud.php");
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
    }
}
// if (isset($_COOKIE['user'])) {
//     header("location:./home.php");
// }
?>
<?php if (have_admin($mysqli)) {
        $hashedPassword = password_hash("123456", PASSWORD_DEFAULT);
        //  $newFileName = uniqid('img_') . '.' . pathinfo("admin.jpg", PATHINFO_EXTENSION); 
        $status = save_user($mysqli, 'admin', 'admin@gmail.com', $hashedPassword, "userLogo.jpg", 1);
        if ($status === true) {
        } else {
            $fail_query = $status;
        }
    } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaleSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
</head>
<body>
    
    <?php 
$branches = get_branch($mysqli);
if ($branches->num_rows > 0): ?>
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php 
            $index = 0;
            while ($branch = $branches->fetch_assoc()): ?>
                <button type="button"  data-bs-target="#carouselExample" data-bs-slide-to="<?php echo $index; ?>" 
                    class="<?php echo $index === 0 ? 'active' : ''; ?>"  
                    aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                    aria-label="Slide <?php echo $index + 1; ?>"></button>
                <?php 
                $index++;
            endwhile; 
            ?>
        </div>
        <!-- Slides -->
        <div class="carousel-inner">
            <?php 
            $branches->data_seek(0); // Reset the pointer to loop through results again
            $index = 0; 
            while ($branch = $branches->fetch_assoc()): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <a href="./select_shop.php?branch_id=<?= $branch['branch_id'] ?>">
                    <img src="./assets/userProfile/shophori.jpg" 
                         style="width: 800px; height: 600px;" 
                         class="d-block w-100" 
                         alt="<?php echo htmlspecialchars($branch['branch_name'] ?? 'Image'); ?>">
                         </a>
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-white"><?php echo htmlspecialchars($branch['branch_name'] ?? 'Branch'); ?></h5>
                        <p class="text-white"><?php echo htmlspecialchars($branch['address'] ?? 'No description available.'); ?></p>
                    </div>
                </div>
                <?php 
                $index++;
            endwhile; 
            ?>
        </div>
        <!-- Controls -->
        <button class="carousel-control-prev text-light" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next text-light" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
<?php 
endif; 
?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
</html>