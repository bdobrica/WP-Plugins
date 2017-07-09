<?php
if (!defined ('SD_AJAX_PROTECTION')) {
	echo json_encode ((object) [ 'error' => 1 ]);
	exit (1);
	}
if (isset ($_POST['key']) && isset ($_POST['value'])) {
	$key = $_POST['key'];
	$value = stripslashes ($_POST['value']);
	try {
		$object->set ($key, $value);
		}
	catch (SD_Exception $e) {
		echo json_encode ((object) [ 'error' => 1 ]);
		exit (1);
		}
	}
echo json_encode ((object) [ 'success' => 1 ]);
exit (1);
?>
