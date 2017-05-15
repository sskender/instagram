<script src="js/upload.msg.js"></script>

<?php

/**
 *  Check if session is active (user logged in)
 */
require_once("../config.php");
require(LIB_PATH."session.manager.php");


/**
 *  Validate photo extension
 */
function checkExtension(&$file_info) {

    $img_exts = array("jpg","jpe","jpeg","gif","png");
    $file_ext = strtolower($file_info["extension"]);

    return in_array($file_ext, $img_exts);
}


/**
 *  Create new photo name (hash)
 */
function generatePhotoName($user_id) {
    return (string)$user_id."_".uniqid("",true).uniqid("",true).".jpg";
}


/**
 *  Resize image and then save with new hash
 */
function resizeImage(&$file_name) {
    global $user_logged_in;
    try {
        $file_size = getimagesize($file_name);
        $ratio = $file_size[0] / $file_size[1];

        if ($ratio > 1) {
            $width  = 1000;
            $height = 1000 / $ratio;
        } else {
            $width = 1000 * $ratio;
            $height = 1000;
        }

        $src  = imagecreatefromstring(file_get_contents($file_name));
        $dst  = imagecreatetruecolor($width,$height);

        do {
            $hash = generatePhotoName($user_logged_in->user_id); 
        } while (file_exists(UPLOAD_PATH.$hash));

        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$file_size[0],$file_size[1]);
        imagedestroy($src);
        imagejpeg($dst,UPLOAD_PATH.$hash);
        imagedestroy($dst);

        return $hash;
    }
    catch (Exception $e) {
        return false;
    }

}



/**
 *  File posted
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /**
     *  By default image not accepted by server
     */
    $result = false;

    /**
     *  Photo upload is set
     */
    if (isset($_FILES["file-upload"])) {
        $file_name = $_FILES["file-upload"]["tmp_name"];
        $file_info = pathinfo($_FILES["file-upload"]["name"]);
        
        if (file_exists($file_name) && checkExtension($file_info)) {
            $file_size = getimagesize($file_name);

            if ($file_size !== false) {
                /**
                 *  Image and extension OK
                 *  Resize and move image to user path
                 *  Save image into SQL
                 */
                $hash = resizeImage($file_name);

                if ($hash) {
                    if ($user_logged_in->uploadPhoto($hash)) {
                        $result = true;
                    }
                }
            }

        }
        
    }


    /**
     *  Profile photo upload is set
     */
    if (isset($_FILES["profile-upload"])) {
        $file_name = $_FILES["profile-upload"]["tmp_name"];
        $file_info = pathinfo($_FILES["profile-upload"]["name"]);
        
        if (file_exists($file_name) && checkExtension($file_info)) {
            $file_size = getimagesize($file_name);

            if ($file_size !== false) {
                /**
                 *  Image and extension OK
                 *  Resize and move image to user path
                 *  Save image into SQL
                 */
                $hash = resizeImage($file_name);

                if ($hash) {
                    if ($user_logged_in->updateProfilePhoto($hash)) {
                        $result = true;
                    }
                }
            }

        }

    }


    /**
     *  Validate upload and redirect
     */
    if ($result) {
        echo "<script>uploadTrue();</script>";
    } else {
        echo "<script>uploadFalse();</script>";
    }


/**
 *  Nothing posted
 *  Just redirect
 */
} else {
    header("Location: home.php");
}


?>