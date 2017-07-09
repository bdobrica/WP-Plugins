<?php
/*
Name: General Settings
Parent: loadsave
Order: 1
*/
#php SD_Theme::_e (/*T[*/''/*]*/);
?>
<?php if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
endif; ?>

<div class="row">
	<div class="col-lg-6">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
		<form action="" method="post">
			<div>
				<label><?php SD_Theme::_e (/*T[*/'Public Scenario'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<div class="bootstrap-switch-square pull-right">
					<input type="checkbox"<?php echo $sd_theme->get ('scenario', 'public') ? ' checked' : ''; ?> data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="public" />
				</div>
			</div>
			<div>
				<label><?php SD_Theme::_e (/*T[*/'Locked for editing'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<div class="bootstrap-switch-square pull-right">
					<input type="checkbox"<?php echo $sd_theme->get ('scenario', 'editable') ? ' checked' : ''; ?> data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="editable" />
				</div>
			</div>
			<hr />
			<p><?php SD_Theme::_e (/*T[*/'Buying company details'/*]*/); ?>:</p>
			<label><?php SD_Theme::_e (/*T[*/'Buyer\'s Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
			<input class="form-control" name="company_name" type="text" value="<?php echo $sd_theme->get ('scenario', 'company_name'); ?>" />
			<label><?php SD_Theme::_e (/*T[*/'Buyer\'s Short Description'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
			<textarea class="form-control" name="company_description"><?php echo $sd_theme->get ('scenario', 'company_description'); ?></textarea>
			<hr />
			<div class="row">
				<div class="col-lg-6">
					<a href="" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
				</div>
				<div class="col-lg-6">
					<button class="btn btn-sm btn-block btn-success" name="scenario_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-lg-6">
		<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
