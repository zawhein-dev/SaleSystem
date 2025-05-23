<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php");

if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $selectPhoto = get_product_with_id($mysqli, $deleteId);
    $path = $selectPhoto['photo'];
    if ($path !== 'product.png')
        $filePath = '../assets/product/' . $path;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            delete_product($mysqli, $deleteId);
            // echo "<script>location.replace('./product_list.php')</script>";
        }
    } else {
        delete_product($mysqli, $deleteId);
        // echo "<script>location.replace('./product_list.php')</script>";
    }
}
$limit = 3;
$page = isset($_GET['pageNo']) ? intval($_GET['pageNo']) : 1;
$offset = ($page - 1) * $limit;
$numberTitle = ($page * $limit) - $limit;
    if (isset($_GET['search_data'])) {
            $searchData = $_GET['search_data'];
            $row =  get_product_with_search_data($mysqli, $searchData);
            $row_count = COUNT($row->fetch_all());
            $pagination_link = ceil($row_count / 3); 
            $products = get_product_with_offset($mysqli, $offset, $limit,$searchData);
    }else{
        $row = get_product($mysqli);
        $row_count  = COUNT($row->fetch_all()); //get number of users
        $pagination_link = ceil($row_count / 3);
        $products = get_search_product_with_offset($mysqli, $offset, $limit);
    }

?>
<?php require_once("../storage/branch_product_crud.php");
?>
<div class="main bg-white">
    <div class="content w-100">
            <?php if(isset($_SESSION['error_message'])){ ?>
                    <div class="alert alert-warning alert-dismissible fade mx-auto show w-75 mt-2" role="alert">
                        <strong><?= $_SESSION['error_message'] ?></strong>
                        <!-- <form method="post"> -->
                        <button type="button" onclick="destroySessionVariable()" name="unsetSession" class=" btn-close close unsetSession" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                        <!-- </form> -->
                    </div>
                    <?php } ?>
        <div class="card w-75 mt-2 mx-auto">
            <div class="card-title fs-3 text-center">Product List</div>
            <div class="card-body">
                <table class="table table-striped w-100 mx-auto">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // $products = get_product($mysqli);
                        // $i = 1;
                        while ($product = $products->fetch_assoc()) {
                            ?>
                            <tr>
                                <td class="align-content-center"><?= $numberTitle + 1 ?></td>
                                <td class="align-content-center"> <?= $product['product_name'] ?></td>
                                <td class="align-content-center"> <?= $product['price'] ?></td>
                                <td class="align-content-center">
                                    <img src="../assets/product/<?= $product['photo'] ?>" alt="productPhoto"
                                        style="width: 80px; height: 80px; border-radius: 70px;">
                                </td>
                                <td class="align-content-center"> <?= $product['description'] ?></td>
                                <td class="align-content-center"> <?= $product['category_name'] ?></td>
                                <td class="align-content-center">
                                    <a href="./add_product.php?product_id=<?= $product['product_id'] ?>"
                                        class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                    <button class="btn btn-sm btn-danger deleteUser"
                                        data-value="<?= $product['product_id'] ?>" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php 
                            // $i++;
                            $numberTitle++;
                        } ?>
                    </tbody>
                </table>
                <?php if (!($row_count <= $limit)) { ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) echo 'disabled' ?>">
                                <a class="page-link" href="?pageNo=<?= $page - 1; ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>">Previous</a>
                            </li>
                            <?php $j = 1;
                            while ($pagination_link >= $j) { ?>
                                <li class="page-item">
                                    <a class="page-link <?php if ($page == $j) echo 'active' ?>" href="?pageNo=<?= $j ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>"><?php echo $j; ?></a>
                                </li>
                            <?php $j++;
                            } ?>
                            <li class="page-item <?php if ($pagination_link == $page) echo 'disabled' ?>">
                                <a class="page-link"" href=" ?pageNo=<?= $page + 1; ?>&search_data=<?= isset($searchData) ? urlencode($searchData) : ''; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>