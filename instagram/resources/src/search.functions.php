<?php


/**
 *  Display user block
 */
function displayBlock(&$user_found) {
?>
        <br>
        <section class="user_block">
            <p><img id="profile_pic" height=75 width=75 src="images/default.jpg" alt="avatar"></p>
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

    $query = "SELECT user_id FROM users WHERE (username LIKE '%$user_searched%' OR email LIKE '%$user_searched%')";
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