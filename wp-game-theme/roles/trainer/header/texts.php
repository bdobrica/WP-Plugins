<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update'])) {
	$data = [
		'round0_begin_message'		=> SD_Theme::r ('round0_begin_message'),
		'round0_end_message'		=> SD_Theme::r ('round0_end_message'),
		'round1_begin_message'		=> SD_Theme::r ('round1_begin_message'),
		'round1_end_message'		=> SD_Theme::r ('round1_end_message'),
		'round2_begin_message'		=> SD_Theme::r ('round2_begin_message'),
		'round2_end_message'		=> SD_Theme::r ('round2_end_message'),
		'round3_begin_message'		=> SD_Theme::r ('round3_begin_message'),
		'round3_end_message'		=> SD_Theme::r ('round3_end_message'),
		'round4_begin_message'		=> SD_Theme::r ('round4_begin_message'),
		'round4_end_message'		=> SD_Theme::r ('round4_end_message'),
		];

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
