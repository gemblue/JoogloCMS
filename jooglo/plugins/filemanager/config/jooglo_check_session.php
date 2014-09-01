<?php

// check session
if(! isset($_SESSION['id'])) {echo 'access forbidden.'; exit;}

function check_user_folder($user_id, $upload_dir, $thumbs_dir)
{
	if(! is_dir(realpath($upload_dir.'_users/'.$user_id))) {
		mkdir($upload_dir.'_users/'.$user_id, 0777, true);
	}
	if(! is_dir(realpath($thumbs_dir.'_users/'.$user_id))) {
		mkdir($thumbs_dir.'_users/'.$user_id, 0777, true);
	}
}

// check user folder
check_user_folder($_SESSION['id'], $current_path, $thumbs_base_path);

?>
