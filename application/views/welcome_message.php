<!DOCTYPE html> 
<html lang = "en"> 
    <head> 
       <meta charset = "utf-8"> 
       <title>iCrime</title> 
       <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/bootstrap.min.css">
       <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->
       <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/frontstyle.css">
       <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/frontstylemediaquery.css">
       <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/sweetalert.css">
       <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>css/carousel.css">
       <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed" rel="stylesheet">
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/jquery-1.11.3.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/bootstrap.min.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/jquery.scrollify.min.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/scrollreveal.min.js"></script>
       <script src="//cdnjs.cloudflare.com/ajax/libs/d3/4.7.2/d3.min.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/d3pie.min.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/carousel.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/sweetalert.min.js"></script>
       <script type = 'text/javascript' src = "<?php echo base_url();?>js/frontscript.js"></script>

    </head>
    <body>
        <div class="main-section section-home" data-section-name="home">
            <div class="toner">
            </div>
            <div class="welcome-text">
                <span class="welcome-big reveal700">
                    iCrime
                </span>
                <span class="welcome-small reveal1000">
                    Your online Crime watch App...
                </span>
                <a class="login-btn-link reveal1000" href="/admin/login">
                    <button class="login-btn">
                        LOGIN
                    </button>
                </a>
            </div>
        </div>
        <div class="main-section section-analysis" data-section-name="analysis">
            <div class="toner">
            </div>
            <div class="header-text reveal1000">
                <span class="welcome-big">
                    Crime Analysis
                </span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7">
                <div id="myPie" class="reveal1000">
                </div>
                <span class="no-data-found">No data found...</span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-5">
                <div class="sub-header-text reveal1200">
                    <div class="no-gutter form-group-lg col-xs-12">
                        <label>Sort by</label>
                        <select class="form-control" id="sort_selector">
                            <option value="1">Per Day</option>
                            <option value="2">Per Week</option>
                            <option value="3" selected>Per Month</option>
                            <option value="4">Per Year</option>
                        </select>
                    </div>
                    <div class="no-gutter form-group-lg col-xs-12 month-holder">
                        <label>Month</label>
                        <select class="form-control" id="month_selector">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="no-gutter form-group-lg col-xs-12 year-holder">
                        <label>Year</label>
                        <select class="form-control" id="year_selector">
                            <?php echo $years; ?>
                        </select>
                    </div>
                    <div class="no-gutter form-group-lg col-xs-12 day-holder">
                        <label>Day</label>
                        <input type="date" class="form-control" id="day_selector">
                    </div>
                    <div class="no-gutter form-group-lg col-xs-12 week-holder">
                        <label>Week</label>
                        <input type="week" class="form-control" id="week_selector">
                    </div>
                    <div class="no-gutter form-group-lg col-xs-12">
                        <button type="button" class="form-control" id="show_results">SHOW RESULTS</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-section section-wanted" data-section-name="wantedlist">
            <div class="toner">
            </div>
            <div class="header-text reveal1000">
                <span class="welcome-big">
                    Wanted List
                </span>
            </div>
            <div class="col-xs-12 no-gutter slide-show-view">
                <div class="owl-carousel owl-theme col-xs-12">
                    <?php echo $wanted_list; ?>
                </div>
            </div>
        </div>
        <script>
            var current_month = parseInt("<?php echo $current_month; ?>");
            $("#month_selector").val(current_month);
            $(".day-holder").hide();
            $(".week-holder").hide();
            $(".month-holder").show();
            $(".year-holder").show();
        </script>
    </body>
</html>