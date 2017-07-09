<?php
/*
Name: Games
Order: 4
*/
?>
<div class="sd-rounded sd-translucent sd-padded">
<?php
$_games = new SD_List ('SD_Game', [sprintf ('owner=%d', $sd_user->get ())]);

if (!$_games->is ('empty')) : ?>
	<fieldset>
		<legend><?php SD_Theme::_e (/*T[*/'Games'/*]*/); ?></legend>
<?php	if (isset ($_GET['game_delete'])) :
		try {
			$_game = new SD_Game ((int) SD_Theme::r ('game_delete')); ?>
			<br />
			<form class="row" action="" method="post">
				<div class="col-xs-12"><?php SD_Theme::_e (sprintf (/*T[*/'Are you sure you want to delete this game: %s (%s)?'/*]*/,
					$_game->get ('name'),
					date ('d-m-Y H:i', $_game->get ('stamp'))
					)); ?><br />
				<input name="game" type="hidden" value="<?php $_game->out (); ?>" />
				<div class="col-xs-6 btn-left"><a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-sm btn-block btn-success"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a></div>
				<div class="col-xs-6 btn-right"><button name="game_delete" class="btn btn-sm btn-block btn-danger"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></button></div>
			</form>
			<br />
			<br />
<?php			}
		catch (SD_Exception $e) {
			} ?>
<?php	endif; ?>
		<div class="table-responsive">
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th><?php SD_Theme::_e (/*T[*/'Game Name'/*]*/); ?></th>
						<th><?php SD_Theme::_e (/*T[*/'Started'/*]*/); ?></th>
						<th><?php SD_Theme::_e (/*T[*/'No. of Players'/*]*/); ?></th>
						<th><?php SD_Theme::_e (/*T[*/'State'/*]*/); ?></th>
						<th><?php SD_Theme::_e (/*T[*/'Active'/*]*/); ?></th>
						<th><?php SD_Theme::_e (/*T[*/'Action'/*]*/); ?></th>
					</tr>
				</thead>
				<tbody>
<?php	foreach ($_games->get () as $_game) : ?>
					<tr>
						<td><?php $_game->out ('name'); ?></td>
						<td><?php echo date ('d-m-Y H:i', $_game->get ('stamp')); ?></td>
						<td><?php $_game->out ('players'); ?></td>
						<td><?php echo SD_Game::$S[$_game->get ('state')]; ?></td>
						<td><?php echo $_game->get ('active') ?
								/*T[*/'yes'/*]*/ :
								/*T[*/'no'/*]*/; ?></td>
						<td>
							<form action="" method="post">
								<input name="game" type="hidden" value="<?php $_game->out (); ?>" />
<?php		if ($_game->get ('active')) : ?>
								<button name="game_pause" class="btn btn-sm btn-warning"><i class="fui-pause"></i></button>
<?php		else : ?>
								<button name="game_play" class="btn btn-sm btn-success"><i class="fui-play"></i></button>
								<a href="<?php $sd_theme->out ('url', ['game_delete' => $_game->get ()]); ?>" class="btn btn-sm btn-info"><i class="fui-trash"></i></a>
<?php		endif; ?>
							</form>
						</td>
					</tr>
<?php	endforeach; ?>
				</tbody>
			</table>
		</div>
	</fieldset>
<?php endif; ?>
</div>
