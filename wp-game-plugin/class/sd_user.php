<?php
/**
 * Core of SD_*
 */

/**
 * User Class
 *
 * @category
 * @package SalesDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class SD_User {
	const LOCALE = '_sd_locale';

	private $ID;

	private $object;

	public function __construct ($data = null) {
		$this->ID = 0;
		$this->object = null;

		if (is_numeric ($data)) {
			$this->object = new SD_Player ((int) $data);
			if ($this->object->get ())
				$this->ID = $this->object->get ();
			}
		else
		if (is_null ($data)) {
			$user = wp_get_current_user ();
			if ($user->ID) {
				$this->ID = $user->ID;
				$this->object = $user;
				}
			}
		}

	public function get ($key = null, $opts = null) {
		if (is_string ($key)) {
			switch ($key) {
				case 'role':
					if ($this->ID == 0) return 'default';
					if ($this->object instanceof WP_User) return 'trainer';
					if ($this->object instanceof SD_Player) return 'player';
					break;
				case 'game':
					if ($this->ID > 0 && ($this->object instanceof SD_Player)) {
						$game = new SD_Game ((int) $this->object->get ('game'));
						if (is_null ($opts) || !is_string ($opts)) return $game;
						return $game->get ($opts);
						}
					return null;
					break;
				case 'object':
					return $this->object;
					break;
				case 'locale':
					if ($this->ID == 0) return '';
					if ($this->object instanceof SD_Player) return $this->object->get ('locale');
					if ($this->object instanceof WP_User) return get_user_meta ($this->object->ID, self::LOCALE, TRUE);
					break;
				}
			}
		return $this->object instanceof SD_Player ? $this->object->get ($key, $opts) : $this->object->get (is_null ($key) ? 'ID' : $key);
		}

	public function out ($key = null, $opts = null) {
		if ($this->object instanceof SD_Player)
			$this->object->out ($key, $opts);
		}

	public function set ($key = null, $value = null) {
		if ($this->object instanceof SD_Player)
			return $this->object->set ($key, $value);
		if ($this->object instanceof WP_User && is_string ($key) && $key == 'locale') {
			$locale = get_user_meta ($this->object->ID, self::LOCALE, TRUE);
			if ($locale)
				update_user_meta ($this->object->ID, self::LOCALE, $opts);
			else
				add_user_meta ($this->object->ID, self::LOCALE, $opts, TRUE);
			}
		}

	public function is ($key = null) {
		if (is_null ($key))
			return $this->ID > 0;
		if (is_string ($key) && $key == 'admin')
			return current_user_can ('remove_users');
		return FALSE;
		}

	public function compute ($key = null, $opts = null) {
		if ($this->object instanceof SD_Player)
			return $this->object->compute ($key, $opts);
		return FALSE;
		}

	public function quotation ($quotation_id = null, $format = 'array') {
		if ($this->object instanceof SD_Player)
			return $this->object->quotation ($quotation_id, $format);
		return FALSE;
		}

	public function report ($html = TRUE, $echo = TRUE) {
		if ($this->object instanceof SD_Player)
			return $this->object->report ($html, $echo);
		return FALSE;
		}

	public function __destruct () {
		}
	}
?>
