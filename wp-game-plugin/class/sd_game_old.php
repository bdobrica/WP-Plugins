<?php
/**
 * Core of SD_*
 */

/**
 * Game Class
 *
 * @category
 * @package SalesDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class SD_Game extends SD_Model {
	public static $version = '1.0.0';

	public static $human = 'Game';

	public static $scheme = [];

	const GET		= 'game';

	const BEGIN_GAME	= 0;
	const ROUND0_BEGIN	= 1;
	const ROUND0_END	= 2;
	const ROUND1_BEGIN	= 3;
	const ROUND1_END	= 4;
	const ROUND2_BEGIN	= 5;
	const ROUND2_END	= 6;
	const ROUND3_BEGIN	= 7;
	const ROUND3_END	= 8;
	const ROUND4_BEGIN	= 9;
	const ROUND4_END	= 10;
	const GAME_REPORT	= 11;
	const END_GAME		= 12;
	const CANCELED_GAME	= 13;

	const MAX_MEETINGS	= 3;

	const NEGOTIATION_0	= 0;
	const NEGOTIATION_1	= 1;
	const NEGOTIATION_2	= 2;
	const NEGOTIATION_3	= 3;

	public static $T = 'sd_games';

	protected static $K = [
		'owner',
		'name',
		'players',
		'scenario',
		'state',
		'state_data',
		'negotiation',
		'market',
		'variables'
		];

	protected static $Q = [
		'`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT',
		'`owner` int(11) NOT NULL DEFAULT 0',
		'`name` varchar(64) NOT NULL DEFAULT \'\'',
		'`players` int(2) NOT NULL DEFAULT 4',
		'`scenario` varchar(128) NOT NULL DEFAULT \'default\'',
		'`state` int(11) NOT NULL DEFAULT 0',
		'`state_data` text NOT NULL',
		'`negotiation` int(11) NOT NULL DEFAULT 0',
		'`market` text NOT NULL',
		'`variables` text NOT NULL'
		];

	public static $S = [
		self::BEGIN_GAME	=> /*T[*/'Game Started'/*]*/,
		self::ROUND0_BEGIN	=> /*T[*/'Begin Registration'/*]*/,
		self::ROUND0_END	=> /*T[*/'End Registration'/*]*/,
		self::ROUND1_BEGIN	=> /*T[*/'Begin Conversations'/*]*/,
		self::ROUND1_END	=> /*T[*/'End Conversations'/*]*/,
		self::ROUND2_BEGIN	=> /*T[*/'Begin Offering'/*]*/,
		self::ROUND2_END	=> /*T[*/'End Offering'/*]*/,
		self::ROUND3_BEGIN	=> /*T[*/'Begin Presentations'/*]*/,
		self::ROUND3_END	=> /*T[*/'End Presentations'/*]*/,
		self::ROUND4_BEGIN	=> /*T[*/'Begin Negotiation'/*]*/,
		self::ROUND4_END	=> /*T[*/'End Negotiation'/*]*/,
		self::GAME_REPORT	=> /*T[*/'Game Report'/*]*/,
		self::END_GAME		=> /*T[*/'Game Ended'/*]*/,
		self::CANCELED_GAME	=> /*T[*/'Game Canceled'/*]*/,
		];

	/**
	 * The player can login only on this rounds:
	 */
	public static $PL = [ /* self::ROUND0_BEGIN, */ self::ROUND1_BEGIN, self::ROUND2_BEGIN, self::ROUND3_BEGIN, self::ROUND4_BEGIN, self::GAME_REPORT ];

	/**
	 * The order of the rounds
	 */
	public static $SO = [
		self::BEGIN_GAME,
/*		self::ROUND0_BEGIN,
		self::ROUND0_END,*/
		self::ROUND1_BEGIN,
		self::ROUND1_END,
		self::ROUND2_BEGIN,
		self::ROUND2_END,
		self::ROUND3_BEGIN,
		self::ROUND3_END,
		self::ROUND4_BEGIN,
		self::ROUND4_END,
		self::GAME_REPORT,
		self::END_GAME
		];

	public static $N = [
		[
			'section'	=> /*T[*/'Client'/*]*/,
			'slug'		=> 'client',
			'fields'	=> [
			'buying_mode',
			'price_weight',
			'adv_budg_weight',
			'paym_term_weight',
			'delivery_weight',
			'features_weight',
			'warranty_weight'
			]
		],
		[
			'section'	=> /*T[*/'Products'/*]*/,
			'slug'		=> 'products',
			'fields'	=> [
			'max_price_accepted',
			'desired_price',
			'desired_quantity',
			'min_adv_budget_accepted',
			'desired_adv_budget',
			'min_paym_term_accepted',
			'desired_payment_term'
			]
		],
		[
			'section'	=> /*T[*/'Warranty'/*]*/,
			'slug'		=> 'warranty',
			'fields'	=> [
			'mandatory',
			'negotiable'
			]
		],
		[
			'section'	=> /*T[*/'Features'/*]*/,
			'slug'		=> 'features',
			'fields'	=> [
			'mandatory',
			'negotiable'
			]
		],
		[
			'section'	=> /*T[*/'Delivery Locations'/*]*/,
			'slug'		=> 'features',
			'fields'	=> [
			'max_delivery_time',
			'desired_delivery_time'
			]
		],
		[
			'section'	=> /*T[*/'Negotiation'/*]*/,
			'slug'		=> 'negotiation',
			'fields'	=> [
			'aggressiveness',
			'ask_for_features',
			'ask_for_warranty',
			'score_weight',
			'sweetener'
			]
		],
		];

	private $scenario;

	public function get ($key = null, $opts = null) {
		if (is_string ($key)) {
			$slug = self::slug ($key);
			switch ($key) {
				case 'round_name':
					return self::$S[$this->data['state']];
					break;
				case 'data':
					$data = unserialize ($this->data['state_data']);
					if (empty ($data)) return [];
					if (is_string ($opts) && isset ($data[$opts]))
						return $data[$opts];
					if ($opts == 'current')
						return $data[$this->data['state']];
					return $data;
					break;
				case 'market':
					$market = unserialize ($this->data['market']);
					if (empty ($market)) return [];
					return $market;
					break;
				}
			}
		return parent::get ($key, $opts);
		}

	public function set ($key = null, $value = null) {
		if (is_string ($key)) {
			$slug = self::slug ($key);
			switch ($key) {
				case 'data':
					$key = 'state_data';
					$value = serialize ($value);
					break;
				case 'market':
					$key = 'market';
					$value = serialize ($value);
					break;
				}
			}
		if (is_array ($key) && in_array ('data', $key)) {
			$key['state_data'] = serialize ($key['data']);
			unset ($key['data']);
			}
		if (is_array ($key) && in_array ('market', $key)) {
			$key['market'] = serialize ($key['market']);
			}
		return parent::set ($key, $value);
		}

	public function turn ($forward = TRUE) {
		if ($this->data['state'] == self::CANCELED_GAME)
			return FALSE;

		$prev = null;
		$next = null;
		reset (self::$SO);
		while (($state = current (self::$SO)) !== FALSE) {
			if ($state == $this->data['state']) {
				$prev = prev (self::$SO);
				if ($prev === FALSE) {
					$prev = null;
					reset (self::$SO);
					$next = next (self::$SO);
					}
				else {
					next (self::$SO);
					$next = next (self::$SO);
					if ($next === FALSE)
						$next = null;
					}
				break;
				}
			next (self::$SO);
			}
		reset (self::$SO);
		
		if ($forward) {
			if (is_null ($next)) return FALSE;
			$this->set ('state', $next);

			if ($next == self::ROUND4_END)
				$this->buy ();

			return TRUE;
			}
		else {
			if (is_null ($prev)) return FALSE;
			$this->set ('state', $prev);

			if ($prev == self::ROUND4_END)
				$this->buy ();

			return TRUE;
			}
		}

	public function buy () {
		if (!in_array ($this->data['state'], [self::ROUND4_END, self::GAME_REPORT]))
			return FALSE;

		$market = $this->scenario ('buying_mode') == 0 ? $this->compute ('buying_learning') : ($this->scenario ('buying_mode') == 1 ? $this->compute ('buying_competitive') : []);
		$this->set ('market', $market);
		return TRUE;
		}

	public function scenario ($key = null, $opts = null) {
		if (!($this->scenario instanceof SD_Scenario))
			$this->scenario = new SD_Scenario ($this->data['scenario']);
		return $this->scenario->get ($key, $opts);
		}

	public function compute ($key, $player = null) {
		$_quotations = [];
		$players = new SD_List ('SD_Player', [ sprintf ('game=%d', $this->ID) ]);
		$products = new SD_List ('SD_Product', $this->scenario ('path'));


		if (is_string ($key)) {
			switch ($key) {
				case 'buying_learning':
					if ($players->is ('empty')) return FALSE;
					$perceived_value_sum = [];

					foreach ($players->get () as $player) {
						$perceived_value = $player->compute ('perceived_value');
						
						foreach ($perceived_value as $product_slug => $product_value) {
							if (!isset ($perceived_value_sum[$product_slug]))
								$perceived_value_sum[$product_slug] = [];
							foreach ($product_value as $quality_slug => $quality_value) {
								if (!isset ($perceived_value_sum[$product_slug][$quality_slug]))
									$perceived_value_sum[$product_slug][$quality_slug] = $quality_value;
								else
									$perceived_value_sum[$product_slug][$quality_slug] += $quality_value;
								}
							}
						}

					$basket = [];

					foreach ($players->get () as $player) {
						$perceived_quantity = $player->compute ('perceived_quantities');

						if (!isset ($basket[$player->get ()]))
							$basket[$player->get ()] = [];

						foreach ($perceived_quantity as $product_slug => $product_quantity) {
							if (!isset ($basket[$player->get ()][$product_slug]))
								$basket[$player->get ()][$product_slug] = [];
							foreach ($product_quantity as $quality_slug => $quality_quantity) {
								if (!isset ($basket[$player->get ()][$product_slug][$quality_slug]))
									$basket[$player->get ()][$product_slug][$quality_slug] = min ([
										$quality_quantity[0],
										$quality_quantity[2] / $perceived_value_sum[$product_slug][$quality_slug]
										]);
								}
							}
						}
	
					return $basket;
					break;
				case 'buying_competitive':
					if ($players->is ('empty')) return FALSE;
					$perceived_value_array = [];
					$remaining_quantity = [];
					$offered_quantity = [];

					foreach ($products->get () as $product) {
						$qualities = $product->get ('quality');
						foreach ($qualities as $quality_slug => $quality_data)
							$remaining_quantity[$product->get ()][$quality_slug] = $quality_data['desired_quantity'];
						}

					foreach ($players->get () as $player) {
						$perceived_value = $player->compute ('perceived_value');
						
						foreach ($perceived_value as $product_slug => $product_value) {
							if (!isset ($perceived_value_array[$product_slug]))
								$perceived_value_array[$product_slug] = [];
							foreach ($product_value as $quality_slug => $quality_value) {
								if (!isset ($perceived_value_array[$product_slug][$quality_slug]))
									$perceived_value_array[$product_slug][$quality_slug] = [];
								$perceived_value_array[$product_slug][$quality_slug][$player->get ()] = $quality_value;
								}
							}

						$perceived_quantity = $player->compute ('perceived_quantity');
						foreach ($perceived_quantity as $product_slug => $product_quantity) {
							foreach ($product_quantity as $quality_slug => $quality_quantity) {
								$offered_quantity[$product_slug][$quality_slug][$player->get ()] = $quality_quantity;
								}
							}
						}

					$basket = [];
					foreach ($perceived_value_array as $product_slug => $product_array) {
						foreach ($product_array as $quality_slug => $quality_array) {
							asort ($quality_array);
							$sorted_array = array_reverse ($quality_array);

							foreach ($sorted_array as $player_id => $player_pv) {
								if ($remaining_quantity[$product_slug][$quality_slug] > 0) {
									$basket[$player_id][$product_slug][$quality_slug] = min ([
										$offered_quantity[$product_slug][$quality_slug][$player_id],
										$remaining_quantity[$product_slug][$quality_slug]
										]);
									$remaining_quantity[$product_slug][$quality_slug] -= $basket[$player_id][$product_slug][$quality_slug];
									}
								else
									$basket[$player_id][$product_slug][$quality_slug] = 0;
								}
							}
						}

					return $basket;
					break;
				}
			}
		}
	}
?>
