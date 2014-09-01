<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $title_page; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>
	
	<div class="body-area">
		<div class="body-area-padding">
		
		<h2>Active Member</h2>
		
		<?php 
		$member_active_total = $this->mdl_report->get_active_member('total', null, '11');
		?>
			
		November 2013 : <?php echo $member_active_total?> active
		
		<br/>
		
		<h2>Top 10 Member Activities</h2>
		
		
		<h3>Comment</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
					
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('comment', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			
			</tbody>
		</table>
		
		<h3>Share Twitter</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('share_twitter', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			
			</tbody>
		</table>
		
		<h3>Share Facebook</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('share_facebook', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			
			</tbody>
		</table>
		
		<h3>Share Google</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('share_google', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			
			</tbody>
		</table>
		
		<h3>Share Mindtalk</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('share_mindtalk', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			
			</tbody>
		</table>
		
		<h3>Check in</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('checkin', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			</tbody>
		</table>
		
		<h3>Poll</h3>
		
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th>No</th>
				<th>User id</th>
				<th>Username</th>
				<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$top_ten_member_activities = $this->mdl_report->get_top_member_activities('poll', 10);
			$i = 1;
			foreach ($top_ten_member_activities as $row)
			{
				?>
				
				<tr><td><?php echo $i?></td><td><?php echo $row->id?></td><td><?php echo $row->username?></td><td><?php echo $row->total_activities?></td></tr>
				
				<?php
				$i++;
			}
			?>
			</tbody>
		</table>
		
		</div>
	</div>
</div>