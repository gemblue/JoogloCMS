<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
		
			<form id="form" method="post" enctype="multipart/form-data" action="<?php echo site_url('cms/admin/do_update_setting');?>">
				
				<div class="label-input">Site Title:</div>
				<div class="bottom-space4">
					<input type="text" name="site_title" value="<?php echo $this->mdl_options->get_options('site_title'); ?>" id="site_title" class="span6" />
				</div>
														
				<div class="label-input">Logo:</div>
				<div class="bottom-space4">
					<div class="form-inline">
						<input type="text" name="logo_form_admin" value="<?php echo $this->mdl_options->get_options('logo_form_admin'); ?>" id="logo_form_admin" class="span6" />
						<a href="<?php echo base_url('jooglo/plugins/filemanager/dialog.php?type=1&field_id=logo_form_admin'); ?>" class="btn btn-iframe" type="button"><i class="icon-folder-open"></i></a>
					</div>
				</div>
				
				<div class="label-input">Existing Theme:</div>
				<div class="bottom-space4">
					<?php
					
					foreach($themes as $row)
					{
						if ($site_template == $row)
						{
							$class = 'theme-img-active';
							$current = '<b>- Active</b>';
						}
						else
						{
							$class = '';
							$current = '';
						}
						?>
						
						<br/><b><?php echo ucfirst($row)?></b> <?php echo $current?><br/>
						<div theme="<?php echo $row;?>" class="theme <?php echo $class?>">
							<img width="700" src="<?php echo site_url('jooglo/application/themes/'.$row.'/screenshot.png');?>" />
							
						</div>
						<br/>
						<?php
					}
					?>
					<br/>
					<small>Note: Click to theme screenshot to change.</small>
					
					<input type="hidden" name="template" value="<?php echo $this->mdl_options->get_options('template'); ?>" id="template" class="span6" />
				</div>		
				
				<button id="tbl_update" class="btn btn-la btn-space1" type="submit">Update</button>
				
			</form>
			
		</div>
	</div>
</div>