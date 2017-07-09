<?php
/*
Name: Products
Parent: scenario
Order: 4
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif;
if (isset ($_GET[SD_Product::GET])) :
	try {
		$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r (SD_Product::GET));
		}
	catch (SD_Exception $e) {
		$product = null;
		}
endif;
if (isset ($_GET['quality'])) :
	$quality = $_GET['quality'];
endif;
if (!isset ($product) || is_null ($product)) :
	$products = new SD_List ('SD_Product');
	if ($products->is ('empty') || ($sd_theme->get ('action') == 'create')) : ?>
	<div class="row">
		<div class="col-lg-6">
			<div class="alert alert-success"><?php SD_Theme::_e (/*T[*/'Use the form bellow to add the first product of this scenario!'/*]*/); ?></div>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 btn-left">
						<label><?php SD_Theme::_e (/*T[*/'Product Name'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-8 col-md-8 col-md-8 col-xs-6 btn-right">
						<input class="form-control input-sm" type="text" name="product_name" value="" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-block btn-success" name="product_create"><?php SD_Theme::_e (/*T[*/'Add Product'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
		</div>
	</div>
<?php	else : ?>
	<div class="row">
		<div class="col-lg-9">
			<?php SD_Theme::_i (__FILE__, __LINE__); ?>
<?php		foreach ($products->get () as $product) : ?>
			<div class="sd-product">
				<div class="row">
					<form action="" method="post">
						<input type="hidden" name="product" value="<?php $product->out (); ?>" />
						<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6 btn-left">
							<div class="input-group input-group-sm">
								<input class="form-control" name="product_name" value="<?php $product->out ('name'); ?>" />
								<div class="input-group-btn">
									<button class="btn btn-sm" name="product_update"><?php SD_Theme::_e (/*T[*/'Update'/*]*/); ?></button>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
							<button class="btn btn-sm btn-block btn-danger" name="product_delete"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 btn-right">
							<a href="<?php echo $product->get ('url', 'update'); ?>" class="btn btn-sm btn-block btn-success sd-quality-create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Quality'/*]*/); ?></a>
						</div>
					</form>
				</div>

				<hr />

				<form action="" method="post">
					<input type="hidden" name="product" value="<?php $product->out (); ?>" />
					<input type="hidden" name="quality" value="" />
<?php			$qualities = $product->get ('quality');
			if (!is_array ($qualities)) $qualities = [];
				$index = 0;
				foreach (SD_Product::$QA as $quality_prop_slug => $quality_prop_name) :
					$index ++; ?>
					<div class="row<?php if (empty ($qualities)) echo ' sd-add-column hidden'; ?>">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label><?php SD_Theme::_e ($quality_prop_name); ?><?php SD_Theme::_h (__FILE__, __LINE__ + $index); ?></label>
						</div>
<?php					if (!empty ($qualities)) :
						foreach ($qualities as $quality_slug => $quality_data) : ?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn-left">
							<input class="form-control input-sm" type="text" name="quality_<?php echo $quality_prop_slug; ?>[]" value="<?php echo $quality_data[$quality_prop_slug]; ?>" />
						</div>
<?php						endforeach;
					endif;  ?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn-left<?php if (!empty ($qualities)) echo ' sd-add-column hidden'; ?>">
							<input class="form-control input-sm" type="text" name="quality_<?php echo $quality_prop_slug; ?>[]" value="" />
						</div>
					</div>
<?php				endforeach; ?>
					<div class="row<?php if (empty ($qualities)) echo ' sd-add-column hidden'; ?>">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						</div>
<?php				reset ($qualities);
				for ($index = 0; $index < sizeof ($qualities); $index++) :
					$quality = each ($qualities);
					if ($quality !== FALSE) : ?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn-left">
							<a href="" class="btn btn-sm btn-block btn-danger sd-quality-delete" data-quality="<?php echo $quality['key']; ?>"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
							<div class="row hidden">
								<div class="col-lg-12">
									<?php SD_Theme::_e ('Are you sure?'); ?>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
									<a href="" class="btn btn-sm btn-block btn-success sd-quality-cancel"><i class="fui-cross"></i></a>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
									<button class="btn btn-sm btn-block btn-danger" name="quality_delete"><i class="fui-check"></i></button>
								</div>
							</div>
						</div>
<?php					else : ?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn-left">
							<a href="" class="btn btn-sm btn-block btn-danger" data-quality="<?php echo $quality['key']; ?>"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
						</div>
<?php					endif; ?>
<?php				endfor; ?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2<?php if (!empty ($qualities)) echo ' sd-add-column hidden'; ?>">
							<button class="btn btn-sm btn-block btn-success" name="quality_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add'/*]*/); ?></button>
						</div>
					</div>
					<div class="row<?php if (empty ($qualities)) echo ' sd-add-column hidden'; ?>">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						</div>
<?php				if (!empty ($qualities)) : ?>
						<div class="col-lg-<?php echo sizeof ($qualities) * 2; ?> btn-left">
							<button class="btn btn-block btn-success" name="quality_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
						</div>
<?php				endif; ?>
						<div class="col-lg-2<?php if (!empty ($qualities)) echo ' sd-add-column hidden'; ?>">
							<button class="btn btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></button>
						</div>
					</div>
				</div>
				<br />
			</form>
<?php		endforeach; ?>
		</div>
		<div class="col-lg-3">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Add New Product'/*]*/); ?></legend>
				<form action="" method="post">
					<div>
						<label><?php SD_Theme::_e (/*T[*/'Product Name'/*]*/); ?></label>
						<input class="form-control" type="text" value="" name="product_name" />
					</div>
					<div class="row">
						<div class="col-lg-12">
							<button class="btn btn-sm btn-block btn-success" name="product_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add New Product'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</fieldset>
		</div>
	</div>
<?php	endif; ?>
<?php else : ?>
	<form action="" method="post">
		<input type="hidden" name="product" value="<?php echo $product->get (); ?>" />
		<div class="input-group">
			<input class="form-control" type="text" value="<?php echo $product->get ('name'); ?>"  name="product_name" />
			<div class="input-group-btn">
				<button class="btn btn-sm btn-block btn-primary" name="product_update"><?php SD_Theme::_e (/*T[*/'Update'/*]*/); ?></button>
			</div>
		</div>
	</form>
	<hr />
<?php
	$qualities = $product->get ('quality');
	if (empty ($qualities) || ($sd_theme->get ('action') == 'create')) : ?>
	<div class="row">
		<div class="col-lg-6">
			<form action="" method="post">
				<input type="hidden" name="product" value="<?php echo $product->get (); ?>" />
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Product Quality'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<input type="text" class="form-control" name="quality_name" value="" />
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Available Quantity'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_quantity" value="" />
							<div class="input-group-btn">
								<span class="btn">units</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/**/'Unit Cost'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_unit_cost" value="" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Purchased Unit Cost'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_purchased_unit_cost" value="" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Max Price Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_max_price_accepted" value="" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Min Adv. Budget Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_min_adv_budget_accepted" value="" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Min Paym. Term Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_min_paym_term_accepted" value="" />
							<div class="input-group-btn">
								<span class="btn">days</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><?php SD_Theme::_e (/*T[*/'Cancel Quality'/*]*/); ?></a>
					</div>
					<div class="col-lg-6">
						<button class="btn btn-sm btn-block btn-primary" name="quality_create"><?php SD_Theme::_e (/*T[*/'Add Product Quality'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php	elseif (isset ($quality)) : 
	$quality_data = $product->get ('quality', $quality); ?>
	<div class="row">
		<div class="col-lg-6">
			<form action="" method="post">
				<input type="hidden" name="product" value="<?php echo $product->get (); ?>" />
				<input type="hidden" name="quality" value="<?php echo $quality; ?>" />
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Product Quality'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<input type="text" class="form-control" name="quality_name" value="<?php echo $quality_data['name']; ?>" />
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Available Quantity'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_quantity" value="<?php echo $quality_data['quantity']; ?>" />
							<div class="input-group-btn">
								<span class="btn">units</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Unit Cost'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_unit_cost" value="<?php echo $quality_data['unit_cost']; ?>" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Purchased Unit Cost'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_purchased_unit_cost" value="<?php echo $quality_data['purchased_unit_cost']; ?>" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Max Price Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_max_price_accepted" value="<?php echo $quality_data['max_price_accepted']; ?>" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Min Adv. Budget Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_min_adv_budget_accepted" value="<?php echo $quality_data['min_adv_budget_accepted']; ?>" />
							<div class="input-group-btn">
								<span class="btn"><?php echo $sd_theme->get ('currency'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Min Paym. Term Accepted'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input type="text" class="form-control" name="quality_min_paym_term_accepted" value="<?php echo $quality_data['min_paym_term_accepted']; ?>" />
							<div class="input-group-btn">
								<span class="btn">days</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><?php SD_Theme::_e (/*T[*/'Cancel Quality'/*]*/); ?></a>
					</div>
					<div class="col-lg-6">
						<button class="btn btn-sm btn-block btn-primary" name="quality_update"><?php SD_Theme::_e (/*T[*/'Update Product Quality'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php	else : ?>
	<div class="row">
<?php		if (!empty ($qualityies)) :
			foreach ($qualities as $quality_slug => $quality_data) : ?>
		<div class="col-lg-3">
			<div class="tile">
				<h3 class="tile-title"><?php echo $quality_data['name']; ?></h3>

				<p><small>
				<?php SD_Theme::_e (/*T[*/'Available Quantity'/*]*/); ?> <?php echo $quality_data['quantity']; ?><br />
				<?php SD_Theme::_e (/*T[*/'Unit Cost'/*]*/); ?> <?php echo $quality_data['unit_cost']; ?><br />
				<?php SD_Theme::_e (/*T[*/'Purchased Unit Cost'/*]*/); ?> <?php echo $quality_data['purchased_unit_cost']; ?></small></p>

				<div class="row">
					<div class="col-lg-6">
						<a href="<?php echo $product->get ('url', 'delete') . '&quality=' . $quality_slug; ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
					</div>
					<div class="col-lg-6">
						<a href="<?php echo $product->get ('url', 'update') . '&quality=' . $quality_slug; ?>" class="btn btn-sm btn-block btn-info"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Edit'/*]*/); ?></a>
					</div>
				</div>
			</div>
		</div>
<?php			endforeach; ?>
<?php		endif; ?>
		<div class="col-lg-3">
			<div class="tile">
				<h3 class="tile-title"><?php SD_Theme::_e (/*T[*/'Add New Quality'/*]*/); ?></h3>

				<p><small>&nbsp;<br />&nbsp;<br />&nbsp;</small></p>

				<div class="row">
					<div class="col-lg-12">
						<a href="<?php echo $product->get ('url', 'create'); ?>" class="btn btn-sm btn-block btn-success"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add New Quality'/*]*/); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php	endif; ?>
<?php endif; ?>


