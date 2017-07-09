<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['location_create'])) {
	$data = [
		'name'			=> SD_Theme::r ('location_name'),
		'delivery_time'		=> SD_Theme::r ('location_delivery_time', 'integer'),
		'delivery_cost'		=> SD_Theme::r ('location_delivery_cost', 'percent'),
		'day_saved_cost'	=> SD_Theme::r ('location_day_saved_cost', 'percent'),
		'max_delivery_time'	=> SD_Theme::r ('location_max_delivery_time', 'integer'),
		'desired_delivery_time'	=> SD_Theme::r ('location_desired_delivery_time', 'integer')
		];

	try {
		$location = new SD_Location ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$location = null;
		}

	if (!is_null ($location)) {
		try {
			$location->save ();
			}
		catch (SD_Exception $e) {
			$location = null;
			}
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['location'])) {
	try {
		$location = new SD_Location ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('location'));
		}
	catch (SD_Exception $e) {
		$location = null;
		}
	if (!is_null ($location)) {
		if (isset ($_POST['location_delete'])) {
			try {
				$location->remove ();
				}
			catch (SD_Exception $e) {
				}
			}
		if (isset ($_POST['location_update'])) {
			$data = [
				'name'			=> SD_Theme::r ('location_name'),
				'delivery_time'		=> SD_Theme::r ('location_delivery_time', 'integer'),
				'delivery_cost'		=> SD_Theme::r ('location_delivery_cost', 'percent'),
				'day_saved_cost'	=> SD_Theme::r ('location_day_saved_cost', 'percent'),
				'max_delivery_time'	=> SD_Theme::r ('location_max_delivery_time', 'integer'),
				'desired_delivery_time'	=> SD_Theme::r ('location_desired_delivery_time', 'integer')
				];
			$location->set ($data);
			}
		}

	SD_Theme::prg ($error);
	}
?>
