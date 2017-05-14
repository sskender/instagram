<?php

require_once("../config.php");
require(LIB_PATH."session.manager.php");
include_once(INCLUDES_PATH."header.php");

?>

        <center>
        <div class="container">

        <?php
        $photos = $user_logged_in->dumpHomePosts();

        foreach ($photos as $photo_row) {
            $photo_i = new Photo($photo_row);
            $user_i  = new User($photo_i->user_id);
        ?>

            <div class="col-md-12">
                <div class="entry-meta table">
                    <h4>
                    Published by
                    <span><b><a href="profile.php?user=<?php echo $user_i->username; ?>"><?php echo $user_i->username; ?></a></b></span>
                     on <span><?php echo $photo_i->timestamp->format("d/M/y"); ?></span> at <span><?php echo $photo_i->timestamp->format("H:i"); ?></span>h
                     </h4>
                </div>
                <div>
                    <img src="<?php echo UPLOAD_PHOTO_PATH.$photo_i->photo_hash; ?>" class="img-responsive" alt="Uploaded photo" width="1000">
                </div>
                <!--
                <div class="read-more padding text-center">
                    <button type="button" class="btn dark-blue-bordered-btn normal-btn">upvote</button>
                </div>
                -->
                <br><br>
            </div>

        <?php
        }
        ?>

        </div>
        </center>

<?php include_once(INCLUDES_PATH."footer.php"); ?>