<?php
/*
Name: Features
Parent: scenario
Order: 6
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
else :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-copy.php');
	if (isset ($sd_not_mine)) return;
endif; ?>
<div class="row">
	<div class="col-lg-6">
		<?php SD_Theme::_i (__FILE__, __LINE__); ?>
<?php $features = new SD_List ('SD_Feature');
if ($features->is ('empty')) : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Add a New Feature'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Feature Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<input class="form-control input-sm" type="text" value="" name="feature_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'% of Unit Cost'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('feature_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<div class="bootstrap-switch-square pull-right">
						<input type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_mandatory" />
					</div>
				</div>
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<div class="bootstrap-switch-square pull-right">
						<input type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_negotiable" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Feature'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="feature_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Feature'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
<?php else : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Add a New Feature'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Feature Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<input class="form-control input-sm" type="text" value="" name="feature_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'% of Unit Cost'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('feature_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<div class="bootstrap-switch-square pull-right">
						<input type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_mandatory" />
					</div>
				</div>
				<div>
					<label><?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					<div class="bootstrap-switch-square pull-right">
						<input type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_negotiable" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Feature'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="feature_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Feature'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Available Features'/*]*/); ?></legend>
<?php	foreach ($features->get () as $feature) : ?>
			<form action="" method="post">
				<input type="hidden" name="feature" value="<?php $feature->out (); ?>" />
				<div class="row">
					<div class="col-lg-6 btn-left">
						<input class="form-control input-sm" type="text" name="feature_name" value="<?php $feature->out ('name'); ?>" />
					</div>
					<div class="col-lg-3">
						<a class="btn btn-sm btn-block btn-info sd-update"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Edit'/*]*/); ?></a>
					</div>
					<div class="col-lg-3 btn-right">
						<a class="btn btn-sm btn-block btn-danger sd-delete"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
					</div>
				</div>
				<div class="sd-read">
					<?php SD_Theme::_e (/*T[*/'Feature Cost'/*]*/); ?>: <?php $feature->out ('cost'); ?>% / 
					<?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?>: <?php echo $feature->get ('mandatory') ? __(/*T[*/'Yes'/*]*/) : __(/*T[*/'No'/*]*/); ?> / 
					<?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?>: <?php echo $feature->get ('negotiable') ? __(/*T[*/'Yes'/*]*/) : __(/*T[*/'No'/*]*/); ?> 
				</div>
				<div class="sd-update hidden">
					<div class="row">
						<div class="col-lg-9">
							<label><?php SD_Theme::_e (/*T[*/'Feature Cost'/*]*/); ?>:</label>
						</div>
						<div class="col-lg-3">
							<?php SD_Theme::inp ('feature_cost', $feature->get ('cost'), 'percent', '%'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label><?php SD_Theme::_e (/*T[*/'Mandatory'/*]*/); ?>:</label>
						</div>
						<div class="col-lg-6">
							<div class="bootstrap-switch-square pull-right">
								<input<?php echo $feature->get ('mandatory') ? ' checked' : ''; ?> type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_mandatory" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label><?php SD_Theme::_e (/*T[*/'Negotiable'/*]*/); ?>:</label>
						</div>
						<div class="col-lg-6">
							<div class="bootstrap-switch-square pull-right">
								<input<?php echo $feature->get ('negotiable') ? ' checked' : ''; ?>  type="checkbox" data-toggle="switch" data-on-text="<i class='fui-check'></i>" data-off-text="<i class='fui-cross'></i>" name="feature_negotiable" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
							<a href="" class="btn btn-sm btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
							<button class="btn btn-sm btn-block btn-success" name="feature_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
						</div>
					</div>
				</div>
				<div class="sd-delete hidden">
					<p><? SD_Theme::_e (/*T[*/'Are you sure you want to delete this feature?'/*]*/); ?></p>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 btn-left">
							<a href="" class="btn btn-sm btn-block btn-success sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Delete'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 btn-right">
							<button class="btn btn-sm btn-block btn-danger" name="feature_delete"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete Feature'/*]*/); ?></button>
						</div>
					</div>
				</div>
			</form>
<?php	endforeach; ?>
		</fieldset>
<?php endif; ?>
	</div>
	<div class="col-lg-6">
<?php include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'scenario-overview.php'); ?>
	</div>
</div>
