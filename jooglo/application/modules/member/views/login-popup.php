<div class="col-md-3 col-sm-3">
		<div class="dev-logo text-right padding-md-x">
			<a href="<?php echo site_url()?>" class="text-no-decoration"><strong style="color:#51a593;">:D</strong>evository</a>
		</div>
</div>

<div class="col-md-4">
	<div class="padding-lg-top padding-lg-bottom">
			
		<?php echo $this->session->flashdata('login_message');?>
			
		<form class="form-horizontal" id="form-login-area" method="POST" action="<?php echo site_url('u/login/do'); ?>">
						
			<div class="form-group">
				<div class="col-sm-8">
					<input type="text" name="param1" class="form-control form-dark" placeholder="username atau email">
				</div>
				<div class="col-sm-5">
					<input type="password" name="param2" class="form-control form-dark" placeholder="password">
				</div>
				<div class="col-sm-3">
					<button type="submit" class="btn btn-primary btn-block">Login</button>
				</div>
			</div>
					
			<div class="form-group">
				<div class="col-sm-8">
					<div class="checkbox">
						<label>
							<input type="checkbox"> Remember me
						</label>
						<span class="margin-md-x">&bull;</span>
						<a href="#" class="link-primary go-forgot">Lupa password?</a>
					</div>
				</div>
			</div>
							
			<div class="form-group">
				<div class="col-sm-8">
					<div class="text-center">
						<span>ATAU LOGIN MELALUI</span>
					</div>
				</div>
			</div>
							
			<div class="form-group">
				<div class="col-sm-4">
					<a href="#" class="btn btn-fb btn-block">Facebook</a>
				</div>
				<div class="col-sm-4">
					<a href="#" class="btn btn-tw btn-block">Twitter</a>
				</div>
			</div>
							
			<!-- Csrf token -->
			<?php
			$action_token = random_string('alnum', 7);
			$this->session->set_userdata('action_token', $action_token);
			?>
							
			<input type="hidden" name="action_token" value="<?php echo $action_token;?>"/>
						
			<!-- Callback Set -->
			<input type="hidden" name="error_callback" value="<?php echo current_url();?>"/>
			<input type="hidden" name="callback" value="<?php echo (isset($_GET['callback'])) ? urlencode($_GET['callback']) : '';?>"/>
			<input type="hidden" name="type" value="popup" />
				
		</form>
						
		<div class="margin-lg-top padding-md-top">
			Belum punya akun? <a href="#" class="link-primary font-bold go-register">Daftar sekarang!</a>
		</div>
	</div>
</div>