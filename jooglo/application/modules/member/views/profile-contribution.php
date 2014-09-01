<div class="padding-lg">
	
	<div class="btn-group btn-group-justified margin-md-bottom">
		<div class="btn-group">
			<a href="<?php echo site_url('~'.$username); ?>" class="btn btn-flat-primary btn-lg <?php echo ($this->uri->segment(4) == '' || $this->uri->segment(4) == 'question') ? 'active' : ''?>">Pertanyaan (<?php echo $questions_total;?>)</a>
		</div>
		<div class="btn-group">
			<a href="<?php echo site_url('~'.$username.'/contribution'); ?>" class="btn btn-flat-primary btn-lg <?php echo ($this->uri->segment(4) == 'contribution') ? 'active' : ''?>">Kontribusi (<?php echo $contribution_total?>)</a>
		</div>
	</div>
		
	<?php 
	foreach ($contribution as $row)
	{ 
		?>
			<div class="user-question-item bg-white padding-sm">
				<span class="margin-xs-right"><?php echo $row->action?>  <a href="<?php echo site_url('discuss/q/'.$row->slug)?>"><?php echo $row->title?></a>.</span>
				<span class="question-date text-right text-grey text-small"><i class="fa fa-clock-o"></i> <?php echo time_ago($row->created_at);?></span>
			</div>
		<?php 
	} 
	?>
</div>

<div class="dev-pagination margin-md-top margin-md-bottom text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>