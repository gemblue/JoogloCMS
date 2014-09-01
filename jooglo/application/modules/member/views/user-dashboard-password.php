<h2 class="h4 margin-md-bottom">Perbaharui Password</h2>

<div class="dash-profile">
	<div class="dash-avatar margin-lg-bottom">
		<a href="<?php echo site_url('u/dashboard/avatar')?>"><img src="<?php echo $avatar?>" width="150" class="img-ava"></a>
	</div>
	
	<?php echo $this->session->flashdata('message');?>
	
	<div class="margin-lg-top">
		<form role="form" method="post" class="form-horizontal" action="<?php echo site_url('u/edit/edit-password?callback='.current_url())?>">
			<div class="form-group">
				<label class="col-sm-12">Current password</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="current">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Nama tidak boleh kosong</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">New password</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="new">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Tidak boleh kosong</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">Re-type new password</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="confirmation">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Format email tidak valid</div></div>-->
			</div>
			
			<button type="submit" class="show_loading btn btn-decade btn-padding-lg margin-lg-top margin-lg-bottom">SAVE</button>
		</form>
	</div>
</div>