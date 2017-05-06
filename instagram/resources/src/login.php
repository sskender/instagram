<?php


/*
 *
 */

require_once("database.php");

function login(&$username,&$hashed_password) {

    $query  = "SELECT user_id FROM users WHERE (
                    (email='$username' OR username='$username') AND password='$hashed_password'
                )";
    $response = db_query($query);

    if ($response) {

        if (mysqli_num_rows($response) == 1) {
            $user_id = mysqli_fetch_array($response)["user_id"];
            return (integer)$user_id;
        }
    }

    return false;
}


?>