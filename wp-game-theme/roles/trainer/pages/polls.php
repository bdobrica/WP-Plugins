<?php
/*
Name: 3rd Round Poll
Parent: scenario
Order: 9
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif; ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
<?php $polls = new SD_List ('SD_Poll');
if ($polls->is ('empty')) : ?>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Add Poll Parameter'/*]*/); ?></legend>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Poll Parameter'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<input class="form-control" type="text" value="" name="poll_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Poll Parameter'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="poll_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Poll Parameter'/*]*/); ?></button>
					</div>
				</div>
			</fieldset>
		</form>
<?php else : ?>
		<form action="" method="post">
			<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Add Poll Parameter'/*]*/); ?></legend>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Poll Parameter'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<input class="form-control" type="text" value="" name="poll_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Poll Parameter'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="poll_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Poll Parameter'/*]*/); ?></button>
					</div>
				</div>
			</fieldset>
		</form>
		<hr />
		<fieldset>
				<legend><?php SD_Theme::_e (/*T[*/'Poll Items'/*]*/); ?></legend>
<?php	foreach ($polls->get () as $poll) : ?>
			<form action="" method="post">
				<input type="hidden" name="poll" value="<?php $poll->out (); ?>" />
				<div class="row">
					<div class="col-lg-10 btn-left">
						<input class="form-control input-sm" type="text" name="poll_name" value="<?php $poll->out ('name'); ?>" />
					</div>
					<div class="col-lg-1">
						<button class="btn btn-sm btn-block btn-info" name="poll_update"><i class="fui-new"></i></a>
					</div>
					<div class="col-lg-1 btn-right">
						<a class="btn btn-sm btn-block btn-danger sd-delete"><i class="fui-trash"></i></a>
					</div>
				</div>
				<div class="sd-delete hidden">
					<p><? SD_Theme::_e (/*T[*/'Are you sure you want to delete this poll?'/*]*/); ?></p>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
							<a href="" class="btn btn-sm btn-block btn-success sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
							<button class="btn btn-sm btn-block btn-danger" name="poll_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete Poll Parameter'/*]*/); ?></button>
						</div>
					</div>
				</div>
			</form>
<?php	endforeach; ?>
		</fieldset>
<?php endif; ?>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
