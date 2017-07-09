<?php
$error = null;

if (isset ($_POST['mail_update'])) {
	$smtp = [
		'smtp_email'	=> SD_Theme::r ('smtp_email'),
		'smtp_host'	=> SD_Theme::r ('smtp_host'),
		'smtp_port'	=> SD_Theme::r ('smtp_port'),
		'smtp_security'	=> SD_Theme::r ('smtp_security'),
		'smtp_username'	=> SD_Theme::r ('smtp_username'),
		'smtp_password'	=> SD_Theme::r ('smtp_password')
		];

	update_option (SD_Plugin::PluginSlug . '_smtp', $smtp);

	SD_Theme::prg ($error);
	}
?>
