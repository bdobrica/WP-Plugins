<?php
/*
Name: User Stats
Order: 9
*/
?>
<div class="sd-rounded sd-translucent sd-padded">
<?php 	$scenarios = new SD_List ('SD_Scenario');
	$trainers = get_users ();
	if (!empty ($trainers)) : ?>
	<fieldset>
		<legend><?php SD_Theme::_e (/*T[*/'User Stats'/*]*/); ?></legend>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>
							<?php SD_Theme::_e (/*T[*/'Trainer'/*]*/); ?>
						</th>
						<th style="text-align: center;">
							<?php SD_Theme::_e (/*T[*/'Scenarios'/*]*/); ?>
						</th>
						<th style="text-align: center;">
							<?php SD_Theme::_e (/*T[*/'Sessions'/*]*/); ?>
						</th>
						<th style="text-align: center;">
							<?php SD_Theme::_e (/*T[*/'Total Users'/*]*/); ?>
						</th>
						<th style="text-align: right;">
							<?php SD_Theme::_e (/*T[*/'Download Stats'/*]*/); ?>
						</th>
					</tr>
				</thead>
				<tbody>
<?php		$total = [
			'scenarios'	=> 0,
			'games'		=> 0,
			'users'		=> 0
			];
		foreach ($trainers as $trainer) :
			if (!$sd_user->is ('admin') && $trainer->ID != $sd_user->get ()) continue;
			$_games = new SD_List ('SD_Game', [sprintf ('owner=%d', $trainer->ID)]);
			$total_users = 0;
			if (!$_games->is ('empty')) :
				foreach ($_games->get () as $_game) :
					$total_users += $_game->get ('players');
				endforeach;
			endif;
			$scenarios_num = 0;

			foreach ($scenarios->get () as $_scenario) {
				$scenarios_num += $_scenario->get ('owner') == $trainer->ID ? 1 : 0;
				}

			$total['scenarios']	+= $scenarios_num;
			$total['games']		+= $_games->get ('sizeof');
			$total['users']		+= $total_users;
?>
					<tr>
						<td>
							<?php echo $trainer->first_name; ?>
							<?php echo $trainer->last_name; ?>
						</td>
						<td style="text-align: center;">
							<?php echo $scenarios_num; ?>
						</td>
						<td style="text-align: center;">
							<?php echo $_games->get ('sizeof'); ?>
						</td>
						<td style="text-align: center;">
							<?php echo $total_users; ?>
						</td>
						<td style="text-align: right;">
							<a href="<?php $sd_plugin->out ('url', 'rpc/export-user-stats.php') ?>?user=<?php echo $trainer->ID; ?>" class="btn btn-sm btn-info"><i class="fui-export"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download'/*]*/); ?></a>
						</td>
					</tr>
<?php		endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php SD_Theme::_e (/*T[*/'Total'/*]*/); ?></th>
						<th style="text-align: center;">
							<?php echo $total['scenarios']; ?>
						</th>
						<th style="text-align: center;">
							<?php echo $total['games']; ?>
						</th>
						<th style="text-align: center;">
							<?php echo $total['users']; ?>
						</th>
						<th style="text-align: right;">
							<a href="<?php $sd_plugin->out ('url', 'rpc/export-user-stats.php') ?>" class="btn btn-sm btn-success"><i class="fui-export"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download'/*]*/); ?></a>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</fieldset>
<?php	endif; ?>
</div>
