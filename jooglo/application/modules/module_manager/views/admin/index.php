<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $template['title']; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>

	<div class="body-area">
		<div class="body-area-padding">

			<div class="row-fluid">
				<div class="span7">

				</div>
				<div class="span5">

				</div>
			</div>


		<?php if (empty($modules)): ?>

			<div class="alert alert-error">Record's not found..</div>

		<?php else: ?>

		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th>Title</th>
					<th>Slug</th>
					<th>Location</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($modules as $slug => $row): ?>
					<tr>
					<td><?php echo $row['module_name'] ?></td>
					<td><?php echo $slug ?></td>
					<td><label class="badge badge-<?php echo ($row['location'] == 'dev')? 'warning': 'primary'; ?>"><?php echo $row['location']; ?></label></td>
					<td><?php echo $row['info']; ?></td>
					<?php if($row['location'] == 'dev'): ?>
					<td><a href="<?php echo site_url('admin/module_manager/'); ?>" class="btn btn-primary">Activate</a></td>
					<?php else: ?>
					<td></td>
					<?php endif; ?>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

		<!-- Pagination -->
		<?php if(isset($pagination)){ ?>
		<?php echo $pagination; ?>
		<?php } ?>

		
	<?php endif ?>


</div>
</div>
</div>