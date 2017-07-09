<?php
$all_states = $sd_user->get ('state');
$state = $all_states[SD_Game::ROUND1_BEGIN];
$already_meet = isset ($state['data']['questions']) ? array_keys ($state['data']['questions']) : [];

/******************************************************************************
* SUBMITTED *******************************************************************
******************************************************************************/

if (isset ($state['submitted']) && $state['submitted']) :
	$sd_user->set ('timer', 'clear'); ?>

<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/goodjob.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-center"><?php $sd_theme->out ('scenario', 'round1_end_message'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
		</div>
	</div>
</div>
<?php
/******************************************************************************
* IN PROGRESS *****************************************************************
******************************************************************************/

elseif (isset ($state['in_progress']) && $state['in_progress']) :
	$character = null;
	#$sd_theme->out ('scenario', 'path');
	if (isset ($_GET['meet'])) :
		try {
			$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r ('meet'));
			}
		catch (SD_Exception $exception) {
			}
	endif;
	if (is_null ($character)) : ?>
<?php		if (sizeof ($already_meet) >= SD_Game::MAX_MEETINGS) :
			$all_states[SD_Game::ROUND1_BEGIN]['in_progress'] = FALSE;
			$all_states[SD_Game::ROUND1_BEGIN]['submitted'] = TRUE;
			$sd_user->set ('state', $all_states);
			$sd_user->set ('timer', 'clear'); ?>
<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/goodjob.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-center"><?php $sd_theme->out ('scenario', 'round1_end_message'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-lg btn-block btn-success"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
		</div>
	</div>
</div>
<?php		else : ?>
<div class="row sd-character-list">
<?php			$characters = new SD_List ('SD_Character');
			if ($characters->is ('empty')) : ?>
<?php			else :
				$characters->sort ();
				$count = 0;
				foreach ($characters->get () as $character) : 
					$count ++; ?>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="tile sd-tile <?php echo $count%3 == 0 ? 'sd-tile-right' : ($count%3 == 2 ? 'sd-tile-center' : 'sd-tile-left'); ?>">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<img src="<?php $character->out ('image', 'neutral'); ?>" class="img-rounded img-responsive" />
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<h3 class="tile-title"><?php $character->out ('name'); ?></h3>
					<p><small><?php $character->out ('position'); ?></small></p>

				</div>
				<form action="" method="post" class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<input type="hidden" name="character" value="<?php $character->out (); ?>" />
					<div class="sd-padded">
						<button href="./?meet=<?php $character->out (); ?>" class="btn btn-sm btn-block btn-success" <?php echo in_array ($character->get (), $already_meet) || sizeof ($already_meet) > SD_Game::MAX_MEETINGS ? 'disabled' : ''; ?> name="meet"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Go To Meeting'/*]*/); ?></button>
					</div>
				</form>
			</div>
			<div class="sd-separator"></div>
		</div>
	</div>
<?php				endforeach; ?>
<?php			endif; ?>
</div>
<?php		endif; ?>
<?php	else :
if (!isset ($state['data']['questions'][$character->get()])) :
	$state['data']['questions'][$character->get()] = [];
	$all_states[SD_Game::ROUND1_BEGIN] = $state;
	$sd_user->set ('state', $all_states);
endif;
	?>
<div class="row sd-character-chat">
	<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
		<div class="sd-character">
			<div class="sd-character-image">
<?php		foreach (SD_Character::$S as $state_slug => $state_name) : ?>
				<img src="<?php $character->out ('image', $state_slug); ?>" class="img-responsive img-rounded <?php echo 'sd-' . $state_slug; echo $state_slug == 'neutral' ? ' sd-backimage' : ' sd-transparent';?> <?php $character->out (); ?>" />
<?php		endforeach; ?>
			</div>
			<div>
				<h5><?php $character->out ('name'); ?></h5>
				<h6><?php $character->out ('position'); ?></h6>
				<p><?php $character->out ('resume'); ?></p>
			</div>
		</div>
	</div>
<?php
		$questions = isset ($state['data']['questions'][$character->get ()]) ? $state['data']['questions'][$character->get ()] : [];
		$conversation = new SD_Conversation ($character); ?>
	<div class="col-lg-9 col-md-9 col-sm-9">
		<div class="sd-message-chat">
<?php		if (!empty ($questions)) :
			foreach ($questions as $question) :
				$answer = $conversation->get ('answer', $question); ?>
			<div class="row">
				<div class="col-lg-11 col-md-11 col-sm-11 col-xs-10">
					<div class="sd-chat left">
						<div class="arrow"></div>
						<h3 class="sd-chat-title"><?php echo $answer->player_name; ?>:</h3>
						<div class="sd-chat-content">
							<p><?php echo $answer->player_question; ?></p>
						</div>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
					<img src="/salesdrive/wp-content/themes/wp-salesdrive/assets/img/user.png" alt="" title="" class="img-rounded" />
				</div>
			</div>
			<div class="row">
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
					<img src="<?php $character->out ('image', 'neutral'); ?>" class="img-rounded" />
				</div>
				<div class="col-lg-11 col-md-11 col-sm-11 col-xs-10">
					<div class="sd-chat right">
						<div class="arrow"></div>
						<h3 class="sd-chat-title"><?php echo $answer->character_name; ?>:</h3>
						<div class="sd-chat-content">
							<p><?php echo $answer->character_answer; ?></p>
						</div>
					</div>
				</div>
			</div>
