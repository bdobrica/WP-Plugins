<?php
/**
 * Core of SD_*
 */

/**
 * NPC Conversation Storage
 *
 * @category
 * @package SalesDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */

/**
 * The conversation is held in human readable XML-form:
 * <character>
 *	<id>{character_id}</id>
 *	<name></name>
 *	<position></position>
 *	<resume></resume>
 *	<question>
 *	Some question?
 *		<score>{question_score}</score>
 *		<stock>{allow_restock}</stock>
 *		<timer>{timer_delay}</timer>
 * 		<answer state="{character_state}">
 *		Some answer.
 *		</answer>
 * 	</question>
 * </character>
 */
class SD_Conversation {
	const DEFAULT_TYPE	= 'json';

	const REUSABLE_NO	= 0;
	const REUSABLE_TOP	= 1;
	const REUSABLE_LIN	= 2;

	private $character;
	private $path;
	private $data;
	private $type;
	private $question_type;

	public function __construct ($character, $data = null) {
		global $sd_theme;

		$this->character = $character instanceof SD_Character ? $character : new SD_Character ($sd_theme->get ('scenario', 'path'), $character);
		$this->question_type = $sd_theme->get ('scenario', 'reusable_questions');
		
		$this->type = self::DEFAULT_TYPE;

		if (is_object ($this->character))
			$this->path = $this->character->get ('file_path_noext') . '.' . $this->type;

		if (!is_null ($data)) {
			if (is_string ($data) && static::is_json ($data))
				$this->data = json_decode ($data);
			else
			if (is_array ($data))
				$this->data = $data;
			else
				throw new SD_Exception ();
			}
		else {
			if (file_exists ($this->path)) {
				$file_data = file_get_contents ($this->path);
				if (($this->type == 'json') && !static::is_json ($file_data))
					throw new SD_Exception ();
				$this->data = json_decode ($file_data);
				}
			else
				$this->data = [];
			}		
		}

	public function set ($key = null, $value = null) {
		if (is_null ($key)) return FALSE;
		if (is_string ($key)) {
			$slug = static::slug ($key);
			if ($slug == 'tree') {
				if (is_string ($value) && static::is_json ($value))
					$this->data = json_decode ($value);
				else
					throw new SD_Exception ();
				}
			if ($slug == 'data') {
				if (is_array ($value))
					$this->data = $value;
				else
					throw new SD_Exception ();
				}
			}
		return $this->save ();
		}

