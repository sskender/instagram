<?php

require_once("../config.php");
$login_error = $register_error = NULL;


/*
 *  Login
 */
if (isset($_POST["submit_login"])) {

    $all_good = true;
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // check username or email
    if (preg_match("/^[a-zA-Z0-9-_.]{5,20}+$/", $username)) {

    }
    elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {

    }
    else {
        $login_error = "Invalid username!";
        $all_good = false;
    }

    // check password
    if (!preg_match("/^[a-zA-Z0-9-_.@$#!%&]{6,50}+$/", $password)) {
        $login_error = ($login_error == NULL) ? "Invalid password!" : "Invalid username and password!";
        $all_good = false;
    }

    // if all good proceed with login
    if ($all_good) {

        $hashed_password = hash("sha256",$password);
        require_once(SRC_PATH."login.php");
    }

}


/*
 *  Register
 */
if (isset($_POST["submit_register"])) {

    $all_good  = true;
    $username  = trim($_POST["usernamesignup"]);
    $email     = $_POST["emailsignup"];
    $password1 = $_POST["passwordsignup"];
    $password2 = $_POST["passwordsignup_confirm"];

    // check passwords
    if (!preg_match("/^[a-zA-Z0-9-_.@$#!%&]{6,50}+$/", $password1) || !preg_match("/^[a-zA-Z0-9-_.@$#!%&]{6,50}+$/", $password2)) {
        $register_error = "Invalid password!";
        $all_good = false;
    }
    elseif ($password1 != $password2) {
        $register_error = "Passwords do not match!";
        $all_good = false;
    }

    // check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Invalid E-Mail address!";
        $all_good = false;
    }

    // check username
    if (!preg_match("/^[a-zA-Z0-9-_.]{5,20}+$/", $username)) {
        $register_error = "Invalid username!";
        $all_good = false;
    }

    // check if all_good
    if ($all_good) {

        $hashed_password = hash("sha256",$password1);
        require_once(SRC_PATH."register.php");
    }

}

?>



<html>
    <head>
        <title>Welcome to Instagram</title>
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
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="mysuperusername690" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                    <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="mysupermail@mail.com"/> 
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