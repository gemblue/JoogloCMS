	<div class="content-admin">
		<div class="head-area heading-active">
			<span class="title"><?php echo $template['title']; ?></span>
			<span class="pull-right"><a href="#" class="link-chevron"><i class="fa fa-chevron-up"></i></a></span>
		</div>
		
		<div class="body-area">
			<div class="body-area-padding">
	
				<div class="row-fluid">
					<div class="span7">
						<div class="bottom-space3"><a class="btn btn-primary" href="<?php echo site_url('control/new_custom/'.$post_type); ?>">New <?php echo ucfirst($post_type);?></a></div>
					</div>
					<div class="span5">
						<!--
						<form method="post" action="<?php echo site_url('control/user/user_search');?>" enctype="application/x-www-form-urlencoded" class="form-search">
							<input type="text" name="inp_search" class="input-block-level search-query" placeholder="Search User...">
						</form>
						-->
					</div>
				</div>
		
		
				<?php if (empty($pg_query)): ?>
					
					<div class="alert alert-error">Record's not found..</div>

				<?php else: ?>
					
					<table class="table table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th>Title</th>
							<th>Slug</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					<?php
					foreach ($pg_query as $row)
					{
						$link_edit = site_url('control/edit_custom/'.$post_type.'/'.$row->id);
						$link_delete =  site_url('control/delete_custom/'.$post_type.'/'.$row->id);
						
						echo '<tr>';
						echo '<td>'.$row->title.'</td>';
						echo '<td>'.$row->slug.'</td>';
						echo '<td><a href="'.$link_edit.'">Edit</a> | <a href="'.$link_delete.'">Delete</a></td>';
						echo '</tr>';
					}
					?>
					
					</tbody>
					</table>
					
					<!-- Pagination -->
					<?php if(isset($pagination)){ ?>
						<?php echo $pagination; ?>
					<?php } ?>
					
		
				<?php endif ?>
				
				
			</div>
		</div>
	</div>