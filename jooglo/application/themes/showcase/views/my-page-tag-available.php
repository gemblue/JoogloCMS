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
					<div><a href="<?php echo site_url();?>"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></div>
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
			
			<div class="text-center">
				
				<div class="visible-md visible-lg">
					<div id="myCanvasContainer">
						<canvas width="800" height="500" id="myCanvas">
							<p>Anything in here will be replaced on browsers that support the canvas element</p>
							<ul>
							<?php
							$tags = $this->jooglo->get_terms('post_tag');
							foreach ($tags as $row)
							{
								$total_post = $this->mdl_post->get_post_by_term($row->slug, 'total', 'publish', 'post_tag');
								if ($total_post > 0)
								{
									?>
									<li><a href="<?php echo site_url('tags/'.$row->slug)?>" class="btn btn-primary margin-md-top" rule="button"><?php echo $row->name?> (<b><?php echo $total_post?></b>)</a></li>
									<?php
								}
							}
							?>
							</ul>
						</canvas>
					</div>
				</div>
				
				<div class="visible-xs">
				<?php
				$tags = $this->jooglo->get_terms('post_tag');
				foreach ($tags as $row)
				{
					$total_post = $this->mdl_post->get_post_by_term($row->slug, 'total', 'publish', 'post_tag');
					if ($total_post > 0)
					{
						?>
						<a href="<?php echo site_url('tags/'.$row->slug)?>" class="btn btn-primary margin-md-top" rule="button"><?php echo $row->name?> <b><?php echo $total_post?></b></a>
						<?php
					}
				}
				?>
				</div>
						
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
