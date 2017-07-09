<?php
$error = null;

if (isset ($_POST['next_turn'])) {
	if (!is_null ($sd_game))
		$sd_game->turn ();
	if (($sd_game->get ('state') == SD_Game::ROUND3_BEGIN) && $sd_theme->get ('scenario', 'allow_sending_emails')) {
		$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
		if (!$players->is ('empty')) {
			$mail_sender		= $sd_theme->get ('scenario', 'round_3_email_sender');
			$mail_subject		= $sd_theme->get ('scenario', 'round_3_email_subject');
			$mail_content		= $sd_theme->get ('scenario', 'round_3_email_content');
			$attachment_file	= unserialize ($sd_theme->get ('scenario', 'round_3_email_attachment'));

			$mail_attachment = [];
			if (is_array ($attachment_file) && file_exists ($attachment_file['file']))
				$mail_attachment[$attachment_file['file']] = $attachment_file['name'];

			$all_emails = [];
			foreach ($players->get () as $player) {
				$emails = $player->get ('emails_array');
				if (!empty ($emails))
					$all_emails = array_merge ($all_emails, $emails);
				}
			if (!empty ($all_emails))
				SD_Theme::m ($mail_sender, $all_emails, $mail_subject, $mail_content, is_array ($mail_attachment) ? $mail_attachment : []);
			}
		}
	SD_Theme::prg ();
	}
if (isset ($_POST['prev_turn'])) {
	if (!is_null ($sd_game))
		$sd_game->turn (FALSE);
	SD_Theme::prg ();
	}
if (isset ($_POST['end_game'])) {
	if (!is_null ($sd_game))
		$sd_game->set ('active', 0);
	SD_Theme::prg ();
	}
if (isset ($_POST['regenerate_market'])) {
	if (!is_null ($sd_game))
		$sd_game->buy ();
	SD_Theme::prg ();
	}
