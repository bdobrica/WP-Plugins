<?php
$error = null;
if (isset ($_POST['player_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND2_BEGIN]['in_progress'] = TRUE;
		$all_states[SD_Game::ROUND2_BEGIN]['submitted'] = FALSE;

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer');
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['quotation_create'])) {
	$data = [
		'product'		=> SD_Theme::r ('product'),
		'quality'		=> SD_Theme::r ('quality'),
		'quantity'		=> SD_Theme::r ('quantity')
		];

	if ($data['quantity'] > 0) {
		$all_states = $sd_user->get ('state');
		$state = $all_states[SD_Game::ROUND2_BEGIN];
		if (!isset ($state['data']['quotations']))
			$state['data']['quotations'] = [];

		$state['data']['quotations'][] = $data;

		$all_states[SD_Game::ROUND2_BEGIN] = $state;
		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['quotation_update'])) {
	$all_states = $sd_user->get ('state');
	$state = $all_states[SD_Game::ROUND2_BEGIN];

	$quotation_id = SD_Theme::r ('quotation');
	$data = [
		'quantity'		=> SD_Theme::r ('quantity'),
		'features'		=> SD_Theme::r ('features'),
		'location'		=> SD_Theme::r ('location'),
		'delivery_term'		=> SD_Theme::r ('delivery_term'),
		'price'			=> SD_Theme::r ('price'),
		'advertising_budget'	=> SD_Theme::r ('advertising_budget'),
		'payment_term'		=> SD_Theme::r ('payment_term'),
		'warranty'		=> SD_Theme::r ('warranty')
		];

	if (isset ($state['data']['quotations'][$quotation_id])) {
		$state['data']['quotations'][$quotation_id] = array_merge ($state['data']['quotations'][$quotation_id], $data);

		$all_states[SD_Game::ROUND2_BEGIN] = $state;
		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['quotation_delete'])) {
	$all_states = $sd_user->get ('state');
	$state = $all_states[SD_Game::ROUND2_BEGIN];

	$quotation_id = SD_Theme::r ('quotation');

	if (isset ($state['data']['quotations'][$quotation_id])) {
		unset ($state['data']['quotations'][$quotation_id]);

		$all_states[SD_Game::ROUND2_BEGIN] = $state;
		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['product_acquire'])) {
	$data = [
		'product'		=> SD_Theme::r ('product'),
		'quality'		=> SD_Theme::r ('quality'),
		'quantity'		=> SD_Theme::r ('acquire')
		];

	if ($data['quantity'] > 0) {
		/** Purchasable Quantitiy Validation Limit */
		$purchasable_quantity = 0;
		try {
			$product = new SD_Product ($sd_theme->get ('scenario', 'path'), $data['product']);
			$product_qualities = $product->get ('quality');
			$purchasable_quantity = $product_qualities[$data['quality']]['purchasable_quantity'];
			}
		catch (SD_Exception $e) {
			}

		$all_states = $sd_user->get ('state');
		$state = $all_states[SD_Game::ROUND2_BEGIN];
		if (!isset ($state['data']['acquired']))
			$state['data']['acquired'] = [];

		if (isset ($state['data']['acquired'][$data['product']][$data['quality']]))
			$state['data']['acquired'][$data['product']][$data['quality']] += $data['quantity'];
		else
			$state['data']['acquired'][$data['product']][$data['quality']] = $data['quantity'];

		/** Purchasable Quantity Validation Enforcement */
		# on on 13.06.2017
		if (isset ($state['data']['acquired'][$data['product']][$data['quality']]) && ($state['data']['acquired'][$data['product']][$data['quality']] > $purchasable_quantity))
			$state['data']['acquired'][$data['product']][$data['quality']] = $purchasable_quantitiy;

		$all_states[SD_Game::ROUND2_BEGIN] = $state;
		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['quotation_submit'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND2_BEGIN]['in_progress'] = FALSE;
		$all_states[SD_Game::ROUND2_BEGIN]['submitted'] = TRUE;

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer', 'clear');
		}

	SD_Theme::prg ($error);
	}
?>