	public function get ($key = null, $opts = null) {
		if (is_null ($key)) return FALSE;
		$slug = static::slug ($key);

		if ($slug == 'path')
			return $this->path;
		if ($slug == 'tree')
			return json_encode ($this->data);
		if ($slug == 'php')
			return $this->data;
		if ($slug == 'answer') {
			$node = $this->_search ($opts);
			if (is_null ($node)) return null;
			return (object) [
				'id'			=> $node->id,
				'player_question'	=> $node->data->player_question,
				'player_name'		=> SD_Theme::__ (/*T[*/'You'/*]*/),
				'character_name'	=> $this->character->get ('name'),
				'character_answer'	=> $node->data->character_answer,
				'character_state'	=> $node->data->character_state,
				'character_image'	=> $this->character->get ('image', 'neutral'),
				'character_delay'	=> $node->data->character_delay,
				'player_score'		=> $node->data->player_score,
				'allow_purchase'	=> $node->data->allow_purchase
				];
			}
		if ($slug == 'stats') {
			return $this->_dijkstra ();
			}
		if ($slug == 'tbs') {
			$out = [];

			$stack = [];
			foreach ($this->data as $node) {
				$node->level = 0;
				array_push ($stack, $node);
				}

			$max_level = 0;

			while (!empty ($stack)) {
				$node = array_shift ($stack);

				$out[] = [
					'type'				=> 'Q',
					'text_' . ($node->level + 1) 	=> trim ($node->data->player_question),
					'facial_expression'		=> $node->data->character_state,
					'answer_delay'			=> intval ($node->data->character_delay),
					'question_score'		=> intval ($node->data->player_score),
					'allow_product_purchase'	=> $node->data->allow_purchase ? 'yes' : 'no',
					];
				$out[] = [
					'type'				=> 'A',
					'text_' . ($node->level + 1)	=> trim ($node->data->character_answer),
					'facial_expression'		=> '',
					'answer_delay'			=> '',
					'question_score'		=> '',
					'allow_product_purchase'	=> '',
					];

				if ($max_level < $node->level) $max_level = $node->level;

				if (!empty ($node->children)) {
					foreach ($node->children as $child)
						$child->level = $node->level + 1;
					$stack = array_merge ($node->children, $stack);
					}
				}

			foreach ($out as $id => $row)
				for ($level = 0; $level <= $max_level; $level++)
					if (!isset ($row['text_' . ($level + 1)]))
						$out[$id]['text_' . ($level + 1)] = '';

			return $out;
			}
		if ($slug == 'current') {
			$out = [];
			if (is_null ($opts) || (is_array ($opts) && empty ($opts))) {
				foreach ($this->data as $node) {
					if (!empty ($node->data->player_question))
						$out[] = (object) [
							'id'			=> $node->id,
							'player_question'	=> $node->data->player_question,
							'parent'		=> -1
							];
					}
				}
			else {
				$last = end ($opts);
				reset ($opts);
				$last = $this->_search ($last);

				switch ($this->question_type) {
					case self::REUSABLE_LIN:
						$children = $this->_children ($last, $opts);
						if (!is_null ($last) && empty ($children)) {
							$siblings = $this->_siblings ($last);
							if (!empty ($siblings)) {
								$chosen_one = end ($siblings);
								$children = $this->_children ($chosen_one, $opts);
								}
							}

						if (is_null ($last))
							foreach ($this->data as $node)
								if (!in_array ($node->id, $opts)) {
									if (!empty ($node->data->player_question))
										$out[] = (object) [
											'id'			=> $node->id,
											'player_question'	=> $node->data->player_question,
											'parent'		=> -1
											];
									}
						
						if (!empty ($children))
							foreach ($children as $node) {
								if (!empty ($node->data->player_question))
									$out[] = (object) [
										'id'			=> $node->id,
										'player_question'	=> $node->data->player_question,
										'parent'		=> $last->id
										];
								}
						break;
					default:
						$children = $this->_children ($last, $opts);
						while (!is_null ($last) && empty ($children)) {
							$last = $this->_parent ($last->id);
							$children = $this->_children ($last, $opts);
							}

						if (is_null ($last))
							foreach ($this->data as $node)
								if (!in_array ($node->id, $opts)) {
									if (!empty ($node->data->player_question))
										$out[] = (object) [
											'id'			=> $node->id,
											'player_question'	=> $node->data->player_question,
											'parent'		=> -1
											];
									}
						
						if (!empty ($children))
							foreach ($children as $node) {
								if (!empty ($node->data->player_question))
									$out[] = (object) [
										'id'			=> $node->id,
										'player_question'	=> $node->data->player_question,
										'parent'		=> $last->id
										];
								}
						break;
					} // end switch
				}
			return $out;
			}
		}

	public function has ($id) {
		$node = $this->_search ($id);
		return !is_null ($node);
		}

	public function save () {
		$pack = '';
		if (is_null ($this->character))
			throw SD_Exception ();
		if ($this->type == 'json')
			$pack = json_encode ($this->data);
		if (($fd = fopen ($this->path, 'w+')) === FALSE)
			throw new SD_Exception ();
		fwrite ($fd, $pack);
		fclose ($fd);
		return TRUE;
		}

	public function render ($questions, $echo = FALSE) {
		$out = '';

		$stack = [];
		foreach ($this->data as $node) array_push ($stack, $node);

		$possible_score = 0;
		$your_score = 0;

		while (!empty ($stack)) {
			$node = array_shift ($stack);

			if (is_string ($node)) {
				$out .= $node;
				continue;
				}

			$out .= '<li>';
	
			$item = '<span class="sd-conversation-question"><span class="sd-conversation-you">' . SD_Theme::__ (/*T[*/'You'/*]*/) . '</span>: ' . $node->data->player_question . '</span><br /><span class="sd-conversation-answer"><span class="sd-conversation-npc">' . $this->character->get ('name') . '</span>: ' . $node->data->character_answer . ' (';
			$item .= SD_Theme::__ (/*T[*/'Score'/*]*/) . ': ' . $node->data->player_score . ')</span>';

			if (!in_array ($node->id, $questions))
				$out .= '<span class="sd-conversation-missed">' . $item . '</span>';
			else {
				$your_score += $node->data->player_score;
				$out .= '<span class="sd-conversation-visited">' . $item . '</span>';
				}
			$possible_score += $node->data->player_score;

			array_unshift ($stack, '</li>' . "\n");

			if (!empty ($node->children)) {
				array_unshift ($stack, '</ul>' . "\n");
				$stack = array_merge ($node->children, $stack);
				array_unshift ($stack, '<ul>' . "\n");
				}
			}
		unset ($stack);

		$out = '<div class="sd-conversation-score">' . SD_Theme::__ (/*T[*/'Score'/*]*/) . ': ' . $your_score . ' / ' . $possible_score . '</div>' . "\n" . '<ul class="sd-conversation-render">' . "\n" . $out . '</ul>' . "\n";

		if (!$echo) return $out;
		echo $out;
		}