if (isset ($_POST['reset_timer'])) {
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$timer = $player->get ('timer');
		$state = $sd_game->get ('state');
		if (isset ($timer[$state])) unset ($timer[$state]);
		$player->set ('timer', $timer);
		}
	catch (SD_Exception $exception) {
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['reset_round'])) {
	/** nu sunt de acord. 22.06.2017 */
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$timer = $player->get ('timer');
		$state = $sd_game->get ('state');
		if (isset ($timer[$state])) unset ($timer[$state]);
		$player->set ('timer', $timer);
		}
	catch (SD_Exception $exception) {
		}
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$all_states = $player->get ('state');
		$state = $sd_game->get ('state');
		if (isset ($all_states[$state])) $all_states[$state] = [];
		if ($state == SD_Game::ROUND4_BEGIN) unset ($all_states[$state]['step']);
		$player->set ('state', $all_states);
		}
	catch (SD_Exception $exception) {
		}
	try {
		$all_data = $sd_game->get ('data');
		$state = $sd_game->get ('state');
		if (isset ($all_data[$state])) $all_data[$state] = [];
		$sd_game->set ('data', $all_data);
		}
	catch (SD_Exception $exception) {
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['cancel_submitted'])) {
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$all_states = $player->get ('timer');
		$state = $sd_game->get ('state');
		if (isset ($all_states[$state]['submitted'])) {
			$all_states[$state]['submitted'] = FALSE;
			$all_states[$state]['in_progress'] = TRUE;
			}
		$player->set ('state', $all_states);
		}
	catch (SD_Exception $exception) {
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['cancel_in_progress'])) {
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$all_states = $player->get ('timer');
		$state = $sd_game->get ('state');
		if (isset ($all_states[$state]['submitted'])) {
			$all_states[$state]['submitted'] = FALSE;
			$all_states[$state]['in_progress'] = FALSE;
			}
		$player->set ('state', $all_states);
		}
	catch (SD_Exception $exception) {
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['begin_vote'])) {
	if ($sd_game->get ('state') == SD_Game::ROUND3_BEGIN) {
		try {
			$current_player = new SD_Player (SD_Theme::r ('player'));
			$sd_game_data = $sd_game->get ('data');
			$sd_game_data[SD_Game::ROUND3_BEGIN]['data'][$current_player->get ()]['in_progress'] = TRUE;
			$sd_game->set ('data', $sd_game_data);

			$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
			if (!$players->is ('empty'))
				foreach ($players->get () as $player)
					$player->set ('timer');

			}
		catch (SD_Exception $exception) {
			}
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['recast_vote'])) {
	if ($sd_game->get ('state') == SD_Game::ROUND3_BEGIN) {
		try {
			$current_player = new SD_Player (SD_Theme::r ('player'));
			$sd_game_data = $sd_game->get ('data');
			$sd_game_data[SD_Game::ROUND3_BEGIN]['data'][$current_player->get ()]['in_progress'] = TRUE;
			$sd_game->set ('data', $sd_game_data);

			$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
			if (!$players->is ('empty'))
				foreach ($players->get () as $player) {
					$all_states = $player->get ('state');
					if (isset ($all_states[SD_Game::ROUND3_BEGIN]['data']['voted'][$current_player->get ()]))
						$all_states[SD_Game::ROUND3_BEGIN]['data']['voted'][$current_player->get ()] = [];
					$player->set ('timer');
					$player->set ('state', $all_states);
					}
			}
		catch (SD_Exception $exception) {
			}
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST['validate_vote'])) {
	if ($sd_game->get ('state') == SD_Game::ROUND3_BEGIN) {
		try {
			$current_player = new SD_Player (SD_Theme::r ('player'));
			$sd_game_data = $sd_game->get ('data');
			$sd_game_data[SD_Game::ROUND3_BEGIN]['data'][$current_player->get ()]['in_progress'] = FALSE;
			$sd_game_data[SD_Game::ROUND3_BEGIN]['data'][$current_player->get ()]['validated'] = TRUE;
			$sd_game->set ('data', $sd_game_data);

			$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
			if (!$players->is ('empty'))
				foreach ($players->get () as $player)
					$player->set ('timer', 'clear');

			}
		catch (SD_Exception $exception) {
			}
		}
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_POST[SD_Game::GET . '_create'])) {
	$data = [
		'owner'		=> $sd_theme->get ('user', 'id'),
		'name'		=> SD_Theme::r (SD_Game::GET . '_name'),
		'players'	=> 1 + SD_Theme::r (SD_Game::GET . '_players', 'int'),
		'scenario'	=> SD_Theme::r (SD_Game::GET . '_scenario'),
		'state'		=> SD_Game::BEGIN_GAME,
		'state_data'	=> serialize([]),
		'active'	=> 1,
		'stamp'		=> time (),
		'locale'	=> SD_Theme::r (SD_Game::GET . '_locale'),
		];

	try {
		$sd_game = new SD_Game ($data);
		$sd_game->save ();
		}
	catch (SD_Exception $e) {
		$sd_game = null;
		}

	if (!is_null ($sd_game)) {
		for ($players = 0; $players < 1 + SD_Theme::r (SD_Game::GET . '_players', 'int'); $players ++) {
			$data = [
				'name'		=> sprintf (/*T[*/'Player #%d'/*]*/, $players + 1),
				'owner'		=> $sd_theme->get ('user', 'id'),
				'game'		=> $sd_game->get (),
				'password'	=> SD_Player::scramble ()
				];
			try {
				$player = new SD_Player ($data);
				$player->save ();
				}
			catch (SD_Exception $e) {
				$player = null;
				}
			}
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['send_password'])) {
	include (WP_PLUGIN_DIR . '/wp-salesdrive/class/tbs/tbs_class.php');
	include (WP_PLUGIN_DIR . '/wp-salesdrive/class/tbs/plugins/tbs_plugin_opentbs.php');

	$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
	$team = [];
	if (!$players->is ('empty')) {
		foreach ($players->get () as $player)
			$team[] = [ 'name' => $player->get ('name') . "\n\n", 'password' => $player->get ('password')];

		$tbs = new clsTinyButStrong ();
		$tbs->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$tbs->LoadTemplate(WP_PLUGIN_DIR . '/wp-salesdrive/assets/docx/password-template.docx', OPENTBS_ALREADY_UTF8);
		$tbs->MergeBlock ('team', $team);

		$passwords = [
			'path' => get_stylesheet_directory () . '/assets/pass',
			'file' => vsprintf ('password_%s_%s.docx', [date ('dmy'), date ('His')])
			];

		$tbs->Show(OPENTBS_FILE, $passwords['path'] . '/' . $passwords['file']);

		try {
			SD_Theme::m ('Amalia Fitil', $_POST['email'], SD_Theme::__ (/*T[*/'SalesDrive Game Passwords'/*]*/), 
			SD_Theme::__ (/*T[*/'SalesDrive Game Passwords'/*]*/), [ $passwords['path'] . '/' . $passwords['file'] => $passwords['file'] ]);
			}
		catch (SD_Exception $e) {
			$error = [ 'email_configuration' => 1 ];
			}

		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['scenario_export'])) {
	try {
		$scenario = new SD_Scenario (SD_Theme::r ('scenario_read'));
		$scenario->export ();
		}
	catch (SD_Exception $e) {
		$error = [ 'export_error' => $e->get ('code') ];
		}
	SD_Theme::prg ($error);
	}

