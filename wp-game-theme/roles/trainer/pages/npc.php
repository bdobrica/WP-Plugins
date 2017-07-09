<?php
/*
Name: Characters
Parent: scenario
Order: 1
*/
#php SD_Theme::_e (/*T[*/''/*]*/);
if (isset ($_GET[SD_Character::GET]) && !isset ($character)) :
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r (SD_Character::GET));
		}
	catch (SD_Exception $e) {
		$character = null;
		}
endif;
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif;
if (!isset ($character) || is_null ($character)) : /** BEGIN: CHARACTER_CREATE */
	$characters = new SD_List ('SD_Character');
	if ($characters->is ('empty') || isset ($_GET['character_create'])) : ?>

	<form action="" method="post" enctype="multipart/form-data">
		<p><?php SD_Theme::_e (/*T[*/'Create a New Character'/*]*/); ?>:</p>
		<hr />
		<div class="row">
			<div class="col-lg-6">
				<label><?php SD_Theme::_e (/*T[*/'Character Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<input class="form-control" name="character_name" type="text" value="" />
				<label><?php SD_Theme::_e (/*T[*/'Character Position'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<input class="form-control" name="character_position" type="text" value="" />
				<label><?php SD_Theme::_e (/*T[*/'Character\'s Short Resume'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<textarea class="form-control" rows="7" name="character_resume"></textarea>
			</div>
			<div class="col-lg-6">
				<label><?php SD_Theme::_e (/*T[*/'Character\'s mood'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
<?php		if (!empty (SD_Character::$S)) :
			foreach (SD_Character::$S as $key => $value) : ?>
				<div class="row">
					<div class="col-lg-2">
						<img alt="<?php SD_Theme::_e ($value); ?>" src="" title="<?php SD_Theme::_e ($value); ?>" class="img-rounded img-responsive" />
					</div>
					<div class="col-lg-10">
						<label><?php SD_Theme::_e ($value); ?>:</label>
						<div class="file-control">
							<div class="input-group input-group-sm">
								<input class="form-control input-sm" type="text" value="" />
								<input class="hidden" type="file" name="character_<?php echo $key; ?>" />
								<span class="input-group-btn">
									<a href="#" class="btn btn-sm file-clear"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Clear'/*]*/); ?></a>
									<a href="#" class="btn btn-sm file-upload"><i class="fui-clip"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Upload'/*]*/); ?></a>
								</span>
							</div>
						</div>
					</div>
				</div>
<?php			endforeach; 
		endif ; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
				<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><?php SD_Theme::_e (/*T[*/'Cancel Character'/*]*/); ?></a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
				<button class="btn btn-sm btn-block btn-primary" name="character_create"><?php SD_Theme::_e (/*T[*/'Save Character'/*]*/); ?></button>
			</div>
		</div>
	</form>
<?php	else :
		$characters->sort (); ?>
	<div class="row">
		<div class="col-lg-6">
	<?php SD_Theme::_i (__FILE__, __LINE__); ?>
<?php			foreach ($characters->get () as $character) : ?>
			<fieldset>
				<legend><?php $character->out ('name'); ?> / <?php $character->out ('position'); ?></legend>
				<div class="row">
					<div class="col-lg-4 btn-left">
						<a href="?page=conversation&character=<?php $character->out (); ?>"><img src="<?php echo $character->get ('image', 'neutral'); ?>" alt="<?php echo $character->get ('name'); ?>" class="img-responsive img-rounded" /></a>
					</div>
					<div class="col-lg-8 btn-right">
						<form action="" method="post">
							<input type="hidden" name="character" value="<?php $character->out (); ?>" />
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left"><button class="btn btn-sm btn-block" name="move_up"><i class="fui-triangle-up"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Move Up'/*]*/); ?></button></div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right"><button class="btn btn-sm btn-block" name="move_down"><i class="fui-triangle-down"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Move Down'/*]*/); ?></button></div>
							</div>
						</form>
						<a href="?page=conversation&character=<?php $character->out (); ?>" class="btn btn-sm btn-block btn-success"><i class="fui-chat"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Conversation'/*]*/); ?></a>
						<a href="<?php echo $character->get ('url', SD_Character::GET) . '&' . SD_Theme::ACTION . '=update'; ?>" class="btn btn-sm btn-block btn-info"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Edit'/*]*/); ?></a>
						<a href="<?php echo $character->get ('url', SD_Character::GET) . '&' . SD_Theme::ACTION . '=delete'; ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
					</div>
				</div>
			</fieldset>
<?php			endforeach; ?>
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'New Character'/*]*/); ?></legend>
				<div class="row">
					<div class="col-lg-4">
						<img src="<?php bloginfo ('stylesheet_directory'); echo '/' . SD_Character::DEFAULT_IMG; ?>" alt="<?php SD_Theme::_e (/*T[*/'New Character'/*]*/); ?>" class="img-responsive img-rounded" />
					</div>
					<div class="col-lg-8">
						<a href="?page=npc&character_create=true" class="btn btn-sm btn-block btn-success"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Character'/*]*/); ?></a>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-lg-6">
<?php		include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
		</div>
	</div>
<?php	endif; ?>
<?php elseif ($sd_theme->get ('action') == 'delete') : ?>
	<form action="" method="post">
		<p><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete: '/*]*/); echo $character->get ('name') . ' ?'; ?></p>
		<input type="hidden" name="character" value="<?php echo $character->get (); ?>" />
		<div class="row">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-6">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-success"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></a>
					</div>
					<div class="col-lg-6">
						<button class="btn btn-sm btn-block btn-danger" name="character_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php elseif ($sd_theme->get ('action') == 'update') : ?>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="character" value="<?php echo $character->get (); ?>" />
		<p><?php SD_Theme::_e (/*T[*/'Edit Existing Character'/*]*/); ?>: <?php echo !is_null ($character) ? $character->get ('name') : ''; ?></p>
		<hr />
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
				<fieldset>
					<legend><?php SD_Theme::_e (/*T[*/'Game Role'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></legend>
					<?php SD_Theme::inp ('character_role', $character->get ('role'), 'select', SD_Character::$R); ?>
					<label><?php SD_Theme::_e (/*T[*/'Character Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<input class="form-control input-sm" name="character_name" type="text" value="<?php echo $character->get ('name'); ?>" />
					<label><?php SD_Theme::_e (/*T[*/'Character Position'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<input class="form-control input-sm" name="character_position" type="text" value="<?php echo $character->get ('position'); ?>" />
					<label><?php SD_Theme::_e (/*T[*/'Character\'s Short Resume'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<textarea class="form-control" rows="5" name="character_resume"><?php echo $character->get ('resume'); ?></textarea>
				</fieldset>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
				<fieldset>
					<legend><?php SD_Theme::_e (/*T[*/'Character\'s Mood'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></legend>
<?php			if (!empty (SD_Character::$S)) :
				foreach (SD_Character::$S as $key => $value) : ?>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-4 btn-left">
							<img alt="<?php SD_Theme::_e ($value); ?>" src="<?php echo $character->get ('image', $key); ?>" title="<?php SD_Theme::_e ($value); ?>" class="img-rounded img-responsive" />
						</div>
						<div class="col-lg-10 col-md-10 col-sm-8 btn-right">
							<label><?php SD_Theme::_e ($value); ?>:</label>
							<div class="file-control">
								<div class="input-group input-group-sm">
									<input class="form-control" type="text" value="" />
									<input class="hidden" type="file" name="character_<?php echo $key; ?>" />
									<span class="input-group-btn">
										<a href="#" class="btn btn-sm file-clear"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Clear'/*]*/); ?></a>
										<a href="#" class="btn btn-sm file-upload"><i class="fui-clip"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Upload'/*]*/); ?></a>
									</span>
								</div>
							</div>
						</div>
					</div>
<?php				endforeach; 
			endif ; ?>
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
				<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
				<button class="btn btn-sm btn-block btn-primary" name="character_update"><?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
			</div>
		</div>
	</form>
<?php endif; ?>
