<?php
$scenario = $sd_theme->get ('scenario');
?>
<div class="sd-rounded sd-translucent sd-padded">
<div class="row">
<?php if (is_null ($sd_game) && is_null ($scenario)) : ?>
	<div class="col-lg-6">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'New Game'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Game Name'/*]*/); ?></label>
				<?php SD_Theme::inp ('game_name', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'No. of Players'/*]*/); ?></label>
				<?php SD_Theme::inp ('game_players', '', 'select', '1:32'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Game Scenario'/*]*/); ?></label>
				<?php $scenarios = new SD_List ('SD_Scenario'); ?>
				<?php SD_Theme::inp ('game_scenario', '', 'select', $scenarios->get ('select', 'name')); ?>
				<button class="btn btn-success btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'Start Game'/*]*/); ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-lg-6">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Scenario File'/*]*/); ?></label>
				<?php SD_Theme::inp ('scenario_import', '', 'file'); ?>
				<button class="btn btn-info btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></button>
			</fieldset>
		</form>
		<div class="separator"><?php SD_Theme::_e (/*T[*/'or'/*]*/); ?></div>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Create New Scenario'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Scenario Name'/*]*/); ?></label>
				<?php SD_Theme::inp ('scenario_name', '', 'string'/*]*/); ?>
				<button class="btn btn-success btn-block btn-sm" name="scenario_create"><?php SD_Theme::_e (/*T[*/'New Scenario'/*]*/); ?></button>
			</fieldset>
		</form>
		<div class="separator"><?php SD_Theme::_e (/*T[*/'or'/*]*/); ?></div>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Select Scenario'/*]*/); ?></legend>
				<?php SD_Theme::inp ('scenario_read', '', 'select', $scenarios->get ('select', 'name')); ?>
				<button name="scenario_export" class="btn btn-info btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'Export Scenario'/*]*/); ?></button>
				<a href="" class="btn btn-danger btn-block btn-sm sd-delete"><?php SD_Theme::_e (/*T[*/'Delete Scenario'/*]*/); ?></a>
				<div class="row hidden sd-delete">
					<p><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete selected scenario?'/*]*/); ?></p>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-success btn-block btn-sm sd-cancel"><i class="fui-cross"></i></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button name="scenario_delete" class="btn btn-danger btn-block btn-sm"><i class="fui-check"></i></button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
<?php elseif ($sd_game instanceof SD_Game) : ?>
	<div class="col-lg-12"><?php SD_Theme::_i (__FILE__, __LINE__); ?></div>
</div>
<div class="row">
	<div class="col-lg-4">
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Current Game'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Game State'/*]*/); ?></label>
				<div class="sd-game-rounds">
<?php	foreach (SD_Game::$SO as $state) : ?>
					<div<?php if ($state == $sd_game->get ('state')) echo ' class="active"'; ?>><?php echo SD_Game::$S[$state]; ?></div>
<?php	endforeach; ?>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<button class="btn btn-danger btn-block btn-sm" name="prev_turn"><i class="fui-triangle-left-large"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Prev'/*]*/); ?></button>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-success btn-block btn-sm" name="next_turn"><?php SD_Theme::_e (/*T[*/'Next'/*]*/); ?>&nbsp;<i class="fui-triangle-right-large"></i></button>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<button class="btn btn-info btn-block btn-sm" name="end_game"><i class="fui-power"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Suspend Game'/*]*/); ?></button>
					</div>
				</div>
			</fieldset>
		</form>
<?php	$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
	$players->sort ('name');
	if ($players->is ('empty')) : ?>
<?php	else :
		foreach ($players->get () as $player) : 
$quotations = $player->get ('quotations', TRUE);
if (empty ($quotations)) echo "quotations: empty"; else echo "quotations: not empty";

