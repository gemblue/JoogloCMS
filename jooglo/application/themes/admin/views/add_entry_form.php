<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
			<form name="post-form" id="post-form" method="post" enctype="multipart/form-data" action="<?php echo site_url('cms/admin/')?>">
				
				<div class="label-input">Your <b>nice</b> entry</div>
				<div class="bottom-space4">
					<input type="text" name="entry_type" class="span6" id="entry_type_title" placeholder="Fruit? People? Places?"/> 
				</div>
				
				<button type="button" class="btn btn-la btn-large btn-generate-entry-type">Generate</button>
			</form>
			<small><a href="#">About entry?</a><small>
		</div>
	</div>
</div>