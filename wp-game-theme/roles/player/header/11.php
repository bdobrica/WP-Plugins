<?php
if (isset ($_POST['email_report'])) {
	$error = null;

	$emails = $sd_user->get ('emails_array');

	$report = $sd_user->report (FALSE, FALSE);

	foreach ($emails as $email) {
		try {
			SD_Theme::m ('Amalia Fitil', $email, SD_Theme::__ (/*T[*/'SalesDrive Game Report'/*]*/), 
			SD_Theme::__ (/*T[*/'SalesDrive Game Report'/*]*/), [ $report['path'] . '/' . $report['file'] => $report['file'] ]);
			}
		catch (SD_Exception $e) {
			$error = [ 'email_configuration' => 1 ];
			}
		}

	SD_Theme::prg ($error, TRUE);
	}
?>
