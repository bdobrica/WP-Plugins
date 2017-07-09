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

$character = SD_Theme::r ('character');

try {
	$character = new SD_Character ($sd_theme->get ('scenario', 'path'), $character);
	}
catch (SD_Exception $exception) {
	echo json_encode ((object)['error' => 1]);
	exit (1);
	}

$conversation = new SD_Conversation ($character);
$data = $conversation->get ('tbs');

$level = 1;
while (isset ($data[0]['text_' . ($level + 1)]))
	$level ++;

$TBS = new clsTinyButStrong ();
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
$TBS->LoadTemplate(dirname (__DIR__) . '/assets/xlsx/export-conversation.xlsx', OPENTBS_ALREADY_UTF8);

$TBS->MergeBlock('cell1,cell2', 'num', $level);
$TBS->MergeBlock('b', $data);

$TBS->Show(OPENTBS_DOWNLOAD, vsprintf ('conversation_%s_%s.xlsx', [date ('dmy'), $character->get ()]));
?>
