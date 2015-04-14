<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
		
			<?php	
			if ($type_form_post == 'edit')
			{
				// Get post data
				foreach ($pg_query as $row)
				{
					$post_id = $row->ID;
					$post_title = $row->post_title;
					$post_author_username = $row->username;
					$post_date = $row->post_date_gmt;
					$slug = $row->post_slug;
					$post_content = $row->post_content;
					$post_status = $row->post_status;
					$post_type = $row->post_type;
				}
					
				// Get the meta data
				$metadesc = $this->mdl_post->get_post_meta($post_id, 'meta_description');
				$metakey = $this->mdl_post->get_post_meta($post_id, 'meta_keyword');
				$featured_image = $this->mdl_post->get_post_thumbnail($post_id);	
					
				// Get the post categories
				$category = $this->mdl_taxonomy->get_post_category($post_id);
				$cat_id = $category['id'];
				$cat_name = $category['name'];
					
				// Get the tags
				$tags = null;
				$tags_array = $this->mdl_taxonomy->get_post_tags($post_id);
				
				foreach ($tags_array as $row)
				{
					$tags .= $row->name.', ';
				}
			}
			?>
				
			<?php if ($type_form_post == 'edit'):?>
				Created : <b><?php echo $post_date.' ('.time_ago($post_date).')</b> by <b>'.$post_author_username.'</b><br/>';?>
			
				<?php if ($post_status == 'draft'):?>
					<div class="top-space4 bottom-space2"><a href="<?php echo site_url('cms/admin/set_publish/'.$post_id.'/'.$current_url_encode);?>" class="btn btn-la">Publish Now</a><a href="<?php echo site_url($slug)?>" class="btn btn-space3" target="_blank">View</a></div>
				<?php else:?>
					<div class="top-space4 bottom-space2"><a href="<?php echo site_url('cms/admin/set_draft/'.$post_id.'/'.$current_url_encode);?>" class="btn btn-la">Make Draft</a><a href="<?php echo site_url($slug)?>" class="btn btn-space3" target="_blank">View</a></div>
				<?php endif;?>
				
			<?php endif;?>
			
			<form id="post-form" method="post" action="<?php echo ($type_form_post == 'new' ? site_url('cms/admin/post_create') : site_url('cms/admin/post_update'));?>" enctype="multipart/form-data">
					
				<input type="hidden" name="post_type" value="<?php echo (isset($post_type) ? $post_type : ''); ?>">
				<input type="hidden" name="post_id" value="<?php echo (isset($post_id) ? $post_id : ''); ?>"/>
				<input type="hidden" name="url_source" value="<?php echo current_url() ?>"/>
					
				<div class="label-input">Title</div>
				<div class="bottom-space4">
					<input type="text" name="post_title" value="<?php echo (isset($post_title) ? $post_title : ''); ?>" class="span6 title" placeholder="The great title here"/> 
				</div>
					
				<div class="label-input">Slug (Seo Friendly Url)</div>
				<div class="bottom-space4">
					<input type="text" name="slug" value="<?php echo (isset($slug) ? $slug : ''); ?>" class="span6 slug"  placeholder="Ex : more-article-please"/>
					<br/>
					<small>You can edit with your own</small>
				</div>
					
				<div class="label-input">Date posted</div>
				<div class="bottom-space4">
					<input type="text" name="post_date" value="<?php echo (isset($post_date) ? $post_date : '');?>" id="date_posted" />
				</div>
					
				<?php if (isset($post_type) && $post_type != 'page'):?>
					
					<div class="label-input">Category</div>
					<div class="bottom-space4">
					<?php 
					if($type_form_post == 'edit')
					{
						# if edit mode on, show cate based on current post type
						if ($post_type == 'post') {
								$category_taxon = 'category';
						} else {
							$category_taxon = $post_type.'_category';
						}
							
						# the query
						$cat_query = $this->mdl_taxonomy->get_all_terms($category_taxon); 
						
						# show
						echo '<select name="cat_id" id="cat_id" class="input">';
							
						foreach ($cat_query as $row)
						{
							echo '<option value='.$row->term_id.' '.($cat_id == $row->term_id ? 'selected="selected"' : '').'>'.$row->name.'</option>';
						}
						echo '</select><br/>';
					}
					else
					{
						# send category post data
						if ($post_type == 'post') {
							$category_taxon = 'category';
						} else {
							$category_taxon = $post_type.'_category';
						}
						
						# the query
						$cat_query = $this->mdl_taxonomy->get_all_terms($category_taxon); 
			
						# show
						echo '<select name="cat" class="input">';
			
						if (empty($cat_query))
						{
							echo '<option value="" selected="selected">Empty</option>';		
						} 
						else 
						{
							foreach ($cat_query as $row)
							{
								echo '<option value='.$row->term_id.'>'.$row->name.'</option>';
							}
						}
			
						echo '</select>';
					}
					?>
						
					<input type="hidden" value="<?php if(isset($cat_id)){echo $cat_id;}?>" name="current_cat_id" />
						
					</div>
				<?php endif ?>
					
				<div class="label-input">Featured Image</div>
				<div class="bottom-space4">
					<div class="post-thumb">
						<div class="form-inline">
							<input type="text" id="featured_image" name="featured_image" value="<?php echo (!empty($featured_image)) ? $featured_image : ''?>" class="span6"  placeholder="Insert an image link or choose from media"/> <a href="<?php echo base_url('jooglo/plugins/filemanager/dialog.php?type=1&field_id=featured_image'); ?>" class="btn btn-iframe" type="button"><i class="icon-folder-open"></i></a>
						</div>
					</div>
					
					<?php if (isset($featured_image)):?>
						<?php if (site_url() == $featured_image) :?>
							<br/>Featured image is not setted.
						<?php else:?>
							<br/><img width="300" src="<?php echo $featured_image;?>" /><br/><br/>
						<?php endif;?>
					<?php endif;?>
				</div>
					
				<div class="bottom-space3">
					<textarea name="post_content" class="input-block-level rte" rows="9" style="height:400px;"><?php echo (isset($post_content) ? $post_content : '');?></textarea>
				</div>
				
				<!-- Meta field / Custom field -->
				<?php
				foreach ($meta_information as $row => $value)
				{
					# get meta value
					if ($type_form_post == 'edit')
					{
						$meta_value = $this->mdl_post->get_post_meta($post_id, $value['meta_key']);	
						?>
						<div class="label-input"><?php echo ucfirst(str_replace('_',' ',$value['meta_key']));?> <a href="<?php echo site_url('cms/admin/delete_field_entry/'.$value['meta_key'].'/'.$post_id.'/'.$current_url_encode)?>">[x]</a></div>
						<div class="bottom-space4">
						<input type="text" name="<?php echo $value['meta_key'];?>" value="<?php echo (isset($meta_value) ? $meta_value : ''); ?>" class="span6" />
						</div>
						<?php
					}
					else
					{
						$meta_value = null;
						?>
						<div class="label-input"><?php echo ucfirst(str_replace('_',' ',$value['meta_key']));?></div>
						<div class="bottom-space4">
						<input type="text" name="<?php echo $value['meta_key'];?>" value="<?php echo (isset($meta_value) ? $meta_value : ''); ?>" class="span6" />
						</div>
						<?php
					}
				}
				?>
					
				<div class="label-input">Tags</div>
				<div class="bottom-space4">
					<div><input type="text" name="tags" value="<?php echo (isset($tags) ? $tags : '');?>" class="span6"  placeholder="Ex: This, Is, Tag"/></div>
				</div>

				<!--Page need post template--> 
				<?php if (isset($post_type) && $post_type == 'page') :?>
				<div class="label-input">Page Template</div>
				<div class="bottom-space4">
					<select name="post_template" class="input">
						<option value="default">Default</option>
						<?php
						$post_template = $this->mdl_post->get_post_meta($post_id, 'post_template');
						
						foreach ($custom_page_template as $row)
						{
							?>
							<option value="<?php echo $row; ?>" <?php echo ($post_template == $row ? 'selected="selected"' : ''); ?> ><?php echo $row; ?></option>
							<?php
						}
						?>
					</select>
					<?php echo $post_template;?>
				</div>
				<?php endif?>
				<!--Close-->
					
				<?php if($type_form_post == 'new'): ?>
				<div class="label-input">Posting Status</div>
				<div class="bottom-space4">
					<select name="post_status" class="input">
						<option value="publish" selected="selected">Published</option>
						<option value="draft">Draft</option>
					</select>
				</div>
				<?php endif ?>
				
				<div id="extra_field_dynamic">
				<!-- Content by Js -->
				</div>
				
				<!-- Custom Field -->
				<input type="button" value="I Need More Field" id="btn_opn_new_field" class="btn btn-la btn-space2"/>
				<br/><br/>
				<div id="form_new_field">
				<div class="label-input">Field Name</div>
				<input type="text" id="field_name" class="span6" /><br/>
				<input type="button" value="Add New" id="btn_new_field" class="btn btn-la btn-space2"/>
				</div>
				<br/><br/>
				
				<button type="submit" class="btn btn-la btn-large btn-space1">Save</button>
					
				<?php if (!isset($post_status)): ?>
					<button type="button" class="btn btn-la btn-large btn-space1 btn-live-preview">Live Preview</button>
				<?php endif;?>
			</form>
		</div>
	</div>
</div>