	<form action="" method="post">
<?php	$_products = new SD_List ('SD_Product');
if (!$_products->is ('empty')) : ?>
		<h6><?php SD_Theme::_e (/*T[*/'Products'/*]*/); ?></h6>
		<hr />
<?php	foreach ($_products->get () as $_product) :
		$_qualities = $_product->get ('quality');
		foreach ($_qualities as $_quality_slug => $_quality_data) : ?>
		<h6><?php $_product->out ('name'); ?> <?php echo $_quality_data['name']; ?></h6>
<?php			foreach (SD_Product::$QA as $_slug => $_label) :
				if (in_array ($_slug, SD_Product::$NQA)) continue; ?>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e ($_label); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_product->get () . '_' . $_quality_slug . '_' . $_slug, $_quality_data[$_slug], 'number'); ?>
			</div>
		</div>
<?php			endforeach; ?>
<?php		endforeach; ?>
<?php	endforeach; ?>
<?php endif; ?>
<?php	$_warranties = new SD_List ('SD_Warranty');
if (!$_warranties->is ('empty')) : ?>
		<h6><?php SD_Theme::_e (/*T[*/'Warranty'/*]*/); ?></h6>
		<hr />
<?php	foreach ($_warranties->get () as $_warranty) : ?>
		<h6><?php $_warranty->out ('name'); ?></h6>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_warranty->get () . '_mandatory', $_warranty->get ('mandatory'), 'switch'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_warranty->get () . '_negotiable', $_warranty->get ('negotiable'), 'switch'); ?>
			</div>
		</div>
<?php	endforeach; ?>
<?php endif; ?>
<?php	$_features = new SD_List ('SD_Feature');
if (!$_features->is ('empty')) : ?>
		<h6><?php SD_Theme::_e (/*T[*/'Features'/*]*/); ?></h6>
		<hr />
<?php	foreach ($_features->get () as $_feature) : ?>
		<h6><?php $_feature->out ('name'); ?></h6>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_feature->get() . '_mandatory', $_feature->get ('mandatory'), 'switch'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_feature->get () . '_negotiable', $_feature->get ('negotiable'), 'switch'); ?>
			</div>
		</div>
<?php	endforeach; ?>
<?php endif; ?>
<?php	$_locations = new SD_List ('SD_Location');
if (!$_locations->is ('empty')) : ?>
		<h6><?php SD_Theme::_e (/*T[*/'Locations'/*]*/); ?></h6>
		<hr />
<?php	foreach ($_locations->get () as $_location) : ?>
		<h6><?php $_location->out ('name'); ?></h6>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Max delivery time accepted'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_location->get () . '_max_delivery_time', $_location->get ('max_delivery_time'), 'number'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Desired delivery time'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ($_location->get () . '_desired_delivery_time', $_location->get ('desired_delivery_time'), 'number'); ?>
			</div>
		</div>
<?php	endforeach; ?>
<?php endif; ?>
		<h6><?php SD_Theme::_e (/*T[*/'Client'/*]*/); ?></h6>
		<hr />
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Buying mode'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('buying_mode', $sd_theme->get ('scenario', 'buying_mode'), 'select', [
					SD_Theme::__ (/*T[*/'Learning'/*]*/),
					SD_Theme::__ (/*T[*/'Competitive'/*]*/),
					]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Price Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('price_weight', $sd_theme->get ('scenario', 'price_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Adv. Budg. Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('adv_budg_weight', $sd_theme->get ('scenario', 'adv_budg_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Paym. Term Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('paym_term_weight', $sd_theme->get ('scenario', 'paym_term_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Delivery Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('delivery_weight', $sd_theme->get ('scenario', 'delivery_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Features Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('features_weight', $sd_theme->get ('scenario', 'features_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Warranty Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('warranty_weight', $sd_theme->get ('scenario', 'warranty_weight'), 'float'); ?>
			</div>
		</div>
		<h6><?php SD_Theme::_e (/*T[*/'Negotiation'/*]*/); ?></h6>
		<hr />
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Aggressiveness'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('aggressiveness', $sd_theme->get ('scenario', 'aggressiveness'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Ask for Features'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('ask_for_features', $sd_theme->get ('scenario', 'ask_for_features'), 'select', [
					'never'		=> /*T[*/'Never'/*]*/,
					'if_any'	=> /*T[*/'If Any'/*]*/,
					'if_most'	=> /*T[*/'If Most'/*]*/,
					'if_all'	=> /*T[*/'If All'/*]*/,
					'always'	=> /*T[*/'Always'/*]*/
					]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Ask for Warranty'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('ask_for_warranty', $sd_theme->get ('scenario', 'ask_for_warranty'), 'select', [
					'never'		=> /*T[*/'Never'/*]*/,
					'if_any'	=> /*T[*/'If Any'/*]*/,
					'if_most'	=> /*T[*/'If Most'/*]*/,
					'if_all'	=> /*T[*/'If All'/*]*/,
					'always'	=> /*T[*/'Always'/*]*/
					]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Score Weight'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('score_weight', $sd_theme->get ('scenario', 'score_weight'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<label><?php SD_Theme::_e (/*T[*/'Sweetener'/*]*/); ?></label>
			</div>
			<div class="col-lg-4">
				<?php SD_Theme::inp ('sweetener', $sd_theme->get ('scenario', 'sweetener'), 'float'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
				<a href="" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
				<button name="negotiation_update" class="btn btn-sm btn-block btn-success"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save'/*]*/); ?></button>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<button name="negotiation_reset" class="btn btn-sm btn-block btn-info"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Load Defaults'/*]*/); ?></button>
			</div>
		</div>
	</form>
