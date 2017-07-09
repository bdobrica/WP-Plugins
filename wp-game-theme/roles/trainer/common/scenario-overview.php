<?php SD_Theme::_i (__FILE__, __LINE__); ?>
<fieldset>
	<legend><?php SD_Theme::_e (/*T[*/'Scenario Overview'/*]*/); ?></legend>
	<div class="progress">
		<div class="progress-bar progress-bar-warning" style="width: <?php $scenario->out ('readiness'); ?>%;"></div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Client'/*]*/); ?>:<a href="?page=client"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Characters'/*]*/); ?>:<a href="?page=npc"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
<?php $common_characters = new SD_List ('SD_Character');
if ($common_characters->is ('empty')) :
else :
	$common_characters->sort ();
	foreach ($common_characters->get () as $common_character) : ?>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
			<img class="img-rounded img-responsive" src="<?php $common_character->out ('image', 'neutral'); ?>" alt="" title="" />
		</div>
<?php	endforeach;
endif; ?>
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Products'/*]*/); ?>:<a href="?page=products"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
<?php $common_products = new SD_List ('SD_Product');
if ($common_products->is ('empty')) :
else : ?>
	<ul>
<?php
	foreach ($common_products->get () as $common_product) : ?>
		<li><i class="fui-radio-unchecked"></i> <?php $common_product->out ('name'); ?></li>
<?php	endforeach; ?>
	</ul>
<?php endif; ?>
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Warranty'/*]*/); ?>:<a href="?page=warranty"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
<?php $common_warranties = new SD_List ('SD_Warranty');
if ($common_warranties->is ('empty')) :
else : ?>
		<ul>
<?php	foreach ($common_warranties->get () as $common_warranty) : ?>
			<li><i class="fui-radio-unchecked"></i> <?php $common_warranty->out ('name'); ?></li>
<?php	endforeach; ?>
		</ul>
<?php endif; ?>
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Features'/*]*/); ?>:<a href="?page=features"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
<?php $common_features = new SD_List ('SD_Feature');
if ($common_features->is ('empty')) :
else : ?>
		<ul>
<?php	foreach ($common_features->get () as $common_feature) : ?>
			<li><i class="fui-radio-unchecked"></i> <?php $common_feature->out ('name'); ?></li>
<?php	endforeach; ?>
		</ul>
<?php endif; ?>
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Locations'/*]*/); ?>:<a href="?page=locations"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
<?php $common_locations = new SD_List ('SD_Location');
if ($common_locations->is ('empty')) :
else : ?>
		<ul>
<?php	foreach ($common_locations->get () as $common_location) : ?>
			<li><i class="fui-radio-unchecked"></i> <?php $common_location->out ('name'); ?></li>
<?php	endforeach; ?>
		</ul>
<?php endif; ?>
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Negotiation'/*]*/); ?>:<a href="?page=negotiations"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Time Limits'/*]*/); ?>:<a href="?page=timing"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
		</div>
	</div>
	<div class="sd-section">
		<div class="sd-section-head"><?php SD_Theme::_e (/*T[*/'Other Parameters'/*]*/); ?>:<a href="?page=other-parameters"><?php SD_Theme::_e (/*T[*/'Modify'/*]*/); ?></a></div>
		<div class="sd-section-body">
		</div>
	</div>
</fieldset>
