<?php


/**
 *  Redirect to index page if user is not logged in
 */

session_start();

if (!isset($_SESSION["user_id"])) {
    
    session_destroy();
    header("Location: index.php");
    exit();

} else {

    require_once(SRC_PATH."database.php");
    require_once(SRC_PATH."photo.class.php");
    require_once(SRC_PATH."user.class.php");
    
    $user_logged_in = new User($_SESSION["user_id"]);

}


?>