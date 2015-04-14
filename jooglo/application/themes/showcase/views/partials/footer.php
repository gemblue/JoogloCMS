<div class="footer text-center">
Copyright © 2014 Nyankod - All rights reserved
</div>

<!-- Modal Submit Product -->
<div class="modal fade" id="submit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Submit Product</h4>
			</div>
			<div class="modal-body">
				
				<div class="connect-options text-center">
					Sebelum submit product terbaik Anda, Silahkan login terlebih dahulu.<br/><br/>
					<a href="<?php echo site_url('nyansocial/auth_twitter/connect')?>" class="btn btn-default btn-twitter" role="button">Twitter Connect <span class="glyphicon glyphicon-share-alt"></span></a>
					<a href="<?php echo site_url('nyansocial/auth_facebook/connect')?>" class="btn btn-default btn-facebook" role="button">Facebook Connect <span class="glyphicon glyphicon-share-alt"></span></a>
				</div>
				
				<form role="form" id="submit-product" class="submit-product">
					<div class="form-group">
						<label>Product Name</label>
						<input type="email" class="form-control" name="product_name" id="product_name" placeholder="Ex: Smadav">
					</div>
					<div class="form-group">
						<label>Categories</label>
						<select name="categories" id="categories" class="form-control">
							<?php
							$categories = $this->jooglo->get_terms('product_category');
							foreach ($categories as $row)
							{
								?>
								<option value="<?php echo $row->term_id?>"><?php echo $row->name?></option>
								<?php
							}
							?>
						</select>
						
						<p class="help-block"><a href="#" data-toggle="modal" data-target="#suggest-modal">Suggest category?</a></p>
						
					</div>
					<div class="form-group">
						<label>Developer</label>
						<input type="text" class="form-control" name="developer" id="developer" placeholder="Write your name or your team/studio">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" class="form-control" name="email" id="email" placeholder="Masukan email Anda">
						<div class="margin-md-top alert alert-info"><small>Mohon masukan email <b>asli</b> Anda. Kami akan menggunakannya untuk setiap pemberitahuan dari kami seperti: Product Anda
						yang telah kami publish.</small></div>
					</div>
					<div class="form-group">
						<label>Website (Link)</label>
						<input type="text" class="form-control" name="website" id="website" placeholder="http://">
					</div>
					<div class="form-group">
						<label>Contact</label>
						<input type="text" class="form-control" name="contact" id="contact" placeholder="Write your phone or your office phone number">
					</div>
					<div class="form-group">
						<label>Latest Version</label>
						<input type="text" class="form-control" name="latest_version" id="latest_version" placeholder="Ex: 2.0 or 1.0">
					</div>
					<div class="form-group">
						<label>Product Description</label>
						<textarea class="form-control" rows="5" name="description" id="description" placeholder="Tell us about your awesome product"></textarea>
						<div class="description-message"></div>
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">Tags</label>
						<input type="text" class="form-control" name="tags" id="tags" placeholder="Write minimal 2 tags">
						<div class="margin-md-top alert alert-info"><small>Pisahkan tag satu dengan yang lain dengan tanda comma (,)</small></div>
					</div>
					<div class="output-send-product"></div>
					<div class="checkbox">
						<label>
						<input type="checkbox" class="term"> Saya setuju syarat dan ketentuan yang berlaku
						</label>
						<div class="margin-md-top"><a target="_blank" href="<?php echo site_url('syarat-dan-ketentuan')?>">Baca syarat dan ketentuan</a></div>
					</div>
				</form>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-nyankod btn-send-product">Submit</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Suggest Category -->
<div class="modal fade" id="suggest-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Suggestion Box</h4>
			</div>
			<div class="modal-body text-center">
				<form role="form">
					<div class="form-group">
						<textarea class="form-control" id="suggestion" placeholder="Your suggestion here."></textarea>
						
					</div>
					<button class="btn btn-suggest btn-primary margin-md-top" type="button">Suggest</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Jquery -->
<?php echo get_theme_js('jquery-1.8.2.min.js');?>

<!-- Jq Tag Canvas -->
<?php echo get_theme_js('jquery.tagcanvas.min.js');?>

<!-- Bootstrap -->
<?php echo get_theme_js('bootstrap.min.js');?>

<!-- Jq Croppic -->
<?php echo get_theme_js('croppic.min.js');?>

<!-- Jq Masonry -->
<?php echo get_theme_js('masonry.pkgd.min.js');?>

<!-- Ios Overlay -->
<script type="text/javascript" src="<?php echo base_url().'jooglo/plugins/iOS-Overlay/js/iosOverlay.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'jooglo/plugins/iOS-Overlay/js/spin.min.js'; ?>"></script>

<!-- Server to client value exchange -->
<input type="hidden" id="site_url" value="<?php echo site_url();?>">
<input type="hidden" id="user_id" value="<?php echo $user_id;?>">
<input type="hidden" id="current_url" value="<?php echo current_url();?>">

<!-- Open Form Trigger -->
<?php if (isset($_GET['action']) && $_GET['action'] == 'open-submit-form'): ?>
<script>
$(document).ready(function(){
	$('#submit-modal').modal('show');
});
</script>
<?php endif; ?>

