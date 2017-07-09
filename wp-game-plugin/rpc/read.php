<?php
if (!defined ('SD_AJAX_PROTECTION')) {
	echo json_encode ((object) [ 'error' => 1 ]);
	exit (1);
	}
if (isset ($_POST['key'])) {
	$key = $_POST['key'];
	echo $object->get ($key);
	exit (1);
	}
?>
