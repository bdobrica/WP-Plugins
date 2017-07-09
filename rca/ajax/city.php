<?php
ini_set ('error_reporting', false);

include (dirname (dirname (__FILE__)) . '/data/siruta.php');

$county = $_POST['c'];

if (!isset ($siruta[$county])) die ('{"error":1}');

echo json_encode ($siruta[$county]);
?>
