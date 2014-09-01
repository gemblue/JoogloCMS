<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Administration <?php echo (isset($template['title']) ? '| '.$template['title'] : ''); ?> <?php echo (isset($site_title) ? '&#8212; '.$site_title : ''); ?></title>
	<meta name='robots' content='noindex, nofollow' />

	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>	
	
	<?php echo get_theme_css('bootstrap.css'); ?>
	<?php echo get_theme_css('bootstrap-responsive.css'); ?>
	<?php echo get_theme_css('font-awesome.min.css'); ?>
	<?php echo get_theme_css('jquery.fancybox.css'); ?>
	<?php echo get_theme_css('style.css'); ?>
	<?php echo $template['css']; ?>

	<?php echo get_theme_js('jquery-1.10.2.js');?>
	<?php echo get_theme_js('bootstrap.min.js');?>
	<?php echo get_theme_js('jquery.fancybox.pack.js');?>
	<?php echo $template['js']; ?>
	
	<!-- Strength Js -->
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('jooglo/plugins/Strengthjs/strength.css')?>" />
	<style>
	.button_strength {
		text-decoration: none;
		font-size: 13px;
		margin-left:15px;
	}
	</style>
	
	<!-- Jq UI -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	
	<!-- Ios  Overlay -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'jooglo/plugins/iOS-Overlay/css/iosOverlay.css'; ?>" />
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">