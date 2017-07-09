<?php
/*
Name: Trainers
Order: 5
Admin: true
*/
$error = isset ($_GET['error']) ? (array) json_decode (stripslashes (urldecode (SD_Theme::r ('error')))) : [];
?>
<div class="sd-rounded sd-translucent sd-padded">
<div class="row">
<?php if (!empty ($error)) :
	if (isset ($error['create']) && $error['create'] == 1) : ?><div class="alert alert-success"><?php SD_Theme::_e (/*T[*/'The username is already in use!'/*]*/); ?></div><?php endif;
	if (isset ($error['create']) && $error['create'] == 2) : ?><div class="alert alert-success"><?php SD_Theme::_e (/*T[*/'There is another user registered with the same password!'/*]*/); ?></div><?php endif;
endif;
if (isset ($_GET['trainer'])) :
	$trainer = new WP_User ((int) $_GET['trainer']);
	if (isset ($_GET['action']) && ($_GET['action'] == 'update')) : ?>
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Update Trainer'/*]*/); ?></legend>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Username'/*]*/); ?>:</label>
				<span class="form-control"><?php echo $trainer->user_login; ?></span>
				<label><?php SD_Theme::_e (/*T[*/'Password'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('password', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'First Name'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('first_name', $trainer->first_name, 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Last Name'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('last_name', $trainer->last_name, 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'E-Mail Address'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('email', $trainer->user_email, 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Phone'/*]*/); ?>:</label>
<?php	$phone = get_user_meta ($trainer->ID, 'phone', true); ?>
				<?php SD_Theme::inp ('phone', $phone, 'string'); ?>
				<div class="row">
					<div class="col-sm-6 btn-left">
						<a class="btn btn-danger btn-block btn-sm" href="<?php $sd_theme->out ('url'); ?>"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
					</div>
					<div class="col-sm-6 btn-right">
						<button class="btn btn-success btn-block btn-sm" name="trainer_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Update'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
<?php 	elseif (isset ($_GET['action']) && ($_GET['action'] == 'delete')) : ?>
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Delete Trainer'/*]*/); ?></legend>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Username'/*]*/); ?>:</label>
				<span class="form-control"><?php echo $trainer->user_login; ?></span>
				<div class="row">
					<div class="col-sm-6 btn-left">
						<a class="btn btn-success btn-block btn-sm" href="<?php $sd_theme->out ('url'); ?>"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel'/*]*/); ?></a>
					</div>
					<div class="col-sm-6 btn-right">
						<button class="btn btn-danger btn-block btn-sm" name="trainer_delete"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
	</div>
<?php	endif; ?>
<?php else : ?>
	<div class="col-lg-4">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Add New Trainer'/*]*/); ?></legend>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Username'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('username', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Password'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('password', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'First Name'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('first_name', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Last Name'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('last_name', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'E-Mail Address'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('email', '', 'string'); ?>
				<label><?php SD_Theme::_e (/*T[*/'Phone'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('phone', '', 'string'); ?>
				<button class="btn btn-success btn-block btn-sm" name="trainer_create"><?php SD_Theme::_e (/*T[*/'Add Trainer'/*]*/); ?></button>
			</form>
		</fieldset>
	</div>
<?php endif; ?>
	<div class="col-lg-8">
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Trainer List'/*]*/); ?></legend>
<?php 	$trainers = get_users ();
	if (!empty ($trainers)) : ?>
		<table class="table table-striped table-hover table-condensed">
			<tr>
				<th><?php SD_Theme::_e (/*T[*/'Username'/*]*/); ?></th>
				<th><?php SD_Theme::_e (/*T[*/'First Name'/*]*/); ?></th>
				<th><?php SD_Theme::_e (/*T[*/'Last Name'/*]*/); ?></th>
				<th><?php SD_Theme::_e (/*T[*/'E-Mail'/*]*/); ?></th>
				<th colspan="2"><?php SD_Theme::_e (/*T[*/'Actions'/*]*/); ?></th>
			</tr>
<?php		foreach ($trainers as $trainer) : ?>
			<tr>
				<td><?php echo $trainer->user_login; ?></td>
				<td><?php echo $trainer->first_name; ?></td>
				<td><?php echo $trainer->last_name; ?></td>
				<td><?php echo $trainer->user_email; ?></td>
				<td>
					<a class="btn btn-sm btn-block btn-info" href="<?php $sd_theme->out ('url', [ 'trainer' => $trainer->ID, 'action' => 'update' ]); ?>"><i class="fui-gear"></i></a>
				</td>
				<td>
					<a class="btn btn-sm btn-block btn-danger" href="<?php $sd_theme->out ('url', [ 'trainer' => $trainer->ID, 'action' => 'delete' ]); ?>"><i class="fui-trash"></i></a>
				</td>
			</tr>
<?php		endforeach; ?>
		</table>
<?php	endif; ?>
		</fieldset>
	</div>
</div>
</div>
