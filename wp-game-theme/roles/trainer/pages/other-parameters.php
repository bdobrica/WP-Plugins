<?php
/*
Name: Other Parameters
Parent: scenario
Order: 11
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
			<legend><?php SD_Theme::_e (/*T[*/'Additional Parameters'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Financing Cost (% / day)'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('financing_cost', $sd_theme->get ('scenario', 'financing_cost'), 'percent', '%'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Currency'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('currency', $sd_theme->get ('scenario', 'currency'), 'string'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Allow Sending E-Mails'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('allow_sending_emails', $sd_theme->get ('scenario', 'allow_sending_emails'), 'switch'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Reusable Questions'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<?php SD_Theme::inp ('reusable_questions', $sd_theme->get ('scenario', 'reusable_questions'), 'select', [0 => 'No', 1 => 'Yes, all the way up', 2 => 'Yes, neighbors only'] ); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Purchase Always On'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('enable_purchase', $sd_theme->get ('scenario', 'enable_purchase'), 'switch'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Round 3 Min Score'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('round_3_min_score', $sd_theme->get ('scenario', 'round_3_min_score'), 'number'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Round 3 Max Score'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('round_3_max_score', $sd_theme->get ('scenario', 'round_3_max_score'), 'number'); ?>
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
