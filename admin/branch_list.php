<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php");
 if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    delete_branch($mysqli, $deleteId);
}; 

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
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $branches =  get_branch($mysqli);
                        $i= 1;
                            while($branch = $branches->fetch_assoc()){
                        ?>
                        <tr>
                            <td class="align-content-center"><?= $i ?></td>
                            <td class="align-content-center"> <?= $branch['branch_name'] ?></td>
                            <td class="align-content-center"> <?= $branch['address'] ?></td>
                             <td class="align-content-center">
                                <a href="./add_branch.php?branch_id=<?= $branch['branch_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                <button class="btn btn-sm btn-danger deleteUser" data-value="<?= $branch['branch_id']?>" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php $i++;} ?>
                    </tbody>
                </table>
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