<?php
$scenarios = new SD_List ('SD_Scenario');
if (!$scenarios->is ('empty')) : ?>
<div class="row">
	<div class="col-lg-6">
		<p><?php SD_Theme::_e (/*T[*/'There is no current scenario to work with. Use the list bellow to choose an appropriate scenario in order to continue.'/*]*/); ?></p>
		<form action="" method="post">
			<div class="input-group">
				<input class="form-control" type="text" name="scenario_name" value="" />
				<div class="input-group-btn">
					<button class="btn" name="scenario_search"><i class="fui-search"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Find'/*]*/); ?></button>
				</div>
			</div>
		</form>
		<br />
		<ul class="nav nav-list">
			<li class="nav-header"><?php SD_Theme::_e (/*T[*/'Available Scenarios (Click to use)'/*]*/); ?></li>
<?php	foreach ($scenarios->get () as $scenario) : ?>
			<li>
				<a href="<?php $sd_theme->out ('url', [SD_Scenario::GET => $scenario->get ()]); ?>"><?php echo $scenario->get ('name'); ?></a>
			</li>
<?php	endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
