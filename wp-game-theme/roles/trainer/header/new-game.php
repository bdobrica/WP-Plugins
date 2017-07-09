<?php
$error = null;

if (isset ($_POST[SD_Game::GET . '_create'])) {
	$data = [
		'owner'		=> $sd_theme->get ('user', 'id'),
		'name'		=> SD_Theme::r (SD_Game::GET . '_name'),
		'players'	=> SD_Theme::r (SD_Game::GET . '_players', 'int'),
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
		for ($players = 0; $players < SD_Theme::r (SD_Game::GET . '_players', 'int'); $players ++) {
			$data = [
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
?>
