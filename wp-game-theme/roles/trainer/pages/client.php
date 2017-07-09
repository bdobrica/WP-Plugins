<?php
/*
Name: Client
Order: 0
Parent: scenario
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif;
?>
<div class="sd-rounded sd-translucent sd-padded">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<?php SD_Theme::_i (__FILE__, __LINE__); ?>
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Client Details'/*]*/); ?></legend>
				<form action="" method="post">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Buying Company'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('company_name', $sd_theme->get ('scenario', 'company_name'), 'string'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label><?php SD_Theme::_e (/*T[*/'Company Description'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php SD_Theme::inp ('company_description', $sd_theme->get ('scenario', 'company_description'), 'richtext'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Buying Mode'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('buying_mode', $sd_theme->get ('scenario', 'buying_mode'), 'select', [
								SD_Theme::__ (/*T[*/'Learning'/*]*/),
								SD_Theme::__ (/*T[*/'Competitive'/*]*/),
								]); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Price Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('price_weight', $sd_theme->get ('scenario', 'price_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Adv. Budg. Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('adv_budg_weight', $sd_theme->get ('scenario', 'adv_budg_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Paym. Term. Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('paym_term_weight', $sd_theme->get ('scenario', 'paym_term_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Delivery Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('delivery_weight', $sd_theme->get ('scenario', 'delivery_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Features Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('features_weight', $sd_theme->get ('scenario', 'features_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label><?php SD_Theme::_e (/*T[*/'Warranty Weight'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<?php SD_Theme::inp ('warranty_weight', $sd_theme->get ('scenario', 'warranty_weight'), 'float'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
							<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-danger btn-block btn-sm"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
							<button class="btn btn-success btn-block btn-sm" name="scenario_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</fieldset>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
		</div>
	</div>
</div>
