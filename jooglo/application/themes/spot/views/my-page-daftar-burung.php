	<div id="blue">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
				<h4><?php echo $title?></b></h4>
				<?php
				$total_post = $this->jooglo->get_post('burung', 'total', 'publish');
				$this->paging->set(2, $total_post, 'daftar-burung');
				
				echo '<pre>';
				print_r($this->jooglo->get_post('burung', 'array', 'publish', $this->paging->get('limit'), $this->paging->get('limit_order'), null, null, 'jooglo_paging_on'));
				echo '</pre>';
				
				echo $this->paging->navigation();
				?>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- blue wrap -->