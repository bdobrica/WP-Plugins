<?php
$error = null;

if (isset ($_POST['submit_vote'])) {
	$all_states = $sd_user->get ('state');
	$state = $all_states[SD_Game::ROUND3_BEGIN];

	$polls = new SD_List ('SD_Poll');
	$game_data = $sd_game->get ('data', 'current');

	$current_player = null;

	if (!empty ($game_data['data']))
		foreach ($game_data['data'] as $player_id => $voting_data) {
			if (isset ($voting_data['in_progress']) && $voting_data['in_progress'] == TRUE) {
				try {
					$current_player = new SD_Player ($player_id);
					}
				catch (SD_Exception $exception) {
					}
				}
			}

	$votes = [];
	if (!$polls->is ('empty'))
		foreach ($polls->get () as $poll)
			$votes[$poll->get ()] = SD_Theme::r ($poll->get ());

	if (!is_null ($current_player)) {
		if (!isset ($state['data']['voted'][$current_player->get ()]))
			$state['data']['voted'][$current_player->get ()] = [];

		$state['data']['voted'][$current_player->get ()][session_id ()] = $votes;
		$all_states[SD_Game::ROUND3_BEGIN] = $state;
		$sd_user->set ('state', $all_states);
		}

	SD_Theme::prg ($error);
	}
?>
