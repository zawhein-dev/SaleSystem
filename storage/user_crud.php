<?php
function save_user($mysqli, $userName, $userEmail, $password, $profile, $role = 3): mixed
{
    try {

        $sql = "INSERT INTO `user` (`user_name`, `user_email`, `password`, `profile`, `role`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $mysqli->error);
        }

        $stmt->bind_param("ssssi", $userName, $userEmail, $password, $profile, $role);

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query: " . $stmt->error);
        }

        return $stmt->insert_id;

    } catch (Exception $e) {

        error_log("Error in save_user: " . $e->getMessage());

        return $e->getMessage();

    }
}
// function save_user($mysqli, $userName, $userEmail, $password, $profile, $role = 3): mixed
// {
//     try {
//         $sql = "INSERT INTO `user`(`user_name`,`user_email`,`password`,`profile`,`role`) 
// VALUES('$userName','$userEmail','$password','$profile',$role)";
//         return $mysqli->query($sql);
//     } catch (Exception $e) { 
//             return $e->getMessage();

//     }
// }


// function save_user($mysqli, $userName, $userEmail, $password, $profile, $role = 3): mixed
// {
//     try {
//         $sql = "INSERT INTO `user`(`user_name`,`user_email`,`password`,`profile`,`role`) 
// VALUES('$userName','$userEmail','$password','$profile',$role)";

//         return $mysqli->query($sql);
//     } catch (\Throwable $th) { {
//             return "Internal server error!";
//         }
//     }
// }

function update_user($mysqli, $userName, $userEmail, $password, $profile, $role, $user_id)
{
    try {
        $sql = "UPDATE `user` 
            SET `user_name` = '$userName',
            `user_email` = '$userEmail',
            `password` = '$password',
            `profile` = '$profile',
            `role` = '$role'
            WHERE `user_id` = $user_id ";
        return $mysqli->query($sql);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function have_admin($mysqli)
{
    $sql = "SELECT COUNT(user_id) as total FROM `user` WHERE `role` = 1";
    $total = $mysqli->query($sql);
    $total = $total->fetch_assoc();
    if ($total['total'] > 0) {
        return false;
    }
    return true;
}
function get_user_with_email($mysqli, $userEmail)
{
    $sql = "SELECT * FROM `user` WHERE `user_email` = '$userEmail' ";
    $user = $mysqli->query($sql);
    return $user->fetch_assoc();
}
function get_user($mysqli)
{
    $sql = "SELECT * FROM `user`";
    // $user = $mysqli->query($sql);
    // return $user->fetch_assoc();
    return $mysqli->query($sql);
}
function get_user_with_search_data($mysqli, $search)
{
    $sql = "SELECT * FROM `user` WHERE `user_name` LIKE '%$search%'";
    return $mysqli->query($sql);
}
function get_user_with_offset($mysqli, $offset, $limit, $search)
{
    $sql = "SELECT * FROM `user`  WHERE `user_name` LIKE '%$search%' LIMIT $limit OFFSET $offset";
    return $mysqli->query($sql);
}
function get_search_user_with_offset($mysqli, $offset, $limit)
{
    $sql = "SELECT * FROM user LIMIT $limit OFFSET $offset";
    return $mysqli->query($sql);
}
function get_user_with_id($mysqli, $user_id)
{
    $sql = "SELECT * FROM `user` WHERE `user_id` = $user_id";
    $user = $mysqli->query($sql);
    return $user->fetch_assoc();
}
function delete_user($mysqli, $deleteId)
{
    try {
        $sql = "DELETE FROM `user` WHERE `user_id` = $deleteId";
        return $mysqli->query($sql);
    } catch (Exception $e) {
        return "Internal server error";
    }
}
