<h2 class="h4 margin-md-bottom">Perbaharui Profile</h2>

<div class="dash-profile">
	<div class="dash-avatar margin-lg-bottom">
		<a href="<?php echo site_url('u/dashboard/avatar')?>"><img src="<?php echo $avatar?>" width="150" class="img-ava"></a>
	</div>
	
	<?php echo $this->session->flashdata('message');?>
	
	<div class="margin-lg-top">
		<form role="form" method="post" class="form-horizontal" action="<?php echo site_url('u/edit/edit-profile?callback='.current_url())?>">
			<div class="form-group">
				<label class="col-sm-12">Username</label>
				<div class="col-sm-12"><?php echo $this->session->userdata('username')?></div>
			</div>
			<div class="form-group">
				<label class="col-sm-12">Nama</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="name" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'name');?>">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Nama tidak boleh kosong</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">Siapa Aku?</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="who" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'who');?>">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Tidak boleh kosong</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">Email</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="email" value="<?php echo $this->mdl_user->get_user_mail($user_id, 'id');?>">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Format email tidak valid</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">Web</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="web" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'web');?>">
				</div>
				<!--<div class="col-sm-12"><div class="margin-xs-top text-decade">Format URL salah</div></div>-->
			</div>
			<div class="form-group">
				<label class="col-sm-12">Tentang</label>
				<div class="col-sm-12">
					<textarea class="form-control" name="about"><?php echo $this->mdl_user->get_user_meta($user_id, 'about');?></textarea>
				</div>
			</div>
			
			<div class="form-group margin-xl-top">
				<label class="col-sm-12">Facebook</label>
				<div class="col-sm-7">
					<input type="text" name="facebook" class="form-control" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'facebook');?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-12">Twitter</label>
				<div class="col-sm-7">
					<input type="text" name="twitter" class="form-control" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'twitter');?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-12">Google Plus</label>
				<div class="col-sm-7">
					<input type="text" name="gplus" class="form-control" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'gplus');?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-12">Linkedin</label>
				<div class="col-sm-7">
					<input type="text" name="linkedin" class="form-control" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'linkedin');?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-12">Github</label>
				<div class="col-sm-7">
					<input type="text" name="github" class="form-control" value="<?php echo $this->mdl_user->get_user_meta($user_id, 'github');?>">
				</div>
			</div>
			
			<button type="submit" class="show_loading btn btn-decade btn-padding-lg margin-lg-top margin-lg-bottom">SAVE</button>
		</form>
	</div>
</div>