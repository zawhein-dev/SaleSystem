<?php require_once("../layout/header.php");
if (isset($_COOKIE['user'])) {

$userData = json_decode($_COOKIE['user'], associative: true);

if (isset($userData['user_id'])) {
    $user_id = $userData['user_id'];
    $currentUser =   get_user_with_id($mysqli, $user_id);
    $current_user_id = $currentUser['user_id'];
}
}
?>
<?php require_once("../layout/navbar.php");
?>
</div></div>

<div class="content mt-4 mx-auto w-75">
    <div class="card w-90 mt-2 mx-1">
        <div class="card-title fs-3 text-center">User Order Detail</div>
        <div class="card-body">
            <table class="table table-striped  w-100 mx-auto">
                <thead>
                    <tr>  
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($_GET['user_id'])){
                        $user_id = $_GET['user_id'];
                      $users = get_user_with_id($mysqli, $user_id);
                    
                    ?>
                    <tr>
                       <td><?= $users['user_name'] ?></td>
                       <td><?= $users['user_email'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once("../layout/footer.php") ?>