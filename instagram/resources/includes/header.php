<!DOCTYPE html>
<html>
    <head>
        <title>Instagram</title>
        <meta name="description" content="Instagram">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/templatemo-style.css">
        <link rel="stylesheet" type="text/css" href="css/profilepage-custom.css">
        <link rel="stylesheet" type="text/css" href="css/upload-button.css">
        <script src="js/upload.msg.js"></script>
        <script src="js/jquery.min.js"></script>
        <script>
            // Check scroll position and add/remove background to navbar
            function checkScrollPosition() {
                if($(window).scrollTop() > 50) {
                    $(".fixed-header").addClass("scroll");
                } else {        
                    $(".fixed-header").removeClass("scroll");
                }
            }
            $(document).ready(function () {   
                checkScrollPosition();
                // nav bar
                $('.navbar-toggle').click(function(){
                    $('.main-menu').toggleClass('show');
                });
                $('.main-menu a').click(function(){
                    $('.main-menu').removeClass('show');
                });
            });
        </script>
    </head>
    <body>
        <div class="fixed-header">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="home.php">Instagram</a>
                </div>
                <nav class="main-menu">
                    <ul>
                        <li>
                            <form action="upload.php" method="post" enctype="multipart/form-data">
                                <div class="file-upload">
                                    <label for="upload" class="file-upload__label">Upload</label>
                                    <input id="upload" class="file-upload__input" type="file" name="file-upload" onchange="this.form.submit()">
                                </div>
                            </form>
                        </li>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="profile.php" class="external">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <br><br><br>
        