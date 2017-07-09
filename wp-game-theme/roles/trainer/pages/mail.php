<?php
/*
Name: SMTP Settings
Parent: general
Order: 0
Admin: true
*/
?>
<?php $smtp = get_option (SD_Plugin::PluginSlug . '_smtp', [
	'smtp_email'	=> '',
	'smtp_host'	=> '',
	'smtp_port'	=> '',
	'smtp_security'	=> 'none',
	'smtp_username'	=> '',
	'smtp_password'	=> ''
	]); ?>
<div class="sd-rounded sd-translucent sd-padded">
<?php SD_Theme::_i (__FILE__, __LINE__); ?>
	<div class="row">
		<div class="col-lg-6">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'SMTP Settings'/*]*/); ?></legend>
				<form action="" method="post">
					<label><?php SD_Theme::_e (/*T[*/'SMTP E-Mail Address'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_email', $smtp['smtp_email'], 'string'); ?>
					<label><?php SD_Theme::_e (/*T[*/'SMTP Host'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_host', $smtp['smtp_host'], 'string'); ?>
					<label><?php SD_Theme::_e (/*T[*/'SMTP Port'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_port', $smtp['smtp_port'], 'string'); ?>
					<label><?php SD_Theme::_e (/*T[*/'SMTP Security'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_security', $smtp['smtp_security'], 'select', [
						'none'	=> 'None',
						'ssl'	=> 'SSL',
						'tls'	=> 'TLS'
						]); ?>
					<label><?php SD_Theme::_e (/*T[*/'SMTP Username'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_username', $smtp['smtp_username'], 'string'); ?>
					<label><?php SD_Theme::_e (/*T[*/'SMTP Password'/*]*/); ?></label>
					<?php SD_Theme::inp ('smtp_password', $smtp['smtp_password'], 'string'); ?>

					<br />

					<div class="row">
						<div class="col-lg-6 btn-left">
							<a class="btn btn-sm btn-block btn-danger" href="<?php $sd_theme->out ('url'); ?>"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 btn-right">
							<button class="btn btn-sm btn-block btn-success" name="mail_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</fieldset>
		</div>
	</div>
</div>
