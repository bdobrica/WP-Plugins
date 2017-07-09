<?php
$all_states = $sd_user->get ('state');
$state = $all_states[SD_Game::ROUND2_BEGIN];
$quotations = isset ($state['data']['quotations']) && is_array ($state['data']['quotations']) ? $state['data']['quotations'] : [];
$acquired = isset ($state['data']['acquired']) && is_array ($state['data']['acquired']) ? $state['data']['acquired'] : [];

$products = new SD_List ('SD_Product');
$products->get ();
$qualities = $products->get ('first')->get ('quality');
$features = new SD_List ('SD_Feature');

$locations = new SD_List ('SD_Location');
$locations_select = $locations->is ('empty') ? [] : $locations->get ('select', 'name');

$warranties = new SD_List ('SD_Warranty');
$warranties_select = $warranties->is ('empty') ? [ 'none' => /*T[*/'None'/*]*/ ] :
	array_merge ( [ 'none' => /*T[*/'None'/*]*/ ], $warranties->get ('select', 'name') );

if (isset ($state['submitted']) && $state['submitted']) : ?>

<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/goodjob.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-center"><?php $sd_theme->out ('scenario', 'round2_end_message'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
		</div>
	</div>
</div>
<?php elseif (isset ($state['in_progress']) && $state['in_progress']) : ?>
<div class="sd-rounded sd-padded sd-translucent">
<div class="row">
	<div class="col-lg-6">
		<div class="sd-quotation">
			<h6><?php $sd_user->out ('name'); ?></h6>
			<h5><?php SD_Theme::_e (/*T[*/'Quotation to '/*]*/); ?><?php $sd_theme->out ('scenario', 'company_name'); ?></h5>
			<p><?php SD_Theme::_e (/*T[*/'We are pleased to respond to your request offering the following'/*]*/); ?>:</p>
<?php	$can_submit = TRUE;
	if (!empty ($quotations)) :
	foreach ($quotations as $quotation_id => $quotation_data) :
		$quotation_is_valid = isset ($quotation_data['location']) && (isset ($quotation_data['quantity']) && ($quotation_data['quantity'] > 0));
		if (!$quotation_is_valid) $can_submit = FALSE;

		$quotation = $sd_user->quotation ($quotation_data, 'array'); ?>
			<div class="sd-quotation-item">
				<form action="" method="post" class="<?php echo $quotation_is_valid ? 'hidden' : ''; ?>">
					<input type="hidden" name="quotation" value="<?php echo $quotation_id; ?>" />
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Product'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><span class="form-control"><?php echo $quotation['product_name']; ?></span></div>
					</div>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Quality'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><span class="form-control"><?php echo $quotation['quality_name']; ?></span></div>
					</div>
<?php	if (!$features->is ('empty')) :
		foreach ($features->get () as $feature) : ?>
					<div class="row">
						<div class="col-lg-6"><label><?php $feature->out ('name'); ?>:</label></div>
						<div class="col-lg-6">
							<div class="bootstrap-switch-square pull-right">
								<input type="checkbox" <?php echo isset($quotation['features']) && in_array ($feature->get(), $quotation['features']) ? 'checked' : ''; ?> data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="features[]'" value="<?php $feature->out (); ?>" />
							</div>
						</div>
					</div>
<?php		endforeach;
	endif; ?>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Quantity'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><input type="text" class="form-control sd-number" name="quantity" value="<?php echo $quotation['quantity']; ?>" data-filter="limit" data-max="<?php echo $units_available[$quotation['quality']] + $quotation['quantity']; ?>" /></div>
					</div>
<?php	if (!empty ($locations_select)) : ?>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Delivery Location'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('location', isset ($quotation['location']) ? $quotation['location'] : '', 'select', $locations_select); ?></div>
					</div>
