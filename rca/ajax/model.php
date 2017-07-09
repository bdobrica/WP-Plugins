<?php
ini_set ('error_reporting', false);

include (dirname (dirname (__FILE__)) . '/data/vehicle_opts.php');
include (dirname (dirname (__FILE__)) . '/data/manufacturers.php');
include (dirname (dirname (__FILE__)) . '/data/all_manufacturers.php');

$man = $_POST['m']; $veh = $_POST['v'];

if (!isset ($manufacturers[$veh])) die ('{"error":1}');

$manufacturer_name = $all_manufacturers[$man];
$man = null;

foreach ($manufacturers[$veh] as $key => $manufacturer) {
	if ($manufacturer[0] != $manufacturer_name) continue;
	$man = $key;
	break;
	}

if (is_null ($man)) die ('{"error":1}');

include (dirname (dirname (__FILE__)) . '/data/models/' . $veh . '_' . $man . '.php');

echo json_encode ($models);
?>
