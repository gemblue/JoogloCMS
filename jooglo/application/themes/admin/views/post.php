	<div class="content-admin">
		<div class="head-area heading-active">
			<span class="title"><?php echo $title_page; ?></span>
			<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
		</div>
		
		<div class="body-area">
			<div class="body-area-padding">
				
				<div class="row-fluid">
					<div class="span7">
						<?php if ($this->uri->segment(3) != 'post_trash'): ?>
							<div class="bottom-space3"><a class="btn btn-la" href="<?php echo site_url('cms/admin/post_new/'.$post_type); ?>">New <?php echo str_replace('_',' ',ucfirst($post_type));?></a></div>
						<?php endif ?>
					</div>
					
					<div class="span5">
						<form id="form-search" method="post" action="<?php echo site_url('cms/admin/post_search'); ?>" enctype="application/x-www-form-urlencoded" class="form-search">
							<input type="text" name="inp_search" class="input-block-level search-query" placeholder="Search...">
							<input type="hidden" name="status" value="<?php echo $status; ?>"/>
							<input type="hidden" name="post_type" value="<?php echo $post_type; ?>"/>
						</form>
					</div>
				</div>
				
				<div class="bottom-space3">
					<?php if ($this->uri->segment(3) != 'post_trash'): ?>
						<ul class="nav nav-pills">
							<li class="<?php echo ($this->uri->segment(4) == 'all' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/post/all/'.$post_type);?>">All</a></li>
							<li class="<?php echo ($this->uri->segment(4) == 'publish' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/post/publish/'.$post_type);?>">Publish</a></li>
							<li class="<?php echo ($this->uri->segment(4) == 'draft' ? 'active' : ''); ?>"><a href="<?php echo site_url('cms/admin/post/draft/'.$post_type); ?>">Draft</a></li>
						</ul>
					<?php endif ?>
					
					<?php if(!empty($num_rows)) :?>
					<span class="pull-right">Total: <b><?php echo $num_rows; ?></b></span>
					<br/>
					<?php endif;?>
					
				</div>
				
				<?php if (empty($pg_query)): ?>
					
					<div class="alert alert-error">Record's not found..</div>

				<?php else: ?>

					<table class="table table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th><input class="select-all" type="checkbox"/></th>
							<th>Title</th>
							<?php if ($post_type != 'page'):?>
							<th width="20%">Category</th>
							<?php endif?>
							<th width="15%">Date</th>
							<th width="10%">Status</th>
							<th width="15%">Type</th>
						</tr>
					</thead>
					<tbody>
					
					<?php 
					$i = 1;
					foreach ($pg_query as $row) 
					{ 
						$link_trash = site_url('cms/admin/set_trash/'.$row->ID.'/'.$current_url_encode);
						$link_restore = site_url('cms/admin/set_restore/'.$row->ID.'/'.$current_url_encode);
						$link_delete = site_url('cms/admin/set_delete/'.$row->ID.'/'.$current_url_encode); 
						$link_enab = site_url('cms/admin/enable_comm').'/'.$row->ID.'/'.$current_url_encode;
						$link_disa = site_url('cms/admin/disable_comm').'/'.$row->ID.'/'.$current_url_encode;
						$link_edit = site_url('cms/admin/post_edit').'/'.$row->ID;
						$link_det_user = site_url('cms/admin/user_edit').'/'.$row->ID;
						
						# get the post type
						$current_post_type = $this->mdl_post->get_post_type('id', $row->ID);
						?>
			
						<tr>
							<td><input id="checkbox_<?php echo $i;?>" name="record[]" class="record" type="checkbox"  value="<?php echo $row->ID?>" /></td>
							<td>
								<div><?php echo $row->post_title; ?></div>
								<?php if($row->post_status == 'trash'): ?>
									<div class="t6"><a href="<?php echo $link_restore; ?>">Restore</a> | <a href="<?php echo $link_delete; ?>">Delete</a></div>
								<?php else: ?>
									<div class="t6"><a href="<?php echo $link_edit; ?>">Edit</a> | <a href="<?php echo $link_trash; ?>">Trash</a> | <a href="<?php echo base_url($row->post_slug); ?>" target="_blank">View</a></div>		
									<br/>									
									<div></div>									
								<?php endif ?>
								
							</td>
							
							<?php if ($post_type != 'page'):?>
							<td>
								<?php 
								$category = $this->mdl_taxonomy->get_post_category($row->ID); 
								echo $category['name'];
								?>
							</td>
							<?php endif?>
							
							<td><div class="t6"><?php echo time_ago($row->post_date_gmt); ?></div></td>
							<td>
								<div>
									<?php 
									
									if ($row->post_status == 'publish') {
										echo '<span class="label label-success">'.ucfirst($row->post_status).'</span>';
									} elseif ($row->post_status == 'draft'){
										echo '<span class="label label-warning">'.ucfirst($row->post_status).'</span>';
									} else {
										echo '<span class="label">'.ucfirst($row->post_status).'</span>';
									}
									
									?>
								</div>
							</td>
							<td><?php echo ucfirst(str_replace('_',' ',$row->post_type));?></td>
						</tr>
						
						<?php 
						$i++;
					} 
					?>
					
					</tbody>
					</table>
					
					<?php if ($this->uri->segment(3) == 'post_trash'):?>
						<div>
						Bulk:<br/><br/>
						<button action="restore" class="btn-bulk btn btn-la">Restore</button>
						<button action="delete_permanent" class="btn-bulk btn btn-la">Delete Permanent</button>
						</div>
					<?php else:?>
						<div>
						Bulk:<br/><br/>
						<button action="trash" class="btn-bulk btn btn-la">Trash</button>
						<button action="publish" class="btn-bulk btn btn-la">Publish</button>
						<button action="draft" class="btn-bulk btn btn-la">Set Draft</button>
						</div>
					<?php endif;?>
					
					
					
					<!-- Pagination -->
					<?php if(isset($pagination)){ ?>
						<?php echo $pagination; ?>
					<?php } ?>
				
				<?php endif ?>
				
			</div>
		</div>
	</div>