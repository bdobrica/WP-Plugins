<?php
$sd_language = new SD_Language ();
$languages = $sd_language->get ('languages');

if (strpos ($_SERVER['REQUEST_URI'], '/trainer/') !== FALSE) : ?>
<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-12">
		<a href="" class="sd-logo"><span><?php SD_Theme::_e (/*T[*/'Sales Drive'/*]*/); ?></span></a>
		<div class="sd-rounded sd-translucent sd-padded sd-center sd-trainer-login">
			<h5><?php SD_Theme::_e (/*T[*/'Trainer Login'/*]*/); ?></h5>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Username:'/*]*/); ?></label>
				<input class="form-control" name="username" type="text" value="" placeholder="" />
				<label><?php SD_Theme::_e (/*T[*/'Password:'/*]*/); ?></label>
				<input class="form-control" name="password" type="password" />
				<label><?php SD_Theme::_e (/*T[*/'Language'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('locale', '', 'select', $languages); ?>
				<br />
				<br />
				<button class="btn btn-block btn-success"><?php SD_Theme::_e (/*T[*/'Login &raquo;'/*]*/); ?></button>
			</form>
			<ul>
				<li><a href="?page=recover"><?php SD_Theme::_e (/*T[*/'Forgot your password?'/*]*/); ?></a></li>
				<!--li><a href="?page=register"><?php SD_Theme::_e (/*T[*/'Register a new account?'/*]*/); ?></a></li-->
			</ul>
		</div>
	</div>
</div>
<?php else : ?>
<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-12">
		<a href="" class="sd-logo"><span><?php SD_Theme::_e (/*T[*/'Sales Drive'/*]*/); ?></span></a>
		<div class="sd-rounded sd-translucent sd-padded sd-center sd-player-login">
			<h5><?php SD_Theme::_e (/*T[*/'Player Login'/*]*/); ?></h5>
			<form action="" method="post">
				<label><?php SD_Theme::_e (/*T[*/'Team Password'/*]*/); ?>:</label>
				<input class="form-control" name="teampass" type="text" value="" placeholder="" />
<?php if (FALSE) : ?>
				<label><?php SD_Theme::_e (/*T[*/'Language'/*]*/); ?>:</label>
				<?php SD_Theme::inp ('locale', '', 'select', $languages); ?>
<?php endif; ?>
				<br />
				<button class="btn btn-block btn-success"><?php SD_Theme::_e (/*T[*/'Login &raquo;'/*]*/); ?></button>
			</form>
			<ul></ul>
		</div>
	</div>
</div>
<?php endif; ?>
