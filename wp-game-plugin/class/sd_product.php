<?php
class SD_Product extends SD_Instance {
	public static $version	= '1.0.0';

	public static $human	= 'Product';

	public static $scheme	= [];

	const GET		= 'product';
	const QUALITY_GET	= 'quality';

	public static $T	= 'products';

	protected static $K = [
		'name',
		'quality'
		];

	public static $A = [
		'units_available'		=> /*T[*/'Units Available'/*]*/,
		'units_quoted'			=> /*T[*/'Units Quoted'/*]*/,
		'average_unit_cost'		=> /*T[*/'Average Unit Cost'/*]*/,
		'average_features_cost'		=> /*T[*/'Average Features Cost'/*]*/,
		'average_delivery_cost'		=> /*T[*/'Average Delivery Cost'/*]*/,
		'average_promotion_cost'	=> /*T[*/'Average Promotion Cost'/*]*/,
		'average_financing_cost'	=> /*T[*/'Average Financing Cost'/*]*/,
		'average_warranty_cost'		=> /*T[*/'Average Warranty Cost'/*]*/,
		'quoted_unit_price'		=> /*T[*/'Quoted Unit Price'/*]*/,
		'average_unit_margin'		=> /*T[*/'Average Unit Margin'/*]*/,
		'average_margin'		=> /*T[*/'Average Margin (%)'/*]*/,
		'total_margin'			=> /*T[*/'Total Margin'/*]*/,
		];

	public static $QA = [
		'name'				=> /*T[*/'Product Quality'/*]*/,
		'quantity'			=> /*T[*/'Available Quantity'/*]*/,
		'unit_type'			=> /*T[*/'Unit Type'/*]*/,
		'unit_cost'			=> /*T[*/'Unit Cost'/*]*/,
		'purchased_unit_cost'		=> /*T[*/'Purchased Unit Cost'/*]*/,
		'purchasable_quantity'		=> /*T[*/'Purchaseable Quantity'/*]*/,
		'max_price_accepted'		=> /*T[*/'Max Price Accepted'/*]*/,
		'desired_price'			=> /*T[*/'Desired Price'/*]*/,
		'desired_quantity'		=> /*T[*/'Desired Quantity'/*]*/,
		'min_adv_budget_accepted'	=> /*T[*/'Min Adv. Budget Accepted'/*]*/,
		'desired_adv_budget'		=> /*T[*/'Desired Adv. Budget'/*]*/,
		'min_paym_term_accepted'	=> /*T[*/'Min Paym. Term Accepted'/*]*/,
		'desired_payment_term'		=> /*T[*/'Desired Payment Term'/*]*/
		];

	public static $NQA = [
		'name',
		'quantity',
		'unit_type',
		'unit_cost',
		'purchased_unit_cost',
		'purchasable_quantity'
		];

	public function get ($key = null, $opts = null) {
		global $sd_game;

		if (is_string ($key)) {
			$slug = self::slug ($key);
			if ($slug == 'quality') {
				$variables = (isset ($sd_game) && $sd_game instanceof SD_Game) ? $sd_game->get ('variables') : [];
				if (!is_null ($opts) && in_array ($opts, array_keys ($this->data['quality']))) {
					$out = $this->data['quality'][$opts];
					if (!empty ($variables) && !empty ($out)) {
						foreach ($out as $key => $value) {
							if (isset ($variables[$this->ID . '_' . $opts . '_' . $key]))
								$out[$key] = $variables[$this->ID . '_' . $opts . '_' . $key];
							}
						}
					}
				else {
					$out = $this->data['quality'];
					if (!empty ($variables) && !empty ($out)) {
						foreach ($out as $quality_slug => $quality_data) {
							if (!empty ($quality_data))
								foreach ($quality_data as $key => $value) {
									if (isset ($variables[$this->ID . '_' . $quality_slug . '_' . $key]))
										$out[$quality_slug][$key] = $variables[$this->ID . '_' . $quality_slug . '_' . $key];
									}
							}
						}
					}
				return $out;
				}
			}
		return parent::get ($key, $opts);
		}

	public function compute ($key) {
		
		}
	}
?>
