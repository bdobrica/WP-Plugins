<?php
/*
Name: Locations
Parent: loadsave
Order: 9
*/
if (is_null ($sd_theme->get ('scenario'))) :
	include (dirname (__DIR__) . DIRECTORY_SEPARATOR . SD_Theme::COMMON_DIR . DIRECTORY_SEPARATOR . 'choose-scenario.php');
	return;
endif;
if (isset ($_GET[SD_Location::GET])) :
	try {
		$location = new SD_Location ($sd_theme->get ('scenario', 'path'), SD_Theme::r (SD_Location::GET));
		}
	catch (SD_Exception $e) {
		$location = null;
		}
endif;
if (!isset ($location) || is_null ($location)) :
	$locations = new SD_List ('SD_Location');
	if ($locations->is ('empty') || ($sd_theme->get ('action') == 'create')) : ?>
	<form action="" method="post">
		<div class="row">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-6">
						<label><?php SD_Theme::_e (/*T[*/'Location Name:'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-6">
						<input class="form-control" type="text" value="" name="location_name" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Time (days):'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input class="form-control" type="text" value="" name="delivery_time" />
							<div class="input-group-btn">
								<span class="btn">days</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost (% of Unit Cost):'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input class="form-control" type="text" value="" name="delivery_cost" />
							<div class="input-group-btn">
								<span class="btn">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<label><?php SD_Theme::_e (/*T[*/'Cost / Day saved (% of Unit Cost):'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-3">
						<div class="input-group">
							<input class="form-control" type="text" value="" name="day_saved_cost" />
							<div class="input-group-btn">
								<span class="btn">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<label><?php SD_Theme::_e (/*T[*/'Max Delivery Time Accepted:'/*]*/); ?><?php SD_Theme::_h (__FILE__, __LINE__); ?></label>
					</div>
					<div class="col-lg-4">
						<div class="input-group">
							<input class="form-control" type="text" value="" name="max_delivery_time" />
							<div class="input-group-btn">
								<span class="btn">days</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Location'/*]*/); ?></a>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-sm btn-block btn-success"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Add Location'/*]*/); ?></button>
			</div>
		</div>
	</form>
<?php	else : ?>
	<div class="row">
<?php		foreach ($locations->get () as $location) : ?>
<?php		endforeach; ?>
		<div class="col-lg-3">
			<div class="tile">
				<h3 class="tile-title"><?php echo $location->get ('name'); ?></h3>
				<p><small><?php SD_Theme::_e (/*T[*/'Standard Delivery Time:'/*]*/); ?> <?php echo $location->get ('delivery_time'); ?></small></p>
				<p><small><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost:'/*]*/); ?> <?php echo $location->get ('delivery_time'); ?></small></p>
				<p><small><?php SD_Theme::_e (/*T[*/'Cost / Day saved:'/*]*/); ?> <?php echo $location->get ('delivery_time'); ?></small></p>
				<div class="row">
					<div class="col-lg-6">
						<a href="<?php echo $location->get ('url', 'delete'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Delete'/*]*/); ?></a>
					</div>
					<div class="col-lg-6">
						<a href="<?php echo $location->get ('url', 'update'); ?>" class="btn btn-sm btn-block btn-info"><i class="fui-gear"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Edit'/*]*/); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php	endif; ?>
<?php else : ?>
	<form action="" method="post">
		<div class="row">
			<div class="col-lg-6">
				<label><?php SD_Theme::_e (/*T[*/'Location Name:'/*]*/); ?></label>
				<input class="form-control" type="text" value="<?php echo $location->get ('name'); ?>" name="location_name" />
				<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Time (days):'/*]*/); ?></label>
				<input class="form-control" type="text" value="<?php echo $location->get ('delivery_time'); ?>" name="delivery_time" />
				<label><?php SD_Theme::_e (/*T[*/'Standard Delivery Cost (% of Unit Cost):'/*]*/); ?></label>
				<input class="form-control" type="text" value="<?php echo $location->get ('delivery_cost'); ?>" name="delivery_cost" />
				<label><?php SD_Theme::_e (/*T[*/'Cost / Day saved (% of Unit Cost):'/*]*/); ?></label>
				<input class="form-control" type="text" value="<?php echo $location->get ('day_saved_cost'); ?>" name="day_saved_cost" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<a href="<?php echo $sd_theme->get ('url'); ?>" class="btn btn-sm btn-block btn-danger"><i class="fui-trash"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Cancel Changes'/*]*/); ?></a>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-sm btn-block btn-success"><i class="fui-plus"></i>&nbsp;<?php SD_Theme::_e (/*T[*/'Save Changes'/*]*/); ?></button>
			</div>
		</div>
	</form>
<?php endif; ?>
