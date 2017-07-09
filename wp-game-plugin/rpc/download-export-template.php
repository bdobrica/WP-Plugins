<?php
define ('SD_AJAX_PROTECTION', TRUE);
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');

if (file_exists (dirname (__DIR__) . '/assets/xlsx/export-state-all-override.xlsx'))
	$file = dirname (__DIR__) . '/assets/xlsx/export-state-all-override.xlsx';
else
	$file = dirname (__DIR__) . '/assets/xlsx/export-state-all.xlsx';

header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header ('Content-Disposition: attachment; filename="export-state-all.xlsx"');

readfile ($file);
?>
