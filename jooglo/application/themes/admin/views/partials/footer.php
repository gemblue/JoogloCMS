	<!-- Bridge to Js -->
	<input type="hidden" id="base_url" value="<?php echo base_url()?>">
	
	<!--
	<div class="footer">
		Copyright &copy; <?php echo (isset($site_title) ? $site_title : 'Project Name'); ?> - All rights reserved.
	</div>
	-->
	
	<!-- Strengh Js-->
	<script type="text/javascript" src="<?php echo site_url('jooglo/plugins/Strengthjs/strength.min.js')?>"></script>
	<!-- Tiny MCE -->
	<script type="text/javascript" src="<?php echo site_url('jooglo/application/themes/admin/assets/js/tinymce/tinymce.min.js');?>"></script>
	<!-- Jq UI -->
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<!-- Jq Validate -->
	<script type="text/javascript" src="<?php echo base_url().'jooglo/plugins/jquery-validation/jquery.validate.min.js'; ?>"></script>
	
	<!-- Ios Overlay -->
	<script type="text/javascript" src="<?php echo base_url().'jooglo/plugins/iOS-Overlay/js/iosOverlay.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'jooglo/plugins/iOS-Overlay/js/spin.min.js'; ?>"></script>
	
	<!-- Admin Script -->
	<script>
	$(document).ready(function(){
					
		var base_url = $('#base_url').val();
		var screen_height = $( window ).height();
		var screen_width = $( window ).width();	
	
		// Setting theme
		$('.theme').click(function(){
		
			var theme_name = $(this).attr('theme');
			
			$('#template').val(theme_name);
			
			// Remove all active
			$('.theme').removeClass('theme-img-active');
			
			// Activate that clicked
			$(this).addClass('theme-img-active');
			
			iosOverlay({
				text: "Choosed",
				duration: 2e3,
				icon: base_url + 'jooglo/plugins/iOS-Overlay/img/check.png'
			});
		});
		
		// Any Alert Must Be Hide
		window.setTimeout(function() { 
			$(".alert").fadeOut(); 
		}, 3000);
		
		// Install Date picker to field
		
		/*
		$('#date_posted').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
		*/
		
		// Generate slug from text
		$(".title").keyup(function() {
			var post_title = $(this).val();
			$(".slug").val(convertToSlug(post_title));	
		});
		
		// Generate post type
		$('.btn-generate-post-type').click(function(){
			var post_type_title = $('#post_type_title').val();
			var post_type_slug = convertToSlug(post_type_title);
			document.location.href = base_url + 'cms/admin/post_new/' + post_type_slug;
		});
		
		// Generate entry type
		$('.btn-generate-entry-type').click(function(){
			var entry_type_title = $('#entry_type_title').val();
			var entry_type_slug = convertToSlug(entry_type_title);
			document.location.href = base_url + 'cms/admin/new_entry/' + entry_type_slug;
		});
					
		// Form validation 
		$("#post-form").validate({
			rules: {
								
				post_title: {
					required: true,
					minlength: 1
				},
				slug: {
					required: true,
					minlength: 1
				},
				metakey: {
					required: true
				},
				metadesc: {
					required: true
				}
			}
		});
		
		// Tiny MCE Init
		tinymce.init({
			selector: ".rte",
			theme: "modern",
			height: 350,
						
			plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
			"table contextmenu directionality textcolor paste textcolor responsivefilemanager"
			],

			toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | subscript superscript | forecolor backcolor | styleselect formatselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | inserttime preview | link unlink anchor image media | code",
			toolbar3: "responsivefilemanager | table | hr removeformat | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
			
			setup: function (editor) {
				editor.on('change', function () {
					tinymce.triggerSave();
				});
			},
			image_advtab: true,
			menubar: false,
			toolbar_items_size: 'small',
			relative_urls: false,
			remove_script_host: false,
			document_base_url: '<?php echo base_url(); ?>',
			pagebreak_separator: '<!-- nextpage -->',
			filemanager_title:"IMAGES MANAGER" ,
			external_filemanager_path: "<?php echo base_url(); ?>jooglo/plugins/filemanager/",
		});
		
		// Save post and submit preview
		$('.btn-live-preview').click(function(){
			
			var datastring = $("#post-form").find('input, select, textarea').serialize();
			
			$.ajax({
				type: "POST",
				url: base_url + 'cms/admin/post_preview',
				data: datastring,
				success: function(data) {
					window.open(base_url + data + "?preview=true", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=" + screen_width + "," + "height=" + screen_height);
				}
			});
		});
		
		// Password Strength
		$('#password').strength({
            strengthClass: 'strength',
            strengthMeterClass: 'strength_meter',
            strengthButtonClass: 'button_strength',
            strengthButtonText: 'Show Password',
            strengthButtonTextToggle: 'Hide Password'
        });
		
		// Responsive file manager pop up for field
		$('.btn-iframe').fancybox({	
			'width'		: 900,
			'height'	: 600,
			'type'		: 'iframe',
			'autoScale'    	: true
		});
		
		// Bulk Action Js
		$('.btn-bulk').click(function(){
			
			var post_record = [];
			var action = $(this).attr('action');
			
			$(':checkbox:checked').each(function(i){
				post_record[i] = $(this).val();
			});
			
			goLoading();
			
			$.post( base_url + 'cms/admin/bulk', { post_record: post_record, action: action })
			.done(function( data ) {
				if (data == 'done')
				{
					location.reload();
				}
				else
				{
					alert('Failed');
				}
			});
		});
		
		// Select all post
		$('.select-all').click(function() {
        
			if (this.checked) 
			{
				$('.record').each(function() { 
					this.checked = true;             
				});
			}
			else
			{
				$('.record').each(function() { 
					this.checked = false;                       
				});         
			}
		});
  	
		// Menu Js
		var heightLeft = $(".menu-left").height();
		var heightRight = $(".content-area").height();
		
		if (heightLeft < heightRight) {
			$(".menu-left").css("height",heightRight+"px");
		} else {
			$(".content-area").css("height",heightLeft+"px");
		}
				
		var $init;
		$init = $(".accordion-body.in");
		$init.parent().addClass("accordion-group-active");
		$init.prev().addClass("accordion-heading-active");
		$init.parent().find(".icon-accordion").html('<i class="icon-minus"></i>');
	
		$(".accordion-toggle").click(function(){
			
			if ($(this).parent().hasClass("accordion-heading-active")) {		
				$(this).parent().removeClass("accordion-heading-active");
				$(this).parents(".accordion-group").removeClass("accordion-group-active");
				$(this).find(".menu-caret").html('<i class="icon-plus"></i>');
			} else {
				var attr_parent = $(this).attr('data-parent');
				if (typeof attr_parent !== 'undefined' && attr_parent !== false) {
					var parent_div_id = $(this).attr("data-parent");
					$(parent_div_id).find(".accordion-heading").removeClass("accordion-heading-active");
					$(parent_div_id).find(".accordion-group").removeClass("accordion-group-active");
					$(parent_div_id).find(".menu-caret").html('<i class="icon-plus"></i>');
				} 
				$(this).parent().addClass("accordion-heading-active");
				$(this).parents(".accordion-group").addClass("accordion-group-active");  
				$(this).find(".menu-caret").html('<i class="icon-minus"></i>');
			}
		});
		/* ------ ACCORDION END ------ */
		
		/* ------ BOX AREA ------ */
		$(".link-chevron").click(function(){
			$(this).parents(".content-admin").find(".body-area").toggle();
			
			if ($(this).parents(".head-area").hasClass("heading-active")) {
				$(this).html('<i class="icon-chevron-down"></i>');
				$(this).parents(".head-area").removeClass("heading-active");
			} else {
				$(this).html('<i class="icon-chevron-up"></i>');
				$(this).parents(".head-area").addClass("heading-active");
			}
			return false;
	
		});
		/* ------ BOX AREA END ------ */
		
		// Function
		function convertToSlug(Text)
		{
			return Text
				.toLowerCase()
				.replace(/[^\w ]+/g,'')
				.replace(/ +/g,'-');
		}
		
		function goLoading()
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
	});
	</script>
</body>
</html>