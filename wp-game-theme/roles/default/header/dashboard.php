<?php
if (!empty ($_POST)) {
	if (isset ($_POST['username']) && isset ($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$locale = isset ($_POST['locale']) ? $_POST['locale'] : '';

		$user = wp_signon ([
			'user_login'	=> $username,
			'user_password'	=> $password
			], FALSE);

		if (is_wp_error ($user)) {
			$message = $user->get_error_message ();
			echo $message;
			}
		else {
			if ($locale) {
				$old_locale = get_user_meta ($user->ID, SD_User::LOCALE, TRUE);
				if ($old_locale)
					update_user_meta ($user->ID, SD_User::LOCALE, $locale);
				else
					add_user_meta ($user->ID, SD_User::LOCALE, $locale, TRUE);
				}

			header ('Location: ' . $_SERVER['REQUEST_URI'], 303);
			exit (1);
			}
		}
	else
	if (isset ($_POST['teampass'])) {
		$password = $_POST['teampass'];
		$locale = isset ($_POST['locale']) ? $_POST['locale'] : '';

		$player = new SD_Player ();
		if ($player->login ($password)) {

			if (empty ($locale)) {
				$game = new SD_Game ((int) $player->get ('game'));
				$locale = $game->get ('locale');
				if ($locale)
					$player->set ('locale', $locale);
				}

			header ('Location: ' . $_SERVER['REQUEST_URI'], 303);
			exit (1);
			}
		}
	}
?>
