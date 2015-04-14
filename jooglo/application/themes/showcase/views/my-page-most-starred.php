<div class="row-fluid">
	<div class="header text-center">
		<div class="container">
			<div class="logo">
			Showcase
			<?php echo get_theme_image('codepolitan.png')?>
			</div>
			
			<div class="slogan">Awesome Handcraft List by Indonesian Coders</div>
			
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<div class="text-center">
						<div class="main-nav">
							<a href="#" class="active" data-toggle="modal" data-target="#submit-modal">SUBMIT PRODUCT</a> . <a href="#" data-toggle="modal" data-target="#suggest-modal">SUGGEST</a>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 col-xs-12">
					<div class="text-center ">
						<div class="main-nav">
							<a href="<?php echo site_url('about')?>">ABOUT</a> . <a href="<?php echo site_url('statistic')?>">STATS</a> . <a href="<?php echo site_url('tag-available')?>">TAG</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	if ($logged_in == true)
	{
		?><div class="person-dude text-center">Welcome. <b><?php echo $this->session->userdata('username')?></b> - <a href="<?php echo site_url('u/logout')?>">Logout</a></div><?php
	}
	?>
	
	<div class="container">
		<div class="menu-top">
			<div class="row">
				<div class="navbar-left col-md-6 col-xs-12">
					<a class="btn btn-nyankod" href="<?php echo site_url()?>" role="button">Latest</a>
					<a class="btn btn-nyankod" href="<?php echo site_url('most-seen')?>" role="button">Most Seen</a>
					<a class="btn btn-active" href="<?php echo site_url('most-starred')?>" role="button">Most Starred</a>
				</div>
				
				<form class="navbar-form pull-right col-md-6 col-xs-12" role="search">
					<div class="form-group">
						<input name="keyword" id="keyword" type="text" class="form-control" placeholder="Search">
					</div>
					<a href="#" class="btn btn-default btn-search" rule="button">Go!</a>
				</form>
			</div>
		</div>
		
		<div class="item-container">
			<div class="row">
				<?php 
				$total_post = $this->star_m->get_most_starred('product', 'total', 'publish');
				$this->paging->set(9, $total_post, 'most-starred');
				$product = $this->star_m->get_most_starred('product', 'array', 'publish', $this->paging->get('limit'), $this->paging->get('limit_order'), 'jooglo_paging_on');
				
				foreach ($product as $row)
				{
					$description = get_excerpt($this->jooglo->get_field_value('post_content', 'ID', $row->ID), 0, 50);
					$tags = $this->jooglo->get_post_tags($row->ID);
					?>
					
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<a href="<?php echo site_url($row->post_slug)?>"><img src="<?php echo $this->jooglo->get_post_thumbnail($row->ID);?>" /></a>
							<div class="caption">
								<h3><?php echo $row->post_title?></h3>
								<div class="margin-md-top">
								<?php
								foreach ($tags as $row_)
								{
									?>
									<span class="badge"><a href="<?php echo site_url('tags/'.$row_->slug)?>"><?php echo $row_->name?></a></span>
									<?php
								}
								?>
								</div>
								
								<p class="margin-md-top"><?php echo $description?></p>
								<p><a href="<?php echo site_url($row->post_slug)?>" class="btn btn-primary btn-nyankod" role="button">See Detail</a></p>
								
								
							</div>
							
						</div>
					</div>
					<?php
				}
				?>
			</div>
			
			<ul class="pagination">
				<?php echo $this->paging->navigation();?>
			</ul>
		</div>
	</div>
	
	<div class="visible-md visible-lg">
		<div class="bottom text-center">
			<div>Mobile Apps / Dekstop Software / Web / Developer Weapon / Library / Cloud</div>
			<button class="margin-md-top btn btn-default btn-super-lg" data-toggle="modal" data-target="#submit-modal">SUBMIT PRODUCT</button>
		</div>
	</div>
</div>
