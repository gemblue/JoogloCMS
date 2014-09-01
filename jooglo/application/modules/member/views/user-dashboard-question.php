<h2 class="h4 margin-lg-bottom">Kamu pernah bertanya ini</h2>
		
<?php 
$total = $this->mdl_discuss->get_questions('total', true, $this->session->userdata('id'));
$pagination_link = pagination_link('u/dashboard/question/', $total, 4, $page_number);
$question = $this->mdl_discuss->get_questions('array', true, $this->session->userdata('id'), $pagination_link['limit'], $pagination_link['offset']);

foreach ($question as $row)
{ 
	?>
	<div class="user-question-item bg-white">
		<div class="padding-lg">
			<a href="#" class="link-primary margin-sm-right h4"><?php echo $row->title?></a>
			<span class="question-date text-right text-grey"><i class="fa fa-clock-o"></i> asked <?php echo time_ago($row->created_at)?></span>
			<div class="question-excerpt margin-sm-top text-grey">
				<!--Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod...-->
			</div>
			<div class="question-label margin-md-top">
				<?php
				$tags = $this->mdl_discuss->get_question_tags($row->id);
				foreach ($tags as $row_)
				{
					?>
					<a href="<?php echo site_url('discuss/label/'.$row_->tag)?>" class="dev-label">&bull; <?php echo $row_->tag;?></a>
					<?php
				}
				?>
			</div>
			<div class="margin-md-top">
				<a href="#" class="link-primary"><?php echo $this->mdl_discuss->get_questions_views('id', $row->id);?> view</a> <span class="margin-sm-x">&bull;</span> 
				<a href="#" class="link-primary"><?php echo $this->mdl_discuss->get_answers('total', $row->id);?> answer</a> <span class="margin-sm-x">&bull;</span> 
				<a href="#" class="link-primary"><?php echo $this->mdl_vote->get_total_vote($row->id, 'discuss_question', 'summary');?> votes</a>
			</div>
		</div>
	</div>
	<?php 
} 
?>
	
<div class="dev-pagination margin-md-top margin-md-bottom text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>