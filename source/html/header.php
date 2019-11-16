<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="canonical" href="https://progressiveweatherapp.com/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progressive Weather App : <?php echo $controller ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Progressive Weather App">
    <link rel="apple-touch-icon" href="images/icons/icon-152x152.png">
    <meta name="msapplication-TileImage" content="images/icons/icon-144x144.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">
    <meta name="theme-color" content="#2F3BA2" />
    <script src="app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

</html>

<body>

    <nav class="navbar navbar-expand-sm  navbar-dark shadow  mb-4 bg-primary sticky-top">
        <a class="navbar-brand" href="#">
            <img src="https://progressiveweatherapp.com/images/pwalogo.png" height="40px" alt="">

        </a>
        <a class="navbar-brand text-dark" href="#">
            <h5>ProgressiveWeatherApp</h5>
        </a>

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb" aria-expanded="true">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navb" class="navbar-collapse collapse hide">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link text-dark" href="/Home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/Weather">Weather</a>
                </li>



            </ul>


            <?php if (isset($_SESSION['Login']['isLOGGEDIN']) && $_SESSION['Login']['isLOGGEDIN'] == true) { ?>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown hidden-md-down">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <strong>My Weather</strong>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a href="/myWeather/Favorites" class="dropdown-item"><strong>myFavorites</strong></a>
                            <a href="/Login/Logout" class="dropdown-item">
                                <div class="dropdown-divider"></div>
                                <strong>Logout</strong>
                            </a>
                    </li>

                </ul>
            <?php } else { ?>

                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown hidden-md-down">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <strong>Login</strong>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">

                            <div class="container login-container">

                                <div class="login-form-1">
                                    <h3>Login</h3>
                                    <form action="/Login" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="LoginFormData[Email]" placeholder="Your Email *" value="" />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="LoginFormData[Password]" placeholder="Your Password *" value="" />
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-block btn-outline-success" value="Login" />
                                        </div>
                                        <div class="form-group">
                                            <a href="/Login/SignUp" class="btn btn-block btn-outline-info">Signup</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </nav>