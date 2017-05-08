<?php


/**
 *  Destroy session,
 *  Redirect to index page
 */

session_start();

unset($_SESSION["user_id"]);
session_destroy();

header("Location: index.php");


?>