?>
		<form action="" method="post">
			<input type="hidden" name="player" value="<?php $player->out (); ?>" />
			<fieldset>
				<legend><?php $player->out ('name'); ?></legend>
				<div class="sd-player-card">
					<div class="row">
						<div class="col-lg-4"><?php SD_Theme::_e (/*T[*/'Password'/*]*/); ?> :</div><div class="col-lg-8"><span class="form-control"><?php $player->out ('password'); ?></span></div>
						<div class="col-lg-4"><?php SD_Theme::_e (/*T[*/'Score'/*]*/); ?> :</div><div class="col-lg-8"><span class="form-control"><?php $player->out ('score'); ?></span></div>
						<div class="col-lg-4"><?php SD_Theme::_e (/*T[*/'State'/*]*/); ?> :</div><div class="col-lg-8">
<?php			$all_states = $player->get ('state');
			if (isset ($all_states[$sd_game->get ('state')]['submitted']) && $all_states[$sd_game->get ('state')]['submitted']) : ?>
						<?php SD_Theme::_e (/*T[*/'Submitted'/*]*/); ?> /
						<a href="<?php $sd_theme->out ('url') ?>cancel_submitted=<?php $player->out (); ?>"><?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
<?php			elseif (isset ($all_states[$sd_game->get ('state')]['in_progress']) && $all_states[$sd_game->get ('state')]['in_progress']) : ?>
						<?php SD_Theme::_e (/*T[*/'In Progress'/*]*/); ?> /
						<a href="<?php $sd_theme->out ('url') ?>cancel_in_progress=<?php $player->out (); ?>"><?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
<?php			else : ?>
						<?php SD_Theme::_e (/*T[*/'Waiting'/*]*/); ?>
<?php			endif; ?>
						</div>
<?php		if (in_array ($sd_game->get ('state'), SD_Game::$PL)) : ?>
						<div class="col-lg-4"><?php SD_Theme::_e (/*T[*/'Reset'/*]*/); ?> :</div>
						<div class="col-lg-4 btn-left"><button name="reset_timer" class="btn btn-sm btn-block btn-info"><i class="fui-time"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Timer'/*]*/); ?></a></div>
						<div class="col-lg-4 btn-right"><button name="reset_round" class="btn btn-sm btn-block btn-danger"><i class="fui-folder"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Round'/*]*/); ?></a></div>
<?php		endif; ?>
					</div>
				</div>
			</fieldset>
		</form>
<?php		endforeach;
	endif; ?>
	</div>
	<div class="col-lg-4">
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Passwords File'/*]*/); ?></legend>
				<div class="row">
					<div class="col-lg-12">
						<?php SD_Theme::inp ('email', $sd_user->get ('user_email')); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="/salesdrive/wp-content/plugins/wp-salesdrive/rpc/passwords.php" class="btn btn-sm btn-success btn-block"><i class="fui-export"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-info btn-block" name="send_password"><i class="fui-mail"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Send2Mail'/*]*/); ?></button>
					</div>
				</div>
			</fieldset>
		</form>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Game Stats'/*]*/); ?></legend>
			<label><?php SD_Theme::_e (/*T[*/'Game Name'/*]*/); ?>: <?php echo $sd_game->get ('name'); ?></label>
				<br />
			<label><?php SD_Theme::_e (/*T[*/'Game Scenario'/*]*/); ?>: <?php echo $sd_game->scenario ('name'); ?></label>
				<br />
		</fieldset>

