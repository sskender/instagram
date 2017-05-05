<?php


/*
 *  Login
 *  Set session
 *  Redirect to user's home page
 */
$db_con = @mysqli_connect(HOST,USER,PASS,DBNAME);
$query  = "SELECT user_id FROM users WHERE (
            (email='$username' OR username='$username') AND password='$hashed_password'
            )";

$response = @mysqli_query($db_con, $query);
@mysqli_close($db_con);

if ($response) {

    if (mysqli_num_rows($response) == 1) {
        // success
        // redirect
        session_start();
        $_SESSION["user_id"] = mysqli_fetch_array($response)["user_id"];
        header("Location: home.php");
        exit();

    } else {
        // invalid credentials
        $login_error = "Wrong user or password!";
    }

} else {
    // sql error
    $login_error = "Database in maintenance!";
}


?>