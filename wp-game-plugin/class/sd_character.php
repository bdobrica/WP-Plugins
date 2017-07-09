<?php
/**
 * Core of SD_*
 */

/**
 * Game NPC
 *
 * @category
 * @package SalesDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class SD_Character extends SD_Instance {
	public static $version	= '1.0.0';

	public static $human	= 'Character';

	public static $scheme	= [];

	const DEFAULT_IMG	= 'assets/img/user.png';
	const GET		= 'character';

	public static $T	= 'characters';

	public static $R = [
		'ceo'		=> /*T[*/'CEO'/*]*/,
		'secretary'	=> /*T[*/'Secretary'/*]*/,
		'other'		=> /*T[*/'Other'/*]*/
		];

	public static $S = [
		'pleased'	=> /*T[*/'Pleased'/*]*/,
		'upset'		=> /*T[*/'Upset'/*]*/,
		'neutral'	=> /*T[*/'Neutral'/*]*/,
		'thinking'	=> /*T[*/'Thinking'/*]*/
		];

	protected static $K = [
		'name',
		'position',
		'role',
		'resume',
		'images',
		'max_score',
		'order'
		];

	public function get ($key = null, $opts = null) {
		if (is_string ($key)) {
			$slug = self::slug ($key);
			switch ($slug) {
				case 'image':
					if (isset ($this->data['images'][$opts]))
						$file = plugin_dir_url ($this->data['images'][$opts]) . basename ($this->data['images'][$opts]);
					else
						$file = get_template_directory_uri () . DIRECTORY_SEPARATOR . self::DEFAULT_IMG;
					return $file;
					break;
				}
			}
		return parent::get ($key, $opts);
		}

	public function remove () {
		if (!empty ($this->data['images']))
			foreach ($this->data['images'] as $key => $value) {
				if (!file_exists ($value)) continue;
				if (!@unlink ($value)) continue;
				}
		return parent::remove ();
		}
	}
?>
