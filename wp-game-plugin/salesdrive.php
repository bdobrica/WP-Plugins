<?php
/*
Plugin Name: SalesDrive
Plugin URI: http://ipotetica.ro/salesdrive
Description:
Author: Bogdan Dobrica
Version: 0.1
Author URI: http://ublo.ro/
*/
define ('WP_SALESDRIVE_PLUGIN', plugin_dir_path (__FILE__));

spl_autoload_register (function ($class) {
        if (strpos ($class, 'SD_') !== 0) return;
        $file = dirname (__FILE__) . '/class/' . strtolower ($class) . '.php';
        if (!file_exists ($file)) return;
        include ($file);
        });

$sd_plugin = new SD_Plugin ();
?>
