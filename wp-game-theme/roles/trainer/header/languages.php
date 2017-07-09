<?php
$error = null;

if (isset ($_POST['language_update'])) {
	$sd_language = new SD_Language ();
	$languages = $sd_language->get ('languages');

	$language_locale = SD_Theme::r ('language_locale');
	if (isset (SD_Language::$LC[$language_locale])) {
		$languages[$language_locale] = SD_Language::$LC[$language_locale];
		$sd_language->set ('languages', $languages);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['language_delete'])) {
	if (isset ($_POST['language_locale'])) {
		$sd_language = new SD_Language ();
		$languages = $sd_language-> get ('languages');

		$language_locale = SD_Theme::r ('language_locale');

		if (($language_locale != SD_Language::DEFAULT_LANGUAGE) && isset ($languages[$language_locale])) {
			unset ($languages[$language_locale]);
			$sd_language->set ('languages', $languages);
			}
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['language_scan'])) {
	$sd_language = new SD_Language ();
	$languages = $sd_language->get ('languages');

	$scan_path = get_template_directory ();
	$lang_dir = $scan_path . DIRECTORY_SEPARATOR . 'lang';
	$po_file = $lang_dir . DIRECTORY_SEPARATOR . SD_Language::lc (SD_Language::DEFAULT_LANGUAGE) . '.po';

	if (!is_dir ($lang_dir)) @mkdir ($lang_dir);
	if (is_dir ($lang_dir)) {
		SD_Language::scan ($po_file, $scan_path);
		}
	foreach ($languages as $language_locale => $language_name) {
		if ($language_locale == SD_Language::DEFAULT_LANGUAGE) continue;
		$translation_file = $lang_dir . DIRECTORY_SEPARATOR . SD_Language::lc ($language_locale) . '.po';
		if (!file_exists ($translation_file))
			@copy ($po_file, $translation_file);
		}

	SD_Theme::prg ($error);
	}
if (isset ($_POST['language_compile'])) {
	$sd_language = new SD_Language ();
	$languages = $sd_language->get ('languages');

	$scan_path = get_template_directory ();
	$lang_dir = $scan_path . DIRECTORY_SEPARATOR . 'lang';

	foreach ($languages as $language_locale => $language_name) {
		$po = $lang_dir . DIRECTORY_SEPARATOR . $language_locale . '.po';
		$mo = $lang_dir . DIRECTORY_SEPARATOR . $language_locale . '.mo';
		SD_Language::mo ($po, $mo);
		}

	SD_Theme::prg ($error);
	}
if ( isset ($_POST['msgstr_update']) ||
	isset ($_POST['msgstr_update_prev']) ||
	isset ($_POST['msgstr_update_next'])) {
	$sd_language = new SD_Language ();

	if (isset ($_GET['msgid'])) {
		$msgids = $sd_language->get ('msgids');
		$num = (int) $_GET['msgid'];
		$msgid = $msgids[$num];

		if (isset ($_POST['msgstr_update_prev'])) {
			$min = min (array_keys ($msgids));
			$prev = $min;
			foreach ($msgids as $key => $value) {
				if ($prev < $key && $key < $num)
					$prev = $key;
				}
			if ($prev == $min) $prev = max (array_keys ($msgids));
			$_GET['msgid'] = (int) $prev;
			}
		else
		if (isset ($_POST['msgstr_update_next'])) {
			$max = max (array_keys ($msgids));
			$next = $max;
			foreach ($msgids as $key => $value) {
				if ($next > $key && $key > $num)
					$next = $key;
				}
			if ($next == $max) $next = min (array_keys ($msgids));
			$_GET['msgid'] = (int) $next;
			}
		else
			unset ($_GET['msgid']);

		$languages = $sd_language->get ('languages');
		foreach ($languages as $language_locale => $language_name) {
			$sd_language->set ('translation', [
				'msgid' => $msgid,
				'locale' => $language_locale,
				'translation' => SD_Theme::r ('msgstr_' . $language_locale)
				]);
			}
		}

	#if (isset ($_POST['msgstr_update']))
		SD_Theme::prg ($error);
	}
?>
