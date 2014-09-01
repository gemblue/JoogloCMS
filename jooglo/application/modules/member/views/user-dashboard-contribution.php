<h2 class="h4 margin-lg-bottom">Kontribusi yang telah kamu berikan</h2>

<?php

// Get total
$param = array(
	'result' => 'total',
	'user_id' => $this->session->userdata('id')
);

$total = $this->activities_m->get_activities_devo($param);

// Setting paging
$pagination_link = pagination_link('u/dashboard/contribution/', $total, 4, $page_number);

// Get data
$param_two = array(
	'result' => 'array',
	'user_id' => $this->session->userdata('id'),
	'limit' => $pagination_link['limit'],
	'limit_order' => $pagination_link['offset']
);

$contrib = $this->activities_m->get_activities_devo($param_two);

foreach ($contrib as $row)
{
	if ($row->action == 'add_answer')
	{
		$row->action = 'Menjawab pertanyaan di';
	}
	
	if ($row->action == 'vote')
	{
		$row->action = 'Melakukan vote di';
	}
	
	if ($row->action == 'add_question')
	{
		$row->action = 'Membuat pertanyaan baru berjudul';
	}
	?>
	<div class="user-question-item bg-white padding-sm">
	<span class="margin-xs-right"><?php echo $row->action?> <a href="<?php echo site_url('discuss/q/'.$row->slug)?>"><?php echo $row->title?></a>.</span>
	<span class="question-date text-right text-grey text-small"><i class="fa fa-clock-o"></i> 5m ago</span>
	</div>
	<?php
}	
?>

<div class="dev-pagination margin-md-top margin-md-bottom text-center">
	<ul class="paging-list">
		<?php echo $pagination_link['link']; ?>
	</ul>
</div>