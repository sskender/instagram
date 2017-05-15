            
            <div class="container small">
                <header>
                    <h4>Update profile</h4>
                </header>

<?php
/**
 *  Update password container
 */
$result_new_password = NULL;

if (isset($_POST["newpass1"]) && isset($_POST["newpass2"])) {
    $newpass1 = $_POST["newpass1"];
    $newpass2 = $_POST["newpass2"];
    
    if (!preg_match(REGEX_PASS, $newpass1) || !preg_match(REGEX_PASS, $newpass2)) {
        $result_new_password = "Invalid password!";
    } elseif ($newpass1 != $newpass2) {
        $result_new_password = "Passwords do not match!";
    } else {
        $result_new_password = ($user_logged_in->updatePassword(hash("sha256",$newpass1))) ? "Password updated successfully!" : "Unable to update password!";
    }
}
?>
                <section>
                    <form method="post" class="contact-form" action="?">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input name="newpass1" type="password" class="form-control" id="name" placeholder="New password" required>
                            </div>    
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pad-l-3">
                            <div class="form-group contact-field">
                                <input name="newpass2" type="password" class="form-control" id="name" placeholder="Confirm new password" required>
                            </div>    
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input class="btn dark-blue-bordered-btn normal-btn" name="updatepass" type="submit" class="button submit" id="profile-update-button" value="Update password">
                        </div>
                    </form>
                    <span><?php echo $result_new_password; ?></span>
                </section>
                <br>

<?php
/**
 *  Update E-Mail container
 */
$result_new_email = NULL;

if (isset($_POST["newemail"])) {
    $email = $_POST["newemail"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result_new_email = "Invalid E-Mail address!";
    } else {
        $result_new_email = ($user_logged_in->updateEmail($email)) ? "E-Mail updated successfully!" : "Unable to update E-Mail!";
    }
}
?>
                <section>
                    <form method="post" class="contact-form" action="?">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input name="newemail" type="email" class="form-control" id="name" placeholder="New E-Mail address" required>
                            </div>    
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input class="btn dark-blue-bordered-btn normal-btn" name="updateemail" type="submit" class="button submit" id="profile-update-button" value="Update address">
                        </div>
                    </form>
                    <span><?php echo $result_new_email; ?></span>
                </section>
                <br>

<?php
/**
 *  Update profile picture container
 */
?>
                <section>
                    <form method="post" class="contact-form" action="upload.php" enctype="multipart/form-data">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="btn dark-blue-bordered-btn normal-btn" name="profile-upload" id="profile-update-button">
                                Update photo
                                <input name="profile-upload" type="file" style="opacity:0" onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>
                </section>
                <br>
            </div>
