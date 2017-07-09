<img src="<?php bloginfo ('stylesheet_directory'); ?>/assets/img/goodjob.png" alt="" title="" class="img-responsive" />
<div class="sd-rounded sd-padded sd-translucent">
<?php if (isset ($_GET['ok'])) : ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="alert alert-success">
				<?php SD_Theme::_e (/*T[*/'The email was successfully sent!'/*]*/); ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php if (isset ($_GET['error'])) : ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="alert alert-danger">
				<?php SD_Theme::_e (/*T[*/'An error has occurred!'/*]*/); ?>
			</div>
		</div>
	</div>
<?php endif; ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php $sd_user->report (); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
			<a href="/salesdrive/wp-content/plugins/wp-salesdrive/rpc/export-report.php" class="btn btn-lg btn-block btn-info"><i class="fui-document"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Download Report'/*]*/); ?></a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
			<form action="" method="post">
				<button name="email_report" class="btn btn-lg btn-block btn-success"><i class="fui-mail"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'E-Mail Report'/*]*/); ?></button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<a href="?page=logout" class="btn btn-lg btn-block btn-danger"><i class="fui-play"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Good Bye!'/*]*/); ?></a>
		</div>
	</div>
</div>

