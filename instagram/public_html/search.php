<?php

require_once("../config.php");
require(INCLUDES_PATH."header.php");
require_once(SRC_PATH."search.functions.php");

?>


        <section>
            <div class="container small">
                <header>
                    <h3><?php echo $title; ?></h3>
                </header>
                <form method="get" action="?" class="contact-form">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input name="user" type="text" class="form-control" id="name" value="<?php if (isset($_GET['user'])) echo $_GET['user']; ?>" placeholder="Username or E-Mail" required>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input class="btn dark-blue-bordered-btn normal-btn" type="submit" class="button submit" value="Search database">
                    </div>
                </form>
            </div>
        </section>


<?php


/**
 *  Search database
 */
if (isset($_GET["user"])) {

    if (!preg_match(REGEX_SEARCH, $_GET["user"]) && !filter_var($_GET["user"], FILTER_VALIDATE_EMAIL)) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
    } else {

        $users_found_generator = searchDatabase($_GET["user"]);

        foreach ($users_found_generator as $user_found_current) {
            displayBlock($user_found_current);
        }

    }

}


/**
 *  Display users following this user
 */
if (isset($_GET["followers"])) {

    if (!preg_match(REGEX_SEARCH, $_GET["followers"]) && !filter_var($_GET["followers"], FILTER_VALIDATE_EMAIL)) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
    } else {
        $user_get = new User($_GET["followers"]);

        if ($user_get->user_id == NULL) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
        } elseif (empty($user_get->followers_list)) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
        } else {

            foreach ($user_get->followers_list as $follow_id) {
                $user_follower = new User($follow_id);
                displayBlock($user_follower);
            }

        }
    }

}


/**
 *  Display users who this user follows
 */
if (isset($_GET["following"])) {

    if (!preg_match(REGEX_SEARCH, $_GET["following"]) && !filter_var($_GET["following"], FILTER_VALIDATE_EMAIL)) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
    } else {
        $user_get = new User($_GET["following"]);

        if ($user_get->user_id == NULL) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
        } elseif (empty($user_get->following_list)) {
        ?>
            <div class="container small">
                <h5>No users found!</h5>
            </div>
        <?php
        } else {

            foreach ($user_get->following_list as $follow_id) {
                $user_following = new User($follow_id);
                displayBlock($user_following);
            }
            
        }
    }

}


?>


<?php

require_once(INCLUDES_PATH."footer.php");

?>