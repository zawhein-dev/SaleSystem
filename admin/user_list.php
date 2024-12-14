<?php require_once("../layout/header.php") ?>
<?php require_once("../layout/navbar.php");
 if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    delete_user($mysqli, $deleteId);
};?>
<div class="main bg-white">
    <div class="content w-100">
        <div class="card w-90 mt-2 mx-1">
            <div class="card-title fs-3 text-center">User List</div>
            <div class="card-body">
                <table class="table table-striped  w-100 mx-auto">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Profile Picture</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $users =  get_user($mysqli);
                        $i= 1;
                            while($user = $users->fetch_assoc()){
                        ?>
                        <tr>
                            <td class="align-content-center"><?= $i ?></td>
                            <td class="align-content-center"> <?= $user['user_name'] ?></td>
                            <td class="align-content-center"> <?= $user['user_email'] ?></td>
                            <td class="align-content-center">
                                                                 <?php
                                                                    switch ($user['role']) {
                                                                        case "1":
                                                                            echo "admin";
                                                                            break;
                                                                        case "2":
                                                                            echo "staff";
                                                                            break;
                                                                        case "3":
                                                                            echo "customer";
                                                                            break;
                                                                        default:
                                                                            break;
                                                                    } ?></td>
                            <td class="align-content-center"><img src="../assets/userProfile/<?= $user['profile'] ?>" alt="user" style="width: 80px; height: 80px; border-radius: 70px;"></td>
                            <td class="align-content-center">
                                <a href="./add_user.php?user_id=<?= $user['user_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                <a href="./change_password.php?user_id=<?= $user['user_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                <button class="btn btn-sm btn-danger deleteUser" data-value="<?= $user['user_id']?>" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
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