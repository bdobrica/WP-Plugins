<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update'])) {
	$image = null;

	if (!empty ($_FILES['timeout_image'])) {
		$data = $_FILES['timeout_image'];

		if (isset($data['error']) && !is_array ($data['error']) && $data['error'] == UPLOAD_ERR_OK && (FALSE !== $ext = array_search (mime_content_type ($data['tmp_name']), [
			'jpg'	=> 'image/jpeg',
			'png'	=> 'image/png'
			], true))) {
		
			$hash = md5_file ($data['tmp_name']);
			$file = sprintf ('%s%s.%s', $sd_theme->get ('scenario', 'assets_path') . DIRECTORY_SEPARATOR, md5_file ($data['tmp_name']), $ext);

			if (!is_dir ($path = $sd_theme->get ('scenario', 'assets_path')))
				if (@mkdir ($path, 0755, TRUE) && @move_uploaded_file ($data['tmp_name'], $file))
					$image = $file;
			}
		}

	$data = [
                'conversation_timer'		=> SD_Theme::r ('conversation_timer'),
                '1st_round_timer'		=> SD_Theme::r ('1st_round_timer'),
                'offer_timer'			=> SD_Theme::r ('offer_timer'),
                'default_delay'			=> SD_Theme::r ('default_delay'),
                'presentation_timer'		=> SD_Theme::r ('presentation_timer'),
                'negotiation_answer_timer'	=> SD_Theme::r ('negotiation_answer_timer'),
                'negotiation_timer'		=> SD_Theme::r ('negotiation_timer')
		];

	if (!is_null ($image))
		$data['timeout_image'] = $image;

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
