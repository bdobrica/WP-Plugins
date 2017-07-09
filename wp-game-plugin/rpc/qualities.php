<?php
/**
 * Core of SD_*
 */

/**
 * Quality.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

try {
	$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('product'));
	}
catch (SD_Exception $exception) {
	echo json_encode ((object) ['error' => 1]);
	exit (1);
	}

$qualities = $product->get ('quality');

$sd_user = $sd_theme->get ('user');
$all_states = $sd_user->get ('state');
$state = $all_states[SD_Game::ROUND3_BEGIN];
$purchasable = $sd_user->compute ($product, 'purchasable_quantity');
$available = $sd_user->compute ($product, 'units_available');

foreach ($qualities as $quality_slug => $quality_data) {
	$qualities[$quality_slug]['purchasable_quantity'] = $purchasable[$quality_slug];
	$qualities[$quality_slug]['quantity'] = $available[$quality_slug];
	}

echo json_encode ($qualities);
?>