if (isset ($_POST['scenario_import'])) {
	if (!empty ($_FILES['scenario_file'])) {
		$file = $_FILES['scenario_file'];

		if (!isset ($file['error']) || is_array ($file['error']))
			$error = ['file_read' => 1];

		if ($file['error'] != UPLOAD_ERR_OK)
			$error = ['file_read' => 2];

		if (FALSE === $ext = array_search (mime_content_type ($file['tmp_name']), [
			'zip'	=> 'application/zip'
			], true))
			$error = ['file_read' => 3];

		if (!is_null ($error))
			SD_Theme::prg ($error);

		try {
			$stamp = SD_Scenario::open_archive ($file['tmp_name']);
			}
		catch (SD_Exception $e) {
			$error = ['file_read' => 4];
			}

		if (!is_null ($error))
			SD_Theme::prg ($error);

		$_GET['imported'] = $stamp;

		SD_Theme::prg ();
		}
	if (isset ($_POST['scenario_stamp']) && isset ($_POST['scenario_name'])) {
		try {
			SD_Scenario::import (SD_Theme::r ('scenario_stamp'), SD_Theme::r ('scenario_name'));
			unset ($_GET['imported']);
			}
		catch (SD_Exception $e) {
			$error = ['file_read' => 5];
			}
		SD_Theme::prg ($error);
		}
	SD_Theme::prg ();
	}

if (isset ($_POST['scenario_delete'])) {
	try {
		$scenario = new SD_Scenario (SD_Theme::r ('scenario_read'));
		}
	catch (SD_Exception $e) {
		$scenario = null;
		$error = ['error_delete' => $e->get ('code')];
		}

	if (!is_null ($scenario)) {
		try {
			$scenario->delete ();
			}
		catch (SD_Exception $e) {
			$error = ['error_delete' => $e->get ('code')];
			}
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['update_state'])) {
	include (dirname (dirname (__FILE__)) . '/common/update-state.php');
	SD_Theme::prg ($error);
	}

if (isset ($_POST['renegotiate_state'])) {
	$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);

	if (!$players->is ('empty')) {
		foreach ($players->get () as $player) {
			$state = $player->get ('state');

			$negotiation_step = $player->get ('negotiation_step');

			$not_set = FALSE;
			if (!isset ($state[SD_Game::ROUND4_BEGIN]['in_progress']))
				$not_set = TRUE;
			else
				$in_progress = $state[SD_Game::ROUND4_BEGIN]['in_progress'];

			$state[SD_Game::ROUND4_BEGIN]['in_progress'] = TRUE;

			$player->set ('state', $state);

			$player->set ('negotiation_step', SD_Game::NEGOTIATION_0);
			$state[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_1] = $player->get ('counter_offer');
			$player->set ('computed');
			$player->set ('negotiation_step', SD_Game::NEGOTIATION_1);
			$state[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_2] = $player->get ('counter_offer');
			$player->set ('computed');
			$player->set ('negotiation_step', SD_Game::NEGOTIATION_2);
			$state[SD_Game::ROUND4_BEGIN]['data']['counter'][SD_Game::NEGOTIATION_3] = $player->get ('counter_offer');
			$player->set ('computed');

			$player->set ('negotiation_step', $negotiation_step);

			if ($not_set)
				unset ($state[SD_Game::ROUND4_BEGIN]['in_progress']);
			else
				$state[SD_Game::ROUND4_BEGIN]['in_progress'] = $in_progress;

			$player->set ('state', $state);
			}
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['update_export_template'])) {
	$file = $_FILES['export_template'];

	if (!isset ($file['error']) || is_array ($file['error']))
		$error = ['file_read' => 1];

	if ($file['error'] != UPLOAD_ERR_OK)
		$error = ['file_read' => 2];
	
	if (FALSE === $ext = array_search (mime_content_type ($file['tmp_name']), [
		'xlsx'	=> 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		], true))
		$error = ['file_read' => 3];

	if (!is_null ($error))
		SD_Theme::prg ($error);

	if (!@move_uploaded_file ($file['tmp_name'], WP_PLUGIN_DIR . '/wp-salesdrive/assets/xlsx/export-state-all-override.xlsx'))
		$error = ['file_read' => 4];

	SD_Theme::prg ($error);
	}