<?php	endif; ?>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Delivery Term'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('delivery_term', isset ($quotation['delivery_term']) ? $quotation['delivery_term'] : 0, 'number', SD_Theme::__(/*T[*/'days'/*]*/)); ?></div>
					</div>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Price'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('price', isset ($quotation['price']) ? $quotation['price'] : 0, 'float', $sd_theme->get ('scenario', 'currency')); ?></div>
					</div>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Advertising Budget'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('advertising_budget', isset ($quotation['advertising']) ? $quotation['advertising'] : 0, 'number', $sd_theme->get ('scenario', 'currency')); ?></div>

					</div>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Payment Term'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('payment_term', isset ($quotation['payment_term']) ? $quotation['payment_term'] : 0, 'number', SD_Theme::__(/*T[*/'days'/*]*/)); ?></div>
					</div>
					<div class="row">
						<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Warranty'/*]*/); ?>:</label></div>
						<div class="col-lg-6"><?php SD_Theme::inp ('warranty', isset ($quotation['warranty']) ? $quotation['warranty'] : 'none', 'select', $warranties_select); ?></div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left"><a href="" class="btn btn-sm btn-block btn-danger sd-cancel <?php echo $quotation_is_valid ? '' : 'hidden'; ?>" name="quotation_delete"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a></div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right"><button class="btn btn-sm btn-block btn-success" name="quotation_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button></div>
					</div>
				</form>
				<div class="sd-quotation-read <?php echo $quotation_is_valid ? '' : 'hidden'; ?>">
					<div class="row">
						<div class="col-lg-10">
							<?php echo $sd_user->quotation ($quotation_data, 'render'); ?>
						</div>
						<div class="col-lg-2">
							<a href="" class="btn btn-sm btn-block btn-info sd-update"><i class="fui-new"></i></a>
							<a class="btn btn-sm btn-block btn-danger sd-delete" name="message_create"><i class="fui-cross"></i></a>
						</div>
					</div>
					<form action="" method="post" class="sd-quotation-delete hidden">
						<input type="hidden" name="quotation" value="<?php echo $quotation_id; ?>" />
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
								<a href="" class="btn btn-sm btn-block btn-success sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
								<button class="btn btn-sm btn-block btn-danger" name="quotation_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="row">
				</div>
			</div>
<?php	endforeach; ?>
			<form action="" method="post">
				<div class="row">
					<button class="btn btn-sm btn-block btn-success sd-confirm" name="quotation_submit" <?php echo $can_submit ? '' : 'disabled'; ?>><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Submit Quotation'/*]*/); ?></button>
				</div>
				<div class="row hidden sd-confirm">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-sm btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="quotation_submit"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
					</div>
				</div>
			</form>
<?php endif; ?>
		</div>
	</div>
	<div class="col-lg-6">
		<form action="" method="post" class="sd-quotation-create">
				<div class="row">
					<div class="col-lg-9"><label class="label-grey label-block"><?php SD_Theme::_e (/*T[*/'Add Product'/*]*/); ?></label></div>
					<div class="col-lg-3"><button class="btn btn-sm btn-block btn-success" name="quotation_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add'/*]*/); ?></button></div>
				</div>
				<div class="row">
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Product'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<select name="product" class="form-control select select-block select-info sd-select-product" data-toggle="select">
<?php	foreach ($products->get ('select', 'name') as $product_slug => $product_name) : ?>
							<option value="<?php echo $product_slug; ?>"><?php echo $product_name; ?></option>
<?php	endforeach; ?>
						</select>
					</div>
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Quality'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<select name="quality" class="form-control select select-block select-info sd-select-quality" data-toggle="select">
<?php	foreach ($qualities as $quality_slug => $quality_data) : ?>
							<option value="<?php echo $quality_slug; ?>" data-quantity="<?php echo $quality_data['quantity']; ?>" data-purchasable-quantity="<?php echo $quality_data['purchasable_quantity']; ?>" data-purchased-unit-cost="<?php echo $quality_data['purchased_unit_cost']; ?>" data-unit-type="<?php echo $quality_data['unit_type']; ?>"><?php echo $quality_data['name']; ?></option>
<?php	endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row">
<?php
	$default_quality = current ($qualities);
	$default_quality_slug = current (array_keys ($qualities));
	$units_available = $sd_user->compute ($products->get ('first'), 'units_available');
	$purchasable_quantity = $sd_user->compute ($products->get ('first'), 'purchasable_quantity');
	$default_quality['quantity'] = isset ($units_available[$default_quality_slug]) ? $units_available[$default_quality_slug] : 0;
	$default_quality['purchasable_quantity'] = isset ($purchasable_quantity[$default_quality_slug]) ? $purchasable_quantity[$default_quality_slug] : 0;
?>
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Quantity'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<div class="input-group">
							<input class="form-control sd-number" type="text" name="quantity" value="0" data-filter="constant" data-link="remaining" data-max="<?php echo $default_quality['quantity']; ?>"/>
							<div class="input-group-btn">
								<span class="btn"><?php echo $default_quality['unit_type']; ?></span>
							</div>
						</div>
					</div>
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Remaining'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<div class="input-group">
							<span class="form-control sd-number" data-name="remaining"><?php echo $default_quality['quantity']; ?></span>
							<div class="input-group-btn">
								<span class="btn"><?php echo $default_quality['unit_type']; ?></span>
							</div>
						</div>
					</div>
				</div>
		</form>
