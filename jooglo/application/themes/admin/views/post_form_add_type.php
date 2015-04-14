<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">
			<form name="post-form" id="post-form" method="post" enctype="multipart/form-data" action="<?php echo site_url('cms/admin/')?>">
				
				<div class="label-input">Your <b>nice</b> type</div>
				<div class="bottom-space4">
					<input type="text" name="post_type" class="span6" id="post_type_title" placeholder="News? Sports? Tips Trick? Cool Post?"/> 
				</div>
				
				<button type="button" class="btn btn-la btn-large btn-generate-post-type">Generate</button>
			</form>
			<small><a href="">About post type?</a><small>
		</div>
	</div>
</div>