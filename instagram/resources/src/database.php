<?php


/**
 *
 */

function db_connect() {

    static $connection; /* Avoid connecting more than once */

    if (!isset($connection)) {
        $connection = mysqli_connect(HOST,USER,PASS,DBNAME);
    }

    if (!$connection) {
        return mysqli_connect_error();
    }

    return $connection;
}


function db_query(&$query) {
    
    $connection = db_connect();
    $response = mysqli_query($connection, $query);

    return $response;
}


?>