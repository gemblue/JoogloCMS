<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
		
			<form id="form" method="post" enctype="multipart/form-data" action="<?php echo site_url('control/do_update_setting');?>">
				
				<div class="label-input">Site Title:</div>
				<div class="bottom-space4">
					<input type="text" name="site_title" value="<?php echo $this->mdl_options->get_options('site_title'); ?>" id="site_title" class="span6" />
					<span id="error_site_title" class="left-space4 error">Site title must be filled</span>
				</div>
				
				<div class="label-input">Template:</div>
				<div class="bottom-space4">
					<input type="text" name="template" value="<?php echo $this->mdl_options->get_options('template'); ?>" id="template" class="span6" />
					<span id="error_template" class="left-space4 error">Template must be filled</span>
				</div>												<div class="label-input">Background:</div>				<div class="bottom-space4">					<input type="text" name="background" value="<?php echo $this->mdl_options->get_options('background'); ?>" id="template" class="span6" />					<span id="error_template" class="left-space4 error">Background must be filled</span>				</div>

				<div class="label-input">Logo:</div>
				<div class="bottom-space4">
					<input type="text" name="logo_form_admin" value="<?php echo $this->mdl_options->get_options('logo_form_admin'); ?>" id="logo_form_admin" class="span6" />
					<span id="error_logo_form_admin" class="left-space4 error">Logo must be filled</span>
				</div>
				
				<div class="label-input">Facebook Link:</div>
				<div class="bottom-space4">
					<input type="text" name="facebook_url" value="<?php echo $this->mdl_options->get_options('facebook_url'); ?>" id="facebook_url" class="span6" />
					<span id="error_facebook_url" class="left-space4 error">Facebook link must be filled</span>
				</div>
				
				<div class="label-input">Twitter Link:</div>
				<div class="bottom-space4">
					<input type="text" name="twitter_url" value="<?php echo $this->mdl_options->get_options('twitter_url'); ?>" id="twitter_url" class="span6" />
					<span id="error_twitter_url" class="left-space4 error">Twitter link must be filled</span>
				</div>
				
				<button id="tbl_update" class="btn btn-la btn-space1" type="submit">Update</button>
				
			</form>
			
		</div>
	</div>
</div>