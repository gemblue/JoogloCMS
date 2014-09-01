<div class="padding-lg-y">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2 class="h3">Menemukan <b><?php echo $total_result?></b> hasil dengan nama "<?php echo $keyword?>"</h2>
				<small><a href="<?php echo site_url('u')?>">Tampilkan kembali semua user</a></small>
			</div>
			<div class="col-md-4">
				<form class="form-horizontal" method="post" action="<?php echo site_url('u/search');?>">
					<div class="form-group">
						<div class="col-sm-9"><input type="text" name="username" class="form-control" placeholder="tulis nama/username..."></div>
						<div class="col-sm-3"><button type="submit" class="btn btn-primary btn-block">Cari</button></div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="user-loop margin-lg-top">
			<?php 
			$i = 1;
			foreach ($result as $row)
			{
				?>
				<div class="user-item-wrapper <?php echo ($i % 3 == 0 ? 'no-margin' : ''); ?>">
					<div class="padding-sm">
						<div class="media">
							<a href="#" class="pull-left">
								<img src="<?php echo $this->mdl_user->get_avatar($row->id)?>" width="80" class="img-ava">
							</a>
							<div class="media-body">
								<div class="user-nama"><a href="#" class="link-primary"><?php echo $row->username?></a></div>
								<div class="user-desc text-small text-grey">~<?php echo $this->mdl_user->get_user_meta($row->id,'who')?></div>
								<div class="user-point margin-md-top">POINT KONTRIBUSI: <?php echo $this->point_m->get_total_point($row->id)?></div>
							</div>
						</div>
					</div>
				</div>
				<?php 
				$i++;
			} 
			?>
			<div class="clear"></div>
		</div>
	</div>
</div>