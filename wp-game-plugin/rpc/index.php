<?php
/**
 * Core of SD_*
 */

/**
 * Entry point for all ajax calls maade from interface elements.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('SD_AJAX_PROTECTION', TRUE);
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$action = strtolower(trim($_POST['action']));
if (!in_array ($action, ['create', 'read', 'update', 'delete'])) {
	echo json_encode ((object)['error' => 1]);
	exit (1);
	}

$object_class = $_POST['object'];
$object_id = $_POST['object_id'];

if (!class_exists ($object_class)) {
	echo json_encode ((object)['error' => 1]);
	exit (1);
	}

if (is_subclass_of ($object_class, 'SD_Instance'))
	$object = new $object_class ($sd_theme->get ('scenario', 'path'), $object_id);
else
	$object = new $object_class ($object_id);

include (__DIR__ . DIRECTORY_SEPARATOR . $action . '.php');
?>
