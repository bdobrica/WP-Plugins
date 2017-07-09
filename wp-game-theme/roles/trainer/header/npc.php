<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['move_up'])) {
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
		}
	catch (SD_Exception $e) {
		$character = null;
		}

	if (!is_null ($character)) {
		$characters = new SD_List ('SD_Character');
		$characters->sort ();
		if (!$characters->is ('empty')) {
			$prev = FALSE;
			$list = $characters->get ();
			reset ($list);
			for ($c = 0; $c < sizeof ($list); $c++) {
				$item = current ($list);
				$item->set ('order', $c + 1);
				next ($list);
				}
			reset ($list);
			while (($item = current ($list)) !== FALSE) {
				if ($character->get () == $item->get ()) {
					$prev = prev ($list);
					break;
					}
				next ($list);
				}
			if ($prev !== FALSE) {
				$a = (int) $prev->get ('order');
				$b = (int) $character->get ('order');
				$c = $b; $b = $a; $a = $c;

				$prev->set ('order', $a);
				$character->set ('order', $b);
				}
			}
		}
	SD_Theme::prg ($error, TRUE);
	}

if (isset ($_POST['move_down'])) {
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
		}
	catch (SD_Exception $e) {
		$character = null;
		}

	if (!is_null ($character)) {
		$characters = new SD_List ('SD_Character');
		$characters->sort ();
		if (!$characters->is ('empty')) {
			$next = FALSE;
			$list = $characters->get ();
			reset ($list);
			for ($c = 0; $c < sizeof ($list); $c++) {
				$item = current ($list);
				$item->set ('order', $c + 1);
				next ($list);
				}
			reset ($list);
			while (($item = current ($list)) !== FALSE) {
				if ($character->get () == $item->get ()) {
					$next = next ($list);
					break;
					}
				next ($list);
				}
			if ($next !== FALSE) {
				$a = (int) $next->get ('order');
				$b = (int) $character->get ('order');
				$c = $b; $b = $a; $a = $c;

				$next->set ('order', $a);
				$character->set ('order', $b);
				}
			}
		}
	SD_Theme::prg ($error, TRUE);
	}

if (isset ($_POST['character_delete'])) {
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
		}
	catch (SD_Exception $e) {
		$character = null;
		}
	
	if (!is_null ($character)) {
		try {
			$character->remove ();
			}
		catch (SD_Exception $e) {
			}
		}

	SD_Theme::prg ($error, TRUE);
	}

if (isset ($_POST['character_create'])) {
	$images = [];
	if (!empty (SD_Character::$S))
		foreach (SD_Character::$S as $key => $value) {
			if (!empty ($_FILES['character_' . $key])) {
				$data = $_FILES['character_' . $key];

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
		'name'		=> SD_Theme::r ('character_name'),
		'position'	=> SD_Theme::r ('character_position'),
		'role'		=> SD_Theme::r ('character_role'),
		'resume'	=> SD_Theme::r ('character_resume'),
		'images'	=> $images
		];

	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$character = null;
		}

	if (!is_null ($character)) {
		try {
			$character->save ();
			}
		catch (SD_Exception $e) {
			$character = null;
			}
		}

	SD_Theme::prg ($error, TRUE);
	}

if (isset ($_POST['character_update'])) {
	if (isset ($_POST['character'])) {
		try {
			$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
			}
		catch (SD_Exception $e) {
			$character = null;
			}

		$images = $character->get ('images');
		if (!empty (SD_Character::$S))
			foreach (SD_Character::$S as $key => $value) {
				if (!empty ($_FILES['character_' . $key])) {
					$data = $_FILES['character_' . $key];

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
			'name'		=> SD_Theme::r ('character_name'),
			'position'	=> SD_Theme::r ('character_position'),
			'role'		=> SD_Theme::r ('character_role'),
			'resume'	=> SD_Theme::r ('character_resume'),
			'images'	=> $images
			];

		try {
			$character->set ($data);
			}
		catch (SD_Exception $e) {
			}

		SD_Theme::prg ($error);
		}
	}
?>
