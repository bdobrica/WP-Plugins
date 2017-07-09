<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update'])) {
	$data = [
		'financing_cost'	=> SD_Theme::r ('financing_cost'),
		'currency'		=> SD_Theme::r ('currency'),
		'allow_sending_emails'	=> SD_Theme::r ('allow_sending_emails'),
		'reusable_questions'	=> SD_Theme::r ('reusable_questions'),
		'enable_purchase'	=> SD_Theme::r ('enable_purchase'),
		'round_3_min_score'	=> SD_Theme::r ('round_3_min_score'),
		'round_3_max_score'	=> SD_Theme::r ('round_3_max_score')
		];

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
