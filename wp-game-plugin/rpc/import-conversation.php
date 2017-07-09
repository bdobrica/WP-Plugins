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
include (dirname (__DIR__) . '/class/phpexcelreader/SpreadsheetReader.php');

/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$data = new SpreadsheetReader ('demo.xlsx');

$col_map = [];

$header_detected = FALSE;
$node = null;
$parents = [];
$tree = [];

$last_id = 0.00;
$step_id = 0.000001;

foreach ($data as $row) {
	if ($header_detected) {
		$type = strtolower (trim ($row[$col_map['type']]));
		if ($type == 'q') {
			$level = 0;
			while (isset ($col_map['level_' . ($level + 1)]) && (trim ($row[$col_map['level_' . ($level + 1)]]) == '')) $level ++;
			$level ++;

			$node = (object) [
				'id'		=> $last_id,
				'name'		=> trim ($row[$col_map['level_' . $level]]),
				'data'		=> (object) [
						'player_question'	=> trim ($row[$col_map['level_' . $level]]),
						'character_answer'	=> '',
						'character_state'	=> $row[$col_map['facial_expression']],
						'character_delay'	=> intval ($row[$col_map['answer_delay']]),
						'player_score'		=> intval ($row[$col_map['player_question_score']]),
						'allow_purchase'	=>
									((strtolower (trim ($row[$col_map['allow_product_purchase']])) == 'yes') ||
									(strtolower (trim ($row[$col_map['allow_product_purchase']])) == '1') ||
									(strtolower (trim ($row[$col_map['allow_product_purchase']])) == 'true'))
						],
				'is_open'	=> true,
				'level'		=> $level,
				'children'	=> []
				];

			$last_id += $step_id;
			}
		if ($type == 'a') {
			$node->data->character_answer = trim ($row[$col_map['level_' . $node->level]]);

			if ($node->level == 1) {
				$tree[] = $node;
				$parents = [];
				}
			else {
				$parent = null;
				if (!empty ($parents)) {
					$parent = array_pop ($parents);
					while (!empty ($parents) && (($parent->level + 1) != $node->level)) $parent = array_pop ($parents);
					}
				if (!is_null ($parent)) {
					$parent->children[] = $node;
					$parents[] = $parent;
					}
				}
			$parents[] = $node;
			}
		continue;
		}

	foreach ($row as $num => $cell) {
		if (strtolower (trim ($cell)) == 'type')			$col_map['type']			= $num;
		if (strtolower (trim ($cell)) == 'face')			$col_map['facial_expression']		= $num;
		if (strtolower (trim ($cell)) == 'delay')			$col_map['answer_delay']		= $num;
		if (strtolower (trim ($cell)) == 'score')			$col_map['player_question_score']	= $num;
		if (strtolower (trim ($cell)) == 'china')			$col_map['allow_product_purchase']	= $num;
		if (strpos (strtolower (trim ($cell)), 'level ') === 0) {
			$level = intval (substr (strtolower (trim ($cell)), 6));
			$col_map['level_' . $level] = $num;
			}
		}
	if (
		isset ($col_map['facial_expression']) &&
		isset ($col_map['answer_delay']) &&
		isset ($col_map['player_question_score']) &&
		isset ($col_map['allow_product_purchase']) &&
		isset ($col_map['level_1'])
		) $header_detected = TRUE;
	var_dump ($header_detected);
	}

var_dump ($tree);

/*
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

$TBS->Show(OPENTBS_DOWNLOAD, 'demo.xlsx');
*/
?>
