<?php
/*
Name: Trainer Report
Hidden: true
*/


if (is_null ($sd_game))
	die (/*T[*/'Error'/*]*/);

$players = new SD_List ('SD_Player', [sprintf ('game=%d', $sd_game->get ())]);
if ($players->is ('empty'))
	die (/*T[*/'Error'/*]*/);

$report = [
	'game_name'	=> $sd_game->get ('name'),
	'game_date'	=> $sd_game->get ('date')
	];

$scores = [];
$finals = [];
$conversations = [];
$hints = [];
$products = [];
$overview = [];

foreach ($players->get () as $player) {
	$data = $player->report_data ();

	$scores[]		= $data['scores'];
	$finals[]		= $data['finals'];
	$conversations[]	= $data['conversations'];
	$hints[]		= $data['hints'];
	$products[]		= $data['products']; //array_merge ($products, $data['products']);
	$overview[]		= $data['overview'];
	}


#var_dump ($scores);
#var_dump ($finals);
#var_dump ($conversations);
#var_dump ($hints);
var_dump ($products);
#var_dump ($overview);
?>