<?php		if ($sd_user->is ('admin') && in_array ($sd_game->get ('state'), [
				SD_Game::ROUND1_END,
				SD_Game::ROUND2_END,
				SD_Game::ROUND3_END,
				SD_Game::ROUND4_END
				])) : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Current Game State'/*]*/); ?></legend>
			<div class="row">
				<a href="<?php $sd_plugin->out ('url', 'rpc/export-state.php') ?>" class="btn btn-sm btn-success btn-block"><i class="fui-export"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download Game State'/*]*/); ?></a>
				<form action="" enctype="multipart/form-data" method="post">
					<label><?php SD_Theme:_e (/*T[*/'Import Current Game State'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<?php SD_Theme::inp ('state_file', '', 'file'); ?>
					<button class="btn btn-sm btn-info btn-block" name="update_state"><i class="fui-upload"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Upload Game State'/*]*/); ?></button>
				</form>
			</div>
			<div class="row">
				<form action="" method="post">
					<button class="btn btn-sm btn-danger btn-block" name="renegotiate_state"><i class="fui-alert-circle"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Renegotiate'/*]*/); ?></button>
				</form>
			</div>
			<div class="row">
				<a href="<?php $sd_plugin->out ('url', 'rpc/download-export-template.php') ?>" class="btn btn-sm btn-success btn-block"><i class="fui-export"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download Game State Template'/*]*/); ?></a>
			</div>
			<div class="row">
				<form action="" enctype="multipart/form-data" method="post">
					<label><?php SD_Theme::_e (/*T[*/'Upload Game State Template'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<?php SD_Theme::inp ('export_template', '', 'file'); ?>
					<button class="btn btn-sm btn-info btn-block" name="update_export_template"><i class="fui-upload"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Upload Game State Template'/*]*/); ?></button>
				</form>
			</div>
			<div class="row">
				<?php SD_Theme::_i (__FILE__, __LINE__); ?>
			</div>
		</fieldset>
<?php		endif; ?>
<?php 		if ($sd_game->get ('state') == SD_Game::ROUND3_BEGIN) : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'3rd Round'/*]*/); ?></legend>
<?php			$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
			$players->sort ('name');
			if ($players->is ('empty')) : ?>
<?php			else : 
				$sd_game_data = $sd_game->get ('data', 'current');

				$is_in_progress = FALSE;
				$is_voted = TRUE;
				foreach ($players->get () as $player) :
					if (isset ($sd_game_data['data'][$player->get ()]['in_progress']) && $sd_game_data['data'][$player->get ()]['in_progress'] == TRUE)
						$is_in_progress = TRUE;
					if (!isset ($sd_game_data['data'][$player->get ()]['voted']) || $sd_game_data['data'][$player->get ()]['voted'] != TRUE)
						$is_voted = FALSE;
				endforeach;

				foreach ($players->get () as $player) : ?>
<?php					if (isset ($sd_game_data['data'][$player->get ()]['in_progress']) && $sd_game_data['data'][$player->get ()]['in_progress'] == TRUE) : ?>
			<div class="row">
				<div class="col-lg-12">
					<?php $player->out ('name'); ?>
				</div>
			</div>
			<div class="sd-autoload-votes" data-player="<?php $player->out (); ?>">
				<?php SD_Theme::_e (/*T[*/'Waiting for votes ...'/*]*/); ?>
			</div>
			<form action="" method="post" class="hidden">
				<input name="player" type="hidden" value="<?php $player->out (); ?>" />
				<div class="row">
					<div class="col-lg-6">
						<button class="btn btn-sm btn-block btn-danger" name="recast_vote"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Recast'/*]*/); ?></button>
					</div>
					<div class="col-lg-6">
						<button class="btn btn-sm btn-block btn-info" name="validate_vote"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Validate'/*]*/); ?></button>
					</div>
				</div>
			</form>
<?php					else : ?>
<?php						if (isset ($sd_game_data['data'][$player->get ()]['voted']) && $sd_game_data['data'][$player->get ()]['voted'] == TRUE) : ?>
<?php						else : ?>
<?php							if (isset ($sd_game_data['data'][$player->get ()]['validated']) && $sd_game_data['data'][$player->get ()]['validated'] == TRUE) : ?>
			<div class="row">
				<div class="col-lg-12">
					<?php $player->out ('name'); ?>
				</div>
			</div>
<?php								$polls = new SD_List ('SD_Poll', $sd_game->scenario ('path'));
								if (!$polls->is ('empty')) :
									foreach ($polls->get () as $poll) : ?>
			<div class="row">
				<div class="col-lg-8"><?php $poll->out ('name'); ?></div>
				<div class="col-lg-4"><span class="form-control"><?php $player->out ('votes', $poll->get ()); ?></div>
			</div>
<?php									endforeach;
								endif; ?>
<?php							else : ?>
			<form action="" method="post">
				<input name="player" type="hidden" value="<?php $player->out (); ?>" />
				<div class="row">
					<div class="col-lg-8">
						<?php $player->out ('name'); ?>
					</div>
					<div class="col-lg-4">
						<button class="btn btn-sm btn-block btn-success" name="begin_vote"<?php echo $is_in_progress || $is_voted ? ' disabled' : ''; ?>><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Begin Vote'/*]*/); ?></button>
					</div>
				</div>
			</form>
<?php							endif; ?>
<?php						endif; ?>
<?php					endif; ?>
<?php				endforeach; ?>
<?php			endif; ?>
		</fieldset>
<?php		endif; ?>
<?php		if (in_array ($sd_game->get ('state'), [ SD_Game::ROUND4_END, SD_Game::GAME_REPORT ])) : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'4th Round'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-12">
						<a href="<?php $sd_plugin->out ('url', 'rpc/export-trainer-report.php') ?>" class="btn btn-sm btn-block btn-success"><?php SD_Theme::_e (/*T[*/'Trainer Report'/*]*/); ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<button class="btn btn-sm btn-block btn-danger" name="regenerate_market"><?php SD_Theme::_e (/*T[*/'Regenerate Market'/*]*/); ?></button>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
<?php			$market = $sd_game->get ('market');
			$charts = [];
			if (!empty ($market)) :
				foreach ($market as $player_id => $basket) :
					$player = new SD_Player ($player_id);
					$perceived_cost = $player->compute ('perceived_cost');
					foreach ($basket as $product_slug => $product_basket) :
						foreach ($product_basket as $quality_slug => $value) :
							if (!isset ($charts[$product_slug][$quality_slug]))
								$charts[$product_slug][$quality_slug] = [[
									/*T[*/'Market Share'/*]*/, 
									/*T[*/'Units Acquired'/*]*/ ]];
							$product = new SD_Product ($sd_game->scenario ('path'), $product_slug);
							$this_quoted_price = $player->compute ($product, 'quoted_unit_price');
							#$charts[$product_slug][$quality_slug][] = [ $player->get ('name') . "\n" . 'PV: ' . $perceived_cost[$product_slug][$quality_slug] . $sd_game->scenario ('currency') , (int) $value ];
							$charts[$product_slug][$quality_slug][] = [
								$player->get ('name') . "\n" .
									'Q' . ': ' . ((int) $market[$player->get ()][$product_slug][$quality_slug]) . "\n" .
									' P' . ': ' . $this_quoted_price[$quality_slug] . $sd_game->scenario ('currency') . "\n" .
									' PC' . ': ' . $perceived_cost[$product_slug][$quality_slug] . $sd_game->scenario ('currency'),
								(int) $market[$player->get ()][$product_slug][$quality_slug]
								];
						endforeach;
					endforeach;
				endforeach;

				foreach ($charts as $product_slug => $product_chart) :
					foreach ($product_chart as $quality_slug => $quality_chart) :
						$product = new SD_Product ($sd_game->scenario ('path'), $product_slug);
						$qualities = $product->get ('quality');
						SD_Theme::c ($quality_chart, $product->get ('name') . ' ' . $qualities[$quality_slug]['name']);
					endforeach;
				endforeach;
			endif; ?>
					</div>
				</div>
			</form>
		</fieldset>
<?php		endif; ?>
	</div>
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Adjustable Parameters'/*]*/); ?></legend>
<?php include (dirname (__DIR__) . '/common/scenario-negotiation.php'); ?>
		</fieldset>
	</div>
<?php elseif ($scenario instanceof SD_Scenario) : ?>
	<div class="col-lg-12">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
	</div>
	<div class="col-lg-6">
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'New Game'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Game Name'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('game_name', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'No. of Players'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('game_players', '', 'select', '1:32'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Game Scenario'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php $scenarios = new SD_List ('SD_Scenario'); ?>
				<?php SD_Theme::inp ('game_scenario', '', 'select', $scenarios->get ('select', 'name')); ?>
				<label><?php SD_Theme::_e (/*T[*/'Language'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php $sd_language = new SD_Language (); $languages = $sd_language->get ('languages'); ?>
				<?php SD_Theme::inp ('game_locale', $sd_user->get ('locale'), 'select', $languages); ?>
				<button class="btn btn-success btn-block btn-sm" name="game_create"><?php SD_Theme::_e (/*T[*/'Start Game'/*]*/); ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-lg-6">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
<?php		if (isset ($_GET['imported'])) :
			$head = SD_Scenario::temp_header (SD_Theme::r ('imported'));
			if (!empty ($head)) : ?>
				<input type="hidden" name="scenario_stamp" value="<?php echo SD_Theme::r ('imported'); ?>" />
				<legend><?php SD_Theme::_e (/*T[*/'Scenario Import'/*]*/); ?></legend>
				<div class="alert alert-success"><?php SD_Theme::_e (/*T[*/'Import scenario succeded! Choose the new scenario name to finish the import.'/*]*/); ?></div>
				<label><?php SD_Theme::_e (/*T[*/'Scenario Name'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('scenario_name', $head['name']); ?>
<?php			else : ?>
				<legend><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></legend>
				<div class="alert alert-danger"><?php SD_Theme::_e (/*T[*/'Import scenario failed! Try again!'/*]*/); ?></div>
				<label><?php SD_Theme::_e (/*T[*/'Scenario File'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('scenario_file', '', 'file'); ?>
<?php			endif; ?>
<?php		else : ?>
				<legend><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Scenario File'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('scenario_file', '', 'file'); ?>
<?php		endif; ?>
				<button name="scenario_import" class="btn btn-info btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'Import Scenario'/*]*/); ?></button>
			</fieldset>
		</form>
		<div class="separator"><?php SD_Theme::_e (/*T[*/'or'/*]*/); ?></div>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Create New Scenario'/*]*/); ?></legend>
				<label><?php SD_Theme::_e (/*T[*/'Scenario Name'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?>:</label>
				<?php SD_Theme::inp ('scenario_name', '', 'string'/*]*/); ?>
				<button name="scenario_create" class="btn btn-success btn-block btn-sm"><?php SD_Theme::_e (/*T[*/'New Scenario'/*]*/); ?></button>
			</fieldset>
		</form>
		<div class="separator"><?php SD_Theme::_e (/*T[*/'or'/*]*/); ?></div>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Select Scenario'/*]*/); ?></legend>
				<?php SD_Theme::inp ('scenario_read', '', 'select', $scenarios->get ('select', 'name')); ?>
				<button class="btn btn-info btn-block btn-sm" name="scenario_export"><?php SD_Theme::_e (/*T[*/'Export Scenario'/*]*/); ?></button>
				<a href="" class="btn btn-danger btn-block btn-sm sd-delete"><?php SD_Theme::_e (/*T[*/'Delete Scenario'/*]*/); ?></a>
				<div class="row hidden sd-delete">
					<p><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete selected scenario?'/*]*/); ?></p>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="" class="btn btn-success btn-block btn-sm sd-cancel"><i class="fui-cross"></i></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button name="scenario_delete" class="btn btn-danger btn-block btn-sm"><i class="fui-check"></i></button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
<?php endif; ?>
</div>
</div>
