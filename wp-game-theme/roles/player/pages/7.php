<?php
$all_states = $sd_user->get ('state');
$state = $all_states[SD_Game::ROUND3_BEGIN];

$polls = new SD_List ('SD_Poll');
$game_data = $sd_game->get ('data', 'current');

$current_player = null;
$all_voted = TRUE;
$all_validated = TRUE;

if (!empty ($game_data['data']))
	foreach ($game_data['data'] as $player_id => $voting_data) {
		if (isset ($voting_data['in_progress']) && $voting_data['in_progress'] == TRUE) {
			try {
				$current_player = new SD_Player ($player_id);
				}
			catch (SD_Exception $exception) {
				}
			}

		if (!isset ($voting_data['voted']) || $voting_data['voted'] != TRUE)
			$all_voted = FALSE;

		if (!isset ($voting_data['validated']) || $voting_data['validated'] != TRUE)
			$all_voted = FALSE;
		}
else {
	$all_voted = FALSE;
	$all_validated = FALSE;
	}

if (!is_null ($current_player)) :
	if (!$polls->is ('empty')) :
		if (isset ($state['data']['voted'][$current_player->get ()][session_id ()]) && !empty ($state['data']['voted'][$current_player->get ()][session_id ()])) : ?>
<div class="sd-rounded sd-padded sd-translucent">
<div class="sd-poll">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3 class="sd-center"><?php SD_Theme::_e (/*T[*/'Thank you for your vote!'/*]*/); ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
		</div>
	</div>
</div>
<?php		else : ?>
<div class="sd-rounded sd-padded sd-translucent">
<div class="sd-poll">
	<form action="" method="post">
		<input type="hidden" name="player" value="<?php $current_player->out (); ?>" />
		<div class="row sd-poll-title">
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
				<h3><?php $current_player->out ('name'); ?></h3>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<h4 class="sd-timer pull-right"></h4>
			</div>
		</div>
<?php			foreach ($polls->get () as $poll) : ?>
		<div class="sd-poll-item">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><p><?php $poll->out ('name'); ?></p><input type="hidden" name="<?php $poll->out (); ?>" value="0" /></div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><a href="" class="btn btn-sm btn-block btn-danger sd-down-vote" data-min="<?php $sd_theme->out ('scenario', 'round_3_min_score'); ?>">&mdash;</a></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><span>0</span></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><a href="" class="btn btn-sm btn-block btn-success sd-up-vote" data-max="<?php $sd_theme->out ('scenario', 'round_3_max_score'); ?>">+</a></div>
			</div>
		</div>
<?php			endforeach; ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="sd-submit-vote">
					<button class="btn btn-lg btn-block btn-success" name="submit_vote"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Submit'/*]*/); ?></button>
				</div>
			</div>
		</div>
	</form>
</div>
</div>
<?php		endif; ?>
<?php	else : ?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="sd-poll">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h3><?php SD_Theme::_e (/*T[*/'Nothing to vote yet!'/*]*/); ?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
			</div>
		</div>
	</div>
</div>
<?php	endif; ?>
<?php else : ?>
<?php	if ($all_voted) : ?>
<div class="sd-rounded sd-padded sd-translucent">
<div class="sd-poll">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3><?php SD_Theme::_e (/*T[*/'Round Ended!'/*]*/); ?></h3>
		</div>
	</div>
</div>
</div>
<?php	else : ?>
<div class="sd-rounded sd-padded sd-translucent">
<div class="sd-poll">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3 class="sd-center"><?php SD_Theme::_e (/*T[*/'Nothing to vote yet!'/*]*/); ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
		</div>
	</div>
</div>
</div>
<?php	endif; ?>

<?php endif; ?>
