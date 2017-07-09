<?php
/*
Name: Negotiation
Parent: scenario
Order: 8
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif; ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Negotiation Parameters'/*]*/); ?></legend>
			<form action="" method="post" enctype="multipart/form-data">
<?php if (!empty (SD_Scenario::$S)) : ?>
				<p><?php SD_Theme::_e (/*T[*/'Negotiation Images'/*]*/); ?>:</p>
<?php	foreach (SD_Scenario::$S as $image_slug => $image_name) : ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e ($image_name); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<?php SD_Theme::inp ('negotiation_image_' . $image_slug, $sd_theme->get ('scenario', 'negotiation_image_' . $image_slug), 'file'); ?>
					</div>
				</div>
<?php	endforeach;
endif; ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Aggressiveness'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<?php SD_Theme::inp ('aggressiveness', $sd_theme->get ('scenario', 'aggressiveness'), 'float'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Ask for Features'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Ask for Warranty'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Score Weight'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<?php SD_Theme::inp ('score_weight', $sd_theme->get ('scenario', 'score_weight'), 'float'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Sweetener'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<?php SD_Theme::inp ('sweetener', $sd_theme->get ('scenario', 'sweetener'), 'float'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Negotiation Hints'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
				</div>
				<div class="sd-hints">
					<input type="hidden" name="hint_delete_id" value="-1" />
<?php $hints = $sd_theme->get ('scenario', 'hints');
if (!empty ($hints)) :
	foreach ($hints as $hint_id => $hint_data) : ?>
					<div class="row hidden">
						<div class="col-lg-12">
							<label><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete this hint?'/*]*/); ?></label>
								<br />
							<?php echo $hint_data['hint_content']; ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
							<a href="" class="btn btn-success btn-block btn-sm sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
							<button href="" class="btn btn-danger btn-block btn-sm" name="hint_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php SD_Theme::inp ('hint_content[]', $hint_data['hint_content'], 'textarea');  ?>
						</div>
						<div class="col-lg-8">
							<label><?php SD_Theme::_e (/*T[*/'Threshold'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-3">
							<?php SD_Theme::inp ('hint_threshold[]', $hint_data['hint_threshold'], 'number', 'pt.'); ?>
						</div>
						<div class="col-lg-1">
							<a href="" class="btn btn-sm btn-danger btn-block sd-delete" data-hint-id="<?php echo $hint_id; ?>"><i class="fui-trash"></i></a>
						</div>
					</div>
<?php	endforeach;
endif; ?>
					<div class="row hidden">
						<div class="col-lg-12">
							<?php SD_Theme::inp ('hint_content[]', '', 'textarea');  ?>
						</div>
						<div class="col-lg-3">
							<label><?php SD_Theme::_e (/*T[*/'Threshold'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-3">
							<?php SD_Theme::inp ('hint_threshold[]', '', 'number', 'pt.'); ?>
						</div>
						<div class="col-lg-3">
							<a href="" class="btn btn-danger btn-block btn-sm sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
						</div>
						<div class="col-lg-3">
							<button href="" class="btn btn-success btn-block btn-sm" name="hint_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Hint'/*]*/); ?></button>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<a href="" class="btn btn-success btn-block btn-sm sd-create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Hint'/*]*/); ?></a>
						</div>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="scenario_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></a>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
