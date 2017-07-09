<?php
spl_autoload_register (function ($class) {
	if (strpos ($class, 'ST_') !== 0) return;
	$file = dirname (__FILE__) . '/class/' . strtolower (substr ($class, 3)) . '.php';
	if (!file_exists ($file)) return;
	include ($file);
	});

$st_theme = new ST_Theme ();
?>
