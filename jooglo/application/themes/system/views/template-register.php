<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo (isset($site_title) ? $site_title.' | ' : ''); ?> Administration</title>
	
	<meta name='robots' content='noindex, nofollow' />
	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	
	<style>
	body {
		margin:0px;
		padding:0px;
		font-family: 'Open Sans', sans-serif;
		background:url(<?php echo base_url('application/views/admin/assets/img/wavegrid.png'); ?>) repeat;
	}
	.container {
		width:500px;
		margin:auto;
		margin-top:100px;
	}
	.logo {
		margin-bottom:20px;
	}
	.login-box {
		width:260px;
		padding:20px 30px;
		text-align:left;
		background:rgba(255,255,255,0.3);
	}
	.login-box input.input {
		padding:0px;
		width:252px;
		height:30px;
		border:1px solid #c0c0c0;
		line-height:30px;
		padding:2px 3px;
	}
	.top-space1 { margin-top:10px; }
	.top-space2 { margin-top:20px; }
	.label {
		color:#767676;
		font-size:0.9em;
		margin-bottom:3px;
	}
	input.btn-login {
		color: #ffffff;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		background-color: #006dcc;
		*background-color: #0044cc;
		background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
		background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
		background-image: -o-linear-gradient(top, #0088cc, #0044cc);
		background-image: linear-gradient(to bottom, #0088cc, #0044cc);
		background-repeat: repeat-x;
		border-color: #0044cc #0044cc #002a80;
		border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
		filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
		padding:5px 20px;
	}
	.error {
		font-size:0.85em;
		margin-bottom:5px;
		color:orange;
	}
	</style>

</head>
<body>
	
<div class="container" align="center">
	<div class="logo">
		<a href="<?php echo base_url(); ?>"><?php echo (!empty($logo_form_admin) ? '<img width="100" src="'.$logo_form_admin.'">' : 'Logo'); ?></a>
	</div>

	<div class="login-box">
		<div style="margin-bottom:10px;"><?php echo $this->session->flashdata('message');?></div>
		<form id="form-register-area" method="POST" action="<?php echo site_url('user/do_register/'.base64_encode(site_url('user/register'))); ?>">
			
			<div class="label">Username</div>			
			<div><input type="text" name="username" class="input"></div>
			<div class="label top-space1">Email</div>			
			<div><input type="text" name="email" class="input"></div>
			<div class="label top-space1">Password</div>			
			<div><input type="password" id="password" name="password" class="input"></div>	
			<div class="label top-space1">Confirm Password</div>			
			<div><input type="password" name="confirm_password" class="input"></div>	
			<div class="label top-space1">Phone</div>			
			<div><input type="text" name="phone" class="input"></div>			
			<div class="top-space2" align="right"><input type="submit" value="Register" class="btn-login"></div>	
			
		</form>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.8.2.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugin/jquery-validation/jquery.validate.min.js');?>"></script>

<script>
$(document).ready(function() {  

	$("#form-register-area").validate({
		rules: {
			
			username: {
				required: true,
				minlength: 3
			},
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true
			}
		},
		messages: {
			username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 6 characters"
			},
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 6 characters long"
			},
			confirm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 6 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address"
		}
	});


});
</script>

</body>
</html>