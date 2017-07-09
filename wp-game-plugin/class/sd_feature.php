<?php
class SD_Feature extends SD_Instance {
	public static $version	= '1.0.0';

	public static $human	= 'Feature';

	public static $scheme	= [];

	const GET		= 'feature';

	public static $T	= 'features';

	protected static $K = [
		'name',
		'cost',
		'mandatory',
		'negotiable'
		];

	public function get ($key = null, $opts = null) {
		global $sd_game;
		$variables = (isset ($sd_game) && $sd_game instanceof SD_Game) ? $sd_game->get ('variables') : [];

		$out = parent::get ($key, $opts);
		if (!empty ($variables) && is_string ($key) && isset ($variables[$this->ID . '_' . $key]))
			$out = $variables[$this->ID . '_' . $key];
		return $out;
		}
	}
?>
