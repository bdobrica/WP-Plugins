<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['poll_create'])) {
	$data = [
		'name'		=> SD_Theme::r ('poll_name'),
		'cost'		=> SD_Theme::r ('poll_cost', 'percent'),
		'mandatory'	=> SD_Theme::r ('poll_mandatory') ? TRUE : FALSE,
		'negotiable'	=> SD_Theme::r ('poll_negotiable') ? TRUE : FALSE
		];

	try {
		$poll = new SD_Poll ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$poll = null;
		}

	if (!is_null ($poll)) {
		try {
			$poll->save ();
			}
		catch (SD_Exception $e) {
			$poll = null;
			}
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['poll'])) {
	try {
		$poll = new SD_Poll ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('poll'));
		}
	catch (SD_Exception $e) {
		$poll = null;
		}
	if (!is_null ($poll)) {
		if (isset ($_POST['poll_delete'])) {
			try {
				$poll->remove ();
				}
			catch (SD_Exception $e) {
				}
			}
		if (isset ($_POST['poll_update'])) {
			try {
				$poll->set ('name', SD_Theme::r ('poll_name'));
				}
			catch (SD_Exception $e) {
				var_dump ($e);
				die ();
				}
			}
		}

	SD_Theme::prg ($error);
	}
?>
