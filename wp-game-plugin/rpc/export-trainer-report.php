<?php
/**
 * Core of SD_*
 */

/**
 * Entry point for all ajax calls maade from interface elements.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('SD_AJAX_PROTECTION', TRUE);
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
include (dirname (__DIR__) . '/class/tbs/tbs_class.php');
include (dirname (__DIR__) . '/class/tbs/plugins/tbs_plugin_opentbs.php');


/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

global $sd_game;

$sd_user = $sd_theme->get ('user');

$games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', $sd_user->get ())]);
if ($games->is ('empty'))
	$sd_game = null;
else
	$sd_game = $games->get ('last');

if (is_null ($sd_game))
	die (/*T[*/'Error'/*]*/);

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
$players->sort ('name');
if ($players->is ('empty'))
	die (/*T[*/'Error'/*]*/);

$report = [
	'game_name'	=> $sd_game->get ('name'),
	'game_date'	=> $sd_game->get ('date')
	];

$scores = [];
$finals = [];
$conversations = [];
$hints = [];
$products = [];
$overview = [];

foreach ($players->get () as $player) {
	$data = $player->report_data ();

	$scores[]		= $data['scores'];
	$finals[]		= $data['finals'];
	$conversations[]	= $data['conversations'];
	$hints[]		= $data['hints'];
	$products[]		= $data['products']; //array_merge ($products, $data['products']);
	$overview[]		= $data['overview'];
	}


if (!class_exists ('clsTinyButStrong')) {
	include (__DIR__ . '/tbs/tbs_class.php');
	include (__DIR__ . '/tbs/plugins/tbs_plugin_opentbs.php');
	}

$tbs = new clsTinyButStrong ();
$tbs->PlugIn(TBS_INSTALL, OPENTBS_PLUGIN);

$tbs->LoadTemplate(dirname (__DIR__) . '/assets/docx/salesdrive-trainer-report.docx', OPENTBS_ALREADY_UTF8);


$market = $sd_game->get ('market');
$chart_no = 1;
$_products = new SD_List ('SD_Product', $sd_game->scenario ('path'));
foreach ($_products->get () as $_product) {
	$qualities = $_product->get ('quality');
	foreach ($qualities as $quality_slug => $quality_data) {
		$chart = [['name' => $_product->get ('name') . ' / ' . $quality_data['name'], 'delete' => 0]];
		$tbs->MergeBlock ('chart_' . $chart_no, $chart);


		$chart_values = [];
		foreach ($players->get () as $player) {
			$chart_values[0][] = htmlentities ($player->get ('name'));
			$chart_values[1][] = (int) $market[$player->get ()][$_product->get ()][$quality_slug];
			/*
			$charts[$product->get ()][$quality_slug][] = [
				$player->get ('name') . "\n" . 
					'Q' . ': ' . ((int) $market[$player->get ()][$product->get ()][$quality_slug]) . $quality_data['unit_type'] . 
					' P' . ': ' . $quoted_price[$product->get ()][$quality_slug] . $sd_game->scenario ('currency') .
					' PV' . ': ' . $perceived_values[$player->get ()][$product->get ()][$quality_slug] . $sd_game->scenario ('currency'),
				(int) $market[$player->get ()][$product->get ()][$quality_slug]
				];
			*/
			}
		$tbs->PlugIn (OPENTBS_CHART, 'Chart ' . $chart_no, 'Sales', $chart_values);

		$chart_no ++;
		}
	}
for ( ;$chart_no < 4; $chart_no++) {
	$chart = [['name' => '', 'delete' => 1]];
	$tbs->MergeBlock ('chart_' . $chart_no, $chart);
	}

#$tbs->PlugIn(OPENTBS_DEBUG_INFO);

$tbs->MergeField ('report', [
	'game_name'	=> $sd_game->get ('name'),
	'game_date'	=> date ('d-m-Y')
	]);

$tbs->MergeBlock ('scores', $scores);
$tbs->MergeBlock ('finals', $finals);
$tbs->MergeBlock ('conversations', $conversations);
$tbs->MergeBlock ('hints', $hints);

$tbs->MergeBlock ('product', $products);
$tbs->MergeBlock ('overview', $overview);

$filename = vsprintf ('report_%s_%s.docx', [date ('dmy'), $sd_game->get ()]);
$tbs->Show (OPENTBS_DOWNLOAD, $filename);
?>
