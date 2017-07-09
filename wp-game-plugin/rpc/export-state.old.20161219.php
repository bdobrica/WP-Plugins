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
	$state = $player->get ('state');

	switch ($sd_game->get ('state')) {
		case SD_Game::ROUND1_END:
			$team[] = [
				'name'	=> $player->get ('name'),
				'score'	=> $player->get ('score', 'round1')
				];
			break;
		case SD_Game::ROUND2_END:
			$state = $state[SD_Game::ROUND2_BEGIN];
			if (isset ($state['data']['quotations']) && !empty ($state['data']['quotations']))
				foreach ($state['data']['quotations'] as $quotation)
					$offer[] = [
						'name'		=> $player->get ('name'),
						'product'	=> $pcf[$quotation['product']],
						'quality'	=> $qcf[$quotation['quality']],
						'features'	=> implode (',', $quotation['features']),
						'warranty'	=> $quotation['warranty'],
						'quantity'	=> $quotation['quantity'],
						'price'		=> $quotation['price'],
						'payment_term'	=> $quotation['payment_term'],
						'location'	=> $quotation['location'],
						'delivery_term'	=> $quotation['delivery_term'],
						'advertising'	=> $quotation['advertising_budget']
						];
			if (isset ($state['data']['acquired']) && !empty ($state['data']['acquired']))
				foreach ($state['data']['acquired'] as $acquired_product => $acquired_data)
					if (!empty ($acquired_data))
						foreach ($acquired_data as $acquired_quality => $acquired_quantity)
							$purchased[] = [
								'name'		=> $player->get ('name'),
								'product'	=> $pcf[$acquired_product],
								'quality'	=> $qcf[$acquired_quality],
								'quantity'	=> $acquired_quantity
								];
			break;
		case SD_Game::ROUND3_END:
			$team[] = [
				'name'	=> $player->get ('name'),
				'score'	=> $player->get ('score', 'round3')
				];
			break;
		case SD_Game::ROUND4_END:
			$state = $state[SD_Game::ROUND4_BEGIN];
			if (!empty ($state['data']['negotiation']))
				$negotiation = $state['data']['negotiation'];

			foreach ($negotiation[SD_Game::NEGOTIATION_1] as $quotation) {
				$offer_a[] = [
					'name'		=> $player->get ('name'),
					'product'	=> $pcf[$quotation['product']],
					'quality'	=> $qcf[$quotation['quality']],
					'features'	=> implode (',', $quotation['features']),
					'warranty'	=> $quotation['warranty'],
					'quantity'	=> $quotation['quantity'],
					'price'		=> $quotation['price'],
					'payment_term'	=> $quotation['payment_term'],
					'location'	=> $quotation['location'],
					'delivery_term'	=> $quotation['delivery_term'],
					'advertising'	=> $quotation['advertising_budget']
					];
				}
			foreach ($negotiation[SD_Game::NEGOTIATION_2] as $quotation) {
				$offer_b[] = [
					'name'		=> $player->get ('name'),
					'product'	=> $pcf[$quotation['product']],
					'quality'	=> $qcf[$quotation['quality']],
					'features'	=> implode (',', $quotation['features']),
					'warranty'	=> $quotation['warranty'],
					'quantity'	=> $quotation['quantity'],
					'price'		=> $quotation['price'],
					'payment_term'	=> $quotation['payment_term'],
					'location'	=> $quotation['location'],
					'delivery_term'	=> $quotation['delivery_term'],
					'advertising'	=> $quotation['advertising_budget']
					];
				}
			foreach ($negotiation[SD_Game::NEGOTIATION_3] as $quotation) {
				$offer_c[] = [
					'name'		=> $player->get ('name'),
					'product'	=> $pcf[$quotation['product']],
					'quality'	=> $qcf[$quotation['quality']],
					'features'	=> implode (',', $quotation['features']),
					'warranty'	=> $quotation['warranty'],
					'quantity'	=> $quotation['quantity'],
					'price'		=> $quotation['price'],
					'payment_term'	=> $quotation['payment_term'],
					'location'	=> $quotation['location'],
					'delivery_term'	=> $quotation['delivery_term'],
					'advertising'	=> $quotation['advertising_budget']
					];
				}
			break;
		}	
	}

$TBS = new clsTinyButStrong ();
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
$TBS->LoadTemplate(dirname (__DIR__) . '/assets/xlsx/export-state-' . $sd_game->get ('state') . '.xlsx', OPENTBS_ALREADY_UTF8);

if (isset ($team))
	$TBS->MergeBlock ('team', $team);

if (isset ($offer) && isset ($purchased)) {
	$TBS->MergeBlock ('offer', $offer);
	$TBS->PlugIn (OPENTBS_SELECT_SHEET, 2);
	$TBS->MergeBlock ('purchased', $purchased);
	}
if (isset ($offer_a) && isset ($offer_b) && isset ($offer_c)) {
	$TBS->MergeBlock ('offer_a', $offer_a);
	$TBS->PlugIn (OPENTBS_SELECT_SHEET, 2);
	$TBS->MergeBlock ('offer_b', $offer_b);
	$TBS->PlugIn (OPENTBS_SELECT_SHEET, 3);
	$TBS->MergeBlock ('offer_c', $offer_c);
	}

$TBS->Show(OPENTBS_DOWNLOAD, vsprintf ('export-game-%d-state-%d-date-%s.xlsx', [$sd_game->get (), $sd_game->get ('state'), date ('dmy')]));
?>
