<?php
/*
Name: Current Scenario
Order: 0
*/
#php SD_Theme::_e (/*T[*/''/*]*/);
?>
<?php if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
else: ?>
	<p class="lead"><?php SD_Theme::_e (/*T[*/'If you are happy with the current scenario, use the buttons bellow to save it.'/*]*/); ?></p>
	<hr />
	<p><?php SD_Theme::_e (/*T[*/'Scenario Name'/*]*/); ?>:</p>
	<form action="" method="post">
		<div class="row">
			<div class="col-lg-6">
				<input class="form-control" name="scenario_new" type="text" value="" />
			</div>
			<div class="col-lg-3">
				<button class="btn btn-block btn-info" name="scenario_saveas"><?php SD_Theme::_e (/*T[*/'Save on Server'/*]*/); ?></button>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-block btn-success" name="scenario_export"><?php SD_Theme::_e (/*T[*/'Save and Download'/*]*/); ?></button>
			</div>
		</div>
	</form>
	<hr />
	<p><?php SD_Theme::_e (/*T[*/'Choose a scenario to load'/*]*/); ?>:</p>
	<form action="" method="post">
		<div class="row">
			<div class="col-lg-6">
				<select class="form-control select select-primary select-block" data-toggle="select" name="scenario_slug">
<?php
$scenarios = new SD_List ('SD_Scenario');
if ($scenarios->is ('empty')) : ?>
					<option value=""><?php SD_Theme::_e (/*T[*/'No available scenario.'/*]*/); ?></option>
<?php else : ?>
					<option value=""><?php SD_Theme::_e (/*T[*/'Choose a scenario ...'/*]*/); ?></option>
<?php
	foreach ($scenarios->get () as $scenario) : ?>
					<option value="<?php echo $scenario->get (); ?>"<?php if ($scenario->get () == $sd_theme->get ('scenario', 'id')) echo ' selected="selected"'; ?>><?php echo $scenario->get ('name'); ?></option>
<?php
	endforeach;
endif; ?>
				</select>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-block btn-warning" name="scenario_load"><?php SD_Theme::_e (/*T[*/'Load Scenario'/*]*/); ?></button>
			</div>
		</div>
	</form>
	<hr />
	<p><?php SD_Theme::_e (/*T[*/'Choose a scenario to upload'/*]*/); ?>:</p>
	<form action="" method="post">
		<div class="row">
			<div class="col-lg-6">
				<div class="file-control">
					<div class="input-group">
						<input class="form-control" type="text" value="" />
						<input class="hidden" type="file" name="scenario_file" />
						<span class="input-group-btn">
							<a href="#" class="btn file-clear"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Clear'/*]*/); ?></a>
							<a href="#" class="btn file-upload"><i class="fui-clip"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Upload'/*]*/); ?></a>
						</span>
					</div>
					<div class="progress hidden">
						<div class="progress-bar" style="width: 45%;">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-block btn-primary" name="scenario_upload"><?php SD_Theme::_e (/*T[*/'Upload Scenario'/*]*/); ?></button>
			</div>
		</div>
	</form>
<?php endif; ?>
