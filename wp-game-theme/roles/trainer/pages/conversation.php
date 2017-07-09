<?php
/*
Name: Conversations
Order: 2
Parent: scenario
*/
$character = null;
if (isset ($_GET[SD_Character::GET]) && !isset ($character)) :
	try {
		$character = new SD_Character ($sd_theme->get ('scenario', 'path'), SD_Theme::r (SD_Character::GET));
		}
	catch (SD_Exception $e) {
		}
endif;

if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif;

if (is_null ($character)) :
$characters = new SD_List ('SD_Character');
	if ($characters->is ('empty')) :
	else : ?>
<div class="row">
<?php		foreach ($characters->get () as $character) : ?>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
		<a href="<?php $sd_theme->out ('url', [SD_Character::GET => $character->get ()]); ?>" class="sd-character-conversation">
			<img class="img-rounded img-responsive" src="<?php $character->out ('image', 'neutral'); ?>" alt="" title="" />
			<?php $character->out ('name'); ?>
			<small><?php $character->out ('position'); ?></small>
		</a>
	</div>
<?php		endforeach; ?>
</div>
<fieldset>
	<legend><?php SD_Theme::_e (/*T[*/'Conversations Statistics'/*]*/); ?></legend>
	<form action="" method="post" class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th><?php SD_Theme::_e (/*T[*/'Character Name'/*]*/); ?></th>
					<th style="width: 8em;"><?php SD_Theme::_e (/*T[*/'Score Cap'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
					<th><?php SD_Theme::_e (/*T[*/'# of Threads'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
					<th><?php SD_Theme::_e (/*T[*/'Min Score'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
					<th><?php SD_Theme::_e (/*T[*/'Max Score'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
					<th><?php SD_Theme::_e (/*T[*/'Average Score'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
					<th><?php SD_Theme::_e (/*T[*/'Score Variance'/*]*/); SD_Theme::_h (__FILE__, __LINE__); ?></th>
				</tr>
			</thead>
			<tbody>
<?php		foreach ($characters->get () as $character) :
			$conversation = new SD_Conversation ($character);
			$stats = $conversation->get ('stats'); ?>
				<tr>
					<th><?php $character->out ('name'); ?></th>
					<th><?php SD_Theme::inp ($character->get () . '_max_score', $character->get ('max_score'), 'number', SD_Theme::__ (/*T[*/'pt.'/*]*/)); ?></th>
					<th style="text-align: center;"><?php echo $stats['num']; ?></th>
					<th style="text-align: center;"><?php echo $stats['min']; ?></th>
					<th style="text-align: center;"><?php echo $stats['max']; ?></th>
					<th style="text-align: center;"><?php echo floor (100 * $stats['avg']) * 0.01; ?></th>
					<th style="text-align: center;"><?php echo floor (10000 * $stats['var']/$stats['avg']) * 0.01; ?>%</th>
				</tr>
<?php		endforeach; ?>
			</tbody>
		</table>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
				<a href="" class="btn btn-block btn-danger btn-sm"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
				<button class="btn btn-block btn-success btn-sm" name="character_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
			</div>
		</div>
	</form>
</fieldset>
<?php	endif;
else : ?>
<div class="row">
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Import/Export Conversation'/*]*/); ?></legend>
			<a href="/salesdrive/wp-content/plugins/wp-salesdrive/rpc/export-conversation.php?character=<?php $character->out (); ?>" class="btn btn-block btn-success"><?php SD_Theme::_e (/*T[*/'Export Conversation (XLSX)'/*]*/); ?></a>
			<form action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="character" value="<?php $character->out (); ?>" />
				<label><?php SD_Theme::_e (/*T[*/'XLSX Conversation File'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<?php SD_Theme::inp ('conversation_file', '', 'file'); ?>
				<button class="btn btn-block btn-danger" name="import_conversation"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Import Conversation'/*]*/); ?></button>
			</form>
		</fieldset>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Conversation Atom'/*]*/); ?></legend>
			<form action="" method="post" class="sd-question-read">
				<input type="hidden" name="character" value="<?php echo $character->get (); ?>" />
				<input type="hidden" name="question" value="" />
				<label><?php SD_Theme::_e (/*T[*/'Player Question'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<textarea class="form-control" name="player_question"></textarea>
				<label><?php SD_Theme::_e (/*T[*/'Character Answer'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<textarea class="form-control" name="character_answer"></textarea>
				<label><?php SD_Theme::_e (/*T[*/'Character Facial Expression'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
				<div class="row">
	<?php foreach (SD_Character::$S as $state_slug => $state_name) : ?>
					<div class="col-lg-3">
						<img src="<?php echo $character->get ('image', $state_slug); ?>" alt="<?php echo $state_name; ?>" title="" class="img-rounded img-responsive sd-blurred" data-state-id="<?php echo $state_slug; ?>" />
						<input type="radio" name="character_state" value="<?php echo $state_slug; ?>" />
					</div>
	<?php endforeach; ?>
				</div>
				<div class="row">
					<div class="col-lg-7">
						<label><?php SD_Theme::_e (/*T[*/'Answer Delay'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-5">
						<?php SD_Theme::inp ('character_delay', $sd_theme->get ('scenario', 'default_delay'), 'number', /*T[*/'sec.'/*]*/); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-7">
						<label><?php SD_Theme::_e (/*T[*/'Player Question Score'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-5">
						<?php SD_Theme::inp ('player_score', '', 'number', /*T[*/'pt.'/*]*/); ?>
					</div>
				</div>
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Allow Product Purchase'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<?php SD_Theme::inp ('allow_purchase', FALSE, 'switch'); ?>
				</div>
				<div class="row hidden sd-question-update">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<button class="btn btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></button>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-block btn-info sd-update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Apply Changes'/*]*/); ?></button>
					</div>
				</div>
				<div class="row sd-question-create">
					<div class="col-lg-12">
						<button class="btn btn-block btn-success sd-create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Question'/*]*/); ?></button>
					</div>
				</div>
			</form>
			<form action="" method="post" class="sd-question-delete hidden">
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Are you sure you want to delete this question?'/*]*/); ?></label>
					<p class="sd-question"></p>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<button class="btn btn-block btn-success sd-no"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'No'/*]*/); ?></button>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-block btn-danger sd-yes"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Yes'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
	<div class="col-lg-8">
		<div class="sd-tree">
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
				<button class="btn btn-block btn-danger sd-tree-btn sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></button>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
				<button class="btn btn-block btn-success sd-tree-btn sd-update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
