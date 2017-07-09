<?php
/*
Name: Timing
Parent: scenario
Order: 10
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
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Time Limits'/*]*/); ?></legend>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="row"><div class="col-lg-12"><?php SD_Theme::_e (/*T[*/'First Round'/*]*/); ?>:</div></div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Character Meeting Time Limit'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('conversation_timer', $sd_theme->get ('scenario', 'conversation_timer'), 'number', 'min'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'First Round Time Limit'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('1st_round_timer', $sd_theme->get ('scenario', '1st_round_timer'), 'number', 'min'); ?>
					</div>
				</div>
				<div class="row"><div class="col-lg-12"><?php SD_Theme::_e (/*T[*/'Second Round'/*]*/); ?>:</div></div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Offering Time Limit'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('offer_timer', $sd_theme->get ('scenario', 'offer_timer'), 'number', 'min'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Default Delay'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('default_delay', $sd_theme->get ('scenario', 'default_delay'), 'number', 'sec'); ?>
					</div>
				</div>
				<div class="row"><div class="col-lg-12"><?php SD_Theme::_e (/*T[*/'Third Round'/*]*/); ?>:</div></div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Team Presentation Time Limit'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('presentation_timer', $sd_theme->get ('scenario', 'presentation_timer'), 'number', 'min'); ?>
					</div>
				</div>
				<div class="row"><div class="col-lg-12"><?php SD_Theme::_e (/*T[*/'Fourth Round'/*]*/); ?>:</div></div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Negotiation Answer Delay'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('negotiation_answer_timer', $sd_theme->get ('scenario', 'negotiation_answer_timer'), 'number', 'sec'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Overall Negotiation Time Limit'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('negotiation_timer', $sd_theme->get ('scenario', 'negotiation_timer'), 'number', 'min'); ?>
					</div>
				</div>
				<div class="row"><div class="col-lg-12"><?php SD_Theme::_e (/*T[*/'Timeout Image'/*]*/); ?>:</div></div>
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Timeout Image'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<?php SD_Theme::inp ('timeout_image', $sd_theme->get ('scenario', 'timeout_image'), 'file'); ?>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-lg-6 btn-left">
						<a href="" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="scenario_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></a>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
	<div class="col-lg-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
