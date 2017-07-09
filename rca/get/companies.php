<?php
define ('WP_USE_THEMES', false);
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp-blog-header.php');

$client = rca_connect ();

$societati = $client->get_societati ();

$out = array ();
foreach ($societati as $key => $value) {
	if (is_numeric ($key))
		$out[] = sprintf ("%d => '%s'", $key, $value);
	else
		$out[] = sprintf ("'%s' => '%s'", $key, $value);
	}
$out = '$companies = array (' . "\n\t" . implode (",\n\t", $out) . "\n);\n";

$file = dirname (dirname (__FILE__)) . '/data/companies.php';

file_put_contents ($file, sprintf ("<?php\n#retrieved on %s\n%s?>", date ('d-m-Y H:i:s'), $out));
?>
