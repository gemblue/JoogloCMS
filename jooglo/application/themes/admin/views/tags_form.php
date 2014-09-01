<?php 
# set form as show
$show_form = 'yes';

if($form_type == 'edit')
{
	if(empty($pg_query)){
		echo "<div class='alert alert-error'>Record's not found..</div>";
		$show_form = 'no';
	} 
	else
	{
		# get detail data
		foreach ($pg_query as $row) 
		{
			$name = $row->name;
			$slug = $row->slug;
			$id = $row->term_id;
		}
	}
}
?>


<?php if($show_form == 'yes'): ?>

	<form action="<?php echo ($form_type == 'new' ? site_url('control/tags_save') : site_url('control/tags_update')); ?>" method="post" id="form_new">

		<input type="hidden" name="id" value="<?php echo (isset($id) ? $id : ''); ?>" />
		
		<div class="label-input">Name</div>
		<div class="bottom-space4">
			<input type="text" name="name" value="<?php echo (isset($name) ? $name : ''); ?>" id="name" class="span6"/>
			<span id="name_error" class="left-space4 error">Name must be filled</span>
		</div>

		<div class="label-input">Slug</div>
		<div class="bottom-space4">
			<input type="text" name="slug" value="<?php echo (isset($slug) ? $slug : ''); ?>" id="slug" class="span6" />
			<span id="slug_error" class="left-space4 error">Slug must be filled</span>
		</div>
		
		<input type="button" id="tbl_save" value="Save" class="btn btn-la btn-space2"/>
		<input type="button" id="tbl_save_draft" value="Cancel" class="btn btn-space2" onClick="history.go(-1);"/> 

	</form>

<?php endif ?>



<script>
$(document).ready(function(){
		
	$("#tbl_save").click(function(){	
			
		var name = $("#name").val();
	
		var error = false;
			
		if (name.length == 0)
		{
			var error = true;
			$("#name_error").fadeIn(500);
		}
		else
		{
			$("#name_error").fadeOut(500);
		}
			
		if(error == false){
			$("#form_new").submit();
		}
	});
	
	/*js slug generator*/
	$("#name").keyup(function() {
			
		var data = $("#name").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('control/generate_slug');?>",
			data: "data=" + data,
			success: function(data)
			{
				$("#slug").val(data);	
			}
		});
			
	});
	/*tutup*/
		
});
</script>
<!--Tutup-->