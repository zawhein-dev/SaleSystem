<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php");
 if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    delete_category($mysqli, $deleteId);
}; 

$limit = 3;
$page = isset($_GET['pageNo']) ? intval($_GET['pageNo']) : 1;
$offset = ($page - 1) * $limit;
$numberTitle = ($page * $limit) - $limit;
    if (isset($_GET['search_data'])) {
            $searchData = $_GET['search_data'];
            $row =  get_category_with_search_data($mysqli, $searchData);
            $row_count = COUNT($row->fetch_all());
            $pagination_link = ceil($row_count / 3); 
            $categories = get_category_with_offset($mysqli, $offset, $limit,$searchData);
    }else{
        $row = get_category($mysqli);
        $row_count  = COUNT($row->fetch_all()); //get number of users
        $pagination_link = ceil($row_count / 3);
        $categories = get_search_category_with_offset($mysqli, $offset, $limit);
    }
?>

<div class="main bg-white">
    <div class="content w-100">
        <div class="card w-60 mt-2 mx-1">
            <div class="card-title fs-3 text-center">Branch List</div>
            <div class="card-body">
                <table class="table table-striped  w-50 mx-auto">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // $categories =  get_category($mysqli);
                        // $i= 1;
                            while($category = $categories->fetch_assoc()){
                        ?>
                        <tr>
                            <td class="align-content-center"><?= $numberTitle + 1 ?></td>
                            <td class="align-content-center"> <?= $category['category_name'] ?></td>
                            <td class="align-content-center"> <?= $category['description'] ?></td>
                             <td class="align-content-center">
                                <a href="./add_category.php?category_id=<?= $category['category_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                <button class="btn btn-sm btn-danger deleteUser" data-value="<?= $category['category_id']?>" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php 
                        // $i++;
                        $numberTitle++;
                    } ?>
                    </tbody>
                </table><?php if (!($row_count <= $limit)) { ?>
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

<div class="modal modal-sm" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation Message</h5>
                <button type="button" class="btn-close btn-sm" id="closeBtn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete..</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" id="deleteBtn">OK</button>
            </div>
        </div>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>