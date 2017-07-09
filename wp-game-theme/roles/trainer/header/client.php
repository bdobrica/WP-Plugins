<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['scenario_update'])) {
	$data = [
		'company_name'		=> SD_Theme::r ('company_name'),
		'company_description'	=> SD_Theme::r ('company_description'),

		'buying_mode'		=> SD_Theme::r ('buying_mode'),
		'price_weight'		=> SD_Theme::r ('price_weight', 'float'),
		'adv_budg_weight'	=> SD_Theme::r ('adv_budg_weight', 'float'),
		'paym_term_weight'	=> SD_Theme::r ('paym_term_weight', 'float'),
		'delivery_weight'	=> SD_Theme::r ('delivery_weight', 'float'),
		'features_weight'	=> SD_Theme::r ('features_weight', 'float'),
		'warranty_weight'	=> SD_Theme::r ('warranty_weight', 'float'),
		];

	$scenario = $sd_theme->get ('scenario');
	if (!is_null ($scenario)) {
		$scenario->set ($data);
		SD_Theme::prg ();
		}
	}
?>
