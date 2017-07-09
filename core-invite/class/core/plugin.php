<?php
/**
 * Core of CoreSite
 */
namespace CoreSite\Core;
/**
 * Plugin Class. The SalesDrive plugin is an instance of this class.
 *
 * @category
 * @package CoreSite
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class Plugin {
	const NAME		= 'Core Invite';
	const CAPABILITY	= 'administrator';
	const MENU		= 'menu';

	const PLUGIN_SLUG	= 'core_invite';

	public function __construct () {
		add_action ('admin_menu', [$this, 'admin_menu']);
		}

	public function get ($key = null, $opts = null) {
		if (is_string ($key)) {
			switch ($key) {
				case 'url':
					return plugins_url ($opts, __DIR__);
					break;
				}
			}
		return null;
		}

	public function out ($key = null, $opts = null) {
		$out = $this->get ($key, $opts);
		if (is_string ($out)) echo $out;
		}

	public function admin_menu () {
		add_menu_page (self::NAME . ' Menu', self::NAME . ' Menu', self::CAPABILITY, Options::PREFIX . self::MENU, [$this, 'admin_page']);

		$options = new Options ();
		$options->register (self::MENU, self::CAPABILITY);
		}

	public function admin_page () {
		}
	}
?>
