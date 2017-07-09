<?php
/**
 * Core of CoreSite;
 */
namespace CoreSite\Core;
/**
 * List Objects
 *
 * @category
 * @package BusinessDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class Find {
	private $list;

	public function __construct ($object, $filter = null) {
		global
			$wpdb,
			$bd_theme;
		$this->list = [];

		if (is_string ($object)) {
			if (strpos ($object, '-') !== FALSE) {
				}
			else
			if (class_exists ($object)) {
				$path = null;

				if (method_exists ($object, 'scan')) {
					if (get_parent_class ($object) == 'BD_Instance') {
						if (is_null ($filter))
							$path = $bd_theme->get ('scenario', 'path');
						else
							$path = $filter;
						}

					#$objects = array_keys (is_null ($path) ? $object::scan () : $object::scan ($path));
					$objects = array_keys ($object::scan ($path, $filter));
					}
				elseif (property_exists ($object, 'Q')) {
					$sql = 'select id from `' . $wpdb->prefix . $object::$T . '` where ' . (empty($filter) ? 1 : implode (' and ', $filter));
					$objects = $wpdb->get_col ($sql);
					}
				if (!empty ($objects))
				foreach ($objects as $object_slug)
					$this->list[$object_slug] = is_null ($path) ? new $object ($object_slug) : new $object ($path, $object_slug);
				}
			}
		}

	public function get ($key = null, $opts = null) {
		if (is_string ($key)) {
			switch ($key) {
				case 'sizeof':
				case 'count':
					return sizeof ($this->list);
					break;
				case 'first':
					reset ($this->list);
					return current ($this->list);
					break;
				case 'last':
					if (empty ($this->list)) return null;
					$out = end ($this->list);
					reset ($this->list);
					return $out;
					break;
				case 'select':
					if (empty ($this->list)) return [];
					$out = [];
					foreach ($this->list as $id => $object)
						$out[$id] = $object->get ($opts);
					return $out;
					break;
				}
			}
		return $this->list;
		}

	public function is ($what = null) {
		if (is_string ($what)) {
			switch ($what) {
				case 'empty':
					return empty ($this->list);
					break;
				}
			}
		}

	public function sort ($by = '', $ord = 'asc') {
		if (empty ($this->list))
			$this->get ();
		
		switch ($by) {
			case 'name':
				uasort ($this->list, [$this, '_cmp_onm']);
				break;
			case 'owner':
				uasort ($this->list, [$this, '_cmp_own']);
				break;
			case 'date':
			case 'stamp':
			case 'time':
				uasort ($this->list, [$this, '_cmp_stm']);
				break;
			default:
				uasort ($this->list, [$this, '_cmp_ord']);
			}
		if ($ord == 'desc')
			$this->list = array_reverse ($this->list, TRUE);
		}
	
	private function _cmp_ord ($a, $b) {
		$va = $a->get ('order');
		$vb = $b->get ('order');
		return $va == $vb ? 0 : ($va < $vb ? -1 : 1);
		}

	private function _cmp_stm ($a, $b) {
		$va = $a->get ('stamp');
		$vb = $b->get ('stamp');
		return $va == $vb ? 0 : ($va < $vb ? -1 : 1);
		}

	private function _cmp_onm ($a, $b) {
		$va = strtolower ($a->get ('name'));
		$vb = strtolower ($b->get ('name'));
		return strcmp ($va, $vb);
		}
	}
?>
