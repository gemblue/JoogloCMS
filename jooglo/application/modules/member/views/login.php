<div class="dev-cover padding-xl-y">
	<div class="container">
		
		<div class="devo-panel active">
			<div class="row">
				<div class="col-md-8">
					<div class="devo-animate"></div>
				</div>
				<div class="col-md-4">
					<div class="padding-lg-top padding-lg-bottom">
					
						<?php if($this->session->flashdata('login_message')): ?>
						<div class="alert alert-danger">
							<?php echo $this->session->flashdata('login_message');?>
						</div>
						<?php endif; ?>
							
						<form class="form-horizontal" id="form-login-area" method="POST" action="<?php echo site_url('u/login/do'); ?>">
						
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" name="param1" class="form-control form-dark" placeholder="username atau email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-9">
									<input type="password" name="param2" class="form-control form-dark" placeholder="password">
								</div>
								<div class="col-sm-3">
									<button type="submit" class="btn btn-primary btn-block">Login</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="checkbox">
										<label>
											<input type="checkbox"> Remember me
										</label>
										<span class="margin-md-x">&bull;</span>
										<a href="<?php echo site_url('u/forgot'); ?>" class="link-primary">Lupa password?</a>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-12">
									<div class="line-dark text-center">
										<span class="bg-dark padding-md-x">ATAU LOGIN MELALUI</span>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<a href="<?php echo site_url('nyansocial/auth_facebook/connect')?>" class="btn btn-fb btn-block">Facebook</a>
								</div>
								<div class="col-sm-6">
									<a href="<?php echo site_url('nyansocial/auth_twitter/connect')?>" class="btn btn-tw btn-block">Twitter</a>
								</div>
							</div>
							
							<!-- Csrf token -->
							<?php
							$action_token = random_string('alnum', 7);
							$this->session->set_userdata('action_token', $action_token);
							?>
							
							<input type="hidden" name="action_token" value="<?php echo $action_token;?>"/>
						
							<!-- Callback Set -->
							<input type="hidden" name="callback" value="<?php echo (isset($_GET['callback'])) ? $_GET['callback'] : site_url() ;?>"/>
							<input type="hidden" name="error_callback" value="<?php echo current_url();?>"/>
							
						</form>
						
						<div class="margin-lg-top padding-md-top">
							Belum punya akun? <a href="<?php echo site_url('u/register'); ?>" class="link-primary font-bold">Daftar sekarang!</a>
						</div>
					</div>
				</div>
			</div>
		</div>	
		
	</div>
</div>