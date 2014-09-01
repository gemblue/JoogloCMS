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
		$total_post = $this->mdl_post->get_post('works', 'total', 'publish');
		
		# read the documentation. set('post per page', 'total post', 'slug').
		$this->paging->set(2, $total_post, 'our-works');
		
		$post_data = $this->mdl_post->get_post('works', 'array', 'publish', $this->paging->get('total_per_page'), $this->paging->get('page_on'));
		
		foreach ($post_data as $row)
		{
			?>
			
			<div class="row">
			<br><br>
			<div class="col-lg-6 centered">
				<img src="<?php echo $template_path.'/assets/img/p03.png';?>" alt="">
			</div><!-- col-lg-6 -->
			<div class="col-lg-6">
				<h4><a href="<?php echo site_url($row->post_slug)?>"><?php echo $row->post_title?></a></h4>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
				<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
				<p>
					<i class="fa fa-circle-o"></i> Mobile Design<br/>
					<i class="fa fa-circle-o"></i> Web Design<br/>
					<i class="fa fa-circle-o"></i> Development<br/>
					<i class="fa fa-link"></i> <a href="#">BlackTie.co</a>
				</p>
			</div>
			</div><!-- row -->
			
			<?php
		}
		?>
		
		<!-- Link Paging -->
		<?php
		echo $this->paging->navigation();
		?>
		<!-- End : Link Paging -->
		
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