<?php	$allow_buying = 0;
	foreach ($products->get () as $product) :
		$product_qualities = $product->get ('quality');
		foreach ($product_qualities as $product_quality)
			$allow_buying += $product_quality['purchasable_quantity'];
	endforeach;

	$enable_purchase = $sd_user->get ('enable_purchase');

	if (($allow_buying) > 0 && $enable_purchase) : ?>
		<form action="" method="post" class="sd-inventory-update">
				<div class="row">
					<div class="col-lg-9"><label class="label-grey label-block"><?php SD_Theme::_e (/*T[*/'Buy Product'/*]*/); ?></label></div>
					<div class="col-lg-3"><button class="btn btn-sm btn-block btn-success" name="product_acquire"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Buy'/*]*/); ?></button></div>
				</div>
				<div class="row">
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Product'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<select name="product" class="form-control select select-block select-info sd-select-product" data-toggle="select">
<?php		foreach ($products->get ('select', 'name') as $product_slug => $product_name) : ?>
							<option value="<?php echo $product_slug; ?>"><?php echo $product_name; ?></option>
<?php		endforeach; ?>
						</select>
					</div>
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Quality'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<select name="quality" class="form-control select select-block select-info sd-select-quality" data-toggle="select">
<?php		foreach ($qualities as $quality_slug => $quality_data) : ?>
							<option value="<?php echo $quality_slug; ?>" data-quantity="<?php echo $quality_data['quantity']; ?>" data-purchasable-quantity="<?php echo $quality_data['purchasable_quantity']; ?>" data-purchased-unit-cost="<?php echo $quality_data['purchased_unit_cost']; ?>" data-unit-type="<?php echo $quality_data['unit_type']; ?>"><?php echo $quality_data['name']; ?></option>
<?php		endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Quantity'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<div class="input-group">
							<input class="form-control sd-number" type="text" name="acquire" value="0" data-filter="limit" data-max="<?php echo $default_quality['purchasable_quantity']; ?>"/>
							<div class="input-group-btn">
								<span class="btn"><?php echo $default_quality['unit_type']; ?></span>
							</div>
						</div>
					</div>
					<div class="col-lg-2"><label><?php SD_Theme::_e (/*T[*/'Price'/*]*/); ?>:</label></div>
					<div class="col-lg-4">
						<div class="input-group">
							<span class="form-control sd-number" data-name="price"><?php echo $default_quality['purchased_unit_cost']; ?></span>
							<div class="input-group-btn">
								<span class="btn"><?php $sd_theme->out ('scenario', 'currency'); ?></span>
							</div>
						</div>
					</div>
				</div>
		</form>
<?php	endif; ?>
		<div class="sd-inventory">
<?php	if (!$products->is ('empty')) : ?>
			<ul class="nav nav-tabs nav-append-content">
<?php
		$first = TRUE;
		foreach ($products->get () as $product) : ?>
				<li<?php if ($first) echo ' class="active"'; ?>><a href="#<?php $product->out (); ?>"><?php $product->out ('name'); ?></a></li>
<?php			$first = FALSE;
		endforeach; ?>
			</ul>
<?php	endif; ?>
<?php	if (!$products->is ('empty')) : ?>
			<div class="tab-content">
<?php		$first = TRUE;
		foreach ($products->get () as $product) :
			$qualities = $product->get ('quality'); ?>
				<div id="<?php $product->out (); ?>" class="tab-pane<?php if ($first) echo ' active'; ?>">
					<div class="table-responsive sd-table">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Quality</th>
<?php			foreach ($qualities as $quality_slug => $quality_data) : ?>
									<th><?php echo $quality_data['name']; ?></th>
<?php			endforeach; ?>
								</tr>
							</thead>
							<tbody>
<?php			foreach (SD_Product::$A as $key => $name) :
				$computed = $sd_user->compute ($product, $key); ?>
								<tr>
									<th><?php SD_Theme::_e ($name); ?></th>
<?php				foreach ($computed as $quality_slug => $value) : ?>
									<td><?php echo $value; ?></td>
<?php				endforeach;
				unset ($computed); ?>
								</tr>
<?php			endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="table-responsive sd-table-split-4">
						<table class="table table-striped table-hover">
							<thead>
<?php	foreach (SD_Player::$A as $key => $name) : ?>
								<th><?php SD_Theme::_e ($name); ?></th>
<?php	endforeach; ?>
							</thead>
							<tbody>
<?php	foreach (SD_Player::$A as $key => $name) : ?>
								<td><?php echo $sd_user->compute ($key); ?></td>
<?php	endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
<?php		$first = FALSE;
		endforeach; ?>
			</div>
<?php	endif; ?>
		</div>
	</div>
</div>
	<div class="sd-timeout"><img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/timeout.png" alt="" title="" /></div>
</div>
<?php else: ?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-justify"><?php $sd_theme->out ('scenario', 'round2_begin_message'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<form action="" method="post">
				<button class="btn btn-lg btn-block btn-success" name="player_begin"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
			</form>
		</div>
	</div>
</div>
<?php endif; ?>
