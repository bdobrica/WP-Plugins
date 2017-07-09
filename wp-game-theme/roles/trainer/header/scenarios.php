<?php
$error = null;

if (!empty ($_POST)) {
	$action = '';
	foreach (SD_Theme::$A as $_action)
		if (isset ($_POST[SD_Scenario::GET . '_' . $_action])) {
			$action = $_action;
			break;
			}
	if ($action == 'create') {
		$scenario = new SD_Scenario ();
		try {
			$scenario->set ('name', SD_Theme::r ('scenario_name'));
			}
		catch (SD_Exception $e) {
			$error = ['error_create' => $e->get ('code')];
			}
		SD_Theme::prg ($error);
		}
	if ($action == 'read') {
		}
	if ($action == 'copy') {
		$scenario = new SD_Scenario (SD_Theme::r ('scenario'));
		$new_scenario = new SD_Scenario ();
		try {
			$new_scenario->set ($scenario, SD_Theme::r ('scenario_saveas'));
			}
		catch (SD_Exception $e) {
			$error = ['error_copy' => $e->get ('code')];
			}
		SD_Theme::prg ($error);
		}
	if ($action == 'update') {
		$new_scenario = new SD_Scenario ();
		try {
			$new_scenario->set ('name', SD_Theme::r ('scenario_name'));
			}
		catch (SD_Exception $e) {
			$new_scenario = null;
			$error = ['error_update' => $e->get ('code')];
			}
		try {
			$old_scenario = new SD_Scenario (SD_Theme::r ('scenario'));
			}
		catch (SD_Exception $e) {
			$old_scenario = null;
			$error = ['error_delete' => $e->get ('code')];
			}
		if (!is_null ($new_scenario) && !is_null ($old_scenario)) {
			#$new_scenario->clone ($old_scenario);
			}
		}
	if ($action == 'delete') {
		try {
			$scenario = new SD_Scenario (SD_Theme::r ('scenario'));
			}
		catch (SD_Exception $e) {
			$scenario = null;
			$error = ['error_delete' => $e->get ('code')];
			}

		if (!is_null ($scenario)) {
			$owner = $scenario->get ('owner');
			
			if ($sd_user->is ('admin') || ($owner == $sd_user->get ())) {
				try {
					$scenario->delete ();
					}
				catch (SD_Exception $e) {
					$error = ['error_delete' => $e->get ('code')];
					}
				}
			}

		SD_Theme::prg ($error);
		}
	if ($action == 'search') {
		if (isset ($_POST['search_string']))
			$_GET['find'] = $_POST['search_string'];

		SD_Theme::prg ();
		}
	if ($action == 'load') {
		try {
			$scenario = new SD_Scenario (SD_Theme::r ('scenario'));
			}
		catch (SD_Exception $e) {
			$scenario = null;
			if (is_null ($error))
				$error = ['load_error', $e->get ('code')];
			else
				$error['load_error'] = $e->get ('code');
			}
		if (!is_null ($scenario)) {
			$sd_theme->set ('scenario', $scenario);
			}
		SD_Theme::prg ($error);
		}
	if ($action == 'export') {
		try {
			$scenario = new SD_Scenario (SD_Theme::r ('scenario'));
			$scenario->export ();
			}
		catch (SD_Exception $e) {
			$error = [ 'export_error' => $e->get ('code') ];
			}
		SD_Theme::prg ($error);
		}
	if ($action == 'import') {
		if (!empty ($_FILES['scenario_file'])) {
			$file = $_FILES['scenario_file'];

			if (!isset ($file['error']) || is_array ($file['error']))
				$error = ['file_read' => 1];

			if ($file['error'] != UPLOAD_ERR_OK)
				$error = ['file_read' => 2];

			if (FALSE === $ext = array_search (mime_content_type ($file['tmp_name']), [
				'zip'	=> 'application/zip'
				], true))
				$error = ['file_read' => 3];

			if (!is_null ($error))
				SD_Theme::prg ($error);

			try {
				$stamp = SD_Scenario::open_archive ($file['tmp_name']);
				}
			catch (SD_Exception $e) {
				$error = ['file_read' => 4];
				}

			if (!is_null ($error))
				SD_Theme::prg ($error);

			$_GET['imported'] = $stamp;

			SD_Theme::prg ();
			}
		if (isset ($_POST['scenario_stamp']) && isset ($_POST['scenario_name'])) {
			try {
				SD_Scenario::import (SD_Theme::r ('scenario_stamp'), SD_Theme::r ('scenario_name'));
				unset ($_GET['imported']);
				}
			catch (SD_Exception $e) {
				$error = ['file_read' => 5];
				var_dump ($e);
				die ();
				}
			SD_Theme::prg ($error);
			}
		SD_Theme::prg ();
		}
	if ($action == 'share') {
		try {
			$scenario = new SD_Scenario (SD_Theme::r ('scenario'));
			}
		catch (SD_Exception $e) {
			$scenario = null;
			if (is_null ($error))
				$error = ['share_error', $e->get ('code')];
			else
				$error['share_error'] = $e->get ('code');
			}
		if (empty ($error)) {
			$trainers = get_users ();
			if (!empty ($trainers))
				foreach ($trainers as $trainer) {
					if (isset ($_POST['acl_' . $trainer->ID]) && $_POST['acl_' . $trainer->ID] == 'on')
						SD_ACL::grant ('read', $scenario, $trainer->ID);
					else {
						/** TODO: create static method for checking if a user is admin */
						if (!user_can ($trainer->ID, 'remove_users') && ($scenario->get ('owner') != $trainer->ID))
							SD_ACL::revoke ('read', $scenario, $trainer->ID);
						}
					}
			}
		}
	}
?>
