<div class="content-admin">	
	<div class="head-area heading-active">		
		<span class="title"><?php echo $title_page; ?></span>		
		<span class="pull-right"><a href="#" class="link-chevron">
		<i class="icon-chevron-up"></i></a></span>		
	</div>	
	
	<div class="body-area">		
		<div class="body-area-padding">			
		<div class="bottom-space3">			
			<?php if($page_list == 'tags'): ?>				
			<a class="btn btn-la" href="<?php echo site_url('cms/admin/tags_new'); ?>">New Tag</a>			
			<?php else: ?>				
			<a class="btn btn-la" href="<?php echo site_url('cms/admin/category_new/'.$post_type); ?>">New <?php echo ucfirst($post_type);?> Category</a>			
			<?php endif ?>			
		</div>	
		
		<div class="bottom-space3" align="right">				
		Total: <?php echo $total;?>			
		</div>						
		
		<?php if (empty($pg_query)): ?>								
		<div class="alert alert-error">Record's not found..</div>							
		<?php else: ?>				
		<table class="table table-condensed table-striped table-bordered">				
			<thead>					
				<tr>						
					<th>Name</th><th>Slug</th><th width="12%">Action</th>
				</tr>				
			</thead>				
			<tbody>					
				<?php 
				foreach ($pg_query as $row)					
				{						
					if ($page_list == 'tags')						
					{							
						$link_edit = site_url('cms/admin/tags_edit/'.$row->term_id);  							
						$link_delete = site_url('cms/admin/tags_delete').'/'.$row->term_id;						
					}		
					else						
					{			
						$link_edit = site_url('cms/admin/category_edit/'.$row->term_id);  					
						$link_delete = site_url('cms/admin/category_delete/'.$row->term_id.'/'.$current_url_encode);				
					}												
					echo '<tr>';						
					echo '<td>'.$row->name.'</td>';		
					echo '<td>'.$row->slug.'</td>';						
					echo '<td><a href="'.$link_edit.'" rel="nofollow">Edit</a> - <a href="'.$link_delete.'" onclick="return confirmDelete();" rel="nofollow">Delete</a></td>';					
					echo '</tr>';				
				}					
				?>									
			</tbody>				
		</table>						
		
		<!-- Pagination -->				
		<?php if(isset($pagination)) { ?>					
		<?php echo $pagination; ?>				
		<?php } ?>						
		<?php endif ?>					
		</div>	
	</div>
</div>

<script> 
function confirmDelete(){	
	return confirm("Are you sure to delete this ?");
}
</script>