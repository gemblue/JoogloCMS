<h2 class="h4 margin-lg-bottom">Pertanyaan-pertanyaan yang mungkin bisa kamu jawab</h2>
	
<div class="dev-question-loop">

<?php 
// Get total question
$total = $this->mdl_discuss->get_questions('total', true);

// Setting paging
$pagination_link = pagination_link('u/dashboard/home/', $total, 4, $page_number);

foreach ($this->mdl_discuss->get_questions('array', true, null, $pagination_link['limit'], $pagination_link['offset']) as $row)
{
	$creator_username = $this->mdl_user->get_username($row->creator, 'id');
	$creator_link = site_url('~'.$creator_username);
	$creator_avatar = $this->mdl_user->get_avatar($row->creator, 'xs');
	?>
	<div class="dev-question-item margin-md-bottom">
		<div class="media">
			<a href="<?php echo $creator_link?>" class="pull-left">
				<img src="<?php echo $creator_avatar?>" width="50" class="img-ava">
			</a>
			<div class="media-body">
				<div class="question-box">
					<div class="question-detail">
						<div class="question-name"><a href="<?php echo $creator_link?>"><?php echo $creator_username?></a> <span class="text-grey text-small margin-xs-left">~Web Programmer</span></div>
						<div class="question-text margin-xs-top">
							<a href="<?php echo site_url('discuss/q/'.$row->slug)?>"><?php echo $row->title?></a>
							<span class="question-date text-right text-grey"><i class="fa fa-clock-o"></i> asked <?php echo time_ago($row->created_at);?></span>
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
					</div>
					<div class="question-bottom margin-md-top text-right">
						<a href="#" class="link-primary"><?php echo $this->mdl_discuss->get_questions_views('id', $row->id);?> view</a> <span class="margin-sm-x">&bull;</span> 
						<a href="#" class="link-primary"><?php echo $this->mdl_discuss->get_answers('total', $row->id);?> answer</a> <span class="margin-sm-x">&bull;</span> 
						<a href="#" class="link-primary"><?php echo $this->mdl_vote->get_total_vote($row->id, 'discuss_question', 'summary');?> votes</a>
					</div>
				</div>
			
			</div>		
		</div>
	</div>
	<?php 
} 
?>
</div>

<div class="dev-pagination margin-xl-top text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>