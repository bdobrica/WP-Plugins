<?php
class SD_Location extends SD_Instance {
	public static $version	= '1.0.0';

	public static $human	= 'Location';

	public static $scheme	= [];

	const GET		= 'location';

	public static $T	= 'locations';

	protected static $K = [
		'name',
		'delivery_time',
		'delivery_cost',
		'day_saved_cost',
		'max_delivery_time',
		'desired_delivery_time'
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
