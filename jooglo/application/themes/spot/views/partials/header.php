<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href=<?php echo $template_path.'assets/ico/favicon.png';?>>

    <title>SPOT - Free Bootstrap 3 Theme</title>

    <!-- Bootstrap core CSS -->
    <?php echo get_theme_css('bootstrap.css');?>
    <?php echo get_theme_css('font-awesome.min.css');?>
    <!-- Custom styles for this template -->
    <?php echo get_theme_css('main.css');?>

    <!-- another css -->
    <?php echo $template['css']; ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">SP<i class="fa fa-circle"></i>T</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li <?php echo ($this->uri->segment(1) == '') ? 'class="active"' : '';?>><a href="<?php echo site_url('')?>">HOME</a></li>
            <li <?php echo ($this->uri->segment(1) == 'about') ? 'class="active"' : '';?>><a href="<?php echo site_url('about')?>">ABOUT</a></li>
            <li <?php echo ($this->uri->segment(1) == 'services') ? 'class="active"' : '';?>><a href="<?php echo site_url('services')?>">SERVICES</a></li>
            <li <?php echo ($this->uri->segment(1) == 'our-works') ? 'class="active"' : '';?>><a href="<?php echo site_url('our-works')?>">WORKS</a></li>
            <li><a data-toggle="modal" data-target="#myModal" href="#myModal"><i class="fa fa-envelope-o"></i></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>