<?php

require_once("../config.php");
require(INCLUDES_PATH."header.php");

?>


<?php

/**
 *  Validate user in URL
 *  If user is invalid, redirect to profile page
 */
if (!empty($_GET["user"])) {

    $redirect_page = true;

    if (preg_match(REGEX_USER, $_GET["user"])) {
        $user_current_profile = new User($_GET["user"]);
        if ($user_current_profile->user_id != NULL) {
            $redirect_page = false;
        }
    }

    if ($redirect_page) {
        header("Location: profile.php");
    }

} else {
    $user_current_profile = &$user_logged_in;
}

?>

    
        <section class="profile-container">
            <center>
            <div class="container small">
            <header>
                <h2><?php echo $user_current_profile->username; ?>'s profile</h2>
            </header>
            <p><img id="profile_pic" height=200 width=200 src="images/default.jpg" alt="avatar"></p>
            <p>Followers: <acronym title="View followers"><a href="search.php?followers=<?php echo $user_current_profile->username; ?>"><span><?php echo $user_current_profile->followers_number; ?></span></a></acronym></p>
            <p>Following: <acronym title="View following"><a href="search.php?following=<?php echo $user_current_profile->username; ?>"><span><?php echo $user_current_profile->following_number; ?></span></a></acronym></p>
            <p>Uploads: <acronym title="View uploads"><a><span><?php echo $user_current_profile->uploaded_photos_number; ?></span></a></acronym></p>
            <?php if($user_logged_in->user_id == $user_current_profile->user_id) { ?>
            <p>E-Mail address: <a><span><?php echo $user_current_profile->email; ?></span></a></p>
            <p>Last login on <a><span><?php echo $user_current_profile->last_login_time->format("d/m/Y H:i"); ?></span></a> from <a><span><?php echo $user_current_profile->last_login_ip; ?></span></a></p>
            <?php } ?>

            <?php
            /**
             *  Follow button integration
             */
            if($user_logged_in->user_id != $user_current_profile->user_id) {
                
                $follow_button_name = NULL;
                $follow_status = $user_logged_in->getFollowButtonValue($user_current_profile);
                
                switch ($follow_status) {
                    case -1:
                        $follow_button_name = "Unavailable";
                        break;
                    case 0:
                        $follow_button_name = "Unfollow";
                        break;
                    case 1:
                        $follow_button_name = "Follow";
                        break;
                    default:
                        $follow_button_name = "Unavailable";
                        break;
                }

                if (isset($_POST["follow"])) {
                    
                    switch ($follow_status) {
                        case 0:
                            $user_logged_in->stopFollowing($user_current_profile);
                            break;
                        case 1:
                            $user_logged_in->startFollowing($user_current_profile);
                            break;
                        default:
                            break;
                    }
                    header("Location: profile.php?user=$user_current_profile->username");

                }
            ?>
            <section id="follow">
                <form action="?user=<?php echo $user_current_profile->username; ?>" method="post">
                    <input class="btn dark-blue-bordered-btn normal-btn" type="submit" name="follow" value="<?php echo $follow_button_name; ?>">
                </form>
            </section>
            <?php } ?>

            </div>
            </center>
        </section>
        
        <section id="update_profile">
            <?php
            /**
            *  Update profile informations integration
            */
            if ($user_logged_in->user_id == $user_current_profile->user_id) { 
                include(INCLUDES_PATH."update.profile.php");
            }
            ?>
        </section>


<?php

require_once(INCLUDES_PATH."footer.php");

?>