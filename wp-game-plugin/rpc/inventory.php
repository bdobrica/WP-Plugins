<?php
/**
 * Core of SD_*
 */

/**
 * Timer. Handles timer events.
 *
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 */
define ('WP_USE_THEMES', FALSE);

include (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-blog-header.php');
/**
 * Hack in order to allow ajax calls. Otherwise, ajax fails with error.
 */
header ('HTTP/1.1 200 OK');

$sd_user = $sd_theme->get ('user');
$products = new SD_List ('SD_Product');
?>
<?php	if (!$products->is ('empty')) : ?>
				<ul class="nav nav-tabs nav-append-content">
<?php
		$first = TRUE;
		foreach ($products->get () as $product) : ?>
					<li<?php if ($first) echo ' class="active"'; ?>><a href="#<?php $product->out (); ?>"><?php $product->out ('name'); ?></a></li>
<?php			$first = FALSE;
		endforeach; ?>
				</ul>
<?php	endif; ?>
<?php	if (!$products->is ('empty')) : ?>
				<div class="tab-content">
<?php		$first = TRUE;
		foreach ($products->get () as $product) :
			$qualities = $product->get ('quality'); ?>
					<div id="<?php $product->out (); ?>" class="tab-pane<?php if ($first) echo ' active'; ?>">
						<div class="table-responsive sd-table">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Quality</th>
<?php			foreach ($qualities as $quality_slug => $quality_data) : ?>
										<th><?php echo $quality_data['name']; ?></th>
<?php			endforeach; ?>
									</tr>
								</thead>
								<tbody>
<?php			foreach (SD_Product::$A as $key => $name) :
				$computed = $sd_user->compute ($product, $key); ?>
									<tr>
										<th><?php SD_Theme::_e ($name); ?></th>
<?php				foreach ($computed as $quality_slug => $value) : ?>
										<td><?php echo $value; ?></td>
<?php				endforeach;
				unset ($computed); ?>
									</tr>
<?php			endforeach; ?>
								</tbody>
							</table>
						</div>
						<div class="table-responsive sd-table-split-4">
							<table class="table table-striped table-hover">
								<thead>
<?php	foreach (SD_Player::$A as $key => $name) : ?>
									<th><?php SD_Theme::_e ($name); ?></th>
<?php	endforeach; ?>
								</thead>
								<tbody>
<?php	foreach (SD_Player::$A as $key => $name) : ?>
									<td><?php echo $sd_user->compute ($key); ?></td>
<?php	endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
<?php		$first = FALSE;
		endforeach; ?>
				</div>
<?php	endif; ?>
