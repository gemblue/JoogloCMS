<?php echo get_partial('header'); ?>
<?php echo get_partial('navbar'); ?>
    
	<!-- Main Container -->

	<div class="main-container">
		
		<div class="wrapper">
			<div class="row-fluid">
				
				<!-- Sidebar Left -->
				<div class="span2">
					<div class="menu-left">
						<?php echo get_partial('sidebar_left'); ?>
					</div>
				</div>
				<!-- Sidebar Left End -->
				
				<!-- Content Area -->
				<div class="span10">
					
					<div class="content-area">
						
						<?php echo get_partial('top'); ?>
						
						<!-- Alert Area -->
						<?php echo $this->session->flashdata('message');?>
						<!-- Alert Area End -->
						
						<!-- Main Content -->
						<?php echo $template['body']; ?>
						<!-- Main Content End -->
						
					</div>
					
				</div>
				<!-- Content Area End -->
				
			</div>
		</div>
	</div>
	<!-- Main Container End -->
	
<?php echo get_partial('footer'); ?>