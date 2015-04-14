<h2><?php echo ucfirst($social);?></h2>

<?php echo $content;?><br/><br/>
<input type="hidden" id="social" name="social" value="<?php echo $social;?>">
<input type="hidden" id="content" name="content" value="<?php echo $content;?>">
<input type="hidden" id="url" name="url" value="<?php echo $url;?>">
<input type="hidden" id="id_post" value="<?php echo $id_post;?>">
<input type="hidden" id="_url" value="<?php echo site_url('module/nyansocial/sharer/share');?>">
<input type="button" id="submit" value="Share">
<br/>
<div id="output"></div>


<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	$('#submit').click(function(){
	
		var _url = $('#_url').val();
		var url = $('#url').val();
		var id_post = $('#id_post').val();
		var social = $('#social').val();
		var content = $('#content').val();
		
		$('#output').html('processing..');
		
		$.ajax({
			type: "post",
			url: _url,
			data: "social=" + social + "&content=" + content + "&url=" + url + "&id_post=" + id_post,
			success: function(data)
			{
				if (data == 'ok')
				{
					$('#output').html('<b>Success shared!!</b>');
					location.reload();
				}
				else
				{
					$('#output').html(data);
					location.reload();
				}
			}
		});
			
	});
		
})
</script>
