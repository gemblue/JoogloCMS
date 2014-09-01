	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class=""><a href="<?php echo site_url(); ?>" target="_blank"><?php echo (isset($site_title) ? $site_title : 'Jooglo CMS'); ?></a></li>
						<li class="divider-vertical"></li>
						<li class=""><a href="<?php echo site_url('cms/admin/setting')?>"><i class="icon-cog right-space5"></i> Setting</a></li>
						<li class="divider-vertical"></li>
					</ul>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="">						
						<a href="<?php echo site_url('u/logout');?>">		

						<?php
						$role = $this->mdl_role->get_user_role($this->session->userdata('id'));
						$role_name = $this->mdl_role->get_role_name($role);
						?>
						<i class="icon-off right-space5"></i><b><?php echo $this->session->userdata('username');?></b> (<?php echo $role_name;?>) - Logout</a>
						</li>
					</ul>
				</div>
			
		</div>
    </div>