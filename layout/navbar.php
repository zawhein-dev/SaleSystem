<!-- #region -->
<?php
if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser =   get_user_with_id($mysqli, $user_id);
       
    }
}
?>
<div class="container_fluid bg-white">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sale and Stock Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            User
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_user.php">Add user</a></li>
                            <li><a class="dropdown-item" href="../admin/user_list.php">User List</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Category
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_category.php">Add Category</a></li>
                            <li><a class="dropdown-item" href="../admin/category_list.php">Category List</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_product.php">Add Product</a></li>
                            <li><a class="dropdown-item" href="../admin/product_list.php">Product List</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Branch
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_branch.php">Add Branch</a></li>
                            <li><a class="dropdown-item" href="../admin/branch_list.php">Branch List</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Branch_Product
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../admin/add_branch_product.php">Add Product in Branch</a></li>
                            <li><a class="dropdown-item" href="../admin/branch_product_list.php">Product in Branch List</a></li>
                        </ul>
                    </li>
                </ul>
                <form method="post">
                <div class="dropdown">
                    <a class="navbar-brand dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../assets/userProfile/<?= $currentUser['profile'] ?>" style="width: 60px; height: 60px; border-radius: 50%;" id="profileImage" alt="Image" class="ms-2">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="../user/profile.php">Profile</a></li>
                        <li>
                            <button class="dropdown-item btn"  type="submit" name="logout">Logout</button>
                           
                        </li>
                    </ul>
                </div>
                </form>
            </div>
        </div>
    </nav>

</div>
<?php
if (isset($_POST['logout'])) {
    setcookie("user", '', -1, "/");
    header('Location:../index.php');
}
?>