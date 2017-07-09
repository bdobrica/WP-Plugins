<?php
$error = NULL;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update'])) {
	$data = [
		'round_3_email_sender'		=> SD_Theme::r ('round_3_email_sender'),
		'round_3_email_subject'		=> SD_Theme::r ('round_3_email_subject'),
		'round_3_email_content'		=> SD_Theme::r ('round_3_email_content')
		];

	if (!empty ($_FILES) && isset ($_FILES['round_3_email_attachment'])) {
		$file_data = $_FILES['round_3_email_attachment'];

		if (!isset($file_data['error']) || is_array ($file_data['error']))
			$error = ['upload_error' => 1];
		if (is_null ($error) && ($file_data['error'] != UPLOAD_ERR_OK))
			$error = ['upload_error' => 2];

		if (is_null ($error)) {
			if (FALSE === $ext = array_search (mime_content_type ($file_data['tmp_name']), [
				'ppt'	=> 'application/vnd.ms-office',
#				'ppt'	=> 'application/vnd.ms-powerpoint',
				'pptx'	=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
				], true))
				$error = ['upload_error' => 3];
			
			if (is_null ($error)) {
				$hash = md5_file ($file_data['tmp_name']);
				$file = sprintf ('%s%s.%s', $sd_theme->get ('scenario', 'assets_path') . DIRECTORY_SEPARATOR, md5_file ($file_data['tmp_name']), $ext);


				if (!is_dir ($path = $sd_theme->get ('scenario', 'assets_path')))
					if (!@mkdir ($path, 0755, TRUE))
						$error = ['upload_error' => 4];

				if (is_null ($error) && !@move_uploaded_file ($file_data['tmp_name'], $file))
					$error = ['upload_error' => 5];
				}
			}

		if (is_null ($error)) {
			$data['round_3_email_attachment'] = serialize ([ 'file' => $file, 'name' => $file_data['name'] ]);
			}
		}

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
