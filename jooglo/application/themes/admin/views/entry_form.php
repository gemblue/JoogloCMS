<?php 
# set form as show
$show_form = 'yes';

# get the extra field information
$meta_info = $this->mdl_entries->get_meta_information($entry_type);

# generate the slug
$slug_auto = random_string('alnum', 10); 

if($form_type == 'edit')
{
	if (empty($pg_query))
	{
		echo "<div class='alert alert-error'>Record's not found..</div>";
		$show_form = 'no';
	} 
	else
	{
		# get detail data
		foreach ($pg_query as $row) 		
		{
			$entry_id = $row->id;
			$title = $row->title;
			$slug = $row->slug;
		}
	}
}
?>

<?php if($show_form == 'yes'): ?>	
	
	<form action="<?php echo site_url('cms/admin/update_entry'); ?>" method="post" id="form">
	
		<div class="label label-inverse">Main Field</div>		
		<br/><br/>		
		
		<input type="hidden" name="entry_id" id="entry_id" value="<?php echo (isset($entry_id) ? $entry_id : ''); ?>" />				
		<input type="hidden" name="entry_type" value="<?php echo (isset($entry_type) ? $entry_type : ''); ?>" id="entry_type" class="span6" >	
		<input type="hidden" name="form_type" value="<?php echo $form_type; ?>" >		
		<div class="label-input">Title</div>
		
		<div class="bottom-space4">
		<input type="text" name="title" value="<?php echo (isset($title) ? $title : ''); ?>" id="title" class="span6"/>
		</div>
		
		<!--
		<div class="label-input">Slug</div>
		<div class="bottom-space4">
		-->
		<input type="hidden" name="slug" value="<?php echo (isset($slug) ? $slug : $slug_auto); ?>" id="slug" class="span6" />
		<!--
		</div>
		-->		
		
		<div class="label label-inverse">Extra</div>
		<br/><br/>
				
		<?php 
		# control meta update by post type, every post type have different meta key
		if (!empty($meta_info))
		{
			foreach ($meta_info as $row => $value)
			{
				# get meta value
				if ($form_type == 'edit')
				{
					$meta_value = $this->mdl_entries->get_entry_meta($entry_id, $value['meta_key']);	
					?>
					<div class="label-input"><?php echo ucfirst(str_replace('_',' ',$value['meta_key']));?> <a href="<?php echo site_url('cms/admin/delete_field_entry/'.$value['meta_key'].'/'.$entry_id.'/'.$current_url_encode)?>">[x]</a></div>
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
		}
		else
		{
			echo '<div>No Extra Field</div><br/><br/>';
		}
		?>
		
		<div id="extra_field_dynamic">
		<!--content by ajax-->
		</div>
		
		<?php
		# add new field only for new post
		if($form_type != 'edit')
		{
			?>
			<input type="button" value="I Need More Field" id="btn_opn_new_field" class="btn btn-la btn-space2"/>
			<br/><br/>
			<div id="form_new_field">
			<div class="label-input">Field Name</div>
			<input type="text" id="field_name" class="span6" /><br/>
			<input type="button" value="Add New" id="btn_new_field" class="btn btn-la btn-space2"/>
			</div>
			<br/><br/>
			<?php
		}
		else
		{
			?>
			<div style="border:1px solid #dedede;padding:10px;">
			Need More field ?<br/>
			<input type="text" id="field_name" placeholder="Field Name" class="span6" /><br/>
			<input type="text" id="field_content" placeholder="Content" class="span6" /><br/>
			<input type="button" value="Add" class="btn btn-la btn-space2" id="btn-add-field"/>
			</div>
		
			<br/><br/>
			<?php
		}
		?>
		
		<input type="submit" value="Save" class="btn btn-la btn-space2"/>
		<input type="button" value="Cancel" class="btn btn-space2" onClick="history.go(-1);"/> 
		
	</form>

<?php endif ?>