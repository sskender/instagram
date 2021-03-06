<?php


/**
 *  If user is already logged in redirect to home page
 */
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit();
}


require_once("../config.php");
require_once(SRC_PATH."database.php");
require_once(SRC_PATH."login.register.php");

$login_error = $register_error = NULL;
$value_user = $value_email = NULL;


/**
 *  Login
 */
if (isset($_POST["submit_login"])) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!preg_match(REGEX_USER, $username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $login_error = "Invalid username or password!";
    }

    if (!preg_match(REGEX_PASS, $password)) {
        $login_error = "Invalid username or password!";
    }

    // proceed with login
    if ($login_error == NULL) {

        $hashed_password = hash("sha256",$password);
        $login_result = login($username,$hashed_password);  /* login_result is either user_id or 0 (false) */

        // valid login
        if ($login_result) {

            // log
            logLogin($login_result);

            // user session
            session_start();
            $_SESSION["user_id"] = $login_result;
            header("Location: home.php");
            exit();

        // invalid login
        } else {
            $login_error = "Invalid username or password!";
        }

    }
}


/**
 *  Register
 */
if (isset($_POST["submit_register"])) {

    $username  = trim($_POST["usernamesignup"]);
    $email     = $_POST["emailsignup"];
    $password1 = $_POST["passwordsignup"];
    $password2 = $_POST["passwordsignup_confirm"];

    // check passwords
    if (!preg_match(REGEX_PASS, $password1) || !preg_match(REGEX_PASS, $password2)) {
        $register_error = "Invalid password!";
    }
    elseif ($password1 != $password2) {
        $register_error = "Passwords do not match!";
    }

    // check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Invalid E-Mail address!";
    } else {
        $value_email = $email;
    }

    // check username
    if (!preg_match(REGEX_USER, $username)) {
        $register_error = "Invalid username!";
    } else {
        $value_user = $username;
    }


    // proceed with register
    if ($register_error == NULL) {

        $hashed_password = hash("sha256",$password1);
        $register_result = register($username,$email,$hashed_password);  /* register_result is either user_id or 0 (false) */

        // codes explained in register.php
        switch ($register_result) {

            case -1:
                $register_error = "Database in maintenance!";
                break;

            case -2:
                $register_error = "Username already in use!";
                break;
            
            case -3:
                $register_error = "E-Mail already in use!";
                break;

            default:
                // log
                logLogin($register_result);

                // user session
                session_start();
                $_SESSION["user_id"] = $register_result;
                header("Location: home.php");
                exit();
        }
        
    }

}

?>



<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Instagram</title>
        <meta charset="utf-8">
        <meta name="description" content="Login and registration">
        <link rel="stylesheet" type="text/css" href="css/indexpage.css">
        <link rel="stylesheet" type="text/css" href="css/indexpage-animation.css">
    </head>
    <body>
        <h1>WELCOME TO INSTAGRAM</h1>
        
        <div class="container">
            <section>				
                <div id="container_demo" >
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">

                        <div id="login" class="animate form">
                            <form action="?" method="post" autocomplete="on"> 
                                <h1>Log in</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                    <input id="username" name="username" required="required" type="text" placeholder="myusername or mymail@mail.com"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. password123" /> 
                                </p>
                                <p class="keeplogin">
                                    <!-- Removed keep logged in option and added error feedback instead -->
                                    <label for="loginkeeping"><?php echo $login_error; ?></label>
                                </p>
                                <p class="login button"> 
                                    <input type="submit" name="submit_login" value="Login" /> 
                                </p>
                                <p class="change_link">
                                    Not a member yet ?
                                    <a href="#toregister" class="to_register">Join us</a>
                                </p>
                            </form>
                        </div>

                        <div id="register" class="animate form">
                            <form  action="?#toregister" method="post" autocomplete="on"> 
                                <h1> Sign up </h1> 
                                <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" value="<?php echo $value_user; ?>"placeholder="mysuperusername690" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                    <input id="emailsignup" name="emailsignup" required="required" type="email" value="<?php echo $value_email; ?>" placeholder="mysupermail@mail.com"/> 
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                                    <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                <p class="keeplogin">
                                    <!-- Removed keep logged in option and added error feedback instead -->
                                    <label for="loginkeeping"><?php echo $register_error; ?></label>
                                </p>
                                <p class="signin button"> 
                                    <input type="submit" name="submit_register" value="Sign up"/> 
                                </p>
                                <p class="change_link">  
                                    Already a member ?
                                    <a href="#tologin" class="to_register"> Go and log in </a>
                                </p>
                            </form>
                        </div>
                        
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>
