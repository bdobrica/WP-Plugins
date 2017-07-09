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

global $sd_game;

$games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', 1)]);
if ($games->is ('empty'))
	$sd_game = null;
else
	$sd_game = $games->get ('last');

if (is_null ($sd_game))
	die (/*T[*/'Error'/*]*/);

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
if ($players->is ('empty'))
	die (/*T[*/'Error'/*]*/);

$game_scores = $sd_game->get ('scores');
var_dump ($game_scores);

foreach ($players->get () as $player) {
	echo $player->get ('name') . "\n";
	$state = $player->get ('state');
	$data = $state[SD_Game::ROUND3_BEGIN];
	echo $player->get ('score', 'round3') . "\n";
#	var_dump ($data);
	}
?>
