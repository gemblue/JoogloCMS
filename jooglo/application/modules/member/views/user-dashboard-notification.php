<h2 class="h4 margin-md-bottom">Notifikasi</h2>

<a href="#" class="btn-notif-read-all link-primary">Mark all as read.</a>

<style>
.notif_not_read{
background:#dedede;
}
</style>

<?php
$total = $this->notification_m->get_user_notification($this->session->userdata('id'), 'all', 'total');
$pagination_link = pagination_link('u/dashboard/notification/', $total, 4, $page_number);
$notification = $this->notification_m->get_user_notification($this->session->userdata('id'), 'all', 'array', $pagination_link['limit'], $pagination_link['offset']);

foreach ($notification as $row)
{ 
	$by_username = $this->mdl_user->get_username($row->by_user, 'id');
	$by_user_link = site_url('~'.$by_username);
		
	if (!empty($row->object_item))
	{	
		$question_title = $this->mdl_discuss->get_questions_title('id', $row->object_item);
		$question_link = site_url('discuss/q/'.$this->mdl_discuss->get_questions_slug('id', $row->object_item));
	}
	
	if ($row->has_read == 0)
	{
		$class = 'notif_not_read';
		$word = 'Mark as read . ';
	}
	else
	{
		$class = '';
		$word = '';
	}
	
	// Subscribe detection
	$is_subscribed = $this->subscribe_m->is_subscribed($row->object_item, $this->session->userdata('id'), 'discuss_question');
	
	?>
	
	<div class="notif-<?php echo $row->id?> notif user-question-item padding-sm-y margin-xs-top <?php echo $class?>">
		<span class="margin-xs-right"><a href="<?php echo $by_user_link?>"><?php echo $by_username?></a> <?php echo $row->activity?> <?php echo (!empty($row->object_item)) ? '<a href="'.$question_link.'">'.$question_title.'</a>' : '';?></span>
		<br/>
		<small>
		<a href="#" notif_id="<?php echo $row->id?>" class="btn-notif-read link-primary"><?php echo $word?></a>
		<a href="#" actions="<?php echo ($is_subscribed ==  true) ? 'unsubscribe' : 'subscribe';?>" notif_id="<?php echo $row->id?>" object_id="<?php echo $row->object_item?>" info="discuss_question" class="btn-subscribe btn-subscribe-<?php echo $row->id?> link-primary"><?php echo ($is_subscribed ==  true) ? 'Unsubscribe' : 'Subscribe';?></a>
		</small>
		<span class="margin-xs-left question-date text-right text-grey text-small"><i class="fa fa-clock-o"></i> <?php echo (strtotime($row->updated_at))? time_ago($row->updated_at): time_ago($row->created_at) ?></span>
	</div>
	<?php } ?>

<div class="dev-pagination margin-xl-top text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>