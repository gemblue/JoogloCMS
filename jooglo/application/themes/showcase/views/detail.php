<div class="row-fluid">
	<div class="header-mini text-center">
		<div class="container">
			<div class="row">
				<div class="logo-little">
					<div class="text-left col-md-6 col-xs-12">
						<a href="<?php echo site_url()?>"><b>Showcase</b></a>
					</div>
				</div>
				
				<div class="text-right col-md-6 col-xs-12">
					<div class="main-nav-mini">
						<a href="#" class="active" data-toggle="modal" data-target="#submit-modal">Submit Product</a> . <a href="<?php echo site_url('about')?>">About</a> . <a href="<?php echo site_url('statistic')?>" data-toggle="modal" data-target="#suggest-modal">Suggest</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	if ($logged_in == true)
	{
		?><div class="person-dude text-center">Welcome. <b><?php echo $this->session->userdata('username')?></b> - <a href="<?php echo site_url('u/logout')?>">Logout</a></div><?php
	}
	?>
	
	<div class="container">
		<div class="menu-top">
			<div class="row">
				<div class="navbar-left col-md-6 col-xs-6">
					<?php 
					foreach ($tags_array as $row)
					{
						?>
						<a class="btn btn-default btn-primary" href="<?php echo site_url('tags/'.$row->slug)?>" role="button"><?php echo $row->name?></a>
						<?php
					}
					?>
				</div>
				
				<form class="navbar-form pull-right col-md-6 col-xs-12" role="search">
					<div class="form-group">
						<input name="keyword" id="keyword" type="text" class="form-control" placeholder="Search">
					</div>
					<a href="#" class="btn btn-default btn-search" rule="button">Go!</a>
				</form>
			</div>
		</div>
		
		<?php if ($status == 'draft'):?>
			<?php if ($author == $user_id) : ?>
				<div class="margin-md-top alert alert-danger">
					<p><b>[Penting]</b> Sementara hanya Anda yang bisa melihat post ini. Admin harus mengecek terlebih dahulu validitas konten Anda.
					Untuk menghindari konten yang tidak relevan. </p>
					<p>
					Jika ada kesalahan dalam input data Anda, silahkan edit langsung di page ini. Url product Anda saat ini
					adalah <a href="<?php echo site_url($slug);?>"><?php echo site_url($slug);?></a></p>
					
					<p>Sistem kami akan mengirim <b>pemberitahuan langsung ke email</b> Anda jika post ini sudah dipublish</p>
				</div>
			<?php endif;?>
		<?php endif;?>
		
		<div class="row">
			<div class="col-md-5">
				
				<div class="alert alert-info crop-tutor margin-md-top">Upload, geser gambar sampai pas, klik tombol crop (warna hijau), kemudian akhiri dengan klik tombol "Finish"</div>
				
				<?php if ($author == $user_id) : ?>
					<a href="#" class="btn btn-primary btn-change-screenshot">Change Screenshot</a>
					<a href="#" class="btn btn-primary btn-change-screenshot-cancel">Cancel</a>
					<a href="#" class="btn btn-nyankod btn-change-screenshot-fix" rule="button">Finish</a>
					<br/><br/>
					<div id="croppicEdit"></div>
					<input type="hidden" id="cropOutputEdit"/>
					<input type="hidden" id="post_id" value="<?php echo $post_id?>"/>
				<?php endif;?>
				
				<div class="thumbnail existing-thumb">
					<img src="<?php echo $this->jooglo->get_post_thumbnail($post_id);?>" />
				</div>
				
				<?php if (isset($_GET['step']) && $_GET['step'] == 'image') :?>
					<div class="margin-md-top alert alert-danger">Untuk mempercantik halaman ini, silahkan ubah gambar diatas dengan logo atau screenshot terbaik dari product Anda.</div>
				<?php endif;?>
			</div>
			
			<div class="col-md-6">
				<div class="detail-display">
					<h3><?php echo $title?></h3>
					<span class="badge"><b id="total_star"><?php echo $this->codelokal->get_accumulative($post_id);?></b> Stars</span>
					<span class="badge"><?php echo $this->jooglo->get_field_value('view', 'ID', $post_id);?></b> Views</span>
					
					<div class="margin-md-top">
						<p><?php echo $content?></p>
					</div>
					
					<div class="margin-md-top"><b>Official Review by Codepolitan</b></div>
					<p>
					<?php 
					$official_review = $this->jooglo->get_post_meta($post_id, 'official_review');
					echo ($official_review != '') ? $official_review : 'Belum ada review';
					?>
					</p>
					
					<div class="margin-md-top"><b>Attribute</b></div>
					<table class="margin-xs-top">
						<tr><td>Category</td><td>&nbsp&nbsp:</td><td>&nbsp<?php echo $category['name']?></td></tr>
						<tr><td>Latest Version</td><td>&nbsp&nbsp:</td><td>&nbsp<?php echo $this->jooglo->get_post_meta($post_id, 'latest_version')?></td></tr>
						<tr><td>Developer</td><td>&nbsp&nbsp:</td><td>&nbsp<?php echo $this->jooglo->get_post_meta($post_id, 'developer')?></td></tr>
						<tr><td>Website</td><td>&nbsp&nbsp:</td><td>&nbsp<a href="#"><?php echo $this->jooglo->get_post_meta($post_id, 'website')?></a></td></tr>
						<tr><td>Contact</td><td>&nbsp&nbsp:</td><td>&nbsp<?php echo $this->jooglo->get_post_meta($post_id, 'contact')?></a></td></tr>
						<tr><td>Submitted by</td><td>&nbsp&nbsp:</td><td>&nbsp<?php echo $this->jooglo->get_user_meta($author, 'name');?></a></td></tr>
					</table>
				</div>
				<div class="edit-form" style="display:none;">
					<form role="form" id="submit-product">
						<input type="hidden" value="<?php echo $post_id?>" id="post_id">
						<input type="hidden" value="<?php echo $date?>" id="post_date">
						<div class="form-group">
							<label>Product Name</label>
							<input type="email" class="form-control" name="product_name" id="det_product_name" value="<?php echo $title?>" placeholder="Ex: Smadav">
						</div>
						<div class="form-group">
							<label>Categories</label>
							<select name="categories" id="det_categories" class="form-control">
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
							
							<p class="help-block"><a href="#" data-toggle="modal" data-target="#suggest-modal">Suggest or edit category?</a></p>
						</div>
						<div class="form-group">
							<label>Developer</label>
							<input type="text" class="form-control" name="developer" id="det_developer" value="<?php echo $this->jooglo->get_post_meta($post_id, 'developer')?>" placeholder="Write your name or your team/studio">
						</div>
						<div class="form-group">
							<label>Website</label>
							<input type="text" class="form-control" name="website" id="det_website" value="<?php echo $this->jooglo->get_post_meta($post_id, 'website')?>" placeholder="Your website product or organization">
						</div>
						<div class="form-group">
							<label>Contact</label>
							<input type="text" class="form-control" name="contact" id="det_contact" value="<?php echo $this->jooglo->get_post_meta($post_id, 'contact')?>" placeholder="Write your phone or your office phone number">
						</div>
						<div class="form-group">
							<label>Latest Version</label>
							<input type="text" class="form-control" name="latest_version" id="det_latest_version" value="<?php echo $this->jooglo->get_post_meta($post_id, 'latest_version')?>" placeholder="Ex: 2.0 or 1.0">
						</div>
						<div class="form-group">
							<label>Product Description</label>
							<textarea class="form-control" rows="7" name="description" id="det_description" placeholder="Tell us about your awesome product"><?php echo $content?></textarea>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Tags</label>
							<input type="text" class="form-control" name="tags" id="det_tags" value="<?php echo $tags?>" placeholder="Write minimal 2 tags">
						</div>
						<div class="alert-reply"></div>
						
						<a class="btn btn-save-detail btn-primary margin-md-top" href="#" role="button">Save</a>
						<a class="btn btn-cancel-detail btn-primary margin-md-top" href="#" role="button">Cancel</a>
					</form>
				</div>
				
				<?php if ($author == $user_id) : ?>
					<a class="btn btn-edit-detail btn-primary margin-md-top" href="#" role="button">Edit</a>
				<?php endif;?>
			</div>
		</div>
		
		<div class="margin-lg-top margin-lg-bottom">
			<a href="#" class="btn btn-nyankod" role="button"><span class="glyphicon glyphicon-play"></span> Demo</a>
			<a target="_blank" href="http://<?php echo $this->jooglo->get_post_meta($post_id, 'website')?>" class="btn btn-nyankod" role="button"><span class="glyphicon glyphicon-download-alt"></span> Visit</a>
			
			<?php
			if ($this->codelokal->is_starred($user_id, $post_id))
			{
				?>
				<a href="#" class="btn btn-nyankod btn-star" action="remove_star" object_id="<?php echo $post_id?>" role="button"><span class="glyphicon glyphicon-star"></span> Remove Star</a>
				<?php
			}
			else
			{
				?>
				<a href="#" class="btn btn-nyankod btn-star" action="give_star" object_id="<?php echo $post_id?>" role="button"><span class="glyphicon glyphicon-star"></span> Give Star</a>
				<?php
			}
			?>
			
			<a href="#" class="btn btn-default btn-facebook share" to="facebook" role="button">Share Facebook <span class="glyphicon glyphicon-share-alt"></span></a>
			<a href="#" class="btn btn-default btn-twitter share" to="twitter" role="button">Share Twitter <span class="glyphicon glyphicon-share-alt"></span></a>
		</div>
	</div>
	
	<div class="visible-md visible-lg">
		<div class="bottom text-center">
			<div>Mobile Apps / Dekstop Software / Web / Developer Weapon / Library / Cloud</div>
			<button class="margin-md-top btn btn-default btn-super-lg" data-toggle="modal" data-target="#submit-modal">SUBMIT PRODUCT</button>
		</div>
	</div>
</div>
