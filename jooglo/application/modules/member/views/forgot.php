<div class="dev-cover padding-xl-y">
	<div class="container">
		
		<div class="devo-panel active">
			
			<div class="row">
				<div class="col-md-8">
					<div class="devo-animate"></div>
				</div>
				<div class="col-md-4">
					<div class="padding-lg-top padding-lg-bottom">
					
						<?php echo $this->session->flashdata('message');?>
						
						<form class="form-horizontal" method="post" action="<?php echo site_url('u/forgot/request')?>">
							<div class="form-group">
								<div class="col-sm-12">
									Untuk mendapatkan akunmu kembali, isilah form berikut dengan email Kamu.
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" name="email" class="form-control form-dark" placeholder="email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<button type="submit" class="btn btn-primary btn-block">DAPATKAN AKUN KEMBALI</button>
								</div>
							</div>
						</form>
						
						<div class="margin-lg-top padding-md-top">
							Ingat akun? <a href="<?php echo site_url('u/login'); ?>" class="link-primary font-bold">Login!</a>
						</div>
					</div>
				</div>
			</div>
		
		</div>
		
	</div>
</div>