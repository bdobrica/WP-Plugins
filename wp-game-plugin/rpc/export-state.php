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

function _offer_cmp ($a, $b) {
	$str_a = strtolower (trim ($a['name'] . $a['product'] . $a['quality']));
	$str_b = strtolower (trim ($b['name'] . $b['product'] . $b['quality']));

	return strcmp ($str_a, $str_b);
	}

function _team_cmp ($a, $b) {
	$str_a = strtolower (trim ($a['name']));
	$str_b = strtolower (trim ($b['name']));

	return strcmp ($str_a, $str_b);
	}

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

$characters = new SD_List ('SD_Character', $sd_game->scenario ('path'));

if (!$characters->is ('empty')) {
	foreach ($characters->get () as $_character) {
		$character[SD_Language::slug ($_character->get ('name'))] = $_character->get ('name');
		}
	}
else
	$character['no_character'] = 'No Character';

$warranties = new SD_List ('SD_Warranty', $sd_game->scenario ('path'));
$warranties_list = $warranties->get ();
$features = new SD_List ('SD_Feature', $sd_game->scenario ('path'));
$features_list = $features->get ();
$locations = new SD_List ('SD_Location', $sd_game->scenario ('path'));
$locations_list = $locations->get ();


$conversation = [];
$offer = [];
$purchase = [];
$presentation = [];
$counter_a = [];
$counter_b = [];
$counter_c = [];
$offer_a = [];
$offer_b = [];
$offer_c = [];

