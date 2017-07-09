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
	try {
		$player = new SD_Player (SD_Theme::r ('player'));
		$all_states = $player->get ('timer');
		$state = $sd_game->get ('state');
		if (isset ($all_states[$state])) $all_states[$state] = [];
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
		'stamp'		=> time ()
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
			var_dump ($e);
			die ();
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
	if (!empty ($_FILES['state_file'])) {
		$file = $_FILES['state_file'];

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
	
		$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);

		$players_map = $players->get ();
		$players_imap = [];
		foreach ($players_map as $player)
			$players_imap[$player->get ('name')] = $player;

		include (WP_PLUGIN_DIR . '/wp-salesdrive/class/phpexcelreader/SpreadsheetReader.php');

		$data = new SpreadsheetReader ($file['tmp_name'], $file['name']);

		switch ($sd_game->get ('state')) {
			case SD_Game::ROUND1_END:
				$col_map = [];
				$header_detected = FALSE;

				$scores = [];

				foreach ($data as $row) {
					if ($header_detected) {
						if (isset ($players_imap[$row[$col_map['player']]]))
							$scores[$players_imap[$row[$col_map['player']]]->get ()] = (int) $row[$col_map['score']];
						continue;
						}
					foreach ($row as $num => $cell) {
						if (strtolower (trim ($cell)) == 'team name')			$col_map['player']			= $num;
						if (strtolower (trim ($cell)) == 'score')			$col_map['score']			= $num;
						}
					if (
						isset ($col_map['player']) &&
						isset ($col_map['score'])
						)
						$header_detected = TRUE;
					}

				$saved_scores = $sd_game->get ('scores');
				$saved_scores[SD_Game::ROUND1_END] = $scores;
				$sd_game->set ('scores', serialize ($saved_scores));

				SD_Theme::prg ($error);
				break;
			case SD_Game::ROUND2_END:
				$sheets = $data->Sheets ();

				$quotations = [];
				$acquired = [];

				$products = new SD_List ('SD_Product');
				$products_map = $products->get ();
				$products_imap = [];
				$qualities_imap = [];
				foreach ($products_map as $product) {
					$products_imap[$product->get ('name')] = $product;
					$qualities_imap[$product->get ('name')] = [];
					$qualities = $product->get ('quality');
					foreach ($qualities as $quality_slug => $quality_data)
						$qualities_imap[$product->get ('name')][$quality_data['name']] = $quality_slug;
					}
				$warranties = new SD_List ('SD_Warranty');
				$warranties_map = $warranties->get ();
				$warranties_imap = [];
				foreach ($warranties_map as $warranty)
					$warranties_imap[$warranty->get ('name')] = $warranty;
				$features = new SD_List ('SD_Feature');
				$features_map = $features->get ();
				$features_imap = [];
				foreach ($features_map as $feature)
					$features_imap[$feature->get ('name')] = $feature;
				$locations = new SD_List ('SD_Location');
				$locations_map = $locations->get ();
				$locations_imap = [];
				foreach ($locations_map as $location)
					$locations_imap[$location->get ('name')] = $location;

				foreach ($sheets as $index => $sheet_name) {
					$data->ChangeSheet ($index);

					$col_map = [];
					$header_detected = FALSE;

					if (strtolower (trim ($sheet_name)) == 'offers') {
						foreach ($data as $row) {
							if ($header_detected) {
								$player_slug = isset ($players_imap[$row[$col_map['player']]]) ? $players_imap[$row[$col_map['player']]]->get () : null;
								if (is_null ($player_slug)) continue;
								$product_slug = (isset ($products_imap[$row[$col_map['product']]]) && $products_imap[$row[$col_map['product']]] instanceof SD_Product) ?
											$products_imap[$row[$col_map['product']]]->get () : null;
								$quality_slug = isset ($qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]]) ?
											$qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]] : null;

								if (is_null ($product_slug) || is_null ($quality_slug)) continue;

								$row_features = [];
								$_row_features = strtolower($row[$col_map['features']]);
								$_row_features = explode (',', $_row_features);
								foreach ($_row_features as $value) {
									$value = trim ($value);
									if (isset ($features_map[$value]))
										$row_features[] = $value;
									}

								$row_warranty = trim(strtolower($row[$col_map['warranty']]));
								if (!isset ($warranties_map[$row_warranty]))
									$row_warranty = 'none';
								$row_location = trim(strtolower($row[$col_map['location']]));
								if (!isset ($locations_map[$row_location]))
									$row_location = '';

								$quotations[$player_slug][] = [
									'product'		=> $product_slug,
									'quality'		=> $quality_slug,
									'quantity'		=> $row[$col_map['quantity']],
									'features'		=> $row_features,
									'location'		=> $row_location,
									'delivery_term'		=> $row[$col_map['delivery_term']],
									'price'			=> $row[$col_map['price']],
									'advertising_budget'	=> $row[$col_map['advertising']],
									'payment_term'		=> $row[$col_map['payment_term']],
									'warranty'		=> $row_warranty
									];

								continue;
								}
							foreach ($row as $num => $cell) {
								if (strtolower (trim ($cell)) == 'team name')			$col_map['player']			= $num;
								if (strtolower (trim ($cell)) == 'product')			$col_map['product']			= $num;
								if (strtolower (trim ($cell)) == 'quality')			$col_map['quality']			= $num;
								if (strtolower (trim ($cell)) == 'features')			$col_map['features']			= $num;
								if (strtolower (trim ($cell)) == 'warranty')			$col_map['warranty']			= $num;
								if (strtolower (trim ($cell)) == 'quantity')			$col_map['quantity']			= $num;
								if (strtolower (trim ($cell)) == 'price')			$col_map['price']			= $num;
								if (strtolower (trim ($cell)) == 'payment term')		$col_map['payment_term']		= $num;
								if (strtolower (trim ($cell)) == 'location')			$col_map['location']			= $num;
								if (strtolower (trim ($cell)) == 'delivery term')		$col_map['delivery_term']		= $num;
								if (strtolower (trim ($cell)) == 'advertising')			$col_map['advertising']			= $num;
								}
							if (
								isset ($col_map['player'])		&&
								isset ($col_map['product'])		&&
								isset ($col_map['quality'])		&&
								isset ($col_map['features'])		&&
								isset ($col_map['warranty'])		&&
								isset ($col_map['quantity'])		&&
								isset ($col_map['price'])		&&
								isset ($col_map['payment_term'])	&&
								isset ($col_map['location'])		&&
								isset ($col_map['delivery_term'])	&&
								isset ($col_map['advertising'])
								)
								$header_detected = TRUE;
							}
						}
					if (strtolower (trim ($sheet_name)) == 'purchase') {
						foreach ($data as $row) {
							if ($header_detected) {
								$player_slug = isset ($players_imap[$row[$col_map['player']]]) ? $players_imap[$row[$col_map['player']]]->get () : null;
								if (is_null ($player_slug)) continue;
								$product_slug = (isset ($products_imap[$row[$col_map['product']]]) && $products_imap[$row[$col_map['product']]] instanceof SD_Product) ?
											$products_imap[$row[$col_map['product']]]->get () : null;
								$quality_slug = isset ($qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]]) ?
											$qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]] : null;

								if (is_null ($product_slug) || is_null ($quality_slug)) continue;
								$acquired[$player_slug][$product_slug][$quality_slug] = $row[$col_map['quantity']];
								continue;
								}
							foreach ($row as $num => $cell) {
								if (strtolower (trim ($cell)) == 'team name')			$col_map['player']			= $num;
								if (strtolower (trim ($cell)) == 'product')			$col_map['product']			= $num;
								if (strtolower (trim ($cell)) == 'quality')			$col_map['quality']			= $num;
								if (strtolower (trim ($cell)) == 'purchased')			$col_map['quantity']			= $num;
								}
							if (
								isset ($col_map['player']) &&
								isset ($col_map['product']) &&
								isset ($col_map['quality']) &&
								isset ($col_map['quantity'])
								)
								$header_detected = TRUE;
							}
						}
					}

				if (!empty ($quotations))
				foreach ($quotations as $player_slug => $quotation_data) {
					$state = $players_map[$player_slug]->get ('state');
					$state[SD_Game::ROUND2_BEGIN]['data']['quotations'] = $quotation_data;
					$players_map[$player_slug]->set ('state', $state);
					}
				if (!empty ($acquired))
				foreach ($acquired as $player_slug => $acquired_data) {
					$state = $players_map[$player_slug]->get ('state');
					$state[SD_Game::ROUND2_BEGIN]['data']['acquired'] = $acquired_data;
					$players_map[$player_slug]->set ('state', $state);
					}
				SD_Theme::prg ($error);
				break;
			case SD_Game::ROUND3_END:
				$col_map = [];
				$header_detected = FALSE;

				$scores = [];

				foreach ($data as $row) {
					if ($header_detected) {
						if (isset ($players_imap[$row[$col_map['player']]]))
							$scores[$players_imap[$row[$col_map['player']]]->get ()] = (int) $row[$col_map['score']];
						continue;
						}

					foreach ($row as $num => $cell) {
						if (strtolower (trim ($cell)) == 'team name')			$col_map['player']			= $num;
						if (strtolower (trim ($cell)) == 'score')			$col_map['score']			= $num;
						}
					if (
						isset ($col_map['player']) &&
						isset ($col_map['score'])
						)
						$header_detected = TRUE;
					}

				$saved_scores = $sd_game->get ('scores');
				$saved_scores[SD_Game::ROUND3_END] = $scores;
				$sd_game->set ('scores', serialize ($saved_scores));

				SD_Theme::prg ($error);
				break;
			case SD_Game::ROUND4_END:
				$sheets = $data->Sheets ();

				$negotiation = [];

				$products = new SD_List ('SD_Product');
				$products_map = $products->get ();
				$products_imap = [];
				$qualities_imap = [];
				foreach ($products_map as $product) {
					$products_imap[$product->get ('name')] = $product;
					$qualities_imap[$product->get ('name')] = [];
					$qualities = $product->get ('quality');
					foreach ($qualities as $quality_slug => $quality_data)
						$qualities_imap[$product->get ('name')][$quality_data['name']] = $quality_slug;
					}
				$warranties = new SD_List ('SD_Warranty');
				$warranties_map = $warranties->get ();
				$warranties_imap = [];
				foreach ($warranties_map as $warranty)
					$warranties_imap[$warranty->get ('name')] = $warranty;
				$features = new SD_List ('SD_Feature');
				$features_map = $features->get ();
				$features_imap = [];
				foreach ($features_map as $feature)
					$features_imap[$feature->get ('name')] = $feature;
				$locations = new SD_List ('SD_Location');
				$locations_map = $locations->get ();
				$locations_imap = [];
				foreach ($locations_map as $location)
					$locations_imap[$location->get ('name')] = $location;

				foreach ($sheets as $index => $sheet_name) {
					$data->ChangeSheet ($index);

					if (strtolower (trim ($sheet_name)) == 'step1') $negotiation_step = SD_Game::NEGOTIATION_1;
					if (strtolower (trim ($sheet_name)) == 'step2') $negotiation_step = SD_Game::NEGOTIATION_2;
					if (strtolower (trim ($sheet_name)) == 'step3') $negotiation_step = SD_Game::NEGOTIATION_3;

					$col_map = [];
					$header_detected = FALSE;

					foreach ($data as $row) {
						if ($header_detected) {
							$player_slug = isset ($players_imap[$row[$col_map['player']]]) ? $players_imap[$row[$col_map['player']]]->get () : null;
							if (is_null ($player_slug)) continue;
							$product_slug = (isset ($products_imap[$row[$col_map['product']]]) && $products_imap[$row[$col_map['product']]] instanceof SD_Product) ?
										$products_imap[$row[$col_map['product']]]->get () : null;
							$quality_slug = isset ($qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]]) ?
										$qualities_imap[$row[$col_map['product']]][$row[$col_map['quality']]] : null;

							if (is_null ($product_slug) || is_null ($quality_slug)) continue;

							$row_features = [];
							$_row_features = strtolower($row[$col_map['features']]);
							$_row_features = explode (',', $_row_features);
							foreach ($_row_features as $value) {
								$value = trim ($value);
								if (isset ($features_map[$value]))
									$row_features[] = $value;
								}

							$row_warranty = trim(strtolower($row[$col_map['warranty']]));
							if (!isset ($warranties_map[$row_warranty]))
								$row_warranty = 'none';
							$row_location = trim(strtolower($row[$col_map['location']]));
							if (!isset ($locations_map[$row_location]))
								$row_location = '';

							$negotiation[$player_slug][$negotiation_step][] = [
								'product'	=> $product_slug,
								'quality'	=> $quality_slug,
								'quantity'	=> $row[$col_map['quantity']],
								'features'	=> $row_features,
								'location'	=> $row_location,
								'delivery_term'	=> $row[$col_map['delivery_term']],
								'price'		=> $row[$col_map['price']],
								'advertising_budget'	=> $row[$col_map['advertising']],
								'payment_term'	=> $row[$col_map['payment_term']],
								'warranty'	=> $row_warranty,
								];
							continue;
							}
						foreach ($row as $num => $cell) {
							if (strtolower (trim ($cell)) == 'team name')			$col_map['player']			= $num;
							if (strtolower (trim ($cell)) == 'product')			$col_map['product']			= $num;
							if (strtolower (trim ($cell)) == 'quality')			$col_map['quality']			= $num;
							if (strtolower (trim ($cell)) == 'features')			$col_map['features']			= $num;
							if (strtolower (trim ($cell)) == 'warranty')			$col_map['warranty']			= $num;
							if (strtolower (trim ($cell)) == 'quantity')			$col_map['quantity']			= $num;
							if (strtolower (trim ($cell)) == 'price')			$col_map['price']			= $num;
							if (strtolower (trim ($cell)) == 'payment term')		$col_map['payment_term']		= $num;
							if (strtolower (trim ($cell)) == 'location')			$col_map['location']			= $num;
							if (strtolower (trim ($cell)) == 'delivery term')		$col_map['delivery_term']		= $num;
							if (strtolower (trim ($cell)) == 'advertising')			$col_map['advertising']			= $num;
							}
						if (
							isset ($col_map['player'])		&&
							isset ($col_map['product'])		&&
							isset ($col_map['quality'])		&&
							isset ($col_map['features'])		&&
							isset ($col_map['warranty'])		&&
							isset ($col_map['quantity'])		&&
							isset ($col_map['price'])		&&
							isset ($col_map['payment_term'])	&&
							isset ($col_map['location'])		&&
							isset ($col_map['delivery_term'])	&&
							isset ($col_map['advertising'])
							)
							$header_detected = TRUE;
						}
					}

				foreach ($negotiation as $player_slug => $negotiation_data) {
					$state = $players_map[$player_slug]->get ('state');

					if (isset ($negotiation_data[SD_Game::NEGOTIATION_1]))
						$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1] = $negotiation_data[SD_Game::NEGOTIATION_1];
					if (isset ($negotiation_data[SD_Game::NEGOTIATION_2]))
						$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2] = $negotiation_data[SD_Game::NEGOTIATION_2];
					if (isset ($negotiation_data[SD_Game::NEGOTIATION_3]))
						$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3] = $negotiation_data[SD_Game::NEGOTIATION_3];

					$players_map[$player_slug]->set ('state', $state);
					}
				SD_Theme::prg ($error);
				break;
			}
		}
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

if (isset ($_POST['negotiation_reset'])) {
	if (!is_null ($sd_game)) {
		$sd_game->set ('variables', '');
		}
	SD_Theme::prg ();
	}
?>
