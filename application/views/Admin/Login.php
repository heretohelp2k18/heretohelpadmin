<!DOCTYPE html> 
<html lang = "en"> 

    <head> 
        <meta charset = "utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Here To Help</title> 
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/bootstrap.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/style.css">
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/mediaquery.css">
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/sweetalert.css">
        <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/loader.css">
        <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed" rel="stylesheet">
        <script type = 'text/javascript' src = "<?php echo base_url(); ?>js/jquery-1.11.3.js"></script>
        <script type = 'text/javascript' src = "<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <script type = 'text/javascript' src = "<?php echo base_url(); ?>js/sweetalert.min.js"></script>
        <script type = 'text/javascript' src = "<?php echo base_url(); ?>js/script.js"></script>

    </head>

    <body>
        <div class="login-holder">
            <div class="col-xs-12 login-title">
                <div class="wrapper">
                    <h3>Login</h3>
                </div>
            </div>
            <div class="col-xs-12 text-center">
                <img src="/images/logo.png" class="img-responsive">
            </div>
            <form action="/admin/login" method="post" class="col-xs-12 login-inner">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control input-sm" id="edit_username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control input-sm" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-submit pull-right">
                </div>
            </form>
        </div>
        
        <script>
            var showMessage = <?php echo $showMessage; ?>;
            if(showMessage)
            {
                swal("Oooopss...", "<?php echo $message; ?>", "error");
            }
        </script>
    </body>
</html>