<?php
/**
 * Core of SD_*
 */

/**
 * Timer. Handles timer events.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

try {
	$current_player = new SD_Player (SD_Theme::r ('player'));
	}
catch (SD_Exception $exception) {
	echo json_encode ((object) ['error' => 1]);
	exit (1);
	}

try {
	$game = new SD_Game ((int) $current_player->get ('game'));
	}
catch (SD_Exception $exception) {
	echo json_encode ((object) ['error' => 2]);
	exit (1);
	}

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $game->get ())]);
if ($players->is ('empty')) {
	echo json_encode ((object) ['error' => 3]);
	exit (1);
	}

$polls = new SD_List ('SD_Poll', $game->scenario ('path'));
if ($polls->is ('empty')) {
	echo json_encode ((object) ['error' => 4]);
	exit (1);
	}

$votes = [];
foreach ($polls->get () as $poll) {
	$votes[] = [
		'slug'	=> $poll->get (),
		'name'	=> $poll->get ('name'),
		'value' => 0
		];
	}

$count = 0;
foreach ($players->get () as $player) {
	$all_states = $player->get ('state');
	$state = $all_states[SD_Game::ROUND3_BEGIN];
	if (!isset ($state['data']['voted'][$current_player->get ()]) || empty($state['data']['voted'][$current_player->get ()])) continue;
	foreach ($state['data']['voted'][$current_player->get ()] as $sid => $data) {
		$count ++;
		foreach ($votes as $key => $value) {
			if (isset ($data[$value['slug']]))
				$votes[$key]['value'] += $data[$value['slug']];
			}
		}
	}

if ($count > 0)
	foreach ($votes as $key => $value) {
		$value['value'] = sprintf ('%.2f', $value['value'] / $count);
		$votes[$key] = (object) $value;
		}
else
	$votes = [];

echo json_encode ($votes);
?>