if (isset ($_POST['negotiation_update'])) {
	$variables = [];
	$_products = new SD_List ('SD_Product');
	if (!$_products->is ('empty')) {
		foreach ($_products->get () as $_product) {
			$_qualities = $_product->get ('quality');
			foreach ($_qualities as $_quality_slug => $_quality_data) {
				foreach (SD_Product::$QA as $_slug => $_label) {
					if (in_array ($_slug, SD_Product::$NQA)) continue;
					if (isset ($_POST[$_product->get () . '_' . $_quality_slug . '_'. $_slug]))
						$variables[$_product->get () . '_' . $_quality_slug . '_'. $_slug] = SD_Theme::r ($_product->get () . '_' . $_quality_slug . '_'. $_slug);
					}
				}
			}
		}
	$_warranties = new SD_List ('SD_Warranty');
	if (!$_warranties->is ('empty')) {
		foreach ($_warranties->get () as $_warranty) {
#			if (isset ($_POST[$_warranty->get () . '_mandatory']))
				$variables[$_warranty->get () . '_mandatory'] = SD_Theme::r ($_warranty->get () . '_mandatory') == 'on';
#			if (isset ($_POST[$_warranty->get () . '_negotiable']))
				$variables[$_warranty->get () . '_negotiable'] = SD_Theme::r ($_warranty->get () . '_negotiable') == 'on';
			}
		}

	$_features = new SD_List ('SD_Feature');
	if (!$_features->is ('empty')) {
		foreach ($_features->get () as $_feature) {
#			if (isset ($_POST[$_feature->get () . '_mandatory']))
				$variables[$_feature->get () . '_mandatory'] = SD_Theme::r ($_feature->get () . '_mandatory') == 'on';
#			if (isset ($_POST[$_feature->get () . '_negotiable']))
				$variables[$_feature->get () . '_negotiable'] = SD_Theme::r ($_feature->get () . '_negotiable') == 'on';
			}
		}
	$_locations = new SD_List ('SD_Location');
	if (!$_locations->is ('empty')) {
		foreach ($_locations->get () as $_location) {
			if (isset ($_POST[$_location->get () . '_max_delivery_time']))
				$variables[$_location->get () . '_max_delivery_time'] = SD_Theme::r ($_location->get () . '_max_delivery_time');
			if (isset ($_POST[$_location->get () . '_desired_delivery_time']))
				$variables[$_location->get () . '_desired_delivery_time'] = SD_Theme::r ($_location->get () . '_desired_delivery_time');
			}
		}
	if (isset ($_POST['buying_mode']))
		$variables['buying_mode'] = SD_Theme::r ('buying_mode');
	if (isset ($_POST['price_weight']))
		$variables['price_weight'] = SD_Theme::r ('price_weight');
	if (isset ($_POST['adv_budg_weight']))
		$variables['adv_budg_weight'] = SD_Theme::r ('adv_budg_weight');
	if (isset ($_POST['paym_term_weight']))
		$variables['paym_term_weight'] = SD_Theme::r ('paym_term_weight');
	if (isset ($_POST['delivery_weight']))
		$variables['delivery_weight'] = SD_Theme::r ('delivery_weight');
	if (isset ($_POST['features_weight']))
		$variables['features_weight'] = SD_Theme::r ('features_weight');
	if (isset ($_POST['warranty_weight']))
		$variables['warranty_weight'] = SD_Theme::r ('warranty_weight');
	if (isset ($_POST['aggressiveness']))
		$variables['aggressiveness'] = SD_Theme::r ('aggressiveness');
	if (isset ($_POST['ask_for_features']))
		$variables['ask_for_features'] = SD_Theme::r ('ask_for_features');
	if (isset ($_POST['ask_for_warranty']))
		$variables['ask_for_warranty'] = SD_Theme::r ('ask_for_warranty');
	if (isset ($_POST['score_weight']))
		$variables['score_weight'] = SD_Theme::r ('score_weight');
	if (isset ($_POST['sweetener']))
		$variables['sweetener'] = SD_Theme::r ('sweetener');

	if (!is_null ($sd_game)) {
		$sd_game->set ('variables', serialize ($variables));
		}
	SD_Theme::prg ();
	}

if (isset ($_POST[SD_Scenario::GET . '_create'])) {
	$scenario = new SD_Scenario ();
	try {
		$scenario->set ('name', SD_Theme::r ('scenario_name'));
		}
	catch (SD_Exception $e) {
		$error = ['error_create' => $e->get ('code')];
		}
	SD_Theme::prg ($error);
	}

if (isset ($_POST['negotiation_reset'])) {
	if (!is_null ($sd_game)) {
		$sd_game->set ('variables', '');
		}
	SD_Theme::prg ();
	}
?>
