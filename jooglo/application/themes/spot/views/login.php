<div class="container">
	<div class="padding-lg-top padding-lg-bottom">
		<h2>Login.</h2>
		
		<?php echo $this->session->flashdata('login_message');?>
		
		<form id="form-login-area" method="POST" action="<?php echo site_url('u/login/do'); ?>">
			
			<div>Username / Email</div>			
			<div><input type="text" name="param1" class="input"></div>
			<div>Password</div>			
			<div><input type="password" name="param2" class="input"></div>	
			
			<!-- Csrf token -->
			<?php
			$action_token = random_string('alnum', 7);
			$this->session->set_userdata('action_token', $action_token);
			?>
			
			<input type="hidden" name="action_token" value="<?php echo $action_token;?>"/>
		
			<!-- Callback Set -->
			<input type="hidden" name="callback" value="<?php echo (isset($_GET['callback'])) ? urlencode($_GET['callback']) : '';?>"/>
		
			<div><input type="submit" value="Login" class="btn-login"></div>	
		</form>
	</div>
</div>