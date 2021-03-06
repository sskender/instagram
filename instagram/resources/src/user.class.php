<?php


/**
 *
 */
class User {

    public $user_id;
    public $username;
    public $hashed_password;
    public $email;

    public $profile_photo_hash;

    public $uploaded_photos_number;


    public $followers_number;   /* Users following this profile. */
    public $followers_list;

    public $following_number;   /* Other users who this profile follows. */
    public $following_list;


    public $last_login_time;
    public $last_login_ip;



    /**
     *  Get user's attributes
     */
    function __construct($identifier) {

        $query = "SELECT * FROM users WHERE username='$identifier' OR user_id='$identifier' LIMIT 1";
        $response = db_query($query);

        if ($response) {

            if (mysqli_num_rows($response) > 0) {
                $data = mysqli_fetch_array($response);

                $this->user_id          = $data["user_id"];
                $this->username         = $data["username"];
                $this->hashed_password  = $data["password"];
                $this->email            = $data["email"];
            }

        }

        if ($this->user_id != NULL) {

            $this->getProfilePhotoHash();
            $this->getNumberOfUploads();
            $this->getFollowers();
            $this->getFollowing();
            $this->getLastLoginInfo();

        }

    }



    private function getFollowers() {
        /**
         *  Users following this user
         */

        $this->followers_list = array();

        $query = "SELECT id_from FROM follows WHERE id_to=$this->user_id";
        $response = db_query($query);

        if ($response) {
            while ($row = mysqli_fetch_array($response)) {
                array_push($this->followers_list, (integer)$row[0]);
            }
        } else {

        }

        $this->followers_number = count($this->followers_list);

    }

    private function getFollowing() {
        /**
         *  Users this user follows
         */
        
        $this->following_list = array();

        $query = "SELECT id_to FROM follows WHERE id_from=$this->user_id";
        $response = db_query($query);
        
        if ($response) {
            while ($row = mysqli_fetch_array($response)) {
                array_push($this->following_list, (integer)$row[0]);
            }
        } else {

        }

        $this->following_number = count($this->following_list);
    }


    private function getNumberOfUploads() {

        $query = "SELECT COUNT(photo_id) AS total FROM uploads WHERE user_id=$this->user_id";
        $response = db_query($query);

        if ($response) {
            $this->uploaded_photos_number = (integer)mysqli_fetch_array($response)["total"];

        } else {
            $this->uploaded_photos_number = 0;
        }
    }


    private function getProfilePhotoHash() {

        $query = "SELECT photo_hash FROM photos WHERE user_id=$this->user_id";
        $response = db_query($query);

        if ($response) {

            if (mysqli_num_rows($response) > 0) {
                $this->profile_photo_hash = mysqli_fetch_array($response)["photo_hash"];
            }
        } else {

        }
    }


    private function getLastLoginInfo() {
        /**
         *  Grab informations about last login:
         *      - Time
         *      - IP address
         */
        
        $query = "SELECT * FROM logs WHERE user_id=$this->user_id ORDER BY log_id DESC LIMIT 1";
        $response = db_query($query);

        if ($response) {

            $data = mysqli_fetch_array($response);
            $this->last_login_ip   = $data["ip"];
            $this->last_login_time = new DateTime($data["timestamp"]);

        } else {
            $this->last_login_ip   = $_SERVER["REMOTE_ADDR"];
            $this->last_login_time = new DateTime();
        }
    }



    /**
     *  Update profile informations
     */
    public function updateEmail($new_email) {

        $query = "UPDATE users SET email='$new_email' WHERE user_id=$this->user_id";
        $response = db_query($query);

        if ($response) {
            $this->email = $new_email;
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword($new_hashed_password) {

        $query = "UPDATE users SET password='$new_hashed_password' WHERE user_id=$this->user_id";
        $response = db_query($query);

        if ($response) {
            $this->hashed_password = $new_hashed_password;
            return true;
        } else {
            return false;
        }
    }

    public function updateProfilePhoto($hash) {

        $query = "SELECT photo_id FROM photos WHERE user_id=$this->user_id";
        $response = db_query($query);

        if ($response) {

            if (mysqli_num_rows($response) > 0) {
                /**
                 *  Entry exists, update row
                 */
                $query = "UPDATE photos SET photo_hash='$hash' WHERE user_id=$this->user_id";
                $response = db_query($query);

                if ($response) {

                    $this->profile_photo_hash = $hash;
                    return true;
                }

            } else {
                /**
                 *  Entry does not exist, insert new row
                 */
                $query = "INSERT INTO photos (user_id,photo_hash) VALUES ($this->user_id, '$hash')";
                $response = db_query($query);

                if ($response) {

                    $this->profile_photo_hash = $hash;
                    return true;
                }
            }

        }

        return false;
    }



    /**
     *  Follow related stuff
     */
    public function startFollowing($other_user) {

        if ($other_user->user_id == NULL) { return false; }

        $query = "INSERT INTO follows (id_from, id_to) VALUES ($this->user_id,$other_user->user_id)";
        $response = db_query($query);

        if ($response) {

            $this->following_number++;
            array_push($this->following_list, $other_user->user_id);

            return true;

        } else {
            return false;
        }
    }

    public function stopFollowing($other_user) {

        if ($other_user->user_id == NULL) { return false; }

        $query = "DELETE FROM follows WHERE (id_from=$this->user_id AND id_to=$other_user->user_id)";
        $response = db_query($query);

        if ($response) {

            $position = array_search($other_user->user_id, $this->following_list);
            if ($position !== false) {
                unset($this->following_list[$position]);
                $this->following_number--;
            }

            return true;

        } else {
            return false;
        }
    }


    public function getFollowButtonValue($other_user) {
        /**
        *   Return codes:
        *       -1 ... database error
        *        0 ... stop following
        *        1 ... start following
        */

        if ($other_user->user_id == NULL) { return -1; }

        $query = "SELECT follow_id FROM follows WHERE (id_from=$this->user_id AND id_to=$other_user->user_id)";
        $response = db_query($query);

        if ($response) {

            if (mysqli_num_rows($response) > 0) {
                /**
                 *  Row exists
                 *  User is following this user
                 *  Only option to stop following is enabled
                 */
                 return 0;
            } else {
                /**
                 *  Row does not exist
                 *  User is not following this user
                 *  Only option to start following is enabled
                 */
                 return 1;
            }

        } else {
            /**
             *  Database error occurred
             */
            return -1;
        }
    }



    /**
     *  Upload related stuff
     */
    public function uploadPhoto($hash) {
        $query = "INSERT INTO uploads (
                        user_id, 
                        photo_hash
                            ) VALUES (
                                $this->user_id,
                                '$hash'
                                    )";
        $response = db_query($query);

        if ($response) {

            $this->uploaded_photos_number++;
            return true;
        }

        return false;

    }



    /**
     *  
     */
    public function dumpHomePosts() {

        $followingIDs = (string)$this->user_id;
        if (!empty($this->following_list)) {
            $followingIDs = $followingIDs . "," . implode(",", $this->following_list);
        }
        $query = "SELECT * FROM uploads WHERE user_id IN ($followingIDs) ORDER BY photo_id DESC";
        $response = db_query($query);

        if ($response) {

            while ($row = mysqli_fetch_array($response)) {
                yield $row;
            }

        } else {

        }
    }



}


?>
