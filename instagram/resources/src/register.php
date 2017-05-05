<?php


/*
 *  Register
 *  Set session
 *  Redirect to user's home page
 */
$new_user_id = NULL;
$db_con = @mysqli_connect(HOST,USER,PASS,DBNAME);

$query = "SELECT 
                SUM(CASE WHEN username='$username' THEN 1 ELSE 0 END) count_users,
                SUM(CASE WHEN email='$email'       THEN 1 ELSE 0 END) count_emails
                    FROM users";

$response     = @mysqli_query($db_con, $query);
$data         = @mysqli_fetch_array($response);
$count_users  = $data["count_users"];
$count_emails = $data["count_emails"];

if ($count_users > 0) {
    $register_error = "Username already in use!";

} elseif ($count_emails > 0) {
    $register_error = "E-Mail already in use!";

} else {
    /*
     *  Actual registration
     */

    // insert into table
    $query = "INSERT INTO users (
                username,
                password,
                email
                ) VALUES (
                    '$username',
                    '$hashed_password',
                    '$email'
                    )";
    $response = @mysqli_query($db_con, $query);

    // grab new id
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $response = @mysqli_query($db_con, $query);

    $new_user_id = (integer)@mysqli_fetch_array($response)["user_id"];

    if ($new_user_id != NULL && $new_user_id != false) {
        // registration successfull

        // clean up
        @mysqli_close($db_con);
        
        // redirect
        session_start();
        $_SESSION["user_id"] = $new_user_id;
        header("Location: home.php");
        exit();

    } else {
        $register_error = "Database in maintenance!";
    }
}

@mysqli_close($db_con);


?>