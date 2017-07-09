<?php
/*
Name: Texts and Messages
Parent: scenario
Order: 12
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif; ?>
<div class="row">
	<div class="col-lg-6">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Texts &amp; Messages'/*]*/); ?></legend>
				<?php /* <label><?php SD_Theme::_e (/T[/'Round 0 Begin Message'/]/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round0_begin_message', $sd_theme->get ('scenario', 'round0_begin_message'), 'richtext'); ?> */?>
				<label><?php SD_Theme::_e (/*T[*/'Game Begin Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round0_end_message', $sd_theme->get ('scenario', 'round0_end_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 1 Begin Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round1_begin_message', $sd_theme->get ('scenario', 'round1_begin_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 1 End Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round1_end_message', $sd_theme->get ('scenario', 'round1_end_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 2 Begin Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round2_begin_message', $sd_theme->get ('scenario', 'round2_begin_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 2 End Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round2_end_message', $sd_theme->get ('scenario', 'round2_end_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 3 Begin Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round3_begin_message', $sd_theme->get ('scenario', 'round3_begin_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 3 End Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round3_end_message', $sd_theme->get ('scenario', 'round3_end_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 4 Begin Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round4_begin_message', $sd_theme->get ('scenario', 'round4_begin_message'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Round 4 End Message'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round4_end_message', $sd_theme->get ('scenario', 'round4_end_message'), 'richtext'); ?>
				<div class="row">
					<div class="col-lg-6 btn-left">
						<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 btn-right">
						<button name="scenario_update" class="btn btn-sm btn-block btn-success"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="col-lg-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
