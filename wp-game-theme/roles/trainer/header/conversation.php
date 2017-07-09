<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['character_update'])) {
	var_dump ($_POST);
	$characters = new SD_List ('SD_Character');
	if ($characters->is ('empty')) {
		$error = ['scenario_read' => 1];
		}
	else {
		foreach ($characters->get () as $character) {
			if (isset ($_POST[$character->get () . '_max_score']))
				$character->set ('max_score', (int) SD_Theme::r ($character->get () . '_max_score'));
			}
		}
	SD_Theme::prg ($error);
	}

if (isset ($_POST['import_conversation'])) {
	if (!empty ($_FILES['conversation_file'])) {
		$file = $_FILES['conversation_file'];

		if (!isset ($file['error']) || is_array ($file['error']))
			$error = ['file_read' => 1];

		if ($file['error'] != UPLOAD_ERR_OK)
			$error = ['file_read' => 2];

		if (FALSE === $ext = array_search (mime_content_type ($file['tmp_name']), [
			'xlsx'	=> 'application/vnd.ms-excel'
			], true))
			$error = ['file_read' => 3];

		if (!is_null ($error))
			SD_Theme::prg ($error);

		include (WP_SALESDRIVE_PLUGIN . '/class/phpexcelreader/SpreadsheetReader.php');

		$data = new SpreadsheetReader ($file['tmp_name'], $file['name']);

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
			}


		try {
			$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('character'));
			}
		catch (SD_Exception $e) {
			$character = null;
			$error = [ 'character_update' => 1 ];
			}

		if (!is_null ($character)) {
			$conversation = new SD_Conversation ($character);
			$conversation->set ('data', $tree);
			$conversation->save ();
			}
		}
	SD_Theme::prg ($error);
	}
?>
