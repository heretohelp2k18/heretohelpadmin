<!DOCTYPE html> 
<html lang = "en"> 

   <head> 
      <meta charset = "utf-8"> 
      <title>Here To Help</title> 
      <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/bootstrap.min.css">
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
      <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/style.css">
      <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/sweetalert.css">
      <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/loader.css">
      <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed" rel="stylesheet">
      <script type = 'text/javascript' src = "<?php echo base_url();?>js/jquery-1.11.3.js"></script>
      <script type = 'text/javascript' src = "<?php echo base_url();?>js/bootstrap.min.js"></script>
      <script type = 'text/javascript' src = "<?php echo base_url();?>js/sweetalert.min.js"></script>
      <script type = 'text/javascript' src = "<?php echo base_url();?>js/script.js"></script>
      
   </head>
	
       <body>
        <div class="loader">
            <div class="loader-graphic">
                <svg class="circle-loader progress" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="15">
                </svg>
            </div>
        </div>
        <nav class="navbar navbar-fixed-top no-print">
            <div class="container-fluid">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="#">Here To Help</a>
              </div>
              <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                  <!--<li><a href="/admin/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>-->
                </ul>
              </div>
            </div>
        </nav>
        <div class="left-nav col-xs-2 no-print">
            <ul class="nav modules">
                <li class="dropdown">
                  <a a href="/admin/appUsers"><i class="fa fa-user" aria-hidden="true"></i>Users</a>
                </li>
                <li class="dropdown">
                  <a a href="/admin/chatbot"><i class="fa fa-wechat" aria-hidden="true"></i>Chat Bot</a>
                </li>
                <li class="dropdown">
                  <a a href="/admin/preferences"><i class="fa fa-sliders" aria-hidden="true"></i>Preferences</a>
                </li>
                <li>
                    <a href="/admin/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
            </ul>
        </div>
        <div class="col-xs-10 print-body">
            <div class="container">
                <div class="main-container">