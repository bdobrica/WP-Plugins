<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update']) || isset ($_POST['hint_create']) || isset ($_POST['hint_delete'])) {
	$images = [];
	$hints = $sd_theme->get ('scenario', 'hints');
	if (empty ($hints))
		$hints = [];

	if (isset ($_POST['hint_delete'])) {
		$hint_id = SD_Theme::r ('hint_delete_id');
		if (isset ($hints[$hint_id]))
			unset ($hints[$hint_id]);
		}

	$hint = [
		'hint_threshold'	=> trim (SD_Theme::r ('hint_threshold', 'number', 'last')),
		'hint_content'		=> trim (SD_Theme::r ('hint_content', 'string', 'last'))
		];

	if (!empty ($hints))
		foreach ($hints as $hint_id => $hint_data) {
			$hints[$hint_id] = [
				'hint_threshold'	=> trim(SD_Theme::r ('hint_threshold', 'number', $hint_id)),
				'hint_content'		=> trim(SD_Theme::r ('hint_content', 'string', $hint_id))
				];
			}

	if (isset ($_POST['hint_create']) || (intval($hint['hint_threshold']) > 0) || !empty ($hint['hint_content']))
		$hints[] = $hint;

	if (!empty (SD_Scenario::$S))
		foreach (SD_Scenario::$S as $key => $value) {
			if (!empty ($_FILES['negotiation_image_' . $key])) {
				$data = $_FILES['negotiation_image_' . $key];

				if (!isset($data['error']) || is_array ($data['error']))
					continue;
				if ($data['error'] != UPLOAD_ERR_OK)
					continue;

				if (FALSE === $ext = array_search (mime_content_type ($data['tmp_name']), [
					'jpg'	=> 'image/jpeg',
					'png'	=> 'image/png'
					], true)) continue;
				
				$hash = md5_file ($data['tmp_name']);
				$file = sprintf ('%s%s.%s', $sd_theme->get ('scenario', 'assets_path') . DIRECTORY_SEPARATOR, md5_file ($data['tmp_name']), $ext);

				if (!is_dir ($path = $sd_theme->get ('scenario', 'assets_path')))
					if (!@mkdir ($path, 0755, TRUE))
						continue;

				if (!@move_uploaded_file ($data['tmp_name'], $file))
					continue;

				$images[$key] = $file;
				}
			}

	$data = [
		'aggressiveness'	=> SD_Theme::r ('aggressiveness'),
		'ask_for_features'	=> SD_Theme::r ('ask_for_features'),
		'ask_for_warranty'	=> SD_Theme::r ('ask_for_warranty'),
		'score_weight'		=> SD_Theme::r ('score_weight'),
		'sweetener'		=> SD_Theme::r ('sweetener'),
		'hints'			=> array_values ($hints)
		];

	if (!empty ($images))
		$data['negotiation_images'] = $images;

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
