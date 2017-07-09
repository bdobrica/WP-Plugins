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

$user = new SD_User ();

$scenario = SD_Theme::r ('scenario');

try {
	$scenario = new SD_Scenario ($scenario);
	}
catch (SD_Exception $e) {
	exit (0);
	}

$trainers = get_users ();
if (!empty ($trainers)) {
?><label><?php SD_Theme::_e (sprintf (/*T[*/'Sharing scenario &laquo;%s&raquo; with:'/*]*/, $scenario->get('name'))); ?></label><?php
	foreach ($trainers as $trainer) {
		if ($scenario->get ('owner') == $trainer->ID) {
?><div class="row"><div class="col-xs-9"><?php echo $trainer->first_name; ?> <?php echo $trainer->last_name; ?></div><div class="col-xs-3 text-right"><?php SD_Theme::_e (/*T[*/'Owner'/*]*/); ?></div></div><?php
			}
		elseif (user_can ($trainer->ID, 'remove_users')) {
?><div class="row"><div class="col-xs-9"><?php echo $trainer->first_name; ?> <?php echo $trainer->last_name; ?></div><div class="col-xs-3 text-right"><?php SD_Theme::_e (/*T[*/'Admin'/*]*/); ?></div></div></div></div><?php
			}
		else {
			$can = SD_ACL::can ('read', $scenario, $trainer->ID);
?><div class="row"><div class="col-xs-9"><?php echo $trainer->first_name; ?> <?php echo $trainer->last_name; ?></div><div class="col-xs-3"><?php SD_Theme::inp ('acl_' . $trainer->ID, $can, 'switch'); ?></div></div><?php
			}
		}
	}
?>