foreach ($players->get () as $player) {
	$player_name = $player->get ('name');
	$state = $player->get ('state');
	$emails_array = $player->get ('emails_array');
	$player_r2_score = $player->get ('score', 'round2');

	$conversation_user = [
		'name'	=> $player_name,
		'score'	=> $player->get ('score', 'round1')
		];

	if (!empty ($emails_array)) {
		$email = [];
		$count = 1;
		foreach ($emails_array as $email_addr) {
			$key = 'email_' . $count;
			$val = 'Email ' . $count;

			$email[$key] = $val;
			$conversation_user[$key] = $email_addr;
			$count ++;
			}
		}
	else {
		$email = [ 'email_1' => 'Email 1' ];
		$conversation_user['email_1'] = '';
		}

	$characters_score = $player->get ('score', 'characters');
	foreach ($character as $character_key => $character_name) {
		$conversation_user[$character_key] = isset ($characters_score[$character_key]) ? $characters_score[$character_key] : 0;
		}

	$conversation[] = $conversation_user;

	if (isset ($state[SD_Game::ROUND2_BEGIN]['data']['quotations']) && !empty ($state[SD_Game::ROUND2_BEGIN]['data']['quotations']))
		foreach ($state[SD_Game::ROUND2_BEGIN]['data']['quotations'] as $quotation) {
			$warranty = isset ($warranties_list[$quotation['warranty']]) ? $warranties_list[$quotation['warranty']]->get ('name') : 'None';
			$location = isset ($locations_list[$quotation['location']]) ? $locations_list[$quotation['location']]->get ('name') : '';
			$features = [];
			if (!empty ($quotation['features']))
				foreach ($quotation['features'] as $_feature_slug)
					if (isset ($features_list[$_feature_slug]))
						$features[] = $features_list[$_feature_slug]->get ('name');
			$offer[] = [
				'name'		=> $player_name,
				'product'	=> $pcf[$quotation['product']],
				'quality'	=> $qcf[$quotation['quality']],
				'features'	=> implode (',', $features),
				'warranty'	=> $warranty,
				'quantity'	=> $quotation['quantity'],
				'price'		=> $quotation['price'],
				'payment_term'	=> $quotation['payment_term'],
				'location'	=> $location,
				'delivery_term'	=> $quotation['delivery_term'],
				'advertising'	=> $quotation['advertising_budget'],
				'score'		=> $player_r2_score
				];
			}
	if (isset ($state[SD_Game::ROUND2_BEGIN]['data']['acquired']) && !empty ($state[SD_Game::ROUND2_BEGIN]['data']['acquired']))
		foreach ($state[SD_Game::ROUND2_BEGIN]['data']['acquired'] as $acquired_product => $acquired_data)
			if (!empty ($acquired_data))
				foreach ($acquired_data as $acquired_quality => $acquired_quantity)
					$purchase[] = [
						'name'		=> $player_name,
						'product'	=> $pcf[$acquired_product],
						'quality'	=> $qcf[$acquired_quality],
						'quantity'	=> $acquired_quantity
						];

	$presentation[] = [
		'name'	=> $player_name,
		'score'	=> $player->get ('score', 'round3')
		];

	if (!empty ($state[SD_Game::ROUND4_BEGIN]['data']['negotiation'])) {
		$negotiation = $state[SD_Game::ROUND4_BEGIN]['data']['negotiation'];
		$counter = $state[SD_Game::ROUND4_BEGIN]['data']['counter'];

		foreach ($negotiation[SD_Game::NEGOTIATION_1] as $quotation) {
			$warranty = isset ($warranties_list[$quotation['warranty']]) ? $warranties_list[$quotation['warranty']]->get ('name') : 'None';
			$location = isset ($locations_list[$quotation['location']]) ? $locations_list[$quotation['location']]->get ('name') : '';
			$features = [];
			if (!empty ($quotation['features']))
				foreach ($quotation['features'] as $_feature_slug)
					if (isset ($features_list[$_feature_slug]))
						$features[] = $features_list[$_feature_slug]->get ('name');

			$offer_a[] = [
				'name'		=> $player_name,
				'product'	=> $pcf[$quotation['product']],
				'quality'	=> $qcf[$quotation['quality']],
				'features'	=> implode (',', $features),
				'warranty'	=> $warranty,
				'quantity'	=> $quotation['quantity'],
				'price'		=> $quotation['price'],
				'payment_term'	=> $quotation['payment_term'],
				'location'	=> $location,
				'delivery_term'	=> $quotation['delivery_term'],
				'advertising'	=> $quotation['advertising_budget']
				];
			}
		if (!empty($counter[SD_Game::NEGOTIATION_1])) {
			$quotation = [];
			foreach ($counter[SD_Game::NEGOTIATION_1] as $parameter_slug => $parameter_data) {
				if (!empty ($parameter_data))
				foreach ($parameter_data as $product_slug => $product_data) {
					if (!empty ($product_data))
					foreach ($product_data as $quality_slug => $quality_value) {
						$quotation[$product_slug][$quality_slug][$parameter_slug] = $quality_value;
						if (!isset ($quotation[$product_slug][$quality_slug]['name'])) {
							$quotation[$product_slug][$quality_slug]['name'] = $player_name;
							$quotation[$product_slug][$quality_slug]['product'] = $pcf[$product_slug];
							$quotation[$product_slug][$quality_slug]['quality'] = $qcf[$quality_slug];
							}
						}
					}
				}

			if (!empty ($quotation))
			foreach ($quotation as $quotation_product) {
				if (!empty ($quotation_product))
				foreach ($quotation_product as $quotation_quality) {
					$warranty = isset ($warranties_list[$quotation_quality['warranty']]) ? $warranties_list[$quotation_quality['warranty']]->get ('name') : 'None';
					$features = [];
					if (!empty ($quotation_quality['features']))
						foreach ($quotation_quality['features'] as $_feature_slug)
							if (isset ($features_list[$_feature_slug]))
								$features[] = $features_list[$_feature_slug]->get ('name');

					$quotation_quality['warranty'] = $warranty;
					$quotation_quality['features'] = implode (',', $features);

					$counter_a[] = $quotation_quality;
					}
				}
			}
		foreach ($negotiation[SD_Game::NEGOTIATION_2] as $quotation) {
			$warranty = isset ($warranties_list[$quotation['warranty']]) ? $warranties_list[$quotation['warranty']]->get ('name') : 'None';
			$location = isset ($locations_list[$quotation['location']]) ? $locations_list[$quotation['location']]->get ('name') : '';
			$features = [];
			if (!empty ($quotation['features']))
				foreach ($quotation['features'] as $_feature_slug)
					if (isset ($features_list[$_feature_slug]))
						$features[] = $features_list[$_feature_slug]->get ('name');

			$offer_b[] = [
				'name'		=> $player_name,
				'product'	=> $pcf[$quotation['product']],
				'quality'	=> $qcf[$quotation['quality']],
				'features'	=> implode (',', $features),
				'warranty'	=> $warranty,
				'quantity'	=> $quotation['quantity'],
				'price'		=> $quotation['price'],
				'payment_term'	=> $quotation['payment_term'],
				'location'	=> $location,
				'delivery_term'	=> $quotation['delivery_term'],
				'advertising'	=> $quotation['advertising_budget']
				];
			}
		if (!empty($counter[SD_Game::NEGOTIATION_2])) {
			$quotation = [];
			foreach ($counter[SD_Game::NEGOTIATION_2] as $parameter_slug => $parameter_data) {
				if (!empty ($parameter_data))
				foreach ($parameter_data as $product_slug => $product_data) {
					if (!empty ($product_data))
					foreach ($product_data as $quality_slug => $quality_value) {
						$quotation[$product_slug][$quality_slug][$parameter_slug] = $quality_value;
						if (!isset ($quotation[$product_slug][$quality_slug]['name'])) {
							$quotation[$product_slug][$quality_slug]['name'] = $player_name;
							$quotation[$product_slug][$quality_slug]['product'] = $pcf[$product_slug];
							$quotation[$product_slug][$quality_slug]['quality'] = $qcf[$quality_slug];
							}
						}
					}
				}

			if (!empty ($quotation))
			foreach ($quotation as $quotation_product) {
				if (!empty ($quotation_product))
				foreach ($quotation_product as $quotation_quality) {
					$warranty = isset ($warranties_list[$quotation_quality['warranty']]) ? $warranties_list[$quotation_quality['warranty']]->get ('name') : 'None';
					$features = [];
					if (!empty ($quotation_quality['features']))
						foreach ($quotation_quality['features'] as $_feature_slug)
							if (isset ($features_list[$_feature_slug]))
								$features[] = $features_list[$_feature_slug]->get ('name');

					$quotation_quality['warranty'] = $warranty;
					$quotation_quality['features'] = implode (',', $features);

					$counter_b[] = $quotation_quality;
					}
				}
			}
		foreach ($negotiation[SD_Game::NEGOTIATION_3] as $quotation) {
			$warranty = isset ($warranties_list[$quotation['warranty']]) ? $warranties_list[$quotation['warranty']]->get ('name') : 'None';
			$location = isset ($locations_list[$quotation['location']]) ? $locations_list[$quotation['location']]->get ('name') : '';
			$features = [];
			if (!empty ($quotation['features']))
				foreach ($quotation['features'] as $_feature_slug)
					if (isset ($features_list[$_feature_slug]))
						$features[] = $features_list[$_feature_slug]->get ('name');

			$offer_c[] = [
				'name'		=> $player_name,
				'product'	=> $pcf[$quotation['product']],
				'quality'	=> $qcf[$quotation['quality']],
				'features'	=> implode (',', $features),
				'warranty'	=> $warranty,
				'quantity'	=> $quotation['quantity'],
				'price'		=> $quotation['price'],
				'payment_term'	=> $quotation['payment_term'],
				'location'	=> $location,
				'delivery_term'	=> $quotation['delivery_term'],
				'advertising'	=> $quotation['advertising_budget']
				];
			}
		if (!empty($counter[SD_Game::NEGOTIATION_3])) {
			$quotation = [];
			foreach ($counter[SD_Game::NEGOTIATION_3] as $parameter_slug => $parameter_data) {
				if (!empty ($parameter_data))
				foreach ($parameter_data as $product_slug => $product_data) {
					if (!empty ($product_data))
					foreach ($product_data as $quality_slug => $quality_value) {
						$quotation[$product_slug][$quality_slug][$parameter_slug] = $quality_value;
						if (!isset ($quotation[$product_slug][$quality_slug]['name'])) {
							$quotation[$product_slug][$quality_slug]['name'] = $player_name;
							$quotation[$product_slug][$quality_slug]['product'] = $pcf[$product_slug];
							$quotation[$product_slug][$quality_slug]['quality'] = $qcf[$quality_slug];
							}
						}
					}
				}

			if (!empty ($quotation))
			foreach ($quotation as $quotation_product) {
				if (!empty ($quotation_product))
				foreach ($quotation_product as $quotation_quality) {
					$warranty = isset ($warranties_list[$quotation_quality['warranty']]) ? $warranties_list[$quotation_quality['warranty']]->get ('name') : 'None';
					$features = [];
					if (!empty ($quotation_quality['features']))
						foreach ($quotation_quality['features'] as $_feature_slug)
							if (isset ($features_list[$_feature_slug]))
								$features[] = $features_list[$_feature_slug]->get ('name');

					$quotation_quality['warranty'] = $warranty;
					$quotation_quality['features'] = implode (',', $features);

					$counter_c[] = $quotation_quality;
					}
				}
			}
		}
	}

