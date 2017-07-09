<?php
/**
 * Core of SD_*
 */

/**
 * Timer. Handles timer events.
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

$sd_user = $sd_theme->get ('user');
$all_states = $sd_user->get ('state');

$negotiation_step = $sd_user->get ('negotiation_step');

if (isset ($_POST['negotiation_counter'])) {
	if ($negotiation_step != SD_Game::NEGOTIATION_3) {
		$quotations = $sd_user->get ('quotations', TRUE);

		$counter = [];
		foreach ($quotations as $quotation_id => $quotation_data)
			$counter[$quotation_id] = $sd_user->quotation ($quotation_data, 'counter');
		
		echo json_encode ((object) [ 'timer' => $sd_user->get ('game', 'negotiation_answer_timer'), 'step' => $negotiation_step, 'get_step' => $sd_user->get ('negotiation_step'), 'counter' => $counter ]);
		exit (1);
		}

	echo json_encode ((object) [ 'reload' => 1 ]);
	exit (1);
	}

if (isset ($_POST['negotiation_timeout'])) {
	$all_states[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_3] = $sd_user->get ('counter_offer');
	$negotiation_step = $all_states[SD_Game::ROUND4_BEGIN]['step'] = SD_Game::NEGOTIATION_3;
	$all_states[SD_Game::ROUND4_BEGIN]['show_hints'] = FALSE;
	$all_states[SD_Game::ROUND4_BEGIN]['in_progress'] = FALSE;
	$all_states[SD_Game::ROUND4_BEGIN]['submitted'] = TRUE;

	$sd_user->set ('state', $all_states);

	echo json_encode ((object) [ 'reload' => 1 ]);
	exit (1);
	}

if (isset ($_POST['negotiation_submit'])) {
	switch ($negotiation_step) {
		case SD_Game::NEGOTIATION_0:
			$all_states[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_1] = $sd_user->get ('counter_offer');
			$negotiation_step = $all_states[SD_Game::ROUND4_BEGIN]['step'] = SD_Game::NEGOTIATION_1;
			break;
		case SD_Game::NEGOTIATION_1:
			$all_states[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_2] = $sd_user->get ('counter_offer');
			$negotiation_step = $all_states[SD_Game::ROUND4_BEGIN]['step'] = SD_Game::NEGOTIATION_2;
			break;
		case SD_Game::NEGOTIATION_2:
			$all_states[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_3] = $sd_user->get ('counter_offer');
			$negotiation_step = $all_states[SD_Game::ROUND4_BEGIN]['step'] = SD_Game::NEGOTIATION_3;
			$all_states[SD_Game::ROUND4_BEGIN]['show_hints'] = FALSE;
			$all_states[SD_Game::ROUND4_BEGIN]['in_progress'] = FALSE;
			$all_states[SD_Game::ROUND4_BEGIN]['submitted'] = TRUE;
			break;
		}

	$sd_user->set ('state', $all_states);

	if ($negotiation_step != SD_Game::NEGOTIATION_3) {
		$quotations = $sd_user->get ('quotations', TRUE);

		$counter = [];
		foreach ($quotations as $quotation_id => $quotation_data)
			$counter[$quotation_id] = $sd_user->quotation ($quotation_data, 'counter');
		
#		echo json_encode ((object) [ 'timer' => $sd_user->get ('game', 'negotiation_answer_timer'), 'step' => $negotiation_step, 'get_step' => $sd_user->get ('negotiation_step'), 'counter' => $counter ]);
#		exit (1);
		}

	echo json_encode ((object) [ 'success' => 1 ]);
	exit (1);
	}

switch ($negotiation_step) {
	case SD_Game::NEGOTIATION_0:
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1]))
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1] = $all_states[SD_Game::ROUND2_BEGIN]['data']['quotations'];

		$quotation_id = SD_Theme::r ('quotation');
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1][$quotation_id])) {
			echo json_encode ((object) [ 'error' => 1 ]);
			exit (1);
			}
		$quotation = $all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1][$quotation_id];

		$quotation['features']			= SD_Theme::r ('features');
		$quotation['delivery_term']		= SD_Theme::r ('delivery_term');
		$quotation['price']			= SD_Theme::r ('price');
		$quotation['advertising_budget']	= SD_Theme::r ('advertising_budget');
		$quotation['payment_term']		= SD_Theme::r ('payment_term');
		$quotation['warranty']			= SD_Theme::r ('warranty');

		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1][$quotation_id] = $quotation;
		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2][$quotation_id] = $quotation;
		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3][$quotation_id] = $quotation;

		$sd_user->set ('state', $all_states);

		//$quotation = $sd_user->quotation ($quotation_id);

		echo json_encode ((object) [ 'html' => $sd_user->quotation ($quotation, 'render') ]);
		exit (1);
		break;
	case SD_Game::NEGOTIATION_1:
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2]))
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2] = $all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1];

		$quotation_id = SD_Theme::r ('quotation');
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2][$quotation_id])) {
			echo json_encode ((object) [ 'error' => 1 ]);
			exit (1);
			}
		$quotation = $all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2][$quotation_id];

		$quotation['features']			= SD_Theme::r ('features');
		$quotation['delivery_term']		= SD_Theme::r ('delivery_term');
		$quotation['price']			= SD_Theme::r ('price');
		$quotation['advertising_budget']	= SD_Theme::r ('advertising_budget');
		$quotation['payment_term']		= SD_Theme::r ('payment_term');
		$quotation['warranty']			= SD_Theme::r ('warranty');

		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2][$quotation_id] = $quotation;
		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3][$quotation_id] = $quotation;

		$sd_user->set ('state', $all_states);

		//$quotation = $sd_user->quotation ($quotation_id);

		echo json_encode ((object) [ 'html' => $sd_user->quotation ($quotation, 'render') ]);
		exit (1);
		break;
	case SD_Game::NEGOTIATION_2:
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3]))
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3] = $all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2];

		$quotation_id = SD_Theme::r ('quotation');
		if (!isset ($all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3][$quotation_id])) {
			echo json_encode ((object) [ 'error' => 1 ]);
			exit (1);
			}
		$quotation = $all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3][$quotation_id];

		$quotation['features']			= SD_Theme::r ('features');
		$quotation['delivery_term']		= SD_Theme::r ('delivery_term');
		$quotation['price']			= SD_Theme::r ('price');
		$quotation['advertising_budget']	= SD_Theme::r ('advertising_budget');
		$quotation['payment_term']		= SD_Theme::r ('payment_term');
		$quotation['warranty']			= SD_Theme::r ('warranty');

		$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3][$quotation_id] = $quotation;

		$sd_user->set ('state', $all_states);

		//$quotation = $sd_user->quotation ($quotation_id);

		echo json_encode ((object) [ 'html' => $sd_user->quotation ($quotation, 'render') ]);
		exit (1);
		break;
	case SD_Game::NEGOTIATION_3:
		break;
	}

?>
