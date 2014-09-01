<div class="padding-lg">
	<div class="btn-group btn-group-justified margin-md-bottom">
		<div class="btn-group">
			<a href="<?php echo site_url('~'.$username); ?>" class="btn btn-flat-primary btn-lg <?php echo ($this->uri->segment(4) == '' || $this->uri->segment(4) == 'question') ? 'active' : ''?>">Pertanyaan (<?php echo $questions_total;?>)</a>
		</div>
		<div class="btn-group">
			<a href="<?php echo site_url('~'.$username.'/contribution'); ?>" class="btn btn-flat-primary btn-lg <?php echo ($this->uri->segment(4) == 'contribution') ? 'active' : ''?>">Kontribusi (<?php echo $contribution_total?>)</a>
		</div>
	</div>

	<?php $this->load->view('discuss/question_loop', array('nothumbnail' => 1)); ?>

</div>

<div class="dev-pagination margin-md-top margin-md-bottom text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>