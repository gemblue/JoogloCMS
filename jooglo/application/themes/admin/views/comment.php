<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
			
			<div class="row-fluid">
				<div class="span7">
					&nbsp;
				</div>
				<div class="span5">
					<form method="post" action="<?php echo site_url('cms/admin/comment_search'); ?>" enctype="application/x-www-form-urlencoded" class="form-search">
						<input type="text" name="inp_search" class="input-block-level search-query" placeholder="Search...">
						<input type="hidden" name="status" value="<?php echo $status;?>"/>
					</form>
				</div>
			</div>
			
			<div class="bottom-space3">
			<?php if(isset($search_result)): ?>
				Found <strong><?php echo $num_rows; ?></strong> records by keyword "<strong><?php echo $keyword; ?></strong>".
			<?php else: ?>
				<ul class="nav nav-pills">
					<li class="<?php echo ($this->uri->segment(4) == 'all' || $this->uri->segment(4) == '' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/comment/all'); ?>">All</a></li>
					<li class="<?php echo ($this->uri->segment(4) == 'publish' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/comment/publish'); ?>">Publish</a></li>
					<li class="<?php echo ($this->uri->segment(4) == 'draft' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/comment/draft'); ?>">Draft</a></li>
				</ul>
			<?php endif ?>
			</div>
			
			<?php if (empty($pg_query)): ?>
				<div class="alert alert-error">Record's not found..</div>
			<?php else: ?>
			
				<span class="pull-right">Total: <b><?php echo $num_rows; ?></b></span>
				<br/><br/>
				<table class="table table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th><input class="select-all" type="checkbox"/></th>
							<th>Comment</th>
						</tr>
					</thead>
				
					<tbody>
					<?php
					$i = 1;
					foreach ($pg_query as $row)
					{
						$link_det = site_url('cms/admin/page_edit/'.$row->object_id);
						$link_det_user = site_url('cms/admin/user_edit/'.$row->author);
						$link_delete = site_url('cms/admin/comment_action/'.$row->comment_id.'/delete/'.$current_url_encode);
						$link_approve = site_url('cms/admin/comment_action/'.$row->comment_id.'/approve/'.$current_url_encode);
						$link_unapprove = site_url('cms/admin/comment_action/'.$row->comment_id.'/unapprove/'.$current_url_encode);
						?>
						
						<tr>
						<td><input id="checkbox_<?php echo $i;?>" name="record[]" class="record" type="checkbox"  value="<?php echo $row->comment_id?>" /></td>
						<td>
						Author: <a href="<?php echo $link_det_user;?>" rel="nofollow"><?php echo $row->username;?></a><br/>
						To: <a href="<?php echo $link_det;?>" rel="nofollow"><?php echo $row->object_id?></a><br/><br/>
						
						<?php echo (!empty($keyword)) ? str_replace($keyword, '<b>'.$keyword.'</b>', $row->comment) : $row->comment;?>
						
						<br/><br/>
						<?php
						if ($row->status == 'draft')
						{
							?>
							<a href="<?php echo $link_delete;?>" onclick="return confirmDelete();" rel="nofollow">Delete</a> - <a href="<?php echo $link_approve;?>" rel="nofollow">Approve</a>
							<?php
						}
						else
						{
							?>
							<a href="<?php echo $link_delete;?>" onclick="return confirmDelete();" rel="nofollow">Delete</a> - <a href="<?php echo $link_unapprove;?>" rel="nofollow">Unapprove</a>
							<?php
						}
						?>
						</td>
						</tr>
						<?php
						$i++;
					}
					?>
			
					
					</tbody>
				</table>
				
				<div>
				Bulk:<br/><br/>
				<button action="delete-comment" class="btn-bulk btn btn-la">Delete</button>
				<button action="approve-comment" class="btn-bulk btn btn-la">Approve</button>
				<button action="unapprove-comment" class="btn-bulk btn btn-la">Unapprove</button>
				</div>
				
				<?php echo (!empty($pagination)) ? $pagination : ''; ?>
			
			<?php endif;?>
		</div>
	</div>
</div>

<!--Js link confirm yo-->
<script>
function confirmDelete()
{
    return confirm("Are you sure to delete this ?");
}
</script>
<!--tutup-->