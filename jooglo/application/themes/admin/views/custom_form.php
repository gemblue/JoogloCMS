<?php 
# set form as show
$show_form = 'yes';

# get the extra field information
$meta_info = $this->mdl_custom_post->get_meta_information($post_type);
$slug_auto = random_string('alnum', 7); 

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
			$custom_id = $row->id;
			$title = $row->title;
			$slug = $row->slug;
		}
	}
}
?>

<?php if($show_form == 'yes'): ?>	
	
	<form action="<?php echo site_url('control/update_custom'); ?>" method="post" id="form">
	
		<div class="label label-inverse">Main Field</div>		
		<br/><br/>		
		
		<input type="hidden" name="custom_id" value="<?php echo (isset($custom_id) ? $custom_id : ''); ?>" />				
		<input type="hidden" name="post_type" value="<?php echo (isset($post_type) ? $post_type : ''); ?>" id="post_type" class="span6" >	
		<input type="hidden" name="form_type" value="<?php echo $form_type; ?>" >		
		<div class="label-input">Title</div>
		
		<div class="bottom-space4">
		<input type="text" name="title" value="<?php echo (isset($title) ? $title : ''); ?>" id="title" class="span6"/>
		</div>
		
		<input type="hidden" name="slug" value="<?php echo (isset($slug) ? $slug : $slug_auto); ?>" id="slug" class="span6" />
					
		<div class="label label-inverse">Extra</div>
		<br/><br/>
				
		<?php 
		# control meta update by post type, every post type have different meta key
		if (!empty($meta_info))
		{
			foreach ($meta_info as $row => $value)
			{
				# get meta value
				if($form_type == 'edit')
				{
					$meta_value = $this->mdl_custom_post->get_post_meta($custom_id, $value['meta_key']);	
				}
				else
				{
					$meta_value = null;
				}
				
				?>
				<div class="label-input"><?php echo ucfirst(str_replace('_',' ',$value['meta_key']));?></div>
				<div class="bottom-space4">
				<input type="text" name="<?php echo $value['meta_key'];?>" value="<?php echo (isset($meta_value) ? $meta_value : ''); ?>" class="span6" />
				</div>
				<?php
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
			<input type="button" value="I Need More Field" id="btn_opn_new_field" class="btn btn-primary btn-space2"/>
			<br/><br/>
			<div id="form_new_field">
			<div class="label-input">Field Name</div>
			<input type="text" id="field_name" class="span6" /><br/>
			<input type="button" value="Add New" id="btn_new_field" class="btn btn-primary btn-space2"/>
			</div>
			<br/><br/>
			<?php
		}
		?>
		
		<input type="submit" value="Save" class="btn btn-primary btn-space2"/>
		<input type="button" value="Cancel" class="btn btn-space2" onClick="history.go(-1);"/> 

	</form>

<?php endif ?>
<!--Tutup-->

<script>
$(document).ready(function(){
	
	$('#form_new_field').hide();
	
	$('#btn_opn_new_field').click(function(){
		$('#form_new_field').show();
	});
	
	$('#btn_new_field').click(function(){
		var field_name = $('#field_name').val();
		var field_name_key = field_name.replace(' ','_');
		var field_name_key_lower = field_name_key.toLowerCase();
	
		$('#extra_field_dynamic').append('<div class=label-input>' + field_name +' </div><div class=bottom-space4><input type=text name=' + field_name_key_lower + ' class=span6 /></div>')
		$('#form_new_field').hide();
	});
	
});
</script>