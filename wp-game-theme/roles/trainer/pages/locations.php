<?php
/*
Name: Locations
Parent: scenario
Order: 7
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
<?php $locations = new SD_List ('SD_Location');
if ($locations->is ('empty')) : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Add a New Delivery Location'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Location Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<input class="form-control input-sm" type="text" value="" name="location_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Time (days)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('location_delivery_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Cost / Day saved (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('location_day_saved_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Max Delivery Time Accepted'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_max_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Desired Delivery Time'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_desired_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Location'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="location_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Location'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
<?php else : ?>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Add a New Delivery Location'/*]*/); ?></legend>
			<form action="" method="post">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Location Name'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<input class="form-control input-sm" type="text" value="" name="location_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Time (days)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('location_delivery_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Cost / Day saved (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<?php SD_Theme::inp ('location_day_saved_cost', '', 'percent', '%'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Max Delivery Time Accepted'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_max_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
						<label><?php SD_Theme::_e (/*T[*/'Desired Delivery Time'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<?php SD_Theme::inp ('location_desired_delivery_time', '', 'integer', __(/*T[*/'days'/*]*/)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Location'/*]*/); ?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-right">
						<button class="btn btn-sm btn-block btn-success" name="location_create"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Location'/*]*/); ?></button>
					</div>
				</div>
			</form>
		</fieldset>
		<fieldset>
			<legend><?php SD_Theme::_e (/*T[*/'Available Locations'/*]*/); ?></legend>
<?php	foreach ($locations->get () as $location) : ?>
			<form action="" method="post">
				<input type="hidden" name="location" value="<?php $location->out (); ?>" />
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 btn-left">
						<input class="form-control input-sm" type="text" name="location_name" value="<?php $location->out ('name'); ?>" />
					</div>
					<div class="col-lg-3">
						<a class="btn btn-sm btn-block btn-info sd-update"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Edit'/*]*/); ?></a>
					</div>
					<div class="col-lg-3 btn-right">
						<a class="btn btn-sm btn-block btn-danger sd-delete"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
					</div>
				</div>
				<div class="sd-read">
					<?php SD_Theme::_e (/*T[*/'Delivery Time'/*]*/); ?>: <?php $location->out ('delivery_time'); ?> <?php SD_Theme::_e (/*T[*/'days'/*]*/); ?> / 
					<?php SD_Theme::_e (/*T[*/'Delivery Cost'/*]*/); ?>: <?php $location->out ('delivery_cost'); ?>% / 
					<?php SD_Theme::_e (/*T[*/'Cost / Day Saved'/*]*/); ?>: <?php $location->out ('day_saved_cost'); ?>%
				</div>
				<div class="sd-update hidden">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Time (days)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
							<?php SD_Theme::inp ('location_delivery_time', $location->get ('delivery_time'), 'integer', __(/*T[*/'days'/*]*/)); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-9">
							<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-3">
							<?php SD_Theme::inp ('location_delivery_cost', $location->get ('delivery_cost'), 'percent', '%'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-9">
							<label><?php SD_Theme::_e (/*T[*/'Cost / Day saved (% of Unit Cost)'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-3">
							<?php SD_Theme::inp ('location_day_saved_cost', $location->get ('day_saved_cost'), 'percent', '%'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<label><?php SD_Theme::_e (/*T[*/'Max Delivery Time Accepted'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
							<?php SD_Theme::inp ('location_max_delivery_time', $location->get ('max_delivery_time'), 'integer', __(/*T[*/'days'/*]*/)); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<label><?php SD_Theme::_e (/*T[*/'Desired Delivery Time'/*]*/); ?>:<?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
							<?php SD_Theme::inp ('location_desired_delivery_time', $location->get ('desired_delivery_time'), 'integer', __(/*T[*/'days'/*]*/)); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<a href="" class="btn btn-sm btn-block btn-danger sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<button class="btn btn-sm btn-block btn-success" name="location_update"><i class="fui-check"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
						</div>
					</div>
				</div>
				<div class="sd-delete hidden">
					<p><? SD_Theme::_e (/*T[*/'Are you sure you want to delete this location?'/*]*/); ?></p>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<a href="" class="btn btn-sm btn-block btn-success sd-cancel"><i class="fui-cross"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Delete'/*]*/); ?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<button class="btn btn-sm btn-block btn-danger" name="location_delete"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete Location'/*]*/); ?></button>
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
