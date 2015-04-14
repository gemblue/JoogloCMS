	<div class="content-admin">
		<div class="head-area heading-active">
			<span class="title"><?php echo $template['title']; ?></span>
			<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
		</div>
		
		<div class="body-area">
			<div class="body-area-padding">
	
				<div class="row-fluid">
					<div class="span7">
						<div class="bottom-space3"><a class="btn btn-la" href="<?php echo site_url('cms/admin/user_new'); ?>">New User</a></div>
					</div>
					<div class="span5">
						<form method="post" action="<?php echo site_url('cms/admin/user_search');?>" enctype="application/x-www-form-urlencoded" class="form-search">
							<input type="text" name="inp_search" class="input-block-level search-query" placeholder="Search User...">
						</form>
					</div>
				</div>
				
				<div class="bottom-space3">
				<?php if(isset($search_result)): ?>
					Found <strong><?php echo $num_rows; ?></strong> records by keyword "<strong><?php echo $keyword; ?></strong>".
				<?php else: ?>
					<ul class="nav nav-pills">
						<li class="<?php echo ($this->uri->segment(4) == 'all' || $this->uri->segment(4) == '' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/user/all'); ?>">All</a></li>
						<li class="<?php echo ($this->uri->segment(4) == 'active' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/user/active'); ?>">Activate</a></li>
						<li class="<?php echo ($this->uri->segment(4) == 'inactive' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/user/inactive'); ?>">Inactive</a></li>
					</ul>
					
					<span class="pull-right">Total: <b><?php echo $num_rows; ?></b></span>
					<br/><br/>
				<?php endif ?>
				</div>
		
		
				<?php if (empty($pg_query)): ?>
					
					<div class="alert alert-error">Record's not found..</div>

				<?php else: ?>
					
					<table class="table table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th><input class="select-all" type="checkbox"/></th>
							<th>Username</th>
							<th>Role</th>
							<th>Email</th>
							<th>Social Network</th>
							<th width="10%">Status</th>
						</tr>
					</thead>
					<tbody>
					
					<?php
					$i = 1;
					foreach ($pg_query as $row)
					{
						$link_edit = site_url('cms/admin/user_edit').'/'.$row->id;
						$link_permanent_delete = site_url('cms/admin/user_delete').'/'.$row->id;
						$link_disactive = site_url('cms/admin/user_disactive').'/'.$row->id.'/'.$current_url_encode;
						$link_activate = site_url('cms/admin/user_activate').'/'.$row->id.'/'.$current_url_encode;								
						?>
						
						<tr>
							<td><input id="checkbox_<?php echo $i;?>" name="record[]" class="record" type="checkbox"  value="<?php echo $row->id?>" /></td>
							<td>
							<?php echo (!empty($keyword)) ? str_replace($keyword, '<b>'.$keyword.'</b>', $row->username) : $row->username;?>
							
							
							<div class="t6"><a href="<?php echo $link_edit; ?>">Edit</a> <!--|<a href="<?php echo $link_permanent_delete; ?>">Permanent Delete</a>--> | 
							
							<?php
							if ($row->status == 'active')
							{ 
								echo '<a href="'.$link_disactive.'">Block</a></div>';
							} 
							else 
							{
								echo '<a href="'.$link_activate.'">Activate</a></div>';
							}	
							?>
							</td>
						
							<td>
							
							<?php 
							$role = $this->mdl_role->get_user_role($row->id);
							$role_name = $this->mdl_role->get_role_name($role);
							?>
						
							<?php echo $role_name;?>
							</td>
						
							<td><?php echo $row->email;?></td>
						
							<td>
							<?php 
							$sosnet['fb_id'] = $this->mdl_user->get_user_meta($row->id, 'fb_id');
							$sosnet['tw_id'] = $this->mdl_user->get_user_meta($row->id, 'tw_id');
							
							if (!empty($sosnet['fb_id']))
							{
								?>
								<span class="label label-success">fb :<?php echo $sosnet['fb_id']?></span>
								<?php
							}
							
							if (!empty($sosnet['tw_id']))
							{
								?>
								<span class="label label-success">tw :<?php echo $sosnet['tw_id']?></span>
								<?php
							}
							?>
							</td>
							
							<td><?php echo $row->status == 'active' ? '<span class="label label-success">'.$row->status.'</span>' : '<span class="label">'.$row->status.'</span>'.'</td>';?>
						</tr>
						<?php
						$i++;
					}
					?>
					
					</tbody>
					</table>
					
					<div>
					Bulk:<br/><br/>
					<button action="activate" class="btn-bulk btn btn-la">Activate</button>
					<button action="block" class="btn-bulk btn btn-la">Block</button>
					</div>
					
					<!-- Pagination -->
					<?php if(isset($pagination)){ ?>
						<?php echo $pagination; ?>
					<?php } ?>
					
		
				<?php endif ?>
				
				
			</div>
		</div>
	</div>

<!--Js link confirm yo-->
<script>
function confirmDelete()
{
    return confirm("Are you sure to delete this?");
}
</script>
<!--tutup-->