	public function docx_render ($questions) {
		$out = [['message' => $this->character->get ('name'), 'answer' => '', 'visited' => 2]];

		$stack = [];
		foreach ($this->data as $node) array_push ($stack, $node);

		$offset = '';
		$piece = '   ';
		$possible_score = 0;
		$your_score = 0;

		while (!empty ($stack)) {
			$node = array_shift ($stack);
			if (is_string ($node)) {
				if ($node == ']' && strlen ($offset) > 0) $offset = substr ($offset, 0, - strlen ($piece));
				if ($node == '[') $offset .= $piece;
				continue;
				}

			$item = [
				'message' => $offset . SD_Theme::__ (/*T[*/'You'/*]*/) . ': ' . trim ($node->data->player_question) . "\n",
				'answer' => $offset . $this->character->get ('name') . ':' . trim ($node->data->character_answer) . ' (' . SD_Theme::__ (/*T[*/'Score'/*]*/) . ': ' . $node->data->player_score . ')',
				'visited' => 0
				];

			if (!in_array ($node->id, $questions)) {
				$out[] = $item;
				}
			else {
				$item['visited'] = 1;
				$your_score += $node->data->player_score;
				$out[] = $item;
				}
			$possible_score += $node->data->player_score;

			if (!empty ($node->children)) {
				array_unshift ($stack, ']');
				$stack = array_merge ($node->children, $stack);
				array_unshift ($stack, '[');
				}
			}
		unset ($stack);

		$out[0]['message'] .= ' (' . SD_Theme::__ (/*T[*/'Score'/*]*/) . ': ' . $your_score . ' / ' . $possible_score . ')';

		return $out;
		}

	private function _dijkstra () {
		$stack = [];
		$max = [];
		$pos = 0;
		foreach ($this->data as $node) array_push ($stack, $node);

		$out = [];
		$prev = FALSE;
		
		while (!empty ($stack)) {
			$node = array_shift ($stack);
			if (is_string ($node)) {
				if ($node == '[') {
					$pos ++;
					}
				if ($node == ']') {
					$out[] = array_sum ($max); //implode (',', $max);
					array_pop ($max);
					$pos --;
					}
				continue;
				}
			$max[$pos] = $node->data->player_score;
			if (!empty ($node->children)) {
				array_unshift ($stack, ']');
				$stack = array_merge ($node->children, $stack);
				array_unshift ($stack, '[');
				}
			}
		unset ($stack);

		if (empty ($out)) return FALSE;

		$min = min ($out);
		$max = max ($out);
		$sum = array_sum ($out);
		$avg = $sum / count ($out);
		$var = 0;
		foreach ($out as $val) $var += ($val - $avg)*($val - $avg);
		$var /= count ($out);

		return [ 'min' => $min, 'max' => $max, 'avg' => $avg, 'var' => $var, 'sig' => sqrt($var), 'num' => count ($out) ];
		}

	private function _search ($id) {
		$stack = [];
		foreach ($this->data as $node) array_push ($stack, $node);

		$result = null;
		while (!empty ($stack)) {
			$node = array_pop ($stack);
			if ($node->id == $id) {
				$result = $node;
				break;
				}
			if (!empty ($node->children))
				$stack = array_merge ($stack, $node->children);
			}
		unset ($stack);

		return $result;
		}

	private function _parent ($id) {
		$stack = [];
		foreach ($this->data as $node) array_push ($stack, $node);

		$result = null;
		while (!empty ($stack)) {
			$node = array_pop ($stack);
			if (!empty ($node->children)) {
				foreach ($node->children as $child)
					if ($child->id == $id) {
						$result = $node;
						break;
						}
				if (!is_null ($result)) break;
				$stack = array_merge ($stack, $node->children);
				}
			}
		unset ($stack);

		return $result;
		}

	private function _children ($node, $list = []) {
		if (empty ($list))
			return $node->children;
		if (empty ($node->children))
			return [];

		$out = [];
		foreach ($node->children as $child)
			if (!in_array ($child->id, $list))
				$out[] = $child;
		return $out;
		}

	private function _siblings ($node, $only_non_empty = FALSE) {
		$parent = $this->_parent ($node->id);
		$out = [];
		foreach ($parent->children as $child)
			if ($child->id != $node->id) {
				if ($only_non_empty && !empty ($child->children))
					$out[] = $child;
				else
					$out[] = $child;
				}
		return $out;
		}

	public static function is_json ($string) {
		$dummy = json_decode ($string);
		if (json_last_error () != JSON_ERROR_NONE) return FALSE;
		if (is_array ($dummy) || is_object ($dummy)) return TRUE;
		return FALSE;
		}

	public static function slug ($key) {
		return trim (preg_replace('/[^a-z]+/', '_', strtolower(trim($key))), '_');
		}
	}
?>