<!-- Start Custom Sscript -->
<script>
$(document).ready(function(){

	var site_url = $('#site_url').val();
	var user_id = $('#user_id').val();
	var current_url = $('#current_url').val();

	if (user_id == '')
	{
		$('.connect-options').show();
		$('.submit-product').hide();
		$('.modal-footer').hide();
	}
	else
	{
		$('.connect-options').hide();
		$('.submit-product').show();
		$('.modal-footer').show();
	}
	
	/* Submit product */
	$('.btn-send-product').click(function(){
			
		var product_name = $('#product_name').val();
		var categories = $('#categories').val();
		var developer = $('#developer').val();
		var email = $('#email').val();
		var website = $('#website').val();
		var contact = $('#contact').val();
		var latest_version = $('#latest_version').val();
		var description = $('#description').val();
		var tags = $('#tags').val();
		var error = false;
		
		// Check desc length
		if (description.length <= 200)
		{	
			error = true;
			$('.description-message').html('<div class="margin-md-top alert alert-danger"><small>Tolong jelaskan product Anda lebih detail. Minimal karakter adalah 200</small></div>');
		}
		else
		{
			$('.description-message').html('');
		}
			
		// Is term checked
		if (!$('.term').is(':checked'))
		{	
			error = true;
			$('.output-send-product').html('<div class="alert alert-danger">Mohon untuk menyetujui syarat dan ketentuan yang berlaku terlebih dahulu.</div>');
		}		
				
		if (error == false)
		{		
			go_loading();
				
			$.post( site_url + "codelokal/web_service/save_product", { email:email,product_name:product_name,categories:categories,developer:developer,website:website,contact:contact,latest_version:latest_version,tags:tags,description:description})
			.done(function( data ) {
				hide_loading();
						
				var reply = jQuery.parseJSON( data );
					
				if (reply.status == 'error')
				{
					$('.output-send-product').html('<div class="alert alert-danger">' + reply.message + '</div>');
				}
				else if (reply.status == 'error_login')
				{
					window.open(site_url + 'connect', 'Connect Account', "height=600,width=500");
				}
				else
				{
					document.location.href = site_url + reply.message + '?step=image';
				}
			});
		}
	});
	
	/* Save/Update detail */
	$('.btn-save-detail').click(function(){
		go_loading();
		
		var product_name = $('#det_product_name').val();
		var post_id = $('#post_id').val();
		var post_date = $('#post_date').val();
		var categories = $('#det_categories').val();
		var developer = $('#det_developer').val();
		var website = $('#det_website').val();
		var contact = $('#det_contact').val();
		var latest_version = $('#det_latest_version').val();
		var description = $('#det_description').val();
		var tags = $('#det_tags').val();
		
		$.post( site_url + "codelokal/web_service/update_product", { post_date:post_date,post_id:post_id,product_name:product_name,categories:categories,developer:developer,website:website,contact:contact,latest_version:latest_version,tags:tags,description:description})
		.done(function( data ) {
			hide_loading();
			
			var reply = jQuery.parseJSON( data );
				
			if (reply.status == 'error')
			{
				$('.alert-reply').html('<div class="alert alert-danger">' + reply.message + '</div>');
			}
			else
			{
				document.location.href = site_url + reply.message;
			}
		})
	});
	
	/* Edit detail product */
	$('.btn-edit-detail').click(function(){
		$('.detail-display').hide();
		$(this).hide();
		$('.edit-form').show();
	})
	
	$('.btn-cancel-detail').click(function(){
		$('.detail-display').show();
		$('.btn-edit-detail').show();
		$('.edit-form').hide();
	})
		
	/* Search product  */
	$('.btn-search').click(function(){
		var keyword = $('#keyword').val();
		document.location.href = site_url + 'search/' + encodeURIComponent(keyword);
	})
	
	$('#keyword').keyup(function(e){
		if (e.keyCode == 13)
		{
			var keyword = $(this).val();
			document.location.href = site_url + 'search/' + encodeURIComponent(keyword);
		}
	});
	
	/* Star button */
	$('.btn-star').click(function(){

		var action  = $(this).attr('action');
		var object_id = $(this).attr('object_id');
		var execute_url;
		var total_star = parseInt($('#total_star').html());
		
		if (action == 'give_star')
		{
			execute_url = site_url + "codelokal/star/insert";
		}
		else if (action == 'remove_star')
		{
			execute_url = site_url + "codelokal/star/delete";
		}
		else
		{
			alert('undefined action.');
		}
		
		$('#total_star').html('Counting..');
		
		$.post( execute_url, { object_id:object_id })
		.done(function( data ) {
			
			$('#total_star').html(total_star);
			
			if (data == 'login_required')
			{
				window.open(site_url + 'connect', 'Connect Account', "height=400,width=350");
			}
			else if (data == 'success')
			{
				if (action == 'give_star')
				{
					// Change to remove star button
					$('.btn-star').attr('action', 'remove_star');
					$('.btn-star').html('<span class="glyphicon glyphicon-star"></span> Remove Star');
					total_star = total_star + 1;
				}
				else
				{
					// Change to give star button
					$('.btn-star').attr('action', 'give_star');
					$('.btn-star').html('<span class="glyphicon glyphicon-star"></span> Give Star');
					total_star = total_star - 1;
				}
			}
			else
			{
				// Change to give star button
				$('.btn-star').attr('action', 'give_star');
				$('.btn-star').html('<span class="glyphicon glyphicon-star"></span> Give Star');
				total_star = total_star - 1;
			}
			
			$('#total_star').html(total_star);
		});
	})
	
	/* Upload Screenshot */
	var croppicContainerModalOptions = {
		uploadUrl: 'http://localhost/JoogloCMS/codelokal/web_service/upload',
		cropUrl: 'http://localhost/JoogloCMS/codelokal/web_service/crop',
		outputUrlId:'cropOutput',
		modal:false,
		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
	}
	var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);
	
	/* Edit Screenshot */
	var croppicEditOptions = {
		uploadUrl: 'http://localhost/JoogloCMS/codelokal/web_service/upload',
		cropUrl: 'http://localhost/JoogloCMS/codelokal/web_service/crop',
		outputUrlId:'cropOutputEdit',
		modal:false,
		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
	}
	var croppicEdit = new Croppic('croppicEdit', croppicEditOptions);
	
	/* Button change screenshot */
	var btn_ok = false;
	$('#croppicEdit').hide();
	$('.btn-change-screenshot-cancel').hide();
	$('.btn-change-screenshot-fix').hide();
	$('.crop-tutor').hide();
	
	$('.btn-change-screenshot').click(function(){
		$('.crop-tutor').show();
		btn_ok = true;
		$('.btn-change-screenshot').hide();
		$('.btn-change-screenshot-cancel').show();
		$('.btn-change-screenshot-fix').show();
		$('.existing-thumb').hide();
		$('#croppicEdit').show();
	});
	
	$('.btn-change-screenshot-cancel').click(function(){
		
		if (btn_ok == true)
		{
			$('.btn-change-screenshot-fix').hide();
			btn_ok = false;
		}
		else
		{
			$('.btn-change-screenshot-fix').hide();
		}
		
		$('.crop-tutor').hide();
		$('.btn-change-screenshot').show();
		$('.btn-change-screenshot-cancel').hide();
		$('.existing-thumb').show();
		$('#croppicEdit').hide();
	});
	
	$('.btn-change-screenshot-fix').click(function(){
		var img_url = $('#cropOutputEdit').val();
		var post_id = $('#post_id').val();
		
		$.post( site_url + "codelokal/web_service/save_image", { img_url: img_url, post_id: post_id })
		.done(function( data ) {
			if (data == 'success')
			{
				location.reload();
			}
			else if (data == 'login_required')
			{
				alert('Login required');
			}
			else if (data == 'not_allowed')
			{
				alert('Not allowed');
			}
			else
			{
				alert('Error');
			}
		});
	})
	
	/* Suggestion */
	$('.btn-suggest').click(function(){
		var suggestion = $('#suggestion').val();
		
		go_loading();
		
		$.post( site_url + "codelokal/web_service/save_suggest", { suggestion: suggestion })
		.done(function( data ) {
			hide_loading();
			
			var reply = jQuery.parseJSON( data );
				
			if (reply.status == 'success')
			{	
				$('#suggestion').val('');
				alert('Terimakasih banyak atas masukannya.');
				$('#suggest-modal').remove();
			}
			else
			{
				alert('Kesalahan teknis');
			}
		});
	});
	
	/* Tag Canvas Cloud */
    if ( ! $('#myCanvas').tagcanvas({
		textColour : '#408071',
		outlineThickness : 3,
		maxSpeed : 0.03,
		depth : 1.5
	})) {
		// TagCanvas failed to load
		$('#myCanvasContainer').hide();
	}
	
	/* Share button */
	$('.share').click(function(){
	
		var to = $(this).attr('to');
		var address;
		
		if (to == 'twitter')
		{
			address = 'https://twitter.com/intent/tweet?url=' + current_url + '&text=Komentar Anda?&via=codepolitan';
		}
		else if (to == 'facebook')
		{
			address = 'http://www.facebook.com/sharer.php?u=' + current_url;
		}
		else
		{
			
		}
		
		window.open(address, 'Share', "height=500,width=500");
	})
	
	
	/* Helper */
	function hide_loading()
	{
		$('.ui-ios-overlay').remove();
	}
	
	function go_loading()
	{
		var opts = {
			lines: 13, // The number of lines to draw
			length: 11, // The length of each line
			width: 5, // The line thickness
			radius: 17, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			color: '#FFF', // #rgb or #rrggbb
			speed: 1, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: 'auto', // Top position relative to parent in px
			left: 'auto' // Left position relative to parent in px
		};
		var target = document.createElement("div");
		document.body.appendChild(target);
		var spinner = new Spinner(opts).spin(target);
		iosOverlay({
			text: "Loading",
			duration: 2e3,
			spinner: spinner
		});
	}
})
</script>
</body>
</html>
