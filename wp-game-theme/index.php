<?php
ini_set ('display_errors', 'on');
$header_file = $sd_theme->get ('dir', 'request::header');

$sd_user = $sd_theme->get ('user');
$user_role = $sd_theme->get ('user', 'role');

if ($user_role == 'trainer' && (isset ($_GET[SD_Scenario::GET]))) {
	$error = null;

	try {
		$scenario = new SD_Scenario ($_GET[SD_Scenario::GET]);
		$sd_theme->set ('scenario', $scenario);
		}
	catch (SD_Exception $e) {
		$scenario = null;
		$error = ['error_read' => $e->get ('code')];
		}

	if (is_null ($error))
		$_GET = isset ($_GET[SD_Theme::GET]) ? [ 'page' => $_GET[SD_Theme::GET] ] : [];
	SD_Theme::prg ($error);
	}

if ($user_role == 'trainer') :
	$games = new SD_List ('SD_Game', ['active=1', sprintf ('owner=%d', $sd_user->get ())]);
	if ($games->is ('empty'))
		$sd_game = null;
	else
		$sd_game = $games->get ('last');
endif;
if ($user_role == 'player') :
	#$sd_game = new SD_Game ((int) $sd_user->get ('game'));
	$sd_game = $sd_user->get ('game');
endif;

if (file_exists ($header_file))
	include ($header_file);

get_header();
?>
<div class="container <?php echo !empty ($user_role) ? 'sd-' . $user_role : ''; ?>">
<?php
$scenario = $sd_theme->get ('scenario');

if ($user_role == 'trainer'):
	$sd_theme->render ('menu');

?>
	<div class="sd-rounded sd-padded sd-translucent">
		<div class="row">
			<div class="col-lg-6">
				<?php $sd_theme->render ('breadcrumbs'); ?>
			</div>
			<div class="col-lg-6">
				<span class="pull-right">
				<?php	$breadcrumbs = $sd_theme->get ('breadcrumbs');
					if (is_array ($breadcrumbs) && in_array ('scenario', array_keys ($breadcrumbs)) && !is_null ($scenario)) : ?>
					<span class="label label-success">
					<?php SD_Theme::_e (/*T[*/'The working scenario is: '/*]*/);
			                echo $scenario->get ('name'); ?>
					</span>
				<?php endif; ?>
				</span>
			</div>
		</div>
<?php $sd_theme->render ('header'); ?>
		<hr />
<?php
	$page_file = $sd_theme->get ('dir', 'request::pages');
	if (file_exists ($page_file)) :
		include ($page_file);
	endif;
?>
	</div>
</div>
<?php
elseif ($user_role == 'player'):
	$sd_theme->render ('player'); ?>

<?php	$page_file = $sd_theme->get ('dir', 'request::pages');
	#echo $page_file;
	if (file_exists ($page_file)) :
		include ($page_file);
	endif; ?>
<?php else:
	$page_file = $sd_theme->get ('dir', 'request::pages');
	if (file_exists ($page_file)) :
		include ($page_file);
	endif;
endif;
get_footer();
?>
