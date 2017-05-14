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
        $hash = generatePhotoName($user_logged_in->user_id); 

        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$file_size[0],$file_size[1]);
        imagedestroy($src);
        imagejpeg($dst,UPLOAD_PHOTO_PATH.$hash);
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























/*

    $upload_result = $user_logged_in->uploadPhoto($_FILES["file-upload"]);

    if ($upload_result) {
        echo "<script>uploadTrue();</script>";
    } else {
        echo "<script>uploadFalse();</script>";
    }
*/




/*
$src = imagecreatefromstring(file_get_contents($fn));
$dst = imagecreatetruecolor($width,$height);
imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
imagedestroy($src);
imagepng($dst,$target_filename_here); // adjust format as needed
imagedestroy($dst);
*/



//header("Location: home.php");





//header("Location: home.php");


/*
if (isset($_FILES["photo"])) {

    print_r($_FILES);
}



$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
*/
?>