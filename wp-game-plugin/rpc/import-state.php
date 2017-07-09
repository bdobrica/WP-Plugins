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
include (dirname (__DIR__) . '/class/phpexcelreader/SpreadsheetReader.php');

/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

global $sd_game;

$sd_user = $sd_theme->get ('user');

$games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', $sd_user->get ())]);
if ($sd_games->is ('empty'))
	$sd_game = null;
else
	$sd_game = $games->get ('last');

if (is_null ($sd_game))
	die (/*T[*/'Error'/*]*/);

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
if ($players->is ('empty'))
	die (/*T[*/'Error'/*]*/);

$pcf = [];
$pct = [];
$qcf = [];
$qct = [];
$products = new SD_List ('SD_Product', $sd_game->scenario ('path'));
foreach ($products->get () as $product) {
	$pcf[$product->get ()] = $product->get ('name');
	$pct[$product->get ('name')] = $product->get ();

	$qualities = $product->get ('quality');
	foreach ($qualities as $quality_slug => $quality_data) {
		$qcf[$quality_slug] = $quality_data['name'];
		$qct[$quality_data['name']] = $quality_slug;
		}
	}

foreach ($players->get () as $player) {
	}

$data = new SpreadsheetReader ('demo.xlsx');
?>
