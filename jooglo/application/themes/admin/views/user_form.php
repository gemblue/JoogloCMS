<div class="content-admin">
	<div class="head-area heading-active">
		<span class="title"><?php echo $template['title']; ?></span>
		<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
	</div>
		
	<div class="body-area">
		<div class="body-area-padding">
			<?php
			// If mode is edit, get user data
			if ($form_type == 'edit')
			{
				foreach ($user_data as $row)
				{
					$username = $row->username;
					$email = $row->email;
					$user_id = $row->id;
					$status = $row->status;
					$tgl_reg = $row->created_at;					
				}
				
				$f_name = $this->mdl_user->get_user_meta($user_id, 'first_name');
				$l_name = $this->mdl_user->get_user_meta($user_id, 'last_name');
				$address = $this->mdl_user->get_user_meta($user_id, 'address');
				$biography = $this->mdl_user->get_user_meta($user_id, 'biography');
				$phone = $this->mdl_user->get_user_meta($user_id, 'phone');
				$avatar = $this->mdl_user->get_avatar($user_id);
				$user_role_id = $this->mdl_role->get_user_role($user_id);
			
				if ($status == 'inactive')
				{ 
					$btn_link = site_url('cms/admin/user_activate').'/'.$user_id.'/'.$current_url_encode;
					$btn_label = 'Activate';
				} 
				else 
				{
					$btn_link = site_url('cms/admin/user_disactive').'/'.$user_id.'/'.$current_url_encode;
					$btn_label = 'Block';
				}	
				
				echo 'Registered : <b>'.$tgl_reg.' ('.time_ago($tgl_reg).')</b><br/>';
				echo '<div class="top-space4 bottom-space2"><a href="'.$btn_link.'" class="btn btn-la">'.$btn_label.'</a></div>';
			}
			
			$special_account = '';
			?>

			<form id="form" method="post" enctype="multipart/form-data" action="<?php echo ($form_type == 'edit') ? site_url('cms/admin/user_update') : site_url('cms/admin/user_add');?>">
				
				<input type="hidden" name="user_id" value="<?php echo ($form_type == 'edit') ? $user_id : '';?>">
				
				<?php if ($form_type == 'edit'):?>
				<div class="avatar-user top-space3 bottom-space3">
					<img src="<?php echo $avatar;?>" />
				</div>
				<?php endif;?>
					
				<div class="label-input">Username *</div>
				<div class="bottom-space4">
					<div><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ''; ?>" id="username" class="span4"/> </div>
				</div>
				
				<div class="label-input">First Name *</div>
				<div class="bottom-space4">
					<div><input type="text" name="f_name" value="<?php echo (isset($f_name)) ? $f_name : ''; ?>" id="f_name" class="span4"/> </div>
				</div>
					
				<div class="label-input">Last Name *</div>
				<div class="bottom-space4">
					<div><input type="text" name="l_name" value="<?php echo (isset($l_name)) ? $l_name : ''; ?>" id="l_name" class="span4"/> </div>
				</div>
					
				<div class="label-input">Email *</div>
				<div class="bottom-space4">
					<input type="text" name="email" value="<?php echo (isset($email)) ? $email : '';?>" id="email" class="span4"/>
				</div>
					
				<div class="label-input">Phone</div>
				<div class="bottom-space4">
					<input type="text" name="phone" value="<?php echo (isset($phone)) ? $phone : '';?>" id="phone" class="span4"/>
				</div>
					
				<div class="label-input">Address</div>
				<div class="bottom-space4">
					<textarea name="address" id="address" class="span6"><?php echo (isset($address)) ? $address : ''; ?></textarea>
				</div>

				<div class="label-input">Biography</div>
				<div class="bottom-space4">
					<textarea name="biography" id="biography" class="span6"><?php echo (isset($biography)) ? $biography : ''; ?></textarea>
				</div>
					
				<div class="label-input">Role</div>
				<div class="bottom-space4">
					<select name="role" id="role" class="input" >
					<?php
					foreach ($role as $row)
					{
						echo '<option value="'.$row->id.'" '.($user_role_id == $row->id ? 'selected="selected"' : '').'>'.$row->name.'</option>';
					}
					?>
					</select>
				</div>
					
				<hr class="la-line">
					
				<div class="label-input">Your <b>Strongest</b> Password</div>
				<div class="bottom-space4">
					<input type="password" id="password" name="password" class="span4"/><br/>
				</div>
					
				<button type="submit" class="btn btn-la btn-space1"><?php echo ($form_type == 'edit') ? 'Update' : 'Create'?></button>
			</form>	
		</div>
	</div>
</div>