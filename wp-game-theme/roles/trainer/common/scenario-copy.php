<?php
$owner = $sd_theme->get ('scenario', 'owner');
$games = new SD_List ('SD_Game', ['active=1', sprintf ('scenario=\'%s\'', $sd_theme->get ('scenario', ''))]);
?>
<div class="sd-rounded sd-translucent sd-padded">
<?php
if ($sd_user->is ('admin')) :
	if (!$games->is ('empty')) : ?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-warning">
			<?php SD_Theme::_e (/*T[*/'There are active games using this scenario. Modifying it could disrupt the games.'/*]*/); ?>
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th><?php SD_Theme::_e (/*T[*/'Session Name'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'Trainer'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'State'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'Session Start Date'/*]*/); ?></th>
						</tr>
					</thead>
					<tbody>
<?php		foreach ($games->get () as $game) : ?>
						<tr>
							<td><?php $game->out ('name'); ?></td>
							<td><?php $game->out ('owner_name'); ?></td>
							<td><?php SD_Theme::_e ($game->get ('round_name')); ?></td>
							<td><?php echo date ('d-m-Y H:i', $game->get ('stamp')); ?></td>
						</tr>
<?php		endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php	endif;
	if (is_null ($owner) || ($owner == $sd_user->get ())) :
	else: ?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-warning">
			<?php SD_Theme::_e (/*T[*/'This is not your scenario. But you are admin. So go for it! Change it! Nobody\'s using it right now.'/*]*/); ?>
		</div>
	</div>
</div>
<?php	endif;
else :
	if ($owner == $sd_user->get ()) : ?>
<div class="row">
	<div class="col-lg-12">
<?php		if (!$games->is ('empty')) : ?>
		<div class="alert alert-warning">
			<?php SD_Theme::_e (/*T[*/'There are active games using this scenario. Modifying it could disrupt the games.'/*]*/); ?>
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th><?php SD_Theme::_e (/*T[*/'Session Name'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'Trainer'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'State'/*]*/); ?></th>
							<th><?php SD_Theme::_e (/*T[*/'Session Start Date'/*]*/); ?></th>
						</tr>
					</thead>
					<tbody>
<?php			foreach ($games->get () as $game) : ?>
						<tr>
							<td><?php $game->out ('name'); ?></td>
							<td><?php $game->out ('owner_name'); ?></td>
							<td><?php SD_Theme::_e ($game->get ('round_name')); ?></td>
							<td><?php echo date ('d-m-Y H:i', $game->get ('stamp')); ?></td>
						</tr>
<?php			endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
<?php		endif; ?>
	</div>
</div>
<?php	else :
		$sd_not_mine = TRUE; ?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-danger">
			<?php SD_Theme::_e (/*T[*/'This is not your scenario. You can make a copy of it in order to be able to change it!'/*]*/); ?>
		</div>
	</div>
</div>
<form action="" method="post">
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 btn-left">
			<label><?php SD_Theme::_e (/*T[*/'Save scenario as'/*]*/); ?>:</label>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 btn-right">
			<?php SD_Theme::inp ('scenario_saveas', $sd_theme->get ('scenario', 'name')); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
			<a href="" class="btn btn-sm btn-danger btn-block"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
			<button name="scenario_copy" class="btn btn-sm btn-success btn-block"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save as'/*]*/); ?></button>
		</div>
	</div>
</form>
<?php	endif;
endif;
?>
</div>
