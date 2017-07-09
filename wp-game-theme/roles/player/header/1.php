<?php
$error = null;
if (isset ($_POST['player_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND0_BEGIN]['in_progress'] = TRUE;
		$all_states[SD_Game::ROUND0_BEGIN]['submitted'] = FALSE;

		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['player_update'])) {
	$data = [
		'name'		=> trim (SD_Theme::r ('name')),
		'emails'	=> trim (SD_Theme::r ('emails'))
		];

	if ($sd_user->get ('role') == 'player') {
		$sd_user->set ($data);
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['player_submit'])) {
	$data = [
		'name'		=> trim (SD_Theme::r ('name')),
		'emails'	=> trim (SD_Theme::r ('emails'))
		];

	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND0_BEGIN]['in_progress'] = FALSE;
		$all_states[SD_Game::ROUND0_BEGIN]['submitted'] = TRUE;
		$data['state'] = $all_states;

		$sd_user->set ($data);
		}

	SD_Theme::prg ($error);
	}
?>
