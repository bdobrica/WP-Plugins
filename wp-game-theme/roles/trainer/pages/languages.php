<?php
/*
Name: Languages
Parent: general
Order: 0
Admin: true
*/
?>
<div class="sd-rounded sd-translucent sd-padded">
<?php
$sd_language = new SD_Language ();

$languages = $sd_language->get ('languages');

if (!empty ($languages)) : ?>
<div class="row">
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Available Languages'/*]*/); ?></legend>
<?php	foreach ($languages as $language_locale => $language_name) : ?>
			<form action="" method="post">
				<input type="hidden" name="language_locale" value="<?php echo $language_locale; ?>" />
				<div class="row">
					<div class="col-lg-10 btn-left"><?php echo $language_name; ?></div>
<?php		if ($language_locale != SD_Language::DEFAULT_LANGUAGE) : ?>
					<div class="col-lg-2 btn-right"><button class="btn btn-block btn-sm btn-danger" name="language_delete"><i class="fui-trash"></i></button></div>
<?php		endif; ?>
				</div>
			</form>
<?php	endforeach; ?>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-12">
						<select class="form-control select select-block" data-toggle="select" name="language_locale">
							<option value=""><?php SD_Theme::_e (/*T[*/'Choose Language'/*]*/); ?></option>
	<?php	foreach (SD_Language::$LC as $language_locale => $language_name) : ?>
							<option value="<?php echo $language_locale; ?>"><?php echo $language_name; ?></option>
	<?php	endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<button class="btn btn-block btn-sm btn-success" name="language_update"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Language'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
	<div class="col-lg-8">
<?php $msgids = $sd_language->get ('msgids');
if (isset ($_GET['msgid'])) :
	$msgid = $msgids[(int) $_GET['msgid']]; ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Translate'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-3">
						<label><?php echo SD_Language::$LC[SD_Language::DEFAULT_LANGUAGE]; ?>:</label>
					</div>
					<div class="col-lg-9">
						<p><?php echo $msgid; ?></p>
					</div>
				</div>
<?php	$languages = $sd_language->get ('languages');
	$translation = $sd_language->get ('translation', $msgid);
	foreach ($languages as $language_locale => $language_name) :
		if ($language_locale == SD_Language::DEFAULT_LANGUAGE) continue; ?>
				<div class="row">
					<div class="col-lg-3">
						<label><?php echo $language_name; ?>:</label>
					</div>
					<div class="col-lg-9">
						<textarea class="form-control" name="msgstr_<?php echo $language_locale; ?>"><?php echo isset ($translation[$language_locale]) ? $translation[$language_locale] : ''; ?></textarea>
					</div>
				</div>
<?php	endforeach; ?>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 btn-left">
						<button class="btn btn-block btn-sm btn-info" name="msgstr_update_prev"><i class="fui-triangle-left-large"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save &amp; Edit Previous'/*]*/); ?></button>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<button class="btn btn-block btn-sm btn-success" name="msgstr_update"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save &amp; Return'/*]*/); ?></button>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 btn-right">
						<button class="btn btn-block btn-sm btn-info" name="msgstr_update_next"><i class="fui-triangle-right-large"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save &amp; Edit Next'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
<?php else : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Messages'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></legend>
		<form action="" method="post">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
					<button class="btn btn-block btn-sm btn-info" name="language_scan"><i class="fui-search"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Scan Code'/*]*/); ?></button>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
					<button class="btn btn-block btn-sm btn-success" name="language_compile"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Compile Translation'/*]*/); ?></button>
				</div>
			</div>
		</form>
<?php	foreach ($msgids as $line => $msgid) :
		$msgid = stripslashes ($msgid); ?>
			<div class="row">
				<div class="col-lg-10">
					<?php echo $msgid; ?>
				</div>
				<div class="col-lg-2">
					<a href="?page=languages&msgid=<?php echo $line; ?>" class="btn btn-block btn-sm btn-info" name="language_translate"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Translate'/*]*/); ?></a>
				</div>
			</div>
<?php	endforeach; ?>
		</fieldset>
<?php endif; ?>
	</div>
<?php endif; ?>
</div>
