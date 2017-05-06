<?php


/*
 *
 */

require_once("database.php");

function fetchNewUserID(&$username) {

    $query = "SELECT user_id FROM users WHERE username='$username'";
    $response = db_query($query);

    if ($response) {

        if (mysqli_num_rows($response) > 0) {
            $new_user_id = (integer)mysqli_fetch_array($response)["user_id"];
            return $new_user_id;
        }
    
    }

    return -1;
}


function register(&$username,&$email,&$hashed_password) {

    /*
     *  Return codes:
     *      -1 ... database error
     *      -2 ... username exists
     *      -3 ... email exists
     *       x ... any number that stands for user id
     */

    $query = "SELECT 
                    SUM(CASE WHEN username='$username' THEN 1 ELSE 0 END) count_users,
                    SUM(CASE WHEN email='$email'       THEN 1 ELSE 0 END) count_emails
                        FROM users";
    $response = db_query($query);

    if ($response) {

        $data         = mysqli_fetch_array($response);
        $count_users  = $data["count_users"];
        $count_emails = $data["count_emails"];

        if ($count_users > 0) {
            return -2;
        }
        if ($count_emails > 0) {
            return -3;
        }

        $query = "INSERT INTO users (
                        username,
                        password,
                        email
                        ) VALUES (
                            '$username',
                            '$hashed_password',
                            '$email'
                            )";
        $response = db_query($query);

        if ($response) {
            return fetchNewUserID($username);
        }

    }
    
    return -1;

}


?>