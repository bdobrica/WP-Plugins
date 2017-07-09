<?php
$error = null;
if (isset ($_POST['player_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND4_BEGIN]['in_progress'] = TRUE;
		$all_states[SD_Game::ROUND4_BEGIN]['submitted'] = FALSE;
		$all_states[SD_Game::ROUND4_BEGIN]['show_hints'] = TRUE;

		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['negotiation_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND4_BEGIN]['in_progress'] = TRUE;
		$all_states[SD_Game::ROUND4_BEGIN]['submitted'] = FALSE;
		$all_states[SD_Game::ROUND4_BEGIN]['show_hints'] = FALSE;

		if (isset ($all_states[SD_Game::ROUND2_BEGIN]['data']['quotations'])) {
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_1] = $all_states[SD_Game::ROUND2_BEGIN]['data']['quotations'];
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_2] = $all_states[SD_Game::ROUND2_BEGIN]['data']['quotations'];
			$all_states[SD_Game::ROUND4_BEGIN]['data']['negotiation'][SD_Game::NEGOTIATION_3] = $all_states[SD_Game::ROUND2_BEGIN]['data']['quotations'];
			}

		$all_states[SD_Game::ROUND4_BEGIN];

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer');
		}

	SD_Theme::prg ($error);
	}
?>
