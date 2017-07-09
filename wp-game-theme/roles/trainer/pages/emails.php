<?php
/*
Name: Round 3 E-Mail
Parent: scenario
Order: 13
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
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Round 3 E-Mail'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Sender Name'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round_3_email_sender', $sd_theme->get ('scenario', 'round_3_email_sender'), 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Subject'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round_3_email_subject', $sd_theme->get ('scenario', 'round_3_email_subject'), 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Content'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('round_3_email_content', $sd_theme->get ('scenario', 'round_3_email_content'), 'richtext'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Attachment'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php $file = unserialize ($sd_theme->get ('scenario', 'round_3_email_attachment')); ?>
				<?php SD_Theme::inp ('round_3_email_attachment', isset ($file['name']) ? $file['name'] : '', 'file'); ?>
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
