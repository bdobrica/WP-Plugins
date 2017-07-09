<?php
$error = null;
if (isset ($_POST['trainer_create'])) {
	$username		= SD_Theme::r ('username');
	$password		= SD_Theme::r ('password');
	$email			= strtolower (trim (SD_Theme::r ('email')));
	$first_name		= SD_Theme::r ('first_name');
	$last_name		= SD_Theme::r ('last_name');
	$phone			= SD_Theme::r ('phone');

	$user_id = username_exists ($username);
	if ($user_id) $error = [ 'create' => 1 ];
	$user_id = email_exists ($email);
	if ($user_id) $error = [ 'create' => 2 ];

	if (is_null ($error)) {
		$user_id = wp_create_user ($username, $password, $email);
		wp_update_user ([
			'ID'		=> $user_id,
			'first_name'	=> $first_name,
			'last_name'	=> $last_name
			]);

		add_user_meta ($user_id, 'phone', $phone, true);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['trainer_update'])) {
	$trainer = new WP_User ((int) SD_Theme::r ('trainer'));

	if (!isset ($trainer->ID)) $error = [ 'update' => 1 ];
	
	$password		= SD_Theme::r ('password');
	$email			= strtolower (trim (SD_Theme::r ('email')));
	$first_name		= SD_Theme::r ('first_name');
	$last_name		= SD_Theme::r ('last_name');
	$phone			= SD_Theme::r ('phone');

	if (is_null ($error)) {
		$trainer_data = [
			'ID'		=> $trainer->ID,
			'user_pass'	=> $password,
			'user_email'	=> $email,
			'first_name'	=> $first_name,
			'last_name'	=> $last_name
			];
		if (!$password) unset ($trainer_data['user_pass']);
		wp_update_user ($trainer_data);
		update_user_meta ($trainer->ID, 'phone', $phone);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['trainer_delete'])) {
	$trainer = new WP_User ((int) SD_Theme::r ('trainer'));

	if (!isset ($trainer->ID)) $error = [ 'delete' => 1 ];

	if (is_null ($error)) {
		require_once (ABSPATH . 'wp-admin/includes/user.php');
		wp_delete_user ($trainer->ID);
		}
	
	SD_Theme::prg ($error);
	}
?>
