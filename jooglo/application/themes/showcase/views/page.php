<div class="row-fluid">
	<div class="header-mini text-center">
		<div class="container">
			<div class="row">
				<div class="logo-little">
					<div class="text-left col-md-6 col-xs-12">
						<a href="<?php echo site_url()?>"><b>Showcase</b></a>
					</div>
				</div>
				
				<div class="text-right col-md-6 col-xs-12">
					<div class="main-nav-mini">
						<a href="#" class="active" data-toggle="modal" data-target="#submit-modal">Submit Product</a> . <a href="<?php echo site_url('about')?>">About</a> . <a href="<?php echo site_url('statistic')?>" data-toggle="modal" data-target="#suggest-modal">Suggest</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<h2><?php echo $title?></h2>
		<div class="menu-top">
		<?php echo $content?>
		</div>
	</div>
	
	<div class="visible-md visible-lg">
		<div class="bottom text-center">
			<div>Mobile Apps / Dekstop Software / Web / Developer Weapon / Library / Cloud</div>
			<button class="margin-md-top btn btn-default btn-super-lg" data-toggle="modal" data-target="#submit-modal">SUBMIT PRODUCT</button>
		</div>
	</div>
</div>
