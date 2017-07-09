<?php
define ('SD_AJAX_PROTECTION', TRUE);
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
include (dirname (__DIR__) . '/class/tbs/tbs_class.php');
include (dirname (__DIR__) . '/class/tbs/plugins/tbs_plugin_opentbs.php');


/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$user_id = isset ($_GET['user']) ? (int) $_GET['user'] : null;
$sd_user = $sd_theme->get ('user');
$users = $sd_user->is ('admin') ? ( is_null ($user_id) ? get_users () : [ new WP_User ($user_id) ] ) : [ $sd_user->get ('object') ];

$stats = [];
$players_total = 0;

$participants = [];

$count = 1;

foreach ($users as $user) {
	$games = new SD_List ('SD_Game', [sprintf ('owner=%d', $user->ID)]);
	if (!$games->is ('empty'))
		foreach ($games->get () as $game) {
			$stats[] = [
				'no'		=> $count++,
				'trainer'	=> $user->first_name . ' ' . $user->last_name,
				'name'		=> $game->get ('name'),
				'session_start' => date ('d-m-Y H:i', $game->get ('stamp')),
				'session_end'	=> date ('d-m-Y H:i', $game->get ('ended')),
				'scenario'	=> $game->scenario ('name'),
				'players'	=> $game->get ('players') 
				];

			$players_total += $game->get ('players');

			$players = new SD_List ('SD_Player', [sprintf ('game=%d', $game->get())]);
			if (!$players->is ('empty'))
				foreach ($players->get () as $player) {
					$emails = $player->get ('emails_array');
					$participants[] = [
						'team' => $player->get ('name'),
						'trainer' => $user->first_name . ' ' . $user->last_name,
						'scenario' => $game->scenario ('name'),
						'game' => $game->get ('name'),
						'start_date' => date ('d-m-Y H:i', $game->get ('stamp')),
						'emails' => implode (',', $emails)
						];
					}
			}
	}

$TBS = new clsTinyButStrong ();
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
$TBS->LoadTemplate(dirname (__DIR__) . '/assets/xlsx/export-user-stats.xlsx', OPENTBS_ALREADY_UTF8);

$TBS->MergeBlock ('stats', $stats);
$TBS->MergeField ('players_total', $players_total);

$TBS->PlugIn (OPENTBS_SELECT_SHEET, 2);
$TBS->MergeBlock ('participants', $participants);

$TBS->Show(OPENTBS_DOWNLOAD, sprintf ('export-user-stats-%s.xlsx', date ('dmy')));
?>