$TBS = new clsTinyButStrong ();
$TBS->SetOption ('noerr', true);
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
if (file_exists (dirname (__DIR__) . '/assets/xlsx/export-state-all-override.xlsx'))
	$TBS->LoadTemplate(dirname (__DIR__) . '/assets/xlsx/export-state-all-override.xlsx', OPENTBS_ALREADY_UTF8);
else
	$TBS->LoadTemplate(dirname (__DIR__) . '/assets/xlsx/export-state-all.xlsx', OPENTBS_ALREADY_UTF8);

$sheets = [
	'Conversations',
	'Offers',
	'Purchases',
	'Presentations',
	'Counter1',
	'Nego1',
	'Counter2',
	'Nego2',
	'Counter3',
	'Nego3'
	];

foreach ($sheets as $sheet) {
	$TBS->PlugIn (OPENTBS_SELECT_SHEET, $sheet);

	switch ($sheet) {
		case 'Conversations':
			if (!empty ($email))
				$TBS->MergeBlock ('email0,email1', $email);
			if (!empty ($character))
				$TBS->MergeBlock ('character0,character1', $character);
			if (!empty ($conversation)) {
				usort ($conversation, '_team_cmp');
				$TBS->MergeBlock ('conversation', $conversation);
				}
			break;
		case 'Offers':
			if (!empty ($offer)) {
				usort ($offer, '_offer_cmp');
				$TBS->MergeBlock ('offer', $offer);
				}
			break;
		case 'Purchases':
			if (!empty ($purchase)) {
				usort ($purchase, '_offer_cmp');
				$TBS->MergeBlock ('purchase', $purchase);
				}
			break;
		case 'Presentations':
			if (!empty ($presentation)) {
				usort ($presentation, '_team_cmp');
				$TBS->MergeBlock ('presentation', $presentation);
				}
			break;
		case 'Counter1':
			if (!empty ($counter_a)) {
				usort ($counter_a, '_offer_cmp');
				$TBS->MergeBlock ('counter_a', $counter_a);
				}
			break;
		case 'Counter2':
			if (!empty ($counter_b)) {
				usort ($counter_b, '_offer_cmp');
				$TBS->MergeBlock ('counter_b', $counter_b);
				}
			break;
		case 'Counter3':
			if (!empty ($counter_a)) {
				usort ($counter_c, '_offer_cmp');
				$TBS->MergeBlock ('counter_c', $counter_c);
				}
			break;
		case 'Nego1':
			if (!empty ($offer_a)) {
				usort ($offer_a, '_offer_cmp');
				$TBS->MergeBlock ('offer_a', $offer_a);
				}
			break;
		case 'Nego2':
			if (!empty ($offer_b)) {
				usort ($offer_b, '_offer_cmp');
				$TBS->MergeBlock ('offer_b', $offer_b);
				}
			break;
		case 'Nego3':
			if (!empty ($offer_c)) {
				usort ($offer_c, '_offer_cmp');
				$TBS->MergeBlock ('offer_c', $offer_c);
				}
			break;
		}
	}

#$TBS->PlugIn(OPENTBS_DEBUG_XML);
$TBS->Show(OPENTBS_DOWNLOAD, vsprintf ('export-game-%d-date-%s.xlsx', [$sd_game->get (), date ('dmy-His')]));
?>
