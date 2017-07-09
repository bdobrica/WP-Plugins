<?php
if (isset ($_POST['scenario_copy'])) {
	$scenario = $sd_theme->get ('scenario');
	$new_scenario = new SD_Scenario ();
	try {
		$new_scenario->set ($scenario, SD_Theme::r ('scenario_saveas'));
		$sd_theme->set ('scenario', $new_scenario);
		}
	catch (SD_Exception $e) {
		$error = ['error_copy' => $e->get ('code')];
		}
	SD_Theme::prg ($error);
	}
?>
