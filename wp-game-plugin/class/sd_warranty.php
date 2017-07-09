<?php
class SD_Warranty extends SD_Instance {
	public static $version	= '1.0.0';

	public static $human	= 'Warranty';

	public static $scheme	= [];

	const GET		= 'warranty';

	public static $T	= 'warranties';

	protected static $K = [
		'name',
		'cost',
		'mandatory',
		'negotiable',
		'order'
		];

	public function get ($key = null, $opts = null) {
		global $sd_game;
		$variables = (isset ($sd_game) && $sd_game instanceof SD_Game) ? $sd_game->get ('variables') : [];

		if (is_string ($key)) {
			switch ($key) {
				case 'better':
					$better = [];
					$warranties = new SD_List ('SD_Warranty', dirname ($this->path));
					if (!empty ($warranties))
						foreach ($warranties->get () as $warranty) {
							if ($warranty->get ('cost') >= $this->get ('cost'))
								$better[] = $warranty->get ();
							}
					return $better;
					break;
				}
			}

		$out = parent::get ($key, $opts);
		if (!empty ($variables) && is_string ($key) && isset ($variables[$this->ID . '_' . $key]))
			$out = $variables[$this->ID . '_' . $key];
		return $out;
		}
	}
?>
