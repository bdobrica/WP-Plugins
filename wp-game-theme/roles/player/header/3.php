<?php
$error = null;
if (isset ($_POST['company_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND1_BEGIN]['company'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['intro'] = TRUE;
		$all_states[SD_Game::ROUND1_BEGIN]['in_progress'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['submitted'] = FALSE;

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer');
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['player_begin'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');
		$all_states[SD_Game::ROUND1_BEGIN]['company'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['intro'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['in_progress'] = TRUE;
		$all_states[SD_Game::ROUND1_BEGIN]['submitted'] = FALSE;

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer');
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['meet'])) {
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
		$sd_user->set ('timer', 'begin');
		$_GET = [ 'meet' => $character->get () ];
		}
	catch (SD_Exception $exception) {
		}
	SD_Theme::prg ($error);
	}
if (isset ($_GET['meet'])) {
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('meet'));
		}
	catch (SD_Exception $exception) {
		}
	}
if (isset ($_GET['leave'])) {
	$sd_user->set ('timer', 'end');
	SD_Theme::prg ($error, TRUE);
	}
if (isset ($_GET['submit'])) {
	if ($sd_user->get ('role') == 'player') {
		$all_states = $sd_user->get ('state');

		$all_states[SD_Game::ROUND1_BEGIN]['company'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['intro'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['in_progress'] = FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['submitted'] = TRUE;

		$sd_user->set ('state', $all_states);

		$sd_user->set ('timer', 'clear');
		}
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

		$all_states[SD_Game::ROUND1_BEGIN]['company']		= TRUE;
		$all_states[SD_Game::ROUND1_BEGIN]['intro']		= FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['in_progress']	= FALSE;
		$all_states[SD_Game::ROUND1_BEGIN]['submitted']		= FALSE;

		$data['state'] = $all_states;

		$sd_user->set ($data);
		}

	SD_Theme::prg ($error);
	}
?>
