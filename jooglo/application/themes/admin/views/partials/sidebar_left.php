<div class="accordion" id="main_menu">

	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'index' ? 'active' : ''); ?>">
		<a href="<?php echo site_url('cms/admin/index'); ?>" class="main_menu"><span class="menu-icon"><i class="icon-home t5"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'index' ? '<b>Dashboard</b>' : 'Dashboard'); ?></span></a>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(2) == 'index' ? 'active' : '');  ?>">
		<a href="<?php echo site_url('cms/admin/report'); ?>" class="main_menu"><span class="menu-icon"><i class="icon-desktop t5"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'report' ? '<b>Report</b>' : 'Report'); ?></span></a>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'post' ? 'active' : ''); ?>">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#main_menu" href="#menu_post">
				<span class="menu-icon"><i class="icon-pencil t5"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'post' ? '<b>Post</b>' : 'Post'); ?> <span class="pull-right menu-caret t6"><i class="icon-plus"></i></span></span>
			</a>
		</div>
		<div id="menu_post" class="accordion-body collapse <?php /* echo ($this->uri->segment(2) == 'post' ? 'in' : ''); */ ?>">
			<div class="accordion-inner">
				<ul class="sub_main_menu">
					<li><a href="<?php echo site_url('cms/admin/post/all/post'); ?>">All Posts</a></li>
					<li><a href="<?php echo site_url('cms/admin/post_new'); ?>">Add New</a></li>
					<li><a href="<?php echo site_url('cms/admin/category'); ?>">Category</a></li>
					<li><a href="<?php echo site_url('cms/admin/add_post_type'); ?>">Add Post Type</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<!-- Post Type Menu -->
	<?php
	$post_type_data = $this->mdl_post->get_all_post_type();
	foreach ($post_type_data as $row)
	{
		$invalid = array('','post','page');

		if (in_array($row->post_type, $invalid, true)) 
		{
			# do nothing
		} 
		else 
		{
			$post_type_title = get_excerpt(make_lable($row->post_type), 0 , 12);
			?>
			
			<div class="accordion-group <?php echo ($this->uri->segment(5) ==  $row->post_type ? 'active' : '');?>">
				<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#main_menu" href="#menu_<?php echo $row->post_type;?>">
					<span class="menu-icon"><i class="icon-pencil t5"></i></span> <span class="menu-text"><?php echo $post_type_title;?> <span class="pull-right menu-caret t6"><i class="icon-plus"></i></span></span>
				</a>
				</div>
				<div id="menu_<?php echo $row->post_type;?>" class="accordion-body collapse">
					<div class="accordion-inner">
						<ul class="sub_main_menu">
							<li><a href="<?php echo site_url('cms/admin/post/all/'.$row->post_type); ?>">All Posts</a></li>
							<li><a href="<?php echo site_url('cms/admin/post_new/'.$row->post_type); ?>">Add New</a></li>
							<li><a href="<?php echo site_url('cms/admin/category/'.$row->post_type); ?>">Category</a></li>
						</ul>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
	<!-- Close -->
	
	<div class="accordion-group <?php /* echo ($this->uri->segment(2) == 'page' ? 'active' : ''); */ ?>">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#main_menu" href="#menu_page">
				<span class="menu-icon"><i class="icon-file t5"></i></span> <span class="menu-text">Page <span class="pull-right menu-caret t6"><i class="icon-plus"></i></span></span>
			</a>
		</div>
		<div id="menu_page" class="accordion-body collapse <?php echo ($this->uri->segment(2) == 'page' ? 'in' : ''); ?>">
			<div class="accordion-inner">
				<ul class="sub_main_menu">
					<li><a href="<?php echo site_url('cms/admin/post/all/page'); ?>">All Pages</a></li>
					<li><a href="<?php echo site_url('cms/admin/post_new/page'); ?>">Add New</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'trash' ? 'active' : ''); ?>">
		<a href="<?php echo site_url('cms/admin/post_trash'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-trash"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'post_trash' ? '<b>Trashed</b>' : 'Trashed'); ?></span></a>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'comment' ? 'active' : '');  ?>">
		<a href="<?php echo site_url('cms/admin/comment'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-comments"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'comment' ? '<b>Comment</b>' : 'Comment'); ?></span></a>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'user' ? 'active' : ''); ?>">
		<a href="<?php echo site_url('cms/admin/user'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-user"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'user' ? '<b>User</b>' : 'User'); ?></span></a>
	</div>
	
	<div class="accordion-group <?php echo ($this->uri->segment(2) == 'media' ? 'active' : '');  ?>">
		<a href="<?php echo site_url('cms/admin/media'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-picture"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'media' ? '<b>Media</b>' : 'Media'); ?></span></a>
	</div>

	<div class="accordion-group <?php echo ($this->uri->segment(3) == 'entry' ? 'active' : '');  ?>">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#main_menu" href="#menu_multi">
				<span class="menu-icon"><i class="icon-pencil t5"></i></span> <span class="menu-text">Entries<span class="pull-right menu-caret t6"><i class="icon-plus"></i></span></span>
			</a>
		</div>
		<div id="menu_multi" class="accordion-body collapse <?php /* echo ($this->uri->segment(2) == 'custom' ? 'in' : ''); */ ?>">
			<div class="accordion-inner">
				<ul class="sub_main_menu">
					<li><a href="<?php echo site_url('cms/admin/add_entry'); ?>">Add Entry Type</a></li>
					<?php
					$entry = $this->mdl_entries->get_all_entry_type();
					foreach ($entry as $row)
					{	
						if ($row->entry_type != '0')
						{
							?>
							<li><a href="<?php echo site_url('cms/admin/entry/'.$row->entry_type); ?>"><?php echo make_lable($row->entry_type)?></a></li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>

	<div class="accordion-group <?php /* echo ($this->uri->segment(2) == 'setting' ? 'active' : ''); */ ?>">
		<a href="<?php echo site_url('admin/module_manager'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-cog"></i></span> <span class="menu-text">Module Manager</span></a>
	</div>
	<div class="accordion-group <?php /* echo ($this->uri->segment(2) == 'setting' ? 'active' : ''); */ ?>">
		<a href="<?php echo site_url('cms/admin/setting'); ?>" class="main_menu"><span class="menu-icon t5"><i class="icon-cog"></i></span> <span class="menu-text"><?php echo ($this->uri->segment(3) == 'setting' ? '<b>Setting</b>' : 'Setting'); ?></span></a>
	</div>
	
</div>