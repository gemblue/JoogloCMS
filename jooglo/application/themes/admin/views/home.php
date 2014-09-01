<div class="bottom-space2">	
	<div class="row-fluid">		
	
		<?php
		$i = 1;
		
		$menu[1] = 'post';
		$menu[2] = 'page';
		
		$total_menu = 2;
		
		while ($i <= $total_menu)
		{
			?>
			<div class="span2">			
				<a href="<?php echo site_url('cms/admin/post_new/'.$menu[$i])?>">		
					<div class="btn-dashhome">				
					<div class="inner-btn-num"><?php echo $this->mdl_post->get_post($menu[$i], 'total', 'publish');?></div>	
					<div class="inner-btn-icon"><i class="icon-pencil"></i></div>				
					<div class="inner-btn-label">New <?php echo $menu[$i]?></div>			
					</div>			
				</a>		
			</div>		
			<?php
			$i++;
		}
		?>
		
	</div>
</div>

<div class="content-admin">	

	<div class="head-area heading-active">		
		<span class="title">NEW USER</span>		
		<span class="pull-right">
		<a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>	
	</div>	

	<div class="body-area">		
	
		<div class="">
		
		<?php 
		$pg_query = $this->mdl_user->get_list_user('all', 5, $this->uri->segment(4)); 
		?>
		
		<?php if (empty($pg_query)): ?>								
			<div class="alert alert-error">Record's not found..</div>			
		<?php else: ?>								

			<table class="table table-condensed table-striped">				
			<thead><tr><th>Username</th><th>Role</th><th>Email</th><th width="10%">Status</th></tr></thead>				

			<tbody>
			<?php
			foreach ($pg_query as $row)
			{
				$link_edit = site_url('cms/admin/user_edit').'/'.$row->id;
				$link_permanent_delete = site_url('cms/admin/user_delete').'/'.$row->id;
				$link_disactive = site_url('cms/admin/user_disactive').'/'.$row->id.'/'.$current_url_encode;
				$link_activate = site_url('cms/admin/user_activate').'/'.$row->id.'/'.$current_url_encode;								
				?>
						
				<tr>
				<td>
				<?php echo $row->username;?>
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
				<td><?php echo $row->status == 'active' ? '<span class="label label-success">'.$row->status.'</span>' : '<span class="label">'.$row->status.'</span>'.'</td>';?>
				</tr>
				<?php
			}
			?>
			</tbody>
			</table>
		
		<?php endif ?>				
		</div>
	
	</div>

</div>