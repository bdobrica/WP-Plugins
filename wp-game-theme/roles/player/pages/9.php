<?php
$all_states = $sd_user->get ('state');
$state = isset ($all_states[SD_Game::ROUND4_BEGIN]) ? $all_states[SD_Game::ROUND4_BEGIN] : [];
$score = $sd_user->get ('score');
$quotations = $sd_user->get ('quotations', TRUE);

#echo $all_states[SD_Game::ROUND4_BEGIN]['step'];
#$all_states[SD_Game::ROUND4_BEGIN]['step'] = SD_Game::NEGOTIATION_2;
#$sd_user->set ('state', $all_states);

/*
unset ($all_states[SD_Game::ROUND4_BEGIN]['step']);
$sd_user->set ('state', $all_states);
*/

$products = new SD_List ('SD_Product');
$products->get ();
$qualities = $products->get ('first')->get ('quality');
$features = new SD_List ('SD_Feature');

$locations = new SD_List ('SD_Location');
$locations_select = $locations->is ('empty') ? [] : $locations->get ('select', 'name');

$warranties = new SD_List ('SD_Warranty');
$warranties_select = $warranties->is ('empty') ? [ 'none' => /*T[*/'None'/*]*/ ] :
	array_merge ( [ 'none' => /*T[*/'None'/*]*/ ], $warranties->get ('select', 'name') );
?>

<?php if (isset ($state['in_progress']) && $state['in_progress']) : ?>
<?php	if (isset ($state['show_hints']) && $state['show_hints']) : 
		$hints = $sd_game->scenario ('hints'); ?>
	<div class="row sd-character-chat">
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="sd-character">
				<div class="sd-character-image">
<?php
		$characters = new SD_List ('SD_Character', $sd_game->scenario ('path'));
		if (!$characters->is ('empty'))
			foreach ($characters->get () as $character)
				if ($character->get ('role') == 'secretary')
					$secretary = $character;

		if (!is_null ($secretary)) : ?>
					<img src="<?php $secretary->out ('image', 'neutral'); ?>" alt="" title="" class="img-responsive img-rounded" />
<?php		endif; ?>
				</div>
				<div>
					<h5><?php $secretary->out ('name'); ?></h5>
					<h6><?php $secretary->out ('position'); ?></h6>
					<p><?php $secretary->out ('resume'); ?></p>
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			<div class="sd-message-hints">
<?php		if (!empty ($hints)) : ?>
				<h4><?php SD_Theme::_e (/*T[*/'Hints'/*]*/); ?></h4>
<?php			foreach ($hints as $hint) :
				if ($hint['hint_threshold'] <= $score) : ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="sd-chat right">
							<div class="sd-chat-content">
								<p><?php echo $hint['hint_content']; ?></p>
							</div>
						</div>
					</div>
				</div>
<?php				endif; ?>
<?php			endforeach; ?>
<?php		endif; ?>
				<form action="" method="post">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
							<button class="btn btn-sm btn-block btn-info"><i class="fui-mail"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Send E-Mail'/*]*/); ?></button>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
							<button class="btn btn-sm btn-block btn-success" name="negotiation_begin"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Begin Negotiation'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php	else : ?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-6">
			<div class="sd-quotation-image">
				<img src="<?php echo $sd_game->scenario ('negotiation_image_neutral'); ?>" alt="" title="" class="img-rounded img-responsive sd-neutral sd-backimage" />
				<img src="<?php echo $sd_game->scenario ('negotiation_image_thinking'); ?>" alt="" title="" class="img-rounded img-responsive sd-thinking sd-transparent" />
			</div>
			<div class="sd-quotation sd-counter-offer">
				<h6><?php echo $sd_game->scenario ('company_name'); ?></h6>
				<h5><?php SD_Theme::_e (/*T[*/'Counter offer for '/*]*/); ?><?php $sd_user->out ('name'); ?></h5>
				<p><?php SD_Theme::_e (/*T[*/'We counter your offer as follows'/*]*/); ?>:</p>
<?php	if (!empty ($quotations)) : ?>
				<div class="sd-quotation-slide">
<?php	foreach ($quotations as $quotation_id => $quotation_data) : ?>
					<div class="sd-quotation-item">
						<div class="sd-quotation-read">
							<div class="row">
								<div class="col-lg-12 sd-counter-offer-item">
									<?php echo $sd_user->quotation ($quotation_data, 'counter'); ?>
								</div>
							</div>
						</div>
					</div>
