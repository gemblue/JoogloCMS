<h2 class="h4 margin-md-bottom">Ganti Avatar</h2>

<?php echo $this->session->flashdata('message');?>

<div class="dash-profile">
	<div class="margin-lg-bottom">
		<img src="<?php echo $this->mdl_user->get_avatar($this->session->userdata('id'), 'md')?>" class="img-ava">
	</div>
	
	<div class="margin-lg-top">
		<form role="form" action="<?php echo site_url('u/edit/edit-avatar?callback='.current_url())?>" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="exampleInputFile">Pilih gambar yang akan diunggah</label>
				<input type="file" name="userfile" id="exampleInputFile">
				<p class="text-decade margin-xs-top">File tidak boleh kosong</p>
			</div>
			<button type="submit" class="btn btn-decade btn-padding-lg margin-md-top margin-lg-bottom">SAVE</button>
		</form>
		
		<div>Note: Secara default avatar Anda akan kita diambil dari <a href="https://en.gravatar.com/support/what-is-gravatar/">Gravatar</a></div>
		
	</div>
</div>