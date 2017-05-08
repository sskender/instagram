<?php


/**
 *  Database connection
 */
define("HOST",   "localhost");
define("USER",   "root");
define("PASS",   "password");
define("DBNAME", "instagram");


/**
 *  Paths
 */
define("SRC_PATH",      "../resources/src/");
define("INCLUDES_PATH", "../resources/includes/");
define("LIB_PATH",      "../resources/lib/");


/**
 *  Regex validation
 */
define("REGEX_SEARCH", "/^[a-zA-Z0-9-_.]{3,20}+$/");
define("REGEX_USER", "/^[a-zA-Z0-9-_.]{5,20}+$/");
define("REGEX_PASS", "/^[a-zA-Z0-9-_.@$#!%&]{6,50}+$/");


/**
 *  Error reporting
 */
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);


?>