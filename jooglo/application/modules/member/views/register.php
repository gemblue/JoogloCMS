<div class="dev-cover padding-xl-y">
	<div class="container">
		
		<div class="devo-panel active">
			<div class="row">
				<div class="col-md-6">
					<div class="devo-register"></div>
				</div>
				<div class="col-md-6">
					<div class="padding-lg-top padding-lg-bottom">
						<h3 class="margin-lg-bottom">Daftar Akun Devository</h3>
						
						<?php if($this->session->flashdata('error_message')): ?>
						<div class="alert alert-danger">
							<?php echo $this->session->flashdata('error_message');?>
						</div>
						<?php endif; ?>
						
						<?php if($this->session->flashdata('success_message')): ?>
						<div class="alert alert-success">
							<?php echo $this->session->flashdata('success_message');?>
						</div>
						<?php endif; ?>
						
						<form class="form-horizontal" id="form-register-area" method="POST" action="<?php echo site_url('u/register/do'); ?>">
							
							<?php
							// If, from third parties. Add extra field.
							if ($username = $this->session->userdata('username'))
							{
								?>
								<div class="margin-md-bottom"><b>Third parties register, source from <b><?php echo ucfirst($this->session->userdata('source'));?></b></div>
								<div class="form-group">
								<div class="col-sm-4"><div class="form-label"><?php echo ucfirst($this->session->userdata('source'));?> ID</div></div>
								<div class="col-sm-8">
									<input type="text" name="third_parties_id" value="<?php echo $this->session->userdata('id');?>" class="form-control form-dark margin-xs-bottom" readonly>
									<input type="hidden" name="third_parties_source" value="<?php echo $this->session->userdata('source');?>"/>
								</div>
								</div>
								<?php
							}
							?>
							
							<div class="form-group">
								<div class="col-sm-4"><div class="form-label">Username</div></div>
								<div class="col-sm-8">
									<input type="text" name="username" value="<?php echo (isset($username)) ? $username : set_value('username'); ?>" class="form-control form-dark margin-xs-bottom">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4"><div class="form-label">Email</div></div>
								<div class="col-sm-8">
									<input type="email" name="email" value="<?php echo set_value('email'); ?>" class="form-control form-dark margin-xs-bottom">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4"><div class="form-label">Password</div></div>
								<div class="col-sm-8">
									<input type="password" name="password" class="form-control form-dark margin-xs-bottom">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4"><div class="form-label">Konfirmasi Password</div></div>
								<div class="col-sm-8">
									<input type="password" name="confirm_password" class="form-control form-dark margin-xs-bottom">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8 col-sm-offset-4">
									<button type="submit" class="btn btn-primary btn-block margin-md-top show_loading">DAFTAR</button>
								</div>
							</div>
							
							<?php
							if (!isset($_GET['source']))
							{
								?>
								<div class="form-group">
								<div class="col-sm-12">
									<div class="line-dark text-center padding-lg-y">
										<span class="bg-dark padding-md-x">ATAU DAFTAR MELALUI</span>
									</div>
								</div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-6">
										<a href="#" class="btn btn-fb btn-block">Facebook</a>
									</div>
									<div class="col-sm-6">
										<a href="#" class="btn btn-tw btn-block">Twitter</a>
									</div>
								</div>
								<?php
							}
							?>
							
							<input type="hidden" name="callback" value="<?php echo current_url();?>"/>	
						</form>
						
						<div class="margin-lg-top padding-md-top">
							Sudah punya akun? <a href="<?php echo site_url('u/login'); ?>" class="link-primary font-bold">Login</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>