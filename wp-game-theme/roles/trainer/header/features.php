<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['feature_create'])) {
	$data = [
		'name'		=> SD_Theme::r ('feature_name'),
		'cost'		=> SD_Theme::r ('feature_cost', 'percent'),
		'mandatory'	=> SD_Theme::r ('feature_mandatory') ? TRUE : FALSE,
		'negotiable'	=> SD_Theme::r ('feature_negotiable') ? TRUE : FALSE
		];

	try {
		$feature = new SD_Feature ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$feature = null;
		}

	if (!is_null ($feature)) {
		try {
			$feature->save ();
			}
		catch (SD_Exception $e) {
			$feature = null;
			}
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['feature'])) {
	try {
		$feature = new SD_Feature ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('feature'));
		}
	catch (SD_Exception $e) {
		$feature = null;
		}
	if (!is_null ($feature)) {
		if (isset ($_POST['feature_delete'])) {
			try {
				$feature->remove ();
				}
			catch (SD_Exception $e) {
				}
			}
		if (isset ($_POST['feature_update'])) {
			$data = [
				'name'		=> SD_Theme::r ('feature_name'),
				'cost'		=> SD_Theme::r ('feature_cost', 'percent'),
				'mandatory'	=> SD_Theme::r ('feature_mandatory') ? TRUE : FALSE,
				'negotiable'	=> SD_Theme::r ('feature_negotiable') ? TRUE : FALSE
				];
			$feature->set ($data);
			}
		}

	SD_Theme::prg ($error);
	}
?>