<?php	endforeach; ?>
				</div>
<?php endif; ?>
			</div>
			<div class="sd-quotation">
				<h6><?php SD_Theme::_e (/*T[*/'Margin Management'/*]*/); ?></h6>
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
		<div class="col-lg-6">
			<div class="sd-quotation-image">
				<img src="<?php echo $sd_game->scenario ('negotiation_image_player'); ?>" alt="" title="" class="img-rounded img-responsive sd-neutral sd-backdrop" />
			</div>
			<div class="sd-quotation sd-negotiate-offer">
				<h6><?php $sd_user->out ('name'); ?></h6>
				<h5><?php SD_Theme::_e (/*T[*/'Quotation to '/*]*/); ?><?php echo $sd_game->scenario ('company_name'); ?></h5>
				<p><?php SD_Theme::_e (/*T[*/'We are pleased to respond to your request offering the following'/*]*/); ?>:</p>
<?php if (!empty ($quotations)) :
	foreach ($quotations as $quotation_id => $quotation_data) :
		$quotation = $sd_user->quotation ($quotation_data, 'array'); ?>
				<div class="sd-quotation-item">
					<form action="" method="post" class="hidden">
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
									<input type="checkbox" <?php echo in_array ($feature->get(), $quotation['features']) ? 'checked' : ''; ?> data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="features[]'" value="<?php $feature->out (); ?>" />
								</div>
							</div>
						</div>
<?php		endforeach;
	endif; ?>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Quantity'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><span class="form-control"><?php echo $quotation['quantity']; ?></span></div>
						</div>
<?php	if (!empty ($locations_select)) : ?>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Delivery Location'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><span class="form-control"><?php echo $quotation['location_name']; ?></span></div>
						</div>
<?php	endif; ?>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Delivery Term'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><?php SD_Theme::inp ('delivery_term', $quotation['delivery_term'], 'number', SD_Theme::__(/*T[*/'days'/*]*/)); ?></div>
						</div>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Price'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><?php SD_Theme::inp ('price', $quotation['price'], 'float', $sd_theme->get ('scenario', 'currency')); ?></div>
						</div>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Advertising Budget'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><?php SD_Theme::inp ('advertising_budget', $quotation['advertising'], 'number', $sd_theme->get ('scenario', 'currency')); ?></div>

						</div>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Payment Term'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><?php SD_Theme::inp ('payment_term', $quotation['payment_term'], 'number', SD_Theme::__(/*T[*/'days'/*]*/)); ?></div>
						</div>
						<div class="row">
							<div class="col-lg-6"><label><?php SD_Theme::_e (/*T[*/'Warranty'/*]*/); ?>:</label></div>
							<div class="col-lg-6"><?php SD_Theme::inp ('warranty', $quotation['warranty'], 'select', $warranties_select); ?></div>
						</div>
						<div class="row">
							<div class="col-lg-12"><a href="" class="btn btn-sm btn-block btn-success sd-negotiate-update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></a></div>
						</div>
					</form>
					<div class="sd-quotation-read">
						<div class="row">
							<div class="col-lg-10 sd-quotation-text">
								<?php echo $sd_user->quotation ($quotation_data, 'render'); ?>
							</div>
							<div class="col-lg-2">
								<a href="" class="btn btn-sm btn-block btn-info sd-update"><i class="fui-new"></i></a>
							</div>
						</div>
					</div>
				</div>
<?php	endforeach; ?>
				<div class="row">
					<a href="" class="btn btn-sm btn-block btn-success sd-confirm"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Submit Quotation'/*]*/); ?></a>
				</div>
				<div class="row sd-confirm hidden">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-sm btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<a href="" class="btn btn-sm btn-block btn-success sd-negotiate-submit"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></a>
					</div>
				</div>
<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
		</div>
		<div class="col-lg-6">
		</div>
	</div>
</div>
<?php	endif; ?>
<?php elseif (isset ($state['submitted']) && $state['submitted']) : ?>
<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/goodjob.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-center"><?php $sd_theme->out ('scenario', 'round4_end_message'); ?></div>
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
<?php else : ?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-justify"><?php $sd_theme->out ('scenario', 'round4_begin_message'); ?></div>
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
