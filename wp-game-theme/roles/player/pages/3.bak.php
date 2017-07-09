<?php ?>
<div class="row">
	<div class="col-lg-9">
	</div>
	<div class="col-lg-3">
		<h1 class="pull-right">00:00</h1>
	</div>
</div>
<div class="row sd-character-list">
<?php $characters = new SD_List ('SD_Character');
if ($characters->is ('empty')) : ?>
<?php else :
	foreach ($characters->get () as $character) : ?>
	<div class="col-lg-4">
		<div class="tile">
			<img src="<?php echo $character->get ('image', 'pleased'); ?>" class="img-rounded img-responsive" />
			<h3 class="tile-title"><?php echo $character->get ('name'); ?></h3>
			<p><small><?php echo $character->get ('position'); ?></small></p>
			<button class="btn btn-block btn-warning sd-goto-meeting"><i class="fui-user"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Go To Meeting'/*]*/); ?></button>
		</div>
	</div>
<?php	endforeach; ?>
<?php endif; ?>
</div>
<div class="row sd-character-chat hidden">
	<div class="col-lg-6">
		<div class="sd-character-image">
<?php $characters = new SD_List ('SD_Character');
if ($characters->is ('empty')) : ?>
<?php else :
	foreach ($characters->get () as $character) :
		foreach (SD_Character::$S as $state_slug => $state_name) : ?>
		<img src="<?php echo $character->get ('image', $state_slug); ?>" class="img-responsive img-rounded hidden <?php echo $state_slug; ?> <?php echo $character->get (); ?>" />
<?php		endforeach; ?>
<?php	endforeach; ?>
<?php endif; ?>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="row">
			<div class="col-lg-2">
				<img src="/salesdrive/wp-content/themes/wp-salesdrive/assets/img/user.png" alt="" title="" class="img-rounded img-responsive" />
			</div>
			<div class="col-lg-10">
				<div class="sd-chat right">
					<div class="arrow"></div>
					<h3 class="sd-chat-title">Smooth Title</h3>
					<div class="sd-chat-content">
						<p>And here's some amazing content. It's very engaging. <br/>Right?</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10">
				<div class="sd-chat left">
					<div class="arrow"></div>
					<h3 class="sd-chat-title">Smooth Title</h3>
					<div class="sd-chat-content">
						<p>And here's some amazing content. It's very engaging. <br/>Right?</p>
					</div>
				</div>
			</div>
			<div class="col-lg-2">
				<img src="/salesdrive/wp-content/themes/wp-salesdrive/assets/img/user.png" alt="" title="" class="img-rounded img-responsive" />
			</div>
		</div>
		<div class="sd-message">
			<label class="radio"><input type="radio" name="message" value="" data-toggle="radio" />Message</label>
			<label class="radio"><input type="radio" name="message" value="" data-toggle="radio" />Message</label>
			<label class="radio"><input type="radio" name="message" value="" data-toggle="radio" />Message</label>
			<label class="radio"><input type="radio" name="message" value="" data-toggle="radio" />Message</label>
			<label class="radio"><input type="radio" name="message" value="" data-toggle="radio" />Message</label>
			<button class="btn btn-block btn-warning sd-send-message"><i class="fui-chat"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Send Message'/*]*/); ?></button>
		</div>
	</div>
</div>
