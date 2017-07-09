<?php
/**
 * Core of SD_*
 */

/**
 * Entry point for all ajax calls maade from interface elements.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('SD_AJAX_PROTECTION', TRUE);
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$character = SD_Theme::r ('character');
$question = SD_Theme::r ('question');

try {
	$character = new SD_Character ($sd_theme->get ('scenario', 'path'), $character);
	}
catch (SD_Exception $exception) {
	echo json_encode ((object)['error' => 1]);
	exit (1);
	}

$character_id = $character->get ();

$sd_user = $sd_theme->get ('user');
$state = $sd_user->get ('state');
$score = $sd_user->get ('score');

if (!isset ($state[SD_Game::ROUND1_BEGIN]['data']['questions']))
	$state[SD_Game::ROUND1_BEGIN]['data']['questions'] = [];
if (!isset ($state[SD_Game::ROUND1_BEGIN]['data']['questions'][$character_id]))
	$state[SD_Game::ROUND1_BEGIN]['data']['questions'][$character_id] = [];

$conversation = new SD_Conversation ($character);
if ($conversation->has ($question))
	$state[SD_Game::ROUND1_BEGIN]['data']['questions'][$character_id][] = $question;

$sd_user->set ('state', $state);

$answer = $conversation->get ('answer', $question);
$score += $answer->player_score;
$sd_user->set ('score', $score);

echo json_encode ((object) [
	'answer' => $answer,
	'questions' => $conversation->get ('current', $state[SD_Game::ROUND1_BEGIN]['data']['questions'][$character_id]),
	'score' => $score
	]);
?>
