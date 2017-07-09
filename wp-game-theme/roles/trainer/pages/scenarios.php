<?php
/*
Name: Scenarios
Order: 3
*/
?>
<div class="sd-rounded sd-translucent sd-padded">
<?php
$scenarios = new SD_List ('SD_Scenario', isset ($_GET['find']) ? $_GET['find'] : null);
if (isset ($_GET['by']))
	$scenarios->sort ($_GET['by'], isset ($_GET['ord']) ? $_GET['ord'] : 'asc');
if ($scenarios->is ('empty')) : ?>
No scenarios found!
<?php else : ?>
<div class="row">
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Create a New Scenario'/*]*/); ?></legend>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Enter Your Scenario Name'/*]*/); ?></label>
				<input class="form-control input-sm" type="text" name="scenario_name" value="" />
				<br />
				<button class="btn btn-sm btn-block btn-success" name="scenario_create"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Create a New Scenario'/*]*/); ?></button>
			</form>
			<div class="tile sd-scenario-update">
				<h3 class="tile-title"><?php SD_Theme::_e (/*T[*/'Duplicate Scenario'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></h3>
				<form action="" method="post">
					<input type="hidden" name="scenario" value="" />
					<label><?php SD_Theme::_e (/*T[*/'Duplicate\'s Name'/*]*/); ?></label>
					<input class="form-control" type="text" name="scenario_name" value="" />
					<br />
					<div class="row">
						<div class="col-lg-6">
							<button class="btn btn-block btn-danger sd-cancel" name="scenario_cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-block btn-info sd-update" name="scenario_copy"><i class="fui-windows"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Copy Scenario'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</div>
			<div class="tile sd-scenario-delete">
				<h3 class="tile-title"><?php SD_Theme::_e (/*T[*/'Delete Scenario'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></h3>
				<form action="" method="post">
					<input type="hidden" name="scenario" value="" />
					<label><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete this scenario?'/*]*/); ?></label>
					<div class="row">
						<div class="col-lg-6">
							<button class="btn btn-block btn-success sd-no" name="scenario_cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-block btn-danger sd-yes" name="scenario_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</div>
		</fieldset>
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
<?php		if (isset ($_GET['imported'])) :
			$head = SD_Scenario::temp_header (SD_Theme::r ('imported'));
			if (!empty ($head)) : ?>
				<input type="hidden" name="scenario_stamp" value="<?php echo SD_Theme::r ('imported'); ?>" />
				<legend><?php SD_Theme::_e (/*T[*/'Scenario Import'/*]*/); ?></legend>
				<div class="alert alert-success"><?php SD_Theme::_e (/*T[*/'Import scenario succeded! Choose the new scenario name to finish the import.'/*]*/); ?></div>
				<label><?php SD_Theme::_e (/*T[*/'Scenario Name'/*]*/); ?></label>
				<?php SD_Theme::inp ('scenario_name', $head['name']); ?>
<?php			else : ?>
				<legend><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></legend>
				<div class="alert alert-danger"><?php SD_Theme::_e (/*T[*/'Import scenario failed! Try again!'/*]*/); ?></div>
				<label><?php SD_Theme::_e (/*T[*/'Scenario File'/*]*/); ?></label>
				<?php SD_Theme::inp ('scenario_file', '', 'file'); ?>
<?php			endif; ?>
<?php		else : ?>
				<legend><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Scenario File'/*]*/); ?></label>
				<?php SD_Theme::inp ('scenario_file', '', 'file'); ?>
<?php		endif; ?>
				<button name="scenario_import" class="btn btn-info btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-lg-8">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Available Scenarios'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="input-group">
					<input class="form-control" type="text" name="search_string" value="<?php echo isset ($_GET['find']) ? $_GET['find'] : ''; ?>" />
					<div class="input-group-btn">
						<button class="btn" name="scenario_search"><i class="fui-search"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Find'/*]*/); ?></button>
					</div>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th>
								<?php SD_Theme::_e (/*T[*/'Name'/*]*/); ?>
								<a href="<?php $sd_theme->out ('url', ['by' => 'name', 'ord' => 'asc']); ?>"><i class="fui-triangle-up"></i></a> /
								<a href="<?php $sd_theme->out ('url', ['by' => 'name', 'ord' => 'desc']); ?>"><i class="fui-triangle-down"></i></a>
							</th>
							<th>
								<?php SD_Theme::_e (/*T[*/'Author'/*]*/); ?>
								<a href="<?php $sd_theme->out ('url', ['by' => 'owner', 'ord' => 'asc']); ?>"><i class="fui-triangle-up"></i></a> /
								<a href="<?php $sd_theme->out ('url', ['by' => 'owner', 'ord' => 'desc']); ?>"><i class="fui-triangle-down"></i></a>
							</th>
							<th class="right">
								<?php SD_Theme::_e (/*T[*/'Date'/*]*/); ?>
								<a href="<?php $sd_theme->out ('url', ['by' => 'date', 'ord' => 'asc']); ?>"><i class="fui-triangle-up"></i></a> /
								<a href="<?php $sd_theme->out ('url', ['by' => 'date', 'ord' => 'desc']); ?>"><i class="fui-triangle-down"></i></a>
							</th>
						</tr>
					</thead>
					<tbody>
<?php	foreach ($scenarios->get () as $scenario) :
		if (!$sd_user->is ('admin') && $scenario->get ('owner') != $sd_user->get () && !SD_ACL::can ('read', $scenario, $sd_user)) continue; 
?>
						<tr>
							<td><a href="<?php $scenario->out ('url'); ?>" rel="<?php $scenario->out ();?>" class="sd-scenario-select"><?php $scenario->out ('name'); ?></a></td>
							<td><?php $scenario->out ('owner_name'); ?></td>
							<td class="right"><?php $scenario->out ('last_update'); ?></td>
						</tr>
<?php	endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="sd-scenario-select">
				<div class="sd-caret"></div>
				<form action="" method="post">
					<input type="hidden" name="scenario" value="" />
					<div class="row">
						<div class="col-xs-4">
							<button name="scenario_load" class="btn btn-sm btn-success btn-block sd-load"><?php SD_Theme::_e (/*T[*/'Load'/*]*/); ?></button>
						</div>
						<div class="col-xs-2">
							<a href="" class="btn btn-sm btn-success btn-block sd-share"><?php SD_Theme::_e (/*T[*/'Share'/*]*/); ?></a>
						</div>
						<div class="col-xs-2">
							<a href="" class="btn btn-sm btn-info btn-block sd-saveas"><?php SD_Theme::_e (/*T[*/'Save as ...'/*]*/); ?></a>
						</div>
						<div class="col-xs-2">
							<button name="scenario_export" class="btn btn-sm btn-success btn-block sd-download"><?php SD_Theme::_e (/*T[*/'Download'/*]*/); ?></button>
						</div>
						<div class="col-xs-2">
							<a href="" class="btn btn-sm btn-danger btn-block sd-delete"><?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
						</div>
					</div>
					<div class="row hidden sd-delete">
						<div class="col-xs-12">
							<p style="text-align: center;"><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete this scenario?'/*]*/); ?></p>
						</div>
						<div class="col-xs-6 btn-left">
							<a href="" class="btn btn-sm btn-success btn-block sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
						</div>
						<div class="col-xs-6 btn-right">
							<button name="scenario_delete" class="btn btn-sm btn-danger btn-block sd-download"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
						</div>
					</div>
					<div class="row hidden sd-saveas">
						<div class="col-xs-12">
							<p style="text-align: center;"><?php SD_Theme::_e (/*T[*/'Are you sure you want to save this scenario as?'/*]*/); ?></p>
						</div>
						<div class="col-xs-3">
							<label><?php SD_Theme::_e (/*T[*/'Save as'/*]*/); ?>:</label>
						</div>
						<div class="col-xs-9">
							<?php SD_Theme::inp ('scenario_saveas', ''); ?>
						</div>
						<div class="col-xs-6 btn-left">
							<a href="" class="btn btn-sm btn-danger btn-block sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
						</div>
						<div class="col-xs-6 btn-right">
							<button name="scenario_copy" class="btn btn-sm btn-success btn-block sd-download"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
						</div>
					</div>
					<div class="row hidden sd-share">
						<div class="col-xs-12">
							<div class="sd-share-list"></div>
						</div>
						<div class="col-xs-6 btn-left">
							<a href="" class="btn btn-sm btn-danger btn-block sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
						</div>
						<div class="col-xs-6 btn-right">
							<button name="scenario_share" class="btn btn-sm btn-success btn-block sd-download"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Apply'/*]*/); ?></button>
						</div>
					</div>
				</form>
			</div>
		</fieldset>
	</div>
</div>
<?php endif; ?>
</div>
