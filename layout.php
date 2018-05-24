﻿<html>

<head>
    <title>SayHi</title>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <script src="./js/sayhi.js"></script>
</head>

<body>
    <nav class="navbar navbar-findcond navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
                <div class="logo navbar-brand">
                    <img src="./img/SayHi_Logo.png" />
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <form class="nav navbar-form navbar-left search_form" id="search" role="search">
                    <input type="text" class="form-control sbox" placeholder="Search">
                    <ul class="list-group autocomplete" id="searchres" style="position:absolute;width:300px; z-index: 100;">
                    </ul>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="http://localhost/SayHi/index.html">Home Page <span class="sr-only">(current)</span></a></li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-fw fa-bell-o"></i> Notification <span class="badge">0</span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><i class="fa fa-fw fa-tag"></i> <span class="badge">Music</span> SayHi <span class="badge">Video</span> SayHi </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-thumbs-o-up"></i> <span class="badge">Music</span> SayHi </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-thumbs-o-up"></i> <span class="badge">Video</span> SayHi </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-thumbs-o-up"></i> <span class="badge">Game</span> SayHi </a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Hello<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Messages</a></li>
                            <li><a href="http://localhost/SayHi/create-account.html">Sign Up</a></li>
                            <li><a href="http://localhost/SayHi/login.html">Sign In</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</body>

</html>