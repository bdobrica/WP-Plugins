<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['warranty_create'])) {
	$data = [
		'name'		=> SD_Theme::r ('warranty_name'),
		'cost'		=> SD_Theme::r ('warranty_cost', 'percent'),
		'mandatory'	=> SD_Theme::r ('warranty_mandatory') ? TRUE : FALSE,
		'negotiable'	=> SD_Theme::r ('warranty_negotiable') ? TRUE : FALSE
		];

	try {
		$warranty = new SD_Warranty ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$warranty = null;
		}

	if (!is_null ($warranty)) {
		try {
			$warranty->save ();
			}
		catch (SD_Exception $e) {
			$warranty = null;
			}
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['warranty'])) {
	try {
		$warranty = new SD_Warranty ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('warranty'));
		}
	catch (SD_Exception $e) {
		$warranty = null;
		}
	if (!is_null ($warranty)) {
		if (isset ($_POST['warranty_delete'])) {
			try {
				$warranty->remove ();
				}
			catch (SD_Exception $e) {
				}
			}
		if (isset ($_POST['warranty_update'])) {
			$data = [
				'name'		=> SD_Theme::r ('warranty_name'),
				'cost'		=> SD_Theme::r ('warranty_cost', 'percent'),
				'mandatory'	=> SD_Theme::r ('warranty_mandatory') ? TRUE : FALSE,
				'negotiable'	=> SD_Theme::r ('warranty_negotiable') ? TRUE : FALSE
				];
			$warranty->set ($data);
			}

		if (isset ($_POST['move_up'])) {
			$warranties = new SD_List ('SD_Warranty');
			$warranties->sort ();
			if (!$warranties->is ('empty')) {
				$prev = FALSE;
				$list = $warranties->get ();
				reset ($list);
				for ($c = 0; $c < sizeof ($list); $c++) {
					$item = current ($list);
					$item->set ('order', $c + 1);
					next ($list);
					}
				reset ($list);

				while (($item = current ($list)) !== FALSE) {
					if ($warranty->get () == $item->get ()) {
						$prev = prev ($list);
						break;
						}
					next ($list);
					}

				if ($prev !== FALSE) {
					$a = (int) $prev->get ('order');
					$b = (int) $warranty->get ('order');
					$c = $b; $b = $a; $a = $c;

					$prev->set ('order', $a);
					$warranty->set ('order', $b);
					}
				}
			}

		if (isset ($_POST['move_down'])) {
			$warranties = new SD_List ('SD_Warranty');
			$warranties->sort ();
			if (!$warranties->is ('empty')) {
				$next = FALSE;
				$list = $warranties->get ();
				reset ($list);
				for ($c = 0; $c < sizeof ($list); $c++) {
					$item = current ($list);
					$item->set ('order', $c + 1);
					next ($list);
					}
				reset ($list);

				while (($item = current ($list)) !== FALSE) {
					if ($warranty->get () == $item->get ()) {
						$next = next ($list);
						break;
						}
					next ($list);
					}

				if ($next !== FALSE) {
					$a = (int) $next->get ('order');
					$b = (int) $warranty->get ('order');
					$c = $b; $b = $a; $a = $c;

					$next->set ('order', $a);
					$warranty->set ('order', $b);
					}
				}
			}
		}

	SD_Theme::prg ($error);
	}
?>
