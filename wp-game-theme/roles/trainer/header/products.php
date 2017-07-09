<?php
$error = null;
include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy-header.php');

if (isset ($_POST['product_create'])) {
	$data = [
		'name'		=> SD_Theme::r ('product_name')
		];

	try {
		$product = new SD_Product ($sd_theme->get ('scenario', 'path'), $data);
		}
	catch (SD_Exception $e) {
		$product = null;
		}

	if (!is_null ($product)) {
		try {
			$product->save ();
			}
		catch (SD_Exception $e) {
			$product = null;
			}
		}

	SD_Theme::prg ($error, TRUE);
	}

if (isset ($_POST['product_update'])) {
	if (isset ($_POST['product'])) {
		try {
			$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('product'));
			}
		catch (SD_Exception $e) {
			$product = null;
			}

		if (!is_null ($product)) {
			$product->set ('name', SD_Theme::r ('product_name'));
			}
		}

	SD_Theme::prg ($error);
	}

if (isset ($_POST['quality_create'])) {
	if (isset ($_POST['product'])) {
		try {
			$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('product'));
			}
		catch (SD_Exception $e) {
			$product = null;
			}

		if (!is_null ($product)) {
			$qualities = $product->get ('quality');
			if (empty ($qualities) || is_string ($qualities))
				$qualities = [];

			$data = [];
			foreach (SD_Product::$QA as $quality_prop_slug => $quality_prop_name)
				$data[$quality_prop_slug] = SD_Theme::r ('quality_' . $quality_prop_slug, 'string', 'last');

			$quality_slug = SD_Instance::slug ($data['name']);
			$qualities[$quality_slug] = $data;

			try {
				$product->set ('quality', $qualities);
				}
			catch (SD_Exception $e) {
				}
			}

		SD_Theme::prg ($error, TRUE);
		}
	}

if (isset ($_POST['quality_update'])) {
	if (isset ($_POST['product'])) {
		try {
			$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('product'));
			}
		catch (SD_Exception $e) {
			$product = null;
			}

		if (!is_null ($product)) {
			$qualities = $product->get ('quality');
			if (!is_array ($qualities))
				$qualities = [];

			$index = 0;
			if (!empty ($qualities))
				foreach ($qualities as $quality_slug => $quality_data) {
					foreach (SD_Product::$QA as $quality_prop_slug => $quality_prop_name) {
						$qualities[$quality_slug][$quality_prop_slug] = SD_Theme::r ('quality_' . $quality_prop_slug, 'string', $index);
						}
					$index ++;
					}
			try {
				$product->set ('quality', $qualities);
				}
			catch (SD_Exception $e) {
				}
			}

		SD_Theme::prg ($error, TRUE);
		}
	}

if (isset ($_POST['quality_delete'])) {
	if (isset ($_POST['product'])) {
		try {
			$product = new SD_Product ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('product'));
			}
		catch (SD_Exception $e) {
			$product = null;
			}

		if (!is_null ($product)) {
			$qualities = $product->get ('quality');
			if (!empty ($qualities) && !is_string ($qualities)) {
				$quality = SD_Theme::r ('quality');
				if (isset ($qualities[$quality])) {
					unset ($qualities[$quality]);
					var_dump ($qualities);
					$product->set ('quality', $qualities);
					}
				}
			}
		}
	SD_Theme::prg ($error, TRUE);
	}

?>
