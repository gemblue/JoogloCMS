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
	
	<form action="<?php echo site_url('control/update_entry'); ?>" method="post" id="form">
	
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
					<div class="label-input"><?php echo ucfirst(str_replace('_',' ',$value['meta_key']));?> <a href="<?php echo site_url('control/delete_field_custom/'.$value['meta_key'].'/'.$entry_id.'/'.$current_url_encode)?>">[x]</a></div>
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

<!--Js Editor-->
<script type="text/javascript" src="<?php echo site_url('application/views/admin/assets/js/tinymce/tinymce.min.js');?>"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/plugin/datetime-picker/jquery-ui-timepicker-addon.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/plugin/jquery-validation/jquery.validate.min.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo base_url().'assets/plugin/datetime-picker/jquery-ui-timepicker-addon.css'; ?>" />
<input type="hidden" id="base_url" value="<?php echo base_url()?>">

<!-- NotifIt -->
<link rel="stylesheet" href="<?php echo base_url('plugins/notifIt/notifIt.css'); ?>" />
<script type="text/javascript" src="<?php echo base_url('plugins/notifIt/notifIt.js'); ?>"></script>

<!--Js General-->
<script>
$(document).ready(function(){
	
	// Init
	$('#form_new_field').hide();
	var base_url = $('#base_url').val();
	
	// Tiny MCE setting
	tinymce.init({
		selector: "textarea",
		theme: "modern",
							
		height: 100,
							
		plugins: [
		"advlist autolink link image lists charmap print preview hr anchor pagebreak",
		"searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
		"table contextmenu directionality textcolor paste textcolor"
		],

		toolbar1: "bullist numlist bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | subscript superscript | forecolor backcolor | styleselect formatselect fontsizeselect",
		
		image_advtab: true,
		menubar: false,
		toolbar_items_size: 'small',
		relative_urls: false,
		remove_script_host: false,
		document_base_url: base_url,
		pagebreak_separator: '<!-- nextpage -->',
		filemanager_title:"IMAGES MANAGER" ,
		external_filemanager_path: base_url + 'assets/plugin/rte-273872/filemanager/',
	});
	
	// Open new field
	$('#btn_opn_new_field').click(function(){
		$('#form_new_field').show();
	});
	
	// New field trigger
	$('#btn_new_field').click(function(){
		var field_name = $('#field_name').val();
		var field_name_key = field_name.replace(' ','_');
		var field_name_key_lower = field_name_key.toLowerCase();
	
		$('#extra_field_dynamic').append('<div class=label-input>' + field_name +' </div><div class=bottom-space4><input type=text name=' + field_name_key_lower + ' class=span6 /></div>')
		$('#form_new_field').hide();
	});
	
	// Add new entries field
	$('#btn-add-field').click(function(){
	
		var field_name = $('#field_name').val(); 
		var field_content = $('#field_content').val(); 
		var entries_id = $('#entry_id').val();
		
		notif({
		  msg: "Creating new field..",
		  position: "left",
		  bgcolor: "#7B0202",
		  color: "#fff",
		  opacity: 0.8,
		  autohide: false
		});
		
		$.post( base_url + 'control/add_field_custom', { data_post: entries_id + '|' + field_name + '|' + field_content })
		.done(function( data ) {
			window.location.reload(false);
		});
		
		return false;
	});
	
});
</script>