<?php
define ('WP_USE_THEMES', false);
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp-blog-header.php');
include (dirname(dirname(__FILE__)) . '/data/vehicle_opts.php');

$client = rca_connect ();

foreach ($vehicle_opts as $cat => $subcat) {
	foreach ($subcat as $sub => $name) {
		echo "$cat -> $sub \n";
		$query = (object) array (
			'tip_inmatriculare' => 'inregistrat',
			'categorie' => $cat,
			'subcategorie' => $sub
			);
		$marci = $client->get_marci ($query);

		print_r ($marci);
		}
	}

#$marci = $client->get_marci ();



/*
$out = array ();
foreach ($societati as $key => $value) {
	if (is_numeric ($key))
		$out[] = sprintf ("%d => '%s'", $key, $value);
	else
		$out[] = sprintf ("'%s' => '%s'", $key, $value);
	}
$out = '$companies = array (' . "\n\t" . implode (",\n\t", $out) . "\n);\n";
*/
?>
