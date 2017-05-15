<?php

require_once("../config.php");
require(LIB_PATH."session.manager.php");
include_once(INCLUDES_PATH."header.php");


/**
 *  Display user block
 */
function displayBlock(&$user_found) {
?>
        <br>
        <section class="user_block">
            <p><img id="profile_pic" height=75 width=75 src="<?php echo ($user_found->profile_photo_hash != NULL) ? UPLOAD_PATH.$user_found->profile_photo_hash : "images/default.jpg" ?>" alt="avatar"></p>
            <acronym title="View profile"><h4><a href="profile.php?user=<?php echo $user_found->username; ?>"><?php echo $user_found->username; ?></a></h4></acronym>
            <div id="follow">
                Followers: <acronym title="View followers"><a href="search.php?followers=<?php echo $user_found->username; ?>"><span><?php echo $user_found->followers_number; ?></span></a></acronym><br>
                Following: <acronym title="View following"><a href="search.php?following=<?php echo $user_found->username; ?>"><span><?php echo $user_found->following_number; ?></span></a></acronym>
            </div>
        </section>
        <br>
<?php
}

/**
 *  Search database for users specific username or email
 */
function searchDatabase(&$user_searched) {

    $query = "SELECT user_id FROM users WHERE (username LIKE '%$user_searched%' OR email='$user_searched')";
    $response = db_query($query);

    if ($response) {

        while ($row = mysqli_fetch_row($response)) {
            $user_found = new User($row[0]);
            yield $user_found;
        }

    } else {

    }
}

?>

        <section>
            <div class="container small">
                <header>
                    <h3>Find people you know</h3>
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

<?php include_once(INCLUDES_PATH."footer.php"); ?>