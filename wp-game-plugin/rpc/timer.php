<?php
/**
 * Core of SD_*
 */

/**
 * Timer. Handles timer events.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$sd_user = $sd_theme->get ('user');
$timer = $sd_user->get ('timer');

echo json_encode ($timer);
?>
