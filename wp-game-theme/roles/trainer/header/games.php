<?php
$error = null;

if (isset ($_POST['game'])) {
	try {
		$_game = new SD_Game ((int) SD_Theme::r ('game'));
		}
	catch (SD_Exception $e) {
		$_game = null;
		}

	if (!is_null ($_game)) {
		if (isset ($_POST['game_play'])) {
			$_games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', $sd_user->get ())]);
			if (!$_games->is ('empty'))
				foreach ($_games->get () as $__game)
					$__game->set ('active', $_game->get () == $__game->get () ? 1 : 0);
			$_game->set ('active', 1);
			}
		if (isset ($_POST['game_pause'])) {
			$_game->set ('active', 0);
			}
		if (isset ($_POST['game_delete'])) {
			try {
				$_game->delete ();
				}
			catch (SD_Exception $e) {
				}
			}
		}

	SD_Theme::prg ($error);
	}
?>
