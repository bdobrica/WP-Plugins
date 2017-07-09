<?php
$error = null;

if (isset ($_POST['scenario_saveas'])) {
	$scenario = SD_Theme::r ('scenario_new');
	}
if (isset ($_POST['scenario_export'])) {
	}
if (isset ($_POST['scenario_load'])) {
	try {
		$scenario = new SD_Scenario (SD_Theme::r ('scenario_slug'));
		}
	catch (SD_Exception $e) {
		$scenario = null;
		if (is_null ($error))
			$error = ['load_error', $e->get ('code')];
		else
			$error['load_error'] = $e->get ('code');
		}
	if (!is_null ($scenario)) {
		$sd_theme->set ('scenario', $scenario);
		}
	SD_Theme::prg ($error);
	}
if (isset ($_POST['scenario_upload'])) {
	}
?>
