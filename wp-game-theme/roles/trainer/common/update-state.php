<?php
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

	$products = new SD_List ('SD_Product', $sd_game->scenario ('path'));
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
	$warranties = new SD_List ('SD_Warranty', $sd_game->scenario ('path'));
	$warranties_map = $warranties->get ();
	$warranties_imap = [];
	foreach ($warranties_map as $warranty)
		$warranties_imap[$warranty->get ('name')] = $warranty;
	$features = new SD_List ('SD_Feature', $sd_game->scenario ('path'));
	$features_map = $features->get ();
	$features_imap = [];
	foreach ($features_map as $feature)
		$features_imap[$feature->get ('name')] = $feature;
	$locations = new SD_List ('SD_Location', $sd_game->scenario ('path'));
	$locations_map = $locations->get ();
	$locations_imap = [];
	foreach ($locations_map as $location)
		$locations_imap[$location->get ('name')] = $location;

	$characters = new SD_List ('SD_Character', $sd_game->scenario ('path'));
	$characters_map = $characters->get ();
	$characters_imap = [];
	foreach ($characters_map as $character)
		$characters_imap[$character->get ('name')] = $character;

	$sheets = $data->Sheets ();
	$scores = [];
	$quotations = [];
	$acquired = [];
	$negotiation = [];

	foreach ($sheets as $sheet_index => $sheet_name) {
		$data->ChangeSheet ($sheet_index);
		$sheet_slug = SD_Language::slug ($sheet_name);

		switch ($sheet_slug) {
			case 'conversations':
				$col_map = [];
				$mls_map = [];
				$chr_map = [];

				$header_detected = FALSE;

				foreach ($characters_map as $character)
					$chr_map[SD_Language::slug ($character->get ('name'))] = -1;

				$emails = [];

				foreach ($data as $row) {
					if ($header_detected) {
						if (isset ($players_imap[$row[$col_map['player']]])) {
							$player_slug = $players_imap[$row[$col_map['player']]]->get ();

							$scores[SD_Game::ROUND1_END][$player_slug]['total'] = (int) $row[$col_map['score']];

							if (!empty ($chr_map))
								foreach ($characters_map as $character) {
									$character_slug = SD_Language::slug ($character->get ('name'));
									if (isset ($chr_map[$character_slug]))
										$scores[SD_Game::ROUND1_END][$player_slug][$character->get ('name')] = (int) $row[$chr_map[$character_slug]];
									}

							if (!empty ($mls_map))
								foreach ($mls_map as $ml_map)
									$emails[$player_slug][] = $row[$ml_map];
							else
								$emails[$player_slug] = [];

							$emails[$player_slug] = !empty ($emails[$player_slug]) ? implode (',', $emails[$player_slug]) : '';
							}
						continue;
						}
					foreach ($row as $num => $cell) {
						$cell_slug = SD_Language::slug ($cell);
						if ($cell_slug == 'team_name')					$col_map['player']			= $num;
						if ($cell_slug == 'total')					$col_map['score']			= $num;
						if (strpos ($cell_slug, 'email_') === 0)			$mls_map[]				= $num;
						if (in_array ($cell_slug, array_keys ($chr_map)))		$chr_map[$cell_slug]			= $num;
						}
					if (
						isset ($col_map['player']) &&
						isset ($col_map['score'])
						)
						$header_detected = TRUE;
					}

				break;

			case 'offers':
			case 'purchases':
				$col_map = [];
				$header_detected = FALSE;

				if ($sheet_slug == 'offers') {
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
							$_row_features = $row[$col_map['features']];
							$_row_features = explode (',', $_row_features);
							foreach ($_row_features as $value) {
								$value = trim ($value);
								if (isset ($features_imap[$value]))
									$row_features[] = $features_imap[$value]->get ();
								}

							$row_warranty = trim($row[$col_map['warranty']]);
							if (!isset ($warranties_imap[$row_warranty]))
								$row_warranty = 'none';
							else
								$row_warranty = $warranties_imap[$row_warranty]->get ();

							$row_location = trim($row[$col_map['location']]);
							if (!isset ($locations_imap[$row_location]))
								$row_location = '';
							else
								$row_location = $locations_imap[$row_location]->get ();

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
							$cell_slug = SD_Language::slug ($cell);
							if ($cell_slug == 'team_name')				$col_map['player']			= $num;
							if ($cell_slug == 'product')				$col_map['product']			= $num;
							if ($cell_slug == 'quality')				$col_map['quality']			= $num;
							if ($cell_slug == 'features')				$col_map['features']			= $num;
							if ($cell_slug == 'warranty')				$col_map['warranty']			= $num;
							if ($cell_slug == 'quantity')				$col_map['quantity']			= $num;
							if ($cell_slug == 'price')				$col_map['price']			= $num;
							if ($cell_slug == 'payment_term')			$col_map['payment_term']		= $num;
							if ($cell_slug == 'location')				$col_map['location']			= $num;
							if ($cell_slug == 'delivery_term')			$col_map['delivery_term']		= $num;
							if ($cell_slug == 'advertising')			$col_map['advertising']			= $num;
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
				if ($sheet_slug == 'purchases') {
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
							$cell_slug = SD_Language::slug ($cell);
							if ($cell_slug == 'team_name')					$col_map['player']			= $num;
							if ($cell_slug == 'product')					$col_map['product']			= $num;
							if ($cell_slug == 'quality')					$col_map['quality']			= $num;
							if ($cell_slug == 'purchased')					$col_map['quantity']			= $num;
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
				break;

			case 'presentations':
				$col_map = [];
				$header_detected = FALSE;

				foreach ($data as $row) {
					if ($header_detected) {
						if (isset ($players_imap[$row[$col_map['player']]]))
							$scores[SD_Game::ROUND3_END][$players_imap[$row[$col_map['player']]]->get ()] = (int) $row[$col_map['score']];
						continue;
						}

					foreach ($row as $num => $cell) {
						$cell_slug = SD_Language::slug ($cell);
						if ($cell_slug == 'team_name')					$col_map['player']			= $num;
						if ($cell_slug == 'score')					$col_map['score']			= $num;
						}
					if (
						isset ($col_map['player']) &&
						isset ($col_map['score'])
						)
						$header_detected = TRUE;
					}

				break;

			case 'nego1':
			case 'nego2':
			case 'nego3':
				if ($sheet_slug == 'nego1') $negotiation_step = SD_Game::NEGOTIATION_1;
				if ($sheet_slug == 'nego2') $negotiation_step = SD_Game::NEGOTIATION_2;
				if ($sheet_slug == 'nego3') $negotiation_step = SD_Game::NEGOTIATION_3;

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
						$_row_features = $row[$col_map['features']];
						$_row_features = explode (',', $_row_features);
						foreach ($_row_features as $value) {
							$value = trim ($value);
							if (isset ($features_imap[$value]))
								$row_features[] = $features_imap[$value]->get ();
							}

						$row_warranty = trim($row[$col_map['warranty']]);
						if (!isset ($warranties_imap[$row_warranty]))
							$row_warranty = 'none';
						else
							$row_warranty = $warranties_imap[$row_warranty]->get ();

						$row_location = trim($row[$col_map['location']]);
						if (!isset ($locations_imap[$row_location]))
							$row_location = '';
						else
							$row_location = $locations_imap[$row_location]->get ();

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
						$cell_slug = SD_Language::slug ($cell);
						if ($cell_slug == 'team_name')				$col_map['player']			= $num;
						if ($cell_slug == 'product')				$col_map['product']			= $num;
						if ($cell_slug == 'quality')				$col_map['quality']			= $num;
						if ($cell_slug == 'features')				$col_map['features']			= $num;
						if ($cell_slug == 'warranty')				$col_map['warranty']			= $num;
						if ($cell_slug == 'quantity')				$col_map['quantity']			= $num;
						if ($cell_slug == 'price')				$col_map['price']			= $num;
						if ($cell_slug == 'payment_term')			$col_map['payment_term']		= $num;
						if ($cell_slug == 'location')				$col_map['location']			= $num;
						if ($cell_slug == 'delivery_term')			$col_map['delivery_term']		= $num;
						if ($cell_slug == 'advertising')			$col_map['advertising']			= $num;
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
				break;
			} // End Sheet Switch
		} // End Sheet Foreach


	$sd_game->set ('scores', serialize ($scores));

	$negotiation_steps = [
		SD_Game::NEGOTIATION_0,
		SD_Game::NEGOTIATION_1,
		SD_Game::NEGOTIATION_2
		];

	foreach ($players_map as $player_slug => $player) {
		$state = $player->get ('state');

		if (!empty ($quotations[$player_slug]))
			$state[SD_Game::ROUND2_BEGIN]['data']['quotations'] = $quotations[$player_slug];
		else
			$state[SD_Game::ROUND2_BEGIN]['data']['quotations'] = [];

		if (!empty ($acquired[$player_slug]))
			$state[SD_Game::ROUND2_BEGIN]['data']['acquired'] = $acquired[$player_slug];
		else
			$state[SD_Game::ROUND2_BEGIN]['data']['acquired'] = [];

		if (isset ($negotiation[$player_slug][SD_Game::NEGOTIATION_1]))
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1] = $negotiation[$player_slug][SD_Game::NEGOTIATION_1];
		else
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1] = [];
			
		if (isset ($negotiation[$player_slug][SD_Game::NEGOTIATION_2]))
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2] = $negotiation[$player_slug][SD_Game::NEGOTIATION_2];
		else
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2] = [];
			
		if (isset ($negotiation[$player_slug][SD_Game::NEGOTIATION_3]))
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3] = $negotiation[$player_slug][SD_Game::NEGOTIATION_3];
		else
			$state[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3] = [];

		$state[SD_Game::ROUND4_BEGIN]['in_progress'] = TRUE;

		$player->set ('state', $state);

		if (isset ($emails[$player_slug]))
			$player->set ('emails', rtrim ($emails[$player_slug], ','));


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
?>
