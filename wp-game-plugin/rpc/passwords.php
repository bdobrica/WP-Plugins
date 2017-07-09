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
include (dirname (__DIR__) . '/class/tbs/tbs_class.php');
include (dirname (__DIR__) . '/class/tbs/plugins/tbs_plugin_opentbs.php');


/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$sd_user = $sd_theme->get ('user');

$games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', $sd_user->get ())]);
$game = $games->get ('last');

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $game->get ())]);
$team = [];
if (!$players->is ('empty')) {
	foreach ($players->get () as $player)
		$team[] = [ 'name' => $player->get ('name') . "\n\n", 'password' => $player->get ('password')];

	$tbs = new clsTinyButStrong ();
	$tbs->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
	$tbs->LoadTemplate(dirname (__DIR__) . '/assets/docx/password-template.docx', OPENTBS_ALREADY_UTF8);
	$tbs->MergeBlock ('team', $team);

	$tbs->Show(OPENTBS_DOWNLOAD, vsprintf ('password_%s.docx', [date ('dmy')]));
	}
die ('Error!');
?>
