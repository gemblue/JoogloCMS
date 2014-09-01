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
		background:#fe9df8;
		font-size:0.85em;
		border:1px solid #e10000;
		padding:5px 10px;
		margin-bottom:10px;
	}
	</style>

</head>
<body>
	
<div class="container" align="center">
	<div class="logo">
		<a href="<?php echo base_url(); ?>"><?php echo (!empty($logo_form_admin) ? '<img src="'.$logo_form_admin.'">' : 'Logo'); ?></a>
	</div>

	<div class="login-box">
		<div style="margin-bottom:10px;"><?php echo $this->session->flashdata('message');?></div>
		<form id="form-login-area" method="POST" action="<?php echo site_url('auth/login/'.base64_encode(site_url('administration'))); ?>">
			<input type="hidden" name="source" value="i-am-handsome">
			<div class="label">Username</div>			
			<div><input type="text" name="param1" class="input" value="<?php echo $this->session->flashdata('input_username');?>"></div>
			<div class="label top-space1">Password</div>			
			<div><input type="password" name="param2" class="input" value="<?php echo $this->session->flashdata('input_password');?>"></div>			
			<div class="top-space2" align="right"><input type="submit" value="Login" class="btn-login"></div>		
		</form>
	</div>
</div>

</body>
</html>