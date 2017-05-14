<?php


/**
 *
 */
class Photo {

    public $photo_id;
    public $user_id;
    public $photo_hash;
    public $timestamp;


    function __construct(&$mysql_row) {

        $this->photo_id        = $mysql_row["photo_id"];
        $this->user_id         = $mysql_row["user_id"];
        $this->photo_hash      = $mysql_row["photo_hash"];
        $this->timestamp       = new DateTime($mysql_row["timestamp"]);

    }


}


?>