<?php			endforeach;
		endif;
?>
		</div>
		<form action="" method="post" class="sd-message-form">
			<input type="hidden" name="character" value="<?php $character->out (); ?>" />
			<div class="sd-message-list">
<?php		$nodes = $conversation->get ('current', $questions);
		if (!empty ($nodes)) : ?>
<?php			foreach ($nodes as $node) : ?>
				<label class="radio"><input type="radio" name="question" value="<?php echo $node->id; ?>" data-toggle="radio" /><?php echo $node->player_question; ?></label>
<?php 			endforeach; ?>
<?php		endif ; ?>
			</div>
			<div class="row">
<?php		if ($sd_theme->get ('scenario', 'reusable_questions') == SD_Conversation::REUSABLE_TOP) : ?>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 btn-left">
					<a href="./?leave=<?php $character->out (); ?>" class="btn btn-block btn-danger"><i class="fui-exit"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Leave'/*]*/); ?></a>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 btn-center">
					<button class="btn btn-left btn-block btn-info sd-message-goup" disabled><i class="fui-triangle-up"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Go Up'/*]*/); ?></button>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 btn-right">
					<button class="btn btn-right btn-block btn-success sd-message-send"><i class="fui-chat"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Send Message'/*]*/); ?></button>
				</div>
<?php		else: ?>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
					<div class="row">
						<a href="" class="btn btn-left btn-block btn-danger sd-confirm"><i class="fui-exit"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Leave'/*]*/); ?></a>
					</div>
					<div class="row sd-confirm hidden">
						<div class="col-lg-6 btn-left">
							<a href="" class="btn btn-block btn-success sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 btn-right">
							<a href="./?leave=<?php $character->out (); ?>" class="btn btn-block btn-danger"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></a>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
					<button class="btn btn-block btn-success sd-message-send"><i class="fui-chat"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Send Message'/*]*/); ?></button>
				</div>
<?php		endif; ?>
			</div>
		</form>
	</div>
</div>
<?php	endif; ?>
<?php
/******************************************************************************
* FIRST SCREEN ****************************************************************
******************************************************************************/

elseif (isset ($state['company']) && $state['company']) : ?>
<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/first-screen.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3 class="sd-center"><?php echo $sd_game->scenario ('company_name'); ?></h3>
			<div class="sd-justify"><?php echo $sd_game->scenario ('company_description'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<form action="" method="post">
				<button class="btn btn-block btn-success" name="company_begin"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
			</form>
		</div>
	</div>
</div>

<?php
/******************************************************************************
* SECOND SCREEN ***************************************************************
******************************************************************************/

elseif (isset ($state['intro']) && $state['intro']) : ?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="sd-justify"><?php $sd_theme->out ('scenario', 'round1_begin_message'); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<form action="" method="post">
				<button class="btn btn-block btn-success" name="player_begin"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Continue'/*]*/); ?></a>
			</form>
		</div>
	</div>
</div>
<?php
/******************************************************************************
* REGISTRATION ****************************************************************
******************************************************************************/

else :
	$allow_submit = FALSE;
	if ($sd_user->get ('name') && $sd_user->get ('emails'))
		$allow_submit = TRUE;
	?>
<div class="sd-rounded sd-padded sd-translucent">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<p class="sd-intro sd-larger sd-center"><?php $sd_theme->out ('scenario', 'round0_begin_message'); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-2">
		</div>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<form action="" method="post">
				<fieldset>
					<legend><?php SD_Theme::_e (/*T[*/'Are you ready?'/*]*/); ?></legend>
					<div class="row">
						<div class="col-lg-12">
							<label><?php SD_Theme::_e (/*T[*/'Company Name'/*]*/); ?>:</label>
							<?php SD_Theme::inp ('name', $sd_user->get ('name'), 'string'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label><?php SD_Theme::_e (/*T[*/'E-Mail Addresses'/*]*/); ?>:</label>
							<?php SD_Theme::inp ('emails', $sd_user->get ('emails'), 'textarea'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
							<a href="<?php $sd_theme->out ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
							<button class="btn btn-sm btn-block btn-success" name="player_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save'/*]*/); ?></button>
						</div>
					</div>
<?php 	if (isset ($_GET['ok'])) : ?>
					<div class="row sd-alert">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p><?php SD_Theme::_e (/*T[*/'Save was successful!'/*]*/); ?></p>
						</div>
					</div>
<?php	endif; ?>
<?php	if ($allow_submit) : ?>
					<div class="row">
						<div class="col-lg-12">
							<a href="" class="btn btn-sm btn-block btn-success sd-confirm"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Submit'/*]*/); ?></a>
						</div>
					</div>
					<div class="row sd-confirm hidden">
						<div class="col-lg-12 sd-center">
							<label><?php SD_Theme::_e (/*T[*/'Are you sure you want to submit?'/*]*/); ?></label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
							<a href="" class="btn btn-sm btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
							<button class="btn btn-sm btn-block btn-success" name="player_submit"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
						</div>
					</div>
<?php	endif; ?>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<?php endif; ?>
