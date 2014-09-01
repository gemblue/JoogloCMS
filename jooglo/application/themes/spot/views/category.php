<?php $this->load->view($template_name.'partials/header');?>

	<div id="blue">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
				<h4>WE WORK HARD TO ACHIEVE EXCELLENCE</h4>
				<p>AND WE ARE HAPPY TO DO IT</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!--  bluewrap -->


	<div class="container desc">
	
	
		<?php
		foreach ($post_data as $row)
		{
			?>
			
			<div class="row">
				<div class="col-lg-6 centered">
					<img src="<?php echo $template_path.'assets/img/p02.png';?>" alt="">
				</div><!-- col-lg-6 -->
				<div class="col-lg-6">
					<h4><?php echo $row->post_title?></h4>
					<p><?php echo get_excerpt($row->post_content);?></p>
				</div>
			</div><!-- row -->
			
			<?php
		}
		?>
		
		<br><br>
	</div><!-- container -->

	
	<div id="r">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
					<h4>WE ARE STORYTELLERS. BRANDS ARE OUR SUBJECTS. DESIGN IS OUR VOICE.</h4>
					<p>We believe ideas come from everyone, everywhere. At BlackTie, everyone within our agency walls is a designer in their own right. And there are a few principles we believe—and we believe everyone should believe—about our design craft. These truths drive us, motivate us, and ultimately help us redefine the power of design.</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><! -- r wrap -->
	
<?php $this->load->view($template_name.'partials/footer'); ?>
