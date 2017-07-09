<?php
/*
Plugin Name: RCA
Plugin URI: http://postasig.ro
Description:
Author: Bogdan Dobrica
Version: 0.1
Author URI: http://ublo.ro/
*/
ini_set ('display_errors', TRUE);
require_once (dirname (__FILE__) . '/card/mobilpay.php');

function rca_mail ($to, $subject, $message, $attachments = array ()) {
	global $rca_settings;

	if (empty ($rca_settings)) $rca_settings = get_option ('rca_settings');

	if (!class_exists ('PHPMailer')) require_once ABSPATH . WPINC . '/class-phpmailer.php';
	if (!class_exists ('SMTP')) require_once ABSPATH . WPINC . '/class-smtp.php';


	$interface = new PHPMailer (true);
	$interface->IsSMTP ();
	$interface->Host	= $rca_settings['smtphost'];
	$interface->SMTDebug	= true;
	$interface->SMTPAuth	= true;
	$interface->SMTPSecure	= $rca_settings['smtpssl'] ? 'ssl' : '';
	$interface->Port	= $rca_settings['smtpport'];
	$interface->Username	= $rca_settings['smtpmail'];
	$interface->Password	= $rca_settings['smtppass'];
	$interface->SetFrom ($rca_settings['smtpmail'], $rca_settings['smtpname']);
	$interface->AddReplyTo ($rca_settings['smtpmail'], $rca_settings['smtpname']);

	if (is_string ($to))
		$interface->AddAddress ($to);
	else
	if (is_array ($to))
		foreach ($to as $address)
			$interface->AddAddress ($address);

	$interface->Subject	= $subject;
	$interface->MsgHTML ($message);

	if (is_array ($attachments) && !empty ($attachments))
	foreach ($attachments as $path => $name) {
		$interface->AddAttachment ($path, $name);
		}
	
	try {
		$interface->Send ();
		}
	catch (phpmailerException $e) {
		var_dump ($e);
		}

	$interface->ClearAddresses ();
	}

function rca_form ($fields, $wrap = null) {
	$out = array ();
	$wrap = !is_null ($wrap) ? :
		"\t<div class=\"form-row %s\" rel=\"%s\">\n\t\t<div class=\"form-label\">\n\t\t\t%s\n\t\t</div>\n\t\t<div class=\"form-item\">\n\t\t\t%s\n\t\t</div>\n\t<div class=\"form-hint-open\"></div><div class=\"form-hint\"><div class=\"form-hint-wrap\">%s</div></div><div class=\"clearfix\"></div></div>\n";

	foreach ($fields as $key => $options) {
		$class = $options['class'];
		$rel = $options['rel'];
		$label = '<label>' . $options['label'] . '</label>';

		switch ((string) $options['type']) {
			case 'select':
				$field_options = array ();
				if (!empty ($options['first']))
					$field_options[] = sprintf ("\t\t\t\t<option value=\"%s\"%s>%s</option>\n",
						$options['first']['key'],
						$options['first']['key'] == $options['default'] ? ' default' : '',
						$options['first']['value']
						);
				if (!empty ($options['options']))
				foreach ($options['options'] as $_k => $_v) {
					$field_options[] = sprintf ("\t\t\t\t<option value=\"%s\"%s>%s</option>\n",
						$_k,
						$_k == $options['default'] ? ' default' : '',
						$_v
						);
					}
				if (!empty ($options['other']))
					$field_options[] = sprintf ("\t\t\t\t<option value=\"%s\"%s>%s</option>\n",
						$options['other']['key'],
						$options['other']['key'] == $options['default'] ? ' default' : '',
						$options['other']['value']
						);

				$field = sprintf ("\t\t\t<select id=\"rca-%s\" name=\"%s\" data-depmap=\"%s\" data-validate=\"%s\" data-required=\"%d\">\n%s\n\t\t\t</select>\n",
					$key,
					$key,
					isset ($options['depmap']) ? str_replace ('"', '&quot;', json_encode ($options['depmap'])) : '',
					isset ($options['validate']) ? str_replace ('"', '&quot;', json_encode ($options['validate'])) : '',
					isset ($options['required']) ? 1 : 0,
					implode ('', $field_options)
					);
				break;
			case 'date':
				$field = sprintf ("\t\t\t<input id=\"rca-%s\" class=\"rca-form-date\" type=\"text\" name=\"%s\" value=\"%s\" />",
					$key,
					$key,
					$options['default']
					);
				break;
			case 'integer':
				$field = sprintf ("\t\t\t<input id=\"rca-%s\" type=\"text\" name=\"%s\" data-validate=\"%s\" data-required=\"%d\" value=\"%s\" />",
					$key,
					$key,
					isset ($options['validate']) ? str_replace ('"', '&quot;', json_encode ($options['validate'])) : '',
					isset ($options['required']) ? 1 : 0,
					$options['default']
					);
				break;
			case 'string':
				$field = sprintf ("\t\t\t<input id=\"rca-%s\" type=\"text\" name=\"%s\" data-validate=\"%s\" data-required=\"%d\" value=\"%s\" />",
					$key,
					$key,
					isset ($options['validate']) ? str_replace ('"', '&quot;', json_encode ($options['validate'])) : '',
					isset ($options['required']) ? 1 : 0,
					$options['default']
					);
				break;
			case 'registration':
				$field = sprintf ("\t\t\t<input id=\"rca-%s\" type=\"text\" name=\"%sa\" value=\"%s\" data-validate=\"%s\" data-required=\"%d\" class=\"rca-form-regno-a\"/> - <input id=\"rca-%s\" type=\"text\" name=\"%sb\" value=\"%s\" data-validate=\"%s\" data-required=\"%d\" class=\"rca-form-regno-b\" /> - <input id=\"rca-%s\" type=\"text\" name=\"%sc\" value=\"%s\" data-validate=\"%s\" data-required=\"%d\" class=\"rca-form-regno-c\" />",
					$key,
					$key,
					$options['default'],
					str_replace ('"', '&quot;', json_encode (array (
						array (
							'type' => 'inarray',
							'data' => array (
								'AB', 'AR', 'AG', 'B', 'BC', 'BH', 'BN', 'BT', 'BV', 'BR', 'BZ', 'CS', 'CL', 'CJ', 'CT', 'CV', 'DB', 'DJ', 'GL', 'GR', 'GJ', 'HR', 'HD', 'IL', 'IS', 'IF', 'MM', 'MH', 'MS', 'NT', 'OT', 'PH', 'SM', 'SJ', 'SB', 'SV', 'TR', 'TM', 'TL', 'VS', 'VL', 'VN'
								),
							'head' => 'Numarul de inmatriculare nu este valid!',
							'text' => '%s'
							)
						))),
					isset ($options['required']) ? 1 : 0,
					$key,
					$key,
					$options['default'],
					str_replace ('"', '&quot;', json_encode (array (
						array (
							'type' => 'number',
							'data' => array (
								'min' => 1,
								'max' => 999
								),
							'head' => 'Numarul de inmatriculare nu este valid!',
							'text' => '%s'
							)
						))),
					isset ($options['required']) ? 1 : 0,
					$key,
					$key,
					$options['default'],
					str_replace ('"', '&quot;', json_encode (array (
						array (
							'type' => 'string',
							'data' => array (
								'lenmin' => 3,
								'lenmax' => 3
								),
							'head' => 'Numarul de inmatriculare nu este valid!',
							'text' => '%s'
							)
						))),
					isset ($options['required']) ? 1 : 0
					);
				break;
			case 'year':
				$field_options = array ();
				for ($year = $options['start']; $year > $options['end']; $year --) {
					$field_options[] = sprintf ("\t\t\t\t<option value=\"%d\"%d>%d</option>\n",
						$year,
						$year == $options['default'] ? ' default' : '',
						$year
						);
					}
				$field = sprintf ("\t\t\t<select id=\"rca-%s\" name=\"%s\">\n%s\n\t\t\t</select>\n",
					$key,
					$key,
					implode ('', $field_options)
					);
				break;
			}

		$hint = $options['hint'];

		$out[] = sprintf ($wrap, $class, $rel, $label, $field, $hint);
		}
	return implode ('', $out);
	}

function rca_car_form () {
	include (dirname (__FILE__) . '/data/vehicle.php');
	include (dirname (__FILE__) . '/data/vehicle_opts.php');
	include (dirname (__FILE__) . '/data/utility.php');
	include (dirname (__FILE__) . '/data/fuel.php');
	include (dirname (__FILE__) . '/data/all_manufacturers.php');

	$fields = array (
		'state' => array (
			'label' => 'Stare vehicul:',
			'hint' => '<h4>Starea vehiculului</h4>In cazul in care masina nu este inca pe numele tau, bifeaza optiunea &quot;In vederea inmatricularii&quot;. In cazul in care alegi &quot;In vederea inmatricularii&quot;, la emiterea politei, societatea de asigurare solicita contractul de vanzare-cumparare si dovada inregistrarii fiscale pe numele noului proprietar.',
			'type' => 'select',
			'default' => '',
			'options' => array (
				'inmatriculat' => 'Inmatriculat',
				'in vederea inmatricularii' => 'In vederea inmatricularii',
				'inregistrat' => 'Inregistrat la primarie',
				'in vederea inregistrarii' => 'In vederea inregistrarii'
				),
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege starea vehiculului ...'
				),
			),
		'start' => array (
			'label' => 'Vreau RCA valabil de la:',
			'hint' => '<h4>Data intrarii in vigoare a politei</h4>Politele de asigurare RCA pot fi emise cu cel mult 30 de zile inainte de data intrarii in vigoare.',
			'type' => 'date',
			'default' => date ('d-m-Y', strtotime ('tomorrow')),
			'required' => 1
			),
		'vehicle' => array (
			'label' => 'Categoria de vehicul:',
			'hint' => '<h4>Categoria de vehicul</h4>Foloseste lista pentru a alege categoria generala de vehiculul.',
			'rel' => '183,55,100,71,278,130',
			'class' => 'form-auto-hint',
			'type' => 'select',
			'default' => '',
			'options' => $vehicle,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege categoria de vehicul ...'
				)
			),
		'vehicleopts' => array (
			'label' => 'Tipul vehiculului:',
			'type' => 'select',
			'hint' => '<h4>Tipul vehiculului</h4>Numai dupa ce ai ales <strong>Categoria de vehicul</strong> vei putea alege mai exact tipul vehiculului pe care iti doresti sa-l asiguri.',
			'rel' => '183,55,100,71,278,130',
			'class' => 'form-auto-hint',
			'default' => '',
			'options' => $all_vehicle_opts,
			'depmap' => $vehicle_opts_map,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege tipul vehiculului ...'
				)
			),
		'utility' => array (
			'label' => 'Utilizare:',
			'type' => 'select',
			'hint' => '<h4>Utilizare</h4>Alege din lista modul in care utilizezi vehiculul. In cazul in care nu stii ce sa alegi, selecteza <strong>personal</strong>, daca esti proprietar persoana fizica, sau <strong>masina de serviciu</strong> daca proprietarul este o persoana juridica.',
			'default' => '',
			'options' => $utility,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege modul de utilizare al vehiculului ...'
				)
			),
		'fuel' => array (
			'label' => 'Combustibil:',
			'hint' => '<h4>Combustibil</h4>Selecteaza din lista modul in care este alimentat vehiculul pe care iti doresti sa-l asiguri. Gasesti informatia in campul P.3 pentru talonul model nou, sau in campul 17 pentru talonul model vechi.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/fuel-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/fuel-old.jpg" /></div>
',
			'rel' => '415,68,84,454,280,80',
			'class' => 'form-auto-hint',
			'type' => 'select',
			'default' => '',
			'options' => $fuel,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege combustibilul vehiculului ...'
				)
			),
		'manufacturer' => array (
			'label' => 'Marca:',
			'hint' => '<h4>Marca Auto</h4>Marca o gasiti la pozitia 8 in talonul vechi sau la pozitia D.1 in taloanele noi.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/manufacturer-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/manufacturer-old.jpg" /></div>
',
			'rel' => '212,53,60,87,282,110',
			'class' => 'form-auto-hint',
			'type' => 'select',
			'default' => '',
			'options' => $all_manufacturers,
			'first' => array (
				'key' => -1,
				'value' => 'Alege marca ...'
				),
			'other' => array (
				'key' => '-2',
				'value' => 'MARCA NU E LISTATA:'
				),
			'required' => 1
			),
		'model' => array (
			'label' => 'Model:',
			'hint' => '<h4>Modelul Auto</h4>Modelul il gasiti la pozitia 9 in taloanele vechi sau pozitia D.3 in taloanele noi.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/model-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/model-old.jpg" /></div>
',
			'rel' => '197,54,140,119,282,50',
			'class' => 'form-auto-hint',
			'type' => 'select',
			'default' => '',
			'options' => array (
				),
			'first' => array (
				'key' => -1,
				'value' => 'Alege modelul ...'
				),
			'other' => array (
				'key' => '-2',
				'value' => 'MODELUL NU E LISTAT:'
				),
			'required' => 1
			),
		'cylinder' => array (
			'label' => 'Capacitate cilindrica:',
			'hint' => '<h4>Capacitatea Cilindrica (cm<sup>3</sup>)</h4>Capacitatea cilindrica a motorului o gasiti la pozitia 17 in taloanele vechi si la pozitia P.1 in taloanele noi.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/cylinder-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/cylinder-old.jpg" /></div>
',
			'rel' => '401,214,30,439,280,30',
			'class' => 'form-auto-hint',
			'type' => 'integer',
			'default' => '',
			'validate' => array (
				'type' => 'number',
				'data' => array (
					'min' => 1,
					'max' => 10000
					),
				'head' => 'Capacitate cilindrica incorecta!',
				'text' => 'Atentie! %s nu pare sa fie o capacitate cilindrica reala.'
				),
			'required' => 1
			),
		'power' => array (
			'label' => 'Putere motor (kW):',
			'hint' => '<h4>Puterea (kW)</h4>Puterea motorului o gasiti la pozitia 17 in taloanele vechi sau la pozitia P.2 in taloanele noi. Puteti calcula puterea in kW inmultind numarul cailor putere (CP) cu 0.733.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/power-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/power-old.jpg" /></div>
',
			'rel' => '416,210,30,439,394,30',
			'class' => 'form-auto-hint',
			'type' => 'integer',
			'default' => '',
			'validate' => array (
				'type' => 'number',
				'data' => array (
					'min' => 1,
					'max' => 2000
					),
				'head' => 'Puterea motorului incorecta!',
				'text' => 'Atentie! %s kW nu pare sa fie puterea corecta a motorului.'
				),
			'required' => 1
			),
		'vin' => array (
			'label' => 'Serie sasiu:',
			'hint' => '<h4>Numarul de identificare (serie sasiu)</h4> Numarul de identificare il gasiti la pozitia 3 in taloanele vechi sau la pozitia E in taloanele noi. Acesta este solicitat de catre companiile de asigurare, care au obligatia legala sa verifice numarul de daune, aflat cu ajutorul CNP-ului si a seriei de sasiu prin interogarea bazei de date a CSA / CEDAM.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/vin-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/vin-old.jpg" /></div>
',
			'rel' => '77,67,180,136,282,160',
			'class' => 'form-auto-hint',
			'type' => 'string',
			'default' => '',
			'options' => array (
				),
			'required' => 1
			),
		'regno' => array (
			'label' => 'Numar de inmatriculare:',
			'hint' => '<h4>Numarul de inmatriculare</h4>Numarul de inmatriculare il gasiti la pozitia 1 in taloanele vechi sau pozitia A in taloanele noi.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/regno-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/regno-old.jpg" /></div>
',
			'rel' => '48,74,100,55,278,100',
			'class' => 'form-auto-hint',
			'type' => 'registration',
			'default' => '',
			'required' => 1
			),
		);
	return rca_form ($fields);
	}

function rca_person_form () {
	include (dirname (__FILE__) . '/data/counties.php');
	$fields = array (
		'pfirstname' => array (
			'label' => 'Prenume (Nume de botez):',
			'hint' => '<h4>Prenume</h4>Completeaza prenumele (numele mic, numele de botez) al proprietarului vehiculului, asa cum apare el in sectiunea 5 din talonul vechi, sau in sectiunea C.2.2 din talonul nou.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/first-name-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/person-old.jpg" /></div>
',
			'type' => 'string',
			'default' => '',
			'required' => 1
			),
		'plastname' => array (
			'label' => 'Nume (Nume de familie):',
			'hint' => '<h4>Nume</h4>Completeaza numele (numele de familie) al proprietarului vehiculului, asa cum apare el in sectiunea 5 din talonul vechi, sau in sectiunea C.2.1 din talonul nou.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/last-name-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/person-old.jpg" /></div>
',
			'type' => 'string',
			'default' => '',
			'required' => 1
			),
		'pcounty' => array (
			'label' => 'Judet:',
			'hint' => '<h4>Judet</h4>Alege din lista judetul in care este inmatriculat vehiculul, asa cum apare el in sectiunea 5 din talonul vechi, sau in sectiunea C.2.3 din talonul nou.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/person-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/person-old.jpg" /></div>
',
			'type' => 'select',
			'default' => '',
			'options' => $counties,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege judetul ...'
				)
			),
		'pcity' => array (
			'label' => 'Localitatea:',
			'hint' => '<h4>Localitatea</h4>Alege cu atentie localitatea in care este inmatriculat vehiculul. Poti selecta localitatea, numai dupa ce ai selectat in prealabil <strong>Judetul</strong> folosind campul anterior. Localitatea o gasesti in sectiunea 5 din talonul vechi, sau in sectiunea C.2.3 din talonul nou.
<div><strong>Talon Nou:</strong><img src="/wp-content/plugins/rca/assets/talon/person-new.jpg" /></div>
<div><strong>Talon Vechi:</strong><img src="/wp-content/plugins/rca/assets/talon/person-old.jpg" /></div>
',
			'type' => 'select',
			'default' => '',
			'options' => array (
				),
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege localitatea ...'
				)
			),
		'puin' => array (
			'label' => 'CNP:',
			'hint' => '<h4>Cod Numeric Personal</h4>Completeaza codul numeric personal al proprietarului vehiculului. Codul numeric personal este alcatuit din 13 cifre si poate fi gasit pe actul de identitate al proprietarului vehiculului',
			'type' => 'string',
			'default' => '',
			'options' => array (
				),
			'validate' => array (
				'type' => 'string',
				'data' => array (
					'lenmin' => 13,
					'lenmax' => 13,
					'regexp' => '^[0-9]+$'
					),
				'head' => 'Codul Numeric Personal nu este valid!',
				'text' => 'Codul Numeric Personal %s nu pare valid!',
				),
			'required' => 1
			),
		'pyear' => array (
			'label' => 'An obtinere permis:',
			'hint' => '<h4>An obtinere permis</h4>Te rugam sa selectezi anul in care ai obtinut permisul de conducere.',
			'type' => 'year',
			'start' => date('Y'),
			'end' => date('Y') - 100,
			'default' => date('Y'),
			'required' => 1
			),
		'pdiscount' => array (
			'label' => 'Reduceri sociale:',
			'hint' => '<h4>Reduceri sociale</h4>Verifica daca te incadrezi intr-una dintre categoriile enumerate pentru a beneficia de o reducere a politei de asigurare.',
			'type' => 'select',
			'default' => '',
			'options' => array (
				'' => 'Nu se aplica',
				'publicservant' => 'Bugetar',
				'retired' => 'Pensionar',
				'disabled' => 'Persoana cu dizabilitati'
				),
			'required' => 1
			),
		'pphone' => array (
			'label' => 'Telefon:',
			'hint' => '<h4>Telefon</h4>Completeaza numarul de telefon pentru a putea fi contactat de unul dintre reprezentantii nostri.',
			'type' => 'string',
			'required' => 1
			),
		'pemail' => array (
			'label' => 'Adresa de email:',
			'hint' => '<h4>Adresa de email</h4>Completeaza adresa de email pentru a putea fi contactat de unul dintre reprezentantii nostri.',
			'type' => 'string',
			'required' => 1
			)
		);

	return rca_form ($fields);
	}

function rca_company_form () {
	include (dirname (__FILE__) . '/data/counties.php');
	include (dirname (__FILE__) . '/data/caen.php');

	$fields = array (
		'cfirstname' => array (
			'label' => 'Prenumele persoanei de contact (Nume de botez):',
			'hint' => '<h4>Prenumele persoanei de contact</h4>Prenumele sau numele mic (de botez) al persoanei de contact care va comunica in numele companiei pentru achizitionarea politei.',
			'type' => 'string',
			'default' => '',
			'required' => 1
			),
		'clastname' => array (
			'label' => 'Numele persoanei de contact (Nume de familie):',
			'hint' => '<h4>Numele persoanei de contact</h4>Numele (de familie) al persoanei de contact care va comunica in numele companiei pentru achizitionarea politei.',
			'type' => 'string',
			'default' => '',
			'required' => 1
			),
		'ccompanyname' => array (
			'label' => 'Denumirea societatii:',
			'hint' => '<h4>Denumirea societatii</h4>Denumirea societatii care utilizeaza vehiculul, asa cum este el mentionat in sectiunea 5 a talonului vechi sau in sectiunea C.2 a talonului nou.',
			'type' => 'string',
			'default' => '',
			'required' => 1
			),
		'ccounty' => array (
			'label' => 'Judet:',
			'hint' => '<h4>Judet</h4>Alege din lista judetul in care societatea care utilizeaza vehiculul isi are sediul social.',
			'type' => 'select',
			'default' => '',
			'options' => $counties,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege judetul ...'
				)
			),
		'ccity' => array (
			'label' => 'Localitatea:',
			'hint' => '<h4>Localitatea</h4>Alege din lista localitatea in care societatea care utilizeaza vehiculul isi are sediul social. Poti selecta localitatea numai dupa ce ai selectat in prealabil <strong>Judetul</strong>',
			'type' => 'select',
			'default' => '',
			'options' => array (
				),
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege localitatea ...'
				)
			),
		'cuin' => array (
			'label' => 'CUI:',
			'hint' => '<h4>Codul Unic de Identificare</h4>Completeaza codul unic de identificare fiscala al societatii (CUI, CIF, RO-ul societatii) care utilizeaza vehiculul.',
			'type' => 'string',
			'default' => '',
			'options' => array (
				),
			'required' => 1
			),
		'ctype' => array (
			'label' => 'Tipul societatii:',
			'hint' => '<h4>Tipul societatii</h4>Alege din lista tipul societatii care utilizeaza vehiculul.',
			'type' => 'select',
			'default' => '',
			'options' => array (
				'SC nefinanciare - P.F.A.' => 'P.F.A., Nefinanciara',
				'SC nefinanciare - P.F.I.' => 'P.F.I., Nefinanciara',
				'SC nefinanciare - S.R.L.' => 'S.R.L., Nefinanciara',
				'SC nefinanciare - S.A.' => 'S.A., Nefinanciara',
				'alte SC nefinanciare' => 'ONG',
				'banci si cooperative de credit' => 'Banci si Cooperative de Credit',
				'alti intermediari financiari' => 'Intermediari Financiari (non-Leasing)',
				'institutii guvernamentale' => 'Institutii Guvernamentale',
				'regii autonome' => 'Regii Autonome'
				# ONG
				# Institutie a statului
				), 
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege tipul societatii ...'
				)
			),
		'cphone' => array (
			'label' => 'Telefon:',
			'hint' => '<h4>Telefon</h4>Completeaza numarul de telefon prin care poate fi contactata persoana de contact responsabila de achizitia politei RCA.',
			'type' => 'string',
			'required' => 1
			),
		'cemail' => array (
			'label' => 'Adresa de email:',
			'hint' => '<h4>Adresa de email</h4>Completeaza adresa de email prin care poate fi contactata persoana de contact responsabila de achizitia politei RCA.',
			'type' => 'string',
			'required' => 1
			),
		'ccaen' => array (
			'label' => 'Cod CAEN:',
			'hint' => '<h4>Cod CAEN</h4>Selecteaza din lista obiectul principal de activitate al societatii care utilizeaza masina.',
			'type' => 'select',
			'default' => '',
			'options' => $caen,
			'required' => 1,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege codul CAEN ...'
				)
			)
		);

	return rca_form ($fields);
	}

function rca_leasing_form () {
	include (dirname (__FILE__) . '/data/leasing.php');
	$fields = array (
		'leasing' => array (
			'label' => 'Companie de leasing:',
			'type' => 'select',
			'hint' => '<h4>Companie de leasing:</h4>Selecteaza din lista compania care a furnizat vehiculul in leasing.',
			'default' => '',
			'options' => $leasing,
			'first' => array (
				'key' => '-1',
				'value' => 'Alege compania de leasing ...'
				)
			)
		);
	return rca_form ($fields);
	}

function rca_get_quotation ($atts, $content) {
	#return '<h2>In curs de actualizare!</h2><div>In acest moment ne consolidam platforma online de cotatii pentru asigurarile de tip RCA pentru a va oferi cele mai bune preturi. Va rugam reveniti!</div>';
	if ($_GET['orderId']) {
		$data = rca_getorder ((int) $_GET['orderId']);
		include (dirname (__FILE__) . '/data/mobilpay.php');

		if ((int) $data['ord_status'] == 2) {
			}

		return sprintf("<p style=\"text-align: center; font-size: 12pt;\">%s</p>", $mobilpay[(int) $data['ord_status']]);
		}
	if ($_POST['getinsurance']) {
		$data = array (
			'company' => $_POST['company'],
			'price' => $_POST['price'],
			'length' => $_POST['length'],
			'record' => $_POST['record'],
			'details' => array (
					'code' => $_POST['code'],
					'offer' => $_POST['offer']
					),
			'bonus' => $_POST['bonus']
			);

		$data = rca_choose ($data);

		if ($_POST['price'] != '--*')
			$payment = rca_mobilpay ($data);
		else {
			include (dirname (__FILE__) . '/data/insurers.php');
			return '<p>In acest moment nu am putut obtine o cotatie pentru ' . ($insurers[$data['ord_company']] ? : $data['ord_company']) . '. Te rugam sa inchizi aceasta fereastra si sa alegi alt asigurator.</p>';
			}

		return $payment;
		}
	if ($_POST['vin']) {
		include (dirname (__FILE__) . '/data/utility.php');
		include (dirname (__FILE__) . '/data/companies.php');
		include (dirname (__FILE__) . '/data/leasing.php');
		include (dirname (__FILE__) . '/data/manufacturers.php');
		include (dirname (__FILE__) . '/data/all_manufacturers.php');

		if ($_POST['manufacturer'] == -2) $_POST['manufacturer'] = null;
		if ($_POST['model'] == -2) $_POST['model'] = null;
	
		$vehicleopts = $_POST['vehicleopts'];
		$manufacturer_name = $all_manufacturers[$_POST['manufacturer']];
		$manufacturer_name = $manufacturer_name ? : $_POST['new_manufacturer'];

		$manufacturer_id = null;

		foreach ($manufacturers[$vehicleopts] as $key => $manufacturer) {
			if ($manufacturer[0] != $manufacturer_name) continue;
			$manufacturer_id = $key;
			break;
			}


		if (file_exists (dirname (__FILE__) . '/data/models/' . $vehicleopts . '_' . $manufacturer_id . '.php'))
			include (dirname (__FILE__) . '/data/models/' . $vehicleopts . '_' . $manufacturer_id . '.php');
		else
			$models = array ();
		$model = $models[$_POST['model']];
		$model_name = $model[0];

		$model_name = $model_name ? : $_POST['new_model'];

		$owner = strpos ($_POST['rcatype'], 'person') !== FALSE ? 'fizica' : 'juridica';
		$lease = strpos ($_POST['rcatype'], 'leasing') !== FALSE ? TRUE : FALSE;

		$client = rca_connect ();

		$date = explode ('-', $_POST['start']);

		$data = array (
			'company'			=> '',
			'companyname'			=> $_POST['ccompanyname'],
			'first_name'			=> $owner == 'fizica' ? $_POST['pfirstname'] : $_POST['cfirstname'],
			'last_name'			=> $owner == 'fizica' ? $_POST['plastname'] : $_POST['clastname'],
			'phone'				=> $owner == 'fizica' ? $_POST['pphone'] : $_POST['cphone'],
			'email'				=> $owner == 'fizica' ? $_POST['pemail'] : $_POST['cemail'],
			'regno'				=> sprintf ('%s-%s-%s', $_POST['regnoa'], $_POST['regnob'], $_POST['regnoc']),
			'vin'				=> $_POST['vin'],
			'state'				=> $_POST['state'],
			'vehicle'			=> $_POST['vehicle'],
			'vehicleopts'			=> $_POST['vehicleopts'],
			'manufacturer'			=> $manufacturer_name,
			'model'				=> $model_name,
			'model_id'			=> $_POST['model'],
			'caryear'			=> null,
			'cylinder'			=> $_POST['cylinder'],
			'power'				=> $_POST['power'],
			'mass'				=> $model[3],
			'seats'				=> $model[4],
			'fuel'				=> $_POST['fuel'],
			'utility'			=> $utility[$_POST['utility']],
			'carid'				=> null,
			
			'owner'				=> $owner,
			'uin'				=> $owner == 'fizica' ? $_POST['puin'] : $_POST['cuin'],
			'city'				=> $owner == 'fizica' ? $_POST['pcity'] : $_POST['ccity'],
			'county'			=> $owner == 'fizica' ? $_POST['pcounty'] : $_POST['ccounty'],
			'pyear'				=> $_POST['pyear'],
			
			'retired'			=> $_POST['pdiscount'] == 1 ? TRUE : FALSE,
			'disabled'			=> $_POST['pdiscount'] == 2 ? TRUE : FALSE,
			'publicservant'			=> $_POST['pdiscount'] == 3 ? TRUE : FALSE,

			'ctype'				=> $owner == 'fizica' ? '' : ($lease ? 'intermediari financiari - leasing' : $_POST['ctype']),

			'caen'				=> $_POST['caen'],
			
			'lease'				=> $lease,
		
			'date'				=> sprintf('%04d-%02d-%02d', $date[2], $date[1], $date[0]),
			'length'			=> 6
			);

		$sort_companies = array (); // sorted by price
		$skip_companies = array ();
		$sort_by_prices = array ();
		$prices = array ();
		$at_least_one = FALSE;
		foreach ($companies as $company) {
			$prices[$company] = array ();
			for ($length = 6; $length<13; $length+=6) {
				$data['company'] = $company;
				$data['length'] = $length;

				$prices[$company][$length] = rca_query ($data, $client);
				if (!empty($prices[$company][$length])) $at_least_one = TRUE;
				}

			if (!$prices[$company][6]['value']) {
				$skip_companies[] = $company;
				continue;
				}
			$average_price = floatval ($prices[$company][6]['value']); ///12 + $prices[$company][12]['value']/24;
		
			$sort_companies[$average_price] = $company;	
			}

		ksort ($sort_companies, SORT_NUMERIC);

		$record = rca_savetodb ($data);

		$wrap = '<div class="rca-results">
	<div class="rca-results-wrap">
		<h2>RCA-ul pentru autoturismul %s porneste de la:</h2>
		<div class="rca-results-header">
			<div class="rca-results-company">
				Companie de Asigurari
			</div>
			<div class="rca-results-6m">
				Polita valabila 6 luni
			</div>
			<div class="rca-results-12m">
				Polita valabila 12 luni
			</div>
			<div class="clearfix"></div>
		</div>
%s
	</div>
	<div class="clearfix"></div>
</div>';
		$rows = array ();
		$rows_wrap = '
		<div class="rca-results-row">
			<div class="rca-results-company">
				%s
			</div>
			<div class="rca-results-6m">
				<form action="" method="post" target="_blank">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span>
					</span>
					<span class="rca-results-bm">
						Clasa Bonus Malus %s
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="6" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
					<button>Alege!</button>
				</form>
			</div>
			<div class="rca-results-12m">
				<form action="" method="post" target="_blank">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span>
					</span>
					<span class="rca-results-bm">
						Clasa Bonus Malus %s
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="12" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
					<button>Alege!</button>
				</form>
			</div>
			<div class="clearfix"></div>
		</div>
';
		
		if (!empty ($skip_companies))
		foreach ($sort_companies as $company) {
			$rows[] = sprintf ($rows_wrap,
				$prices[$company][6]['name'],
				$prices[$company][6]['value'] ? : '--*',
				$prices[$company][6]['bonus'] ? : 'N/A',
				$prices[$company][6]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][6]['code'],
				$prices[$company][6]['offer'],
				$prices[$company][6]['bonus'],
				$prices[$company][12]['value'] ? : '--*',
				$prices[$company][12]['bonus'] ? : 'N/A',
				$prices[$company][12]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][12]['code'],
				$prices[$company][12]['offer'],
				$prices[$company][12]['bonus']
				);
			}

		$rows_wrap = '
		<div class="rca-results-row">
			<div class="rca-results-company">
				%s
			</div>
			<div class="rca-results-6m">
				<form action="" method="post" target="_blank">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span>
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="6" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
				</form>
			</div>
			<div class="rca-results-12m">
				<form action="" method="post" target="_blank">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span>
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="12" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
				</form>
			</div>
			<div class="clearfix"></div>
			<div class="rca-results-error">%s</div>
		</div>
';
		if (!empty ($skip_companies))
		foreach ($skip_companies as $company) {
			$rows[] = sprintf ($rows_wrap,
				$prices[$company][6]['name'],
				$prices[$company][6]['value'] ? : '--*',
				$prices[$company][6]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][6]['code'],
				$prices[$company][6]['offer'],
				$prices[$company][6]['bonus'],
				$prices[$company][12]['value'] ? : '--*',
				$prices[$company][12]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][12]['code'],
				$prices[$company][12]['offer'],
				$prices[$company][12]['bonus'],
				$prices[$company][12]['error']
				);
			}

		$out = sprintf ($wrap,
			sprintf ('%s-%s-%s', 
				$_POST['regnoa'],
				$_POST['regnob'],
				$_POST['regnoc']
				),
			implode ('', $rows)
			);

		return $out;
		}


	$wrap = '<form action="" method="post">
<div class="rca-form">
	<div class="rca-form-front">
		<h2>Calculator RCA</h2>
		<p>Masina personala, pe firma sau in leasing? E important pentru stabilirea tarifului. Atentie! Completeaza folosind informatii din talonul masinii!</p>
		<div class="rca-form-sel">
			<div class="rca-form-cell"><label><input type="radio" name="rcatype" value="person" /> <br /> Persoana Fizica</label></div>
			<div class="rca-form-cell"><label><input type="radio" name="rcatype" value="company" /> <br /> Persoana Juridica</label></div>
			<div class="rca-form-cell"><label><input type="radio" name="rcatype" value="leasing-person" /> <br /> Leasing cu utilizator <br /> Persoana Fizica</label></div>
			<div class="rca-form-cell"><label><input type="radio" name="rcatype" value="leasing-company" /> <br /> Leasing cu utilizator <br /> Persoana Juridica</label></div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="rca-form-car">
		<h2>Date despre masina ta</h2>
%s
	</div>
	<div class="rca-form-leasing">
		<h2>Date despre leasing</h2>
%s
	</div>
	<div class="rca-form-person">
		<h2>Date despre tine</h2>
%s
	</div>
	<div class="rca-form-company">
		<h2>Date despre firma</h2>
%s
	</div>
	<div class="rca-form-quote">
		<button class="rca-form-submit">Calculeaza RCA!</button>
	</div>
	<div class="rca-form-alert-wrap">
		<div class="rca-form-alert">
			<h2></h2>
			<p></p>
			<button>Inchide</button>
		</div>
	</div>
</div>
</form>';

	$out = sprintf ($wrap,
		rca_car_form (),
		rca_leasing_form (),
		rca_person_form (),
		rca_company_form ()
		);

	return $out;
	}

function rca_connect () {
	global $rca_settings;
	if (empty ($rca_settings)) $rca_settings = get_option ('rca_settings');

	if ($rca_settings['rcaapien']) {
		$rca_settings['wsdlurl'] = 'http://ws-rca.24broker.ro/?wsdl';
		$rca_settings['loginurl'] = 'http://ws-rca.24broker.ro/';
		}
	else {
		$rca_settings['wsdlurl'] = 'http://ws-rca-dev.24broker.ro/?wsdl';
		$rca_settings['loginurl'] = 'http://ws-rca-dev.24broker.ro/';
		}


	$client = new SoapClient ($rca_settings['wsdlurl'], array (
			'trace' => false,
			'cache' => WSDL_CACHE_NONE,
			'connection_timeout' => 30));
	$param = new SoapVar (array (
			'utilizator' => $rca_settings['username'],
			'parola' => $rca_settings['password']
			), SOAP_ENC_OBJECT);
	$header = new SoapHeader ($rca_settings['loginurl'], 'autentificare', $param, false);

	$client->__setSoapHeaders ($header);

	return $client;
	}

function rca_query ($data = array (), $client = null, $debug = FALSE) {
	global $rca_settings;

	include (dirname (__FILE__) . '/data/fuel.php');
	include (dirname (__FILE__) . '/data/insurers.php');
	include (dirname (__FILE__) . '/data/utility.php');

	if (is_null ($client)) $client = rca_connect ();

	$utility = is_numeric ($data['utility']) ? $utility[$data['utility']] : $data['utility'];

	$proprietar = null;

	if ($data['owner'] == 'fizica') {
		$proprietar = (object) array (
			'tip_persoana'		=> $data['owner'],
			'cod_unic'		=> $data['uin'],
			'nume'			=> $data['last_name'],
			'prenume'		=> $data['first_name'],
			'societate'		=> $data['companyname'],
			'adresa'		=> (object) array (
							'localitate_siruta'	=> $data['city'],
							'judet'			=> $data['county'],
							'strada'		=> 'Strada',
							'numar'			=> '1',
							'bloc'			=> '1',
							'scara'			=> '1',
							'etaj'			=> '1',
							'apartament'		=> '1',
							'cod_postal'		=> '000000'
							),
			'data_permis_conducere'	=> $data['pyear'] . '-01-01',
			'pensionar'		=> false,
			'handicapat'		=> false,
			'bugetar'		=> false,
			'an_fara_daune'		=> date('Y') - $data['pyear'] + 1,
			'numar_daune'		=> 0,

			'email'			=> $data['email'],
			'telefon_mobil'		=> $data['phone'],
			);
		}
	else {
		$proprietar = (object) array (
			'tip_persoana'		=> $data['owner'],
			'cod_unic'		=> $data['uin'],
			'nume'			=> $data['last_name'],
			'prenume'		=> $data['first_name'],
			'societate'		=> $data['companyname'],
			'adresa'		=> (object) array (
							'localitate_siruta'	=> $data['city'],
							'judet'			=> $data['county'],
							'strada'		=> 'Strada',
							'numar'			=> '1',
							'bloc'			=> '1',
							'scara'			=> '1',
							'etaj'			=> '1',
							'apartament'		=> '1',
							'cod_postal'		=> '000000'
							),
			'parc_auto'		=> 0,
			'domeniul_activitate'	=> $data['caen'],
			'categorie_pj'		=> $data['ctype'],
			'an_fara_daune'		=> date('Y') - $data['pyear'] + 1,
			'numar_daune'		=> 0,
			'societate_de_leasing'	=> false,

			'email'			=> $data['email'],
			'telefon_mobil'		=> $data['phone'],
			);
		}

	$query = (object) array (
		/** insurer */
		'societate'		=> $data['company'],

		/** car */
		'vehicul'		=> (object) array (
						'numar_inmatriculare'	=> $data['regno'],
						'tip_inmatriculare'	=> $data['state'],
						'serie_sasiu'		=> $data['vin'],
						'categorie'		=> $data['vehicle'],
						'subcategorie'		=> $data['vehicleopts'],
						'marca'			=> $data['manufacturer'],
						'model'			=> $data['model'],
						'model_id'		=> (int) $data['model_id'],
						'an_fabricatie'		=> $data['caryear'],
						'capacitate_cilindrica'	=> $data['cylinder'],
						'putere'		=> $data['power'],
						'masa_maxima'		=> $data['mass'],
						'numar_locuri'		=> $data['seats'],
						'combustibil'		=> $fuel[$data['fuel']],
						'tip_utilizare'		=> $utility,
						'carte_identitate'	=> $data['carid'],
						),

		/** proprietar */
		'proprietar'		=> $proprietar,
		'clasa_bm_anterioara'	=> 'B0',
		'reduceri'		=> (object) array (
					'reducere_tehnica'	=> 0,
					'are_alta_polita'	=> false,
					'are_alta_polita_tip'	=> '',
					'are_copil_minor'	=> false,
					'are_polita_casco'	=> false,
					'plata_integrala'	=> true,
					'ani_fara_dauna'	=> date('Y') - $data['pyear'] + 1,
					'parc_auto'		=> 1,
					'zona_rurala'		=> false,
					),
		'majorari'		=> (object) array (
					'procent_majorare'	=> 0,
					),
		'data_inceput'		=> is_null ($data['date']) ? $data['start'] : $data['date'],
		'durata'		=> $data['length'],
		'generare_oferta'	=> $rca_settings['insurancer'] ? ($data['company'] == 'cityinsurance' ? false : true) : false
		/** */
		);

	echo ' ';
	ob_flush ();
	if ($debug) {
		echo '<pre>';
		echo "QUERY:\n";
		echo var_dump ($query);
		echo '</pre>';
		}

	try {
		$answer = $client->get_cotatie ($query);
		if ($debug) {
			echo '<pre>';
			echo "XML REQUEST:\n";
			echo $client->__getLastRequest();
			echo '</pre>';
			echo '<pre>';
			echo "ANSWER:\n";
			var_dump ($answer);
			echo '</pre>';
			}
		if ($answer->eroare) {
			$message = unserialize($answer->mesaj);
			if (is_array ($message)) $message = current($message);
			if (is_array ($message)) $message = $message['mesaj'];
			return array ('name' => $answer->societate_nume, 'error' => preg_replace ('/\[[^\[]+\]/', '', $message));
			}

		return array (
			'value' => $answer->prima,
			'name' => $answer->societate_nume,
			'code' => property_exists ($answer, 'cod_cotatie') ? $answer->cod_cotatie : '',
			'offer' => $rca_settings['insurancer'] ? $answer->identificator_oferta : 'FooBar001',
			'bonus' => $answer->clasa_bm ? : '',
			);
		}
	catch (SoapFault $e) {
		if ($debug) {
			echo '<pre>';
			echo "XML REQUEST:\n";
			echo $client->__getLastRequest();
			echo '</pre>';
			echo '<pre>';
			echo "FAULT:\n";
			var_dump ($e);
			echo '</pre>';
			}
		return array (
			'error' => $e->getMessage(),
			'name' => $insurers[$data['company']]
			);
		}
	}

function rca_confirm ($record_id = null, $client = null) {
	global
		$wpdb,
		$rca_settings;

	include (dirname (__FILE__) . '/data/companies.php');
	include (dirname (__FILE__) . '/data/vehicle.php');
	include (dirname (__FILE__) . '/data/vehicle_opts.php');
	include (dirname (__FILE__) . '/data/utility.php');
	include (dirname (__FILE__) . '/data/fuel.php');
	include (dirname (__FILE__) . '/data/caen.php');
	include (dirname (__FILE__) . '/data/manufacturers.php');
	include (dirname (__FILE__) . '/data/all_manufacturers.php');
	include (dirname (__FILE__) . '/data/counties.php');
	include (dirname (__FILE__) . '/data/siruta.php');
	include (dirname (__FILE__) . '/data/insurers.php');

	if (is_null ($client)) $client = rca_connect ();

	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d;', array ((int) $record_id));
	$data = $wpdb->get_row ($sql, ARRAY_A);

	$data['ord_details'] = unserialize ($data['ord_details']);
	$data['address'] = unserialize ($data['address']);

	$address_street = $data['address']['street'];
	$address_number = $data['address']['number'];
	$address_building = $data['address']['building'];
	$address_entrance = $data['address']['entrance'];
	$address_floor = $data['address']['floor'];
	$address_appartment = $data['address']['appartment'];
	$address_zipcode = $data['address']['zipcode'];

	list ($receipt_date, $receipt_number) = explode ("\n", $data['ord_details']['mentions']);
	$receipt_date = date ('Y-m-d', strtotime ($receipt_date));
	$receipt_number = substr ($receipt_number, 0, strpos ($receipt_number, ',') - 3);

	if ($_POST['update'] || $_POST['recover'])
		foreach ($data as $key => $value)
			if (isset ($_POST[$key]))
				$data[$key] = $_POST[$key];

	$man = null;
	$veh = $data['vehicle'];
	foreach ($manufacturers[$veh] as $manufacturer_id => $manufacturer_data)
		if ($manufacturer_data[0] == $data['manufacturer'])
			$man = $manufacturer_id;

	$model = array ();
	$models = array ();

	if (file_exists (dirname (__FILE__) . '/data/models/' . $veh . '_' . $man . '.php'))
		include (dirname (__FILE__) . '/data/models/' . $veh . '_' . $man . '.php');

	foreach ($models as $model_id => $model_data)
		if ($model_data[0] == $data['model'])
			$model = $model_data;

	if ($_POST['update'] || $_POST['recover']) {
		$data['cylinder'] = $model[1];
		$data['power'] = $model[2];
		$data['seats'] = $model[4];

		$address_street = $data['address']['street'] = $_POST['address_street'];
		$address_number = $data['address']['number'] = $_POST['address_number'];
		$address_building = $data['address']['building'] = $_POST['address_building'];
		$address_entrance = $data['address']['entrance'] = $_POST['address_entrance'];
		$address_floor = $data['address']['floor'] = $_POST['address_floor'];
		$address_appartment = $data['address']['appartment'] = $_POST['address_appartment'];
		$address_zipcode = $data['address']['zipcode'] = $_POST['address_zipcode'];

		$data['address'] = serialize ($data['address']);
/** regenerate query */
		if ($_POST['recover']) {
			$_company = $data['company'];
			$_length = $data['length'];

			$sort_companies = array (); // sorted by price
			$skip_companies = array ();
			$sort_by_prices = array ();
			$prices = array ();
			$at_least_one = FALSE;
			foreach ($companies as $company) {
				$prices[$company] = array ();
				for ($length = 6; $length<13; $length+=6) {
					$data['company'] = $company;
					$data['length'] = $length;

					$prices[$company][$length] = rca_query ($data, $client, FALSE);
					if (!empty($prices[$company][$length])) $at_least_one = TRUE;
					}

				if (!$prices[$company][6]['value']) {
					$skip_companies[] = $company;
					continue;
					}
				$average_price = floatval ($prices[$company][6]['value']); ///12 + $prices[$company][12]['value']/24;
			
				$sort_companies[$average_price] = $company;	
				}

			ksort ($sort_companies, SORT_NUMERIC);

			$data['company'] = $_company;
			$data['length'] = $_length;
			}
/** end regenerate query */
		rca_updatedb ($data);
		}

	if ($_POST['getinsurance']) {
		$_ord_details = $data['ord_details'];
		$_ord_details['code'] = $_POST['code'];
		$_ord_details['offer'] = $_POST['offer'];
		$data = rca_choose (array (
			'company' => $_POST['company'],
			'price' => $_POST['price'],
			'length' => $_POST['length'],
			'record' => (int) $record_id,
			'details' => $_ord_details,
			'bonus' => $_POST['bonus'],
			));
		}

	if ($_POST['submit']) {
		$issued = rca_issue ((int) $record_id);
		if ($issued) {
?>
			<p>Polita a fost emisa cu succes. Te rugam sa te intorci in <a href="<?php menu_page_url ('rca-broker'); ?>">pagina cu lista cotatiilor</a> pentru a genera PDF-ul care se transmite clientului.</p>
<?php
			}
?>
		<p class="submit"><a href="<?php menu_page_url ('rca-broker'); ?>">Lista Cotatii</a></p>
<?php
		return;
		}

?>
	<p>Emitent: <strong><?php echo $insurers[$data['company']]; ?></strong></p>
	<p>Pret: <strong><?php echo $data['ord_price']; ?> lei</strong></p>
	<p>Clasa Bonus Malus: <strong><?php echo $data['ord_bonus']; ?></strong></p>
	<form action="" method="post">
	<h2>Date despre vehicul</h2>
	<table>
		<tbody>
			<tr>
				<th>
					<label>Numar de inmatriculare</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['regno']; ?>" name="regno" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Tip inmatriculare</label>
				</th>
				<td>
					<select name="state">
						<option value="inmatriculat" <?php echo $data['state'] == 'inmatriculat' ? 'checked' : ''; ?>>Inmatriculat</option>
						<option value="in vederea inmatricularii" <?php echo $data['state'] == 'in vederea inmatricularii' ? 'checked' : ''; ?>>In vederea inmatricularii</option>
						<option value="inregistrat" <?php echo $data['state'] == 'inregistrat' ? 'checked' : ''; ?>>Inregistrat la primarie</option>
						<option value="in vederea inregistrarii" <?php echo $data['state'] == 'in vederea inregistrarii' ? 'checked' : ''; ?>>In vederea inregistrarii</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Serie sasiu</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['vin']; ?>" name="vin" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Categorie vehicul</label>
				</th>
				<td>
					<select name="vehicle" class="updateform">
<?php
					foreach ($vehicle as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['vehicle'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Subcategorie vehicul</label>
				</th>
				<td>
					<select name="vehicleopts">
<?php
					foreach ($vehicle_opts[$data['vehicle']] as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['vehicleopts'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Marca</label>
				</th>
				<td>
					<select name="manufacturer" class="updateform">
<?php
					$selected = '';
					foreach ($all_manufacturers as $key => $value) {
?>
						<option value="<?php echo $value; ?>" <?php echo $data['manufacturer'] == $value ? ($selected = 'selected') : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Marca nu e listata:</label>
				</th>
				<td>
					<input type="text" name="new_manufacturer" value="<?php echo $selected ? '' : $data['manufacturer']; ?>" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Model</label>
				</th>
				<td>
					<select name="model" class="updateform">
<?php
					if (!empty ($models)) {
						$selected = '';
						foreach ($models as $key => $value) {
?>
						<option value="<?php echo $value[0]; ?>" <?php echo $data['model'] == $value[0] ? ($selected = 'selected') : ''; ?>><?php echo $value[0]; ?></option>
<?php
							}
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Modelul nu e listat:</label>
				</th>
				<td>
					<input type="text" name="new_model" value="<?php echo $selected ? '' : $data['model']; ?>" />
				</td>
			</tr>
			<tr>
				<th>
					<label>An fabricatie</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['caryear']; ?>" name="caryear" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Capacitate cilindrica</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['cylinder']; ?>" name="cylinder" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Puterea</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['power']; ?>" name="power" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Nr. de locuri</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['seats']; ?>" name="seats" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Combustibil</label>
				</th>
				<td>
					<select name="fuel">
<?php
					foreach ($fuel as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['fuel'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Tip de utilizare</label>
				</th>
				<td>
					<select name="utility">
<?php
					foreach ($utility as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['utility'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<h2>Date despre proprietar</h2>
	<table>
		<tbody>
			<tr>
				<th>
					<label>Telefon</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['phone']; ?>" name="phone" />
				</td>
			</tr>
			<tr>
				<th>
					<label>E-Mail</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['email']; ?>" name="email" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Tip proprietar</label>
				</th>
				<td>
					<ul>
						<li><label><input type="radio" value="fizica" name="owner" <?php echo $data['owner'] == 'fizica' ? 'checked' : ''; ?>>Persoana Fizica</label></li>
						<li><label><input type="radio" value="juridica" name="owner" <?php echo $data['owner'] == 'juridica' ? 'checked' : ''; ?>>Persoana Juridica</label></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>
					<label>Cod unic de identificare (firme) / CNP (persoane)</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['uin']; ?>" name="uin" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Nume de familie</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['last_name']; ?>" name="last_name" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Prenume</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['first_name']; ?>" name="first_name" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Companie (pentru persoane juridice)</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['company']; ?>" name="company" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Judet</label>
				</th>
				<td>
					<select name="county" class="updateform">
<?php
					foreach ($counties as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['county'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Localitate</label>
				</th>
				<td>
					<select name="city">
<?php
					foreach ($siruta[$data['county']] as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['city'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Strada</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_street; ?>" name="address_street" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Numar</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_number; ?>" name="address_number" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Bloc</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_building; ?>" name="address_building" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Scara</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_entrance; ?>" name="address_entrance" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Etaj</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_floor; ?>" name="address_floor" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Apartament</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_appartment; ?>" name="address_appartment" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Cod Postal</label>
				</th>
				<td>
					<input type="text" value="<?php echo $address_zipcode; ?>" name="address_zipcode" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Anul obtinerii permisului</label>
				</th>
				<td>
					<input type="text" value="<?php echo $data['pyear']; ?>" name="pyear" />
				</td>
			</tr>
			<tr>
				<th>
					<label>Domeniul de activitate</label>
				</th>
				<td>
					<select name="caen">
						<option value="">Nu e cazul</option>
<?php
					foreach ($caen as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['caen'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label>Categorie persoane juridice</label>
				</th>
				<td>
					<select name="ctyle">
						<option value="">Nu e cazul</option>
<?php
					$ctypes = array (
						'SC nefinanciare - P.F.A.' => 'P.F.A., Nefinanciara',
						'SC nefinanciare - P.F.I.' => 'P.F.I., Nefinanciara',
						'SC nefinanciare - S.R.L.' => 'S.R.L., Nefinanciara',
						'SC nefinanciare - S.A.' => 'S.A., Nefinanciara',
						'alte SC nefinanciare' => 'ONG',
						'banci si cooperative de credit' => 'Banci si Cooperative de Credit',
						'alti intermediari financiari' => 'Intermediari Financiari (non-Leasing)',
						'institutii guvernamentale' => 'Institutii Guvernamentale',
						'regii autonome' => 'Regii Autonome' );
					foreach ($ctypes as $key => $value) {
?>
						<option value="<?php echo $key; ?>" <?php echo $data['ctype'] == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
<?php
						}
?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
<input id="rcaupdate" type="submit" value="Actualizeaza Valori" name="update" class="button button-primary" />
<input id="rcareissue" type="submit" value="Recalculeaza Cotatii" name="recover" class="button button-primary" />
<input id="rcaconfirm" type="submit" value="Emite Polita" name="submit" class="button button-primary" /> <a href="<?php menu_page_url ('rca-broker'); ?>" class="button button-primary">Lista Cotatii</a></p>
	</form>
	<script type="text/javascript">
	jQuery('.updateform').change(function(e){
		jQuery('#rcaupdate').click();
		});
	</script>
<?php
		$wrap = '<p>RCA-ul pentru autoturismul %s porneste de la:</p>
<table class="widefat fixed">
	<thead>
		<tr>
			<th>
				Companie de Asigurari
			</th>
			<th>
				Polita valabila 6 luni
			</th>
			<th>
				Polita valabila 12 luni
			</th>
			<th>
				Erori (daca e cazul):
			</th>
		</tr>
	</thead>
	<tbody>
%s
	</tbody>
</table';
		$rows = array ();
		$rows_wrap = '
		<tr>
			<td>
				%s
			</td>
			<td>
				<form action="" method="post">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span> / %s
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="6" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
					<button class="button button-secondary">Alege!</button>
				</form>
			</td>
			<td>
				<form action="" method="post">
					<input type="hidden" name="getinsurance" value="1" />
					<span class="rca-results-price">
						%s <span class="rca-results-currency">lei</span> / %s
					</span>
					<input type="hidden" name="price" value="%s" />
					<input type="hidden" name="company" value="%s" />
					<input type="hidden" name="length" value="12" />
					<input type="hidden" name="record" value="%d" />
					<input type="hidden" name="code" value="%s" />
					<input type="hidden" name="offer" value="%s" />
					<input type="hidden" name="bonus" value="%s" />
					<button class="button button-secondary">Alege!</button>
				</form>
			</td>
			<td>%s</td>
		</tr>
';
	if (!empty ($sort_companies) || !empty ($skip_companies)) {	
		foreach ($sort_companies as $company) {
			$rows[] = sprintf ($rows_wrap,
				$prices[$company][6]['name'],
				$prices[$company][6]['value'] ? : '--*',
				$prices[$company][6]['bonus'] ? : '--*',
				$prices[$company][6]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][6]['code'],
				$prices[$company][6]['offer'],
				$prices[$company][6]['bonus'] ? : '--*',
				$prices[$company][12]['value'] ? : '--*',
				$prices[$company][12]['bonus'] ? : '--*',
				$prices[$company][12]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][12]['code'],
				$prices[$company][12]['offer'],
				$prices[$company][12]['bonus'] ? : '--*',
				$prices[$company][6]['error'] ? : '-'
				);
			}
		foreach ($skip_companies as $company) {
			$rows[] = sprintf ($rows_wrap,
				$prices[$company][6]['name'],
				$prices[$company][6]['value'] ? : '--*',
				$prices[$company][6]['bonus'] ? : '--*',
				$prices[$company][6]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][6]['code'],
				$prices[$company][6]['offer'],
				$prices[$company][6]['bonus'] ? : '--*',
				$prices[$company][12]['value'] ? : '--*',
				$prices[$company][12]['bonus'] ? : '--*',
				$prices[$company][12]['value'] ? : '--*',
				$company,
				$record,
				$prices[$company][12]['code'],
				$prices[$company][12]['offer'],
				$prices[$company][12]['bonus'] ? : '--*',
				$prices[$company][6]['error'] ? : '-'
				);
			}
		$out = sprintf ($wrap,
			$data['regno'],
			implode ('', $rows)
			);
		echo $out;
		}
	}

function rca_issue ($record_id = null, $client = null) {
	global
		$wpdb,
		$rca_settings;

	include (dirname (__FILE__) . '/data/fuel.php');

	if (is_null ($client)) $client = rca_connect ();

	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d;', array ((int) $record_id));
	$data = $wpdb->get_row ($sql, ARRAY_A);

	$data['ord_details'] = unserialize ($data['ord_details']);

	list ($receipt_date, $receipt_number) = explode ("\n", $data['ord_details']['mentions']);
	$receipt_date = date ('Y-m-d', strtotime ($receipt_date));
	$receipt_number = hexdec (substr ($receipt_number, 0, strpos ($receipt_number, ',') - 3));

	$query = (object) array (
		'identificator_oferta'	=> $data['ord_details']['offer'],
		'mod_de_plata'		=> 'chitanta broker',
		'chitanta_serie'	=> 'MobilPay',
		'chitanta_numar'	=> $receipt_number,

		'data_platii'		=> $receipt_date,

		/** car */
		'vehicul'		=> (object) array (
						'numar_inmatriculare'	=> $data['regno'],
						'tip_inmatriculare'	=> $data['state'],
						'serie_sasiu'		=> $data['vin'],
						'categorie'		=> $data['vehicle'],
						'subcategorie'		=> $data['vehicleopts'],
						'marca'			=> $data['manufacturer'],
						'model'			=> $data['model'],
						'model_id'		=> $data['model_id'],
						'an_fabricatie'		=> $data['caryear'],
						'capacitate_cilindrica'	=> $data['cylinder'],
						'putere'		=> $data['power'],
						'masa_maxima'		=> $data['mass'],
						'numar_locuri'		=> $data['seats'],
						'combustibil'		=> $fuel[$data['fuel']],
						'tip_utilizare'		=> $data['utility'],
						'carte_identitate'	=> $data['carid'],
						),

		/** proprietar */
		'proprietar'		=> (object) array (
					'tip_persoana'		=> $data['owner'],
					'cod_unic'		=> $data['uin'],
					'nume'			=> $data['last_name'],
					'prenume'		=> $data['first_name'],
					'societate'		=> $data['company'],
					'adresa'		=> (object) array (
									'localitate_siruta'	=> $data['city'],
									'judet'			=> $data['county'],
									'strada'		=> '',
									'numar'			=> '',
									'bloc'			=> '',
									'scara'			=> '',
									'etaj'			=> '',
									'apartament'		=> '',
									'cod_postal'		=> ''
									),
					'data_permis_conducere'	=> $data['pyear'],
					'pensionar'		=> false,
					'handicapat'		=> false,
					'bugetar'		=> false,
					'numar_daune'		=> 0,
					'domeniul_de_activitate'=> $data['caen'],
					'societate_de_leasing'	=> false,
					'categorie_pj'		=> $data['ctype'],
					'email'			=> $data['email'],
					'telefon_mobil'		=> $data['phone']
					)
		/** */
		);

	$update = array (
		'record' => $data['id'],
		'status' => $data['ord_status'],
		'details' => array ()
		);

	try {
		$data = $client->emitere_polita ($query);
		if ($data->eroare) {
			echo '<p>' . $data->eroare . '</p>';
			return FALSE;
			}

		$update['details'] = array (
			'insurance_series'	=> $data->serie,
			'insurance_number'	=> $data->numar,
			'insurance_cost'	=> $data->prima
			);

		rca_updateorder ($update);

		return TRUE;
		}
	catch (SoapFault $e) {
		//return FALSE;
		}
	}

function rca_getpdf ($record_id, $client = null) {
	global
		$wpdb,
		$rca_settings;

	if (is_null ($client)) $client = rca_connect ();

	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d;', array ((int) $record_id));
	$data = $wpdb->get_row ($sql, ARRAY_A);

	$mail = $data['email'];

	$data['ord_details'] = unserialize ($data['ord_details']);
	$update = array (
		'record' => $data['id'],
		'status' => $data['ord_status'],
		'details' => array ()
		);

	$query = (object) array (
		'identificator_oferta'	=> $data['ord_details']['offer'],
		'redescarcare' => true
		);

	try {
		$data = $client->get_polita_pdf ($query);
		if ($data->eroare) return FALSE;

		$hash = md5 ($data->serie . $data->numar . time ());

		$update['details'] = array (
			'insurance_series'	=> $data->serie,
			'insurance_number'	=> $data->numar,
			'insurance_hash'	=> $hash
			);
		rca_updateorder ($update);

		@file_put_contents (dirname (__FILE__) . '/cache/' . $hash . '.pdf', base64_decode($data->pdf));

		$attachments = array (dirname (__FILE__) . '/cache/' . $hash . '.pdf' => 'polita_rca_postasig.pdf');

		$subject = @file_get_contents (dirname (__FILE__) . '/mail/template/subject_im.txt');
		$content = @file_get_contents (dirname (__FILE__) . '/mail/template/content_im.html');
		
		rca_mail ($mail, $subject, $content, $attachments);

		return TRUE;
		}
	catch (SoapFault $e) {
		return FALSE;
		}
	}

function rca_scripts () {
	wp_enqueue_style ('jquery-ui-smoothness', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css', array (), '1.11.2', 'all');
	wp_enqueue_style ('rca-style', plugin_dir_url (__FILE__) . '/style/rca-style.css', array ('jquery-ui-smoothness'), '0.32', 'all');

	wp_enqueue_script ('jquery-ui-core');
	wp_enqueue_script ('jquery-ui-datepicker');
	wp_enqueue_script ('rca-script', plugin_dir_url (__FILE__) . '/scripts/rca-script.js', array ('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), '0.51', TRUE);
	}

function rca_savetodb ($data) {
	global $wpdb;

	$sql = $wpdb->prepare ('insert into `' . $wpdb->prefix . 'rcadata` values (
			null,
			%s,
			%s,
			%s,
			%s,
			%s,
			%s,
			%s,
			%s,
			%d,
			%d,
			%s,
			%s,
			%d,
			%d,
			%d,
			%d,
			%d,
			%d,
			%s,
			%s,
			%s,
			%s,
			%s,
			%s,
			%s,
			%d,
			%d,
			%d,
			%d,
			%s,
			%d,
			%d,
			%s,
			%s,
			%f,
			%d,
			%d,
			%s,
			%s,
			%ld
			);', array (
			$data['companyname'],
			$data['first_name'],
			$data['last_name'],
			$data['phone'],
			$data['email'],
			$data['regno'],
			$data['vin'],
			$data['state'],
			$data['vehicle'],
			$data['vehicleopts'],
			$data['manufacturer'],
			$data['model'],
			$data['model_id'],
			$data['caryear'],
			$data['cylinder'],
			$data['power'],
			$data['mass'],
			$data['seats'],
			$data['fuel'],
			$data['utility'],
			$data['owner'],
			$data['uin'],
			$data['city'],
			$data['county'],
			$data['address'],
			$data['pyear'],
			$data['retired'],
			$data['disabled'],
			$data['publicservant'],
			$data['ctype'],
			$data['caen'],
			$data['lease'],
			$data['date'],
			'',
			0.00,
			0,
			0,
			'',
			serialize(array()),
			time()
			));
	
	$wpdb->query ($sql);
	$id = $wpdb->insert_id;

	$content = 'Pentru detalii, click aici: ' . "\n";
	$content .= 'http://www.postasig.ro/wp-admin/admin.php?page=rca-view-quotation&record_id=' . $id;

	rca_mail (array (
		'office@postasig.ro',
		'viorel.motoroiu@postasig.ro',
		'bogdan.manole@postasig.ro',
		'cristian.sillo@postasig.ro'
		), $data['first_name'] . ' ' . $data['last_name'] . ' ' . $data['companyname'] . ' a solicitat o cotatie.', $content);

	return $id;
	}

function rca_updatedb ($data) {
	global $wpdb;

	if (!$data['id']) return FALSE;

	$record_id = $data['id'];
	unset ($data['id']);
	unset ($data['ord_company']);
	unset ($data['ord_price']);
	unset ($data['length']);
	unset ($data['ord_length']);
	unset ($data['ord_status']);
	unset ($data['ord_details']);
	unset ($data['stamp']);

	$keys = array ();
	$vals = array ();
	foreach ($data as $key => $value) {
		$keys[] = '`' . $key . '`=%s';
		$vals[] = is_array ($value) ? serialize($value) : $value;
		}
	$vals[] = $record_id;

	$sql = $wpdb->prepare ('update `' . $wpdb->prefix . 'rcadata` set ' . implode (',', $keys) . ' where id=%d;', $vals);
	$wpdb->query ($sql);

	return TRUE;
	}

function rca_choose ($data) {
	global $wpdb;
	$sql = $wpdb->prepare ('update `' . $wpdb->prefix . 'rcadata` set company=%s,ord_company=%s,ord_price=%f,ord_length=%d,ord_status=%d,ord_details=%s,ord_bonus=%s where id=%d', array (
			$data['company'],
			$data['company'],
			(float) $data['price'],
			(int) $data['length'],
			1,
			serialize($data['details'] ? : array ()),
			$data['bonus'],
			(int) $data['record'],
			));
	$wpdb->query ($sql);
	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d', $data['record']);
	$out = $wpdb->get_row ($sql, ARRAY_A);
	$out['ord_details'] = unserialize ($out['ord_details']);
	return $out;
	}

function rca_getorder ($id) {
	global $wpdb;
	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d', (int) $id);
	$data = $wpdb->get_row ($sql, ARRAY_A);
	$data['ord_details'] = unserialize ($data['ord_details']);
	return $data;
	}

function rca_updateorder ($data) {
	global $wpdb;

	$details = unserialize ($wpdb->get_var ($wpdb->prepare ('select ord_details from `' . $wpdb->prefix . 'rcadata` where id=%d;', (int) $data['record'])));
	if (!empty ($data['details']))
	foreach ($data['details'] as $key => $value) $details[$key] = $value;

	$sql = $wpdb->prepare ('update `' . $wpdb->prefix . 'rcadata` set ord_status=%d,ord_details=%s where id=%d', array (
			(int) $data['status'],
			serialize ($details),
			(int) $data['record']
			));
	$wpdb->query ($sql);
	}

function rca_install () {
	global $wpdb;
	$sql = 'create table `' . $wpdb->prefix . 'rcadata` (
		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`company` text NOT NULL,
		`first_name` text NOT NULL,
		`last_name` text NOT NULL,
		`phone` varchar(24) NOT NULL DEFAULT \'\',
		`email` varchar(64) NOT NULL DEFAULT \'\',
		`regno` varchar(10) NOT NULL DEFAULT \'B-000-AAA\',
		`vin` varchar(17) NOT NULL DEFAULT \'\',
		`state` varchar(32) NOT NULL DEFAULT 0,
		`vehicle` int(2) NOT NULL DEFAULT 0,
		`vehicleopts` int(2) NOT NULL DEFAULT 0,
		`manufacturer` varchar(50) NOT NULL DEFAULT \'\',
		`model` text NOT NULL,
		`model_id` int(11) NOT NULL DEFAULT 0,
		`caryear` int(4) NOT NULL DEFAULT 0,
		`cylinder` int(11) NOT NULL DEFAULT 0,
		`power` int(11) NOT NULL DEFAULT 0,
		`mass` int(11) NOT NULL DEFAULT 0,
		`seats` int(11) NOT NULL DEFAULT 0,
		`fuel` varchar(16) NOT NULL DEFAULT \'\',
		`utility` varchar(16) NOT NULL DEFAULT \'\',
		`owner` enum(\'fizica\',\'juridica\') NOT NULL DEFAULT \'fizica\',
		`uin` varchar(13) NOT NULL DEFAULT \'\',
		`city` TEXT NOT NULL,
		`county` TEXT NOT NULL,
		`address` TEXT NOT NULL,
		`pyear` int(4) NOT NULL DEFAULT 0,
		`retired` int(1) NOT NULL DEFAULT 0,
		`disabled` int(1) NOT NULL DEFAULT 0,
		`publicservant` int(1) NOT NULL DEFAULT 0,
		`ctype` varchar(32) NOT NULL DEFAULT \'\',
		`caen` int(2) NOT NULL DEFAULT 0,
		`lease` int(1) NOT NULL DEFAULT 0,
		`start` date NOT NULL DEFAULT \'2015-01-01\',
		`ord_company` varchar(64) NOT NULL DEFAULT \'\',
		`ord_price` float(9,2) NOT NULL DEFAULT 0.00,
		`ord_length` int(2) NOT NULL DEFAULT 12,
		`ord_status` int(2) NOT NULL DEFAULT 0,
		`ord_details` text NOT NULL,
		`stamp` int NOT NULL DEFAULT 0
		);';
	$wpdb->query ($sql);
	}

function rca_broker () {
	global
		$wpdb,
		$rca_settings;

	if ($_POST['record_id']) {
		#if ($_POST['issue'])
		#	$issued = rca_issue ((int) $_POST['record_id']);
		#if ($_POST['pdf'])
			$issued = rca_getpdf ((int) $_POST['record_id']);
		}

	$npp = $rca_settings['recordspp'] ? : 25;
	$all = $wpdb->get_var ('select count(1) from `' . $wpdb->prefix . 'rcadata`;');
	$cur = $_GET['pg'] ? : 0;

?>
	<h2>Activitate PostAsig</h2>
	<div class="tablenav">
		<div class="tablenav-pages">
<?php

	$page_query = parse_url ($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	for ($pg = 0; $pg < ceil($all/$npp); $pg++) {
?>
		<a href="<?php echo $_SERVER['REQUEST_URI'] . ($page_query ? '&' : '?') . 'pg=' . $pg; ?>"><?php echo $pg+1; ?></a>
<?php
		}
?>
		</div>
	</div>
<?php

	$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` order by stamp desc limit %d,%d;', array (
		$cur*$npp,
		$npp
		));

	$rcadata = $wpdb->get_results ($sql);

	$rows = array ();
	$c = 1 + $cur * $npp;
	if ($rcadata)
	foreach ($rcadata as $rcaquote) {
		$rcaquote->ord_details = unserialize ($rcaquote->ord_details);

		$actions = '<a class="button button-seconday" href="' . menu_page_url ('rca-view-quotation', 0) . '&record_id=' . $rcaquote->id . '">Vezi Detalii</a>';
		if ($rcaquote->ord_details['offer'] && ($rcaquote->ord_details['mobilpay'] == 'confirmed')) {
			if ($rcaquote->ord_details['insurance_series'] && $rcaquote->ord_details['insurance_number']) {
				if ($rcaquote->ord_details['insurance_hash'] && file_exists (dirname (__FILE__) . '/cache/' . $rcaquote->ord_details['insurance_hash'] . '.pdf'))
					$actions = '<a class="button button-primary" href="' . plugins_url ('cache/' . $rcaquote->ord_details['insurance_hash'] . '.pdf', __FILE__) . '" target="_blank">Descarca PDF</a>';
				else
					$actions = '<form action="" method="post"><input type="hidden" name="record_id" value="' . $rcaquote->id . '" /><input type="submit" value="Genereaza PDF" name="pdf" class="button button-primary" /></form>';
				}
			else
				$actions = '<a href="' . menu_page_url ('rca-broker-edit-request', 0) . '&record_id=' . $rcaquote->id . '" class="button button-primary" />Valideaza Polita</a>';
			}

		$rows[] = array (
			$c++,
			$rcaquote->last_name,
			$rcaquote->first_name,
			$rcaquote->owner,
			$rcaquote->company,
			$rcaquote->phone,
			$rcaquote->email,
			$rcaquote->regno,
			$rcaquote->vin,
			$rcaquote->start,
			$actions
			);
		}

	echo '
	<table class="widefat fixed">
		<thead>
			<tr><th>#</th><th>Nume</th><th>Prenume</th><th>Persoana?</th><th>Societate</th><th>Telefon</th><th>E-Mail</th><th>Masina</th><th>VIN</th><th>Data</th><th>Actiuni</th></tr>
		</thead>
		<tbody>
';
	foreach ($rows as $row)
		echo sprintf ('<tr><td>%s</td></tr>', implode ('</td><td>', $row));

	echo '
		</tbody>
	</table>';
	}

function rca_settings () {
	global $rca_settings;

	$subject_qm = @file_get_contents (dirname (__FILE__) . '/mail/template/subject_qm.txt');
	$content_qm = @file_get_contents (dirname (__FILE__) . '/mail/template/content_qm.html');
	$subject_pm = @file_get_contents (dirname (__FILE__) . '/mail/template/subject_pm.txt');
	$content_pm = @file_get_contents (dirname (__FILE__) . '/mail/template/content_pm.html');
	$subject_im = @file_get_contents (dirname (__FILE__) . '/mail/template/subject_im.txt');
	$content_im = @file_get_contents (dirname (__FILE__) . '/mail/template/content_im.html');

	#$rca_settings['signature'] = '';
	#$rca_settings['mobilpay'] = '';
	#$rca_settings['insurancer'] = '';
	#$rca_settings['rcaapien'] = '';
	#$rca_settings['smtpname'] = '';

	if ($_POST['update']) {
		foreach ($rca_settings as $key => $value) {
			$rca_settings[$key] = isset ($_POST[$key]) ? $_POST[$key] : $value;
			}

		@file_put_contents (dirname (__FILE__) . '/mail/template/subject_qm.txt',	$subject_qm = $_POST['subject_qm']);
		@file_put_contents (dirname (__FILE__) . '/mail/template/content_qm.html',	$content_qm = $_POST['content_qm']);
		@file_put_contents (dirname (__FILE__) . '/mail/template/subject_pm.txt',	$subject_pm = $_POST['subject_pm']);
		@file_put_contents (dirname (__FILE__) . '/mail/template/content_pm.html',	$content_pm = $_POST['content_pm']);
		@file_put_contents (dirname (__FILE__) . '/mail/template/subject_im.txt',	$subject_im = $_POST['subject_im']);
		@file_put_contents (dirname (__FILE__) . '/mail/template/content_im.html',	$content_im = $_POST['content_im']);

		update_option ('rca_settings', $rca_settings);
		}
?>
	<form action="" method="post">
		<h2>API Settings</h2>
		<hr />
		<ul>
<?php /* ?>
			<li>
				<label for="rca-wsdlurl">RCA API WSDL URL:</label>
				<input type="text" name="wsdlurl" id="rca-wsdlurl" value="<?php echo $rca_settings['wsdlurl']; ?>" />
			</li>
			<li>
				<label for="rca-loginurl">RCA API Login URL:</label>
				<input type="text" name="loginurl" id="rca-loginurl" value="<?php echo $rca_settings['loginurl']; ?>" />
			</li>
*/ ?>
			<li>
				<label for="rca-username">RCA API Username:</label>
				<input type="text" name="username" id="rca-username" value="<?php echo $rca_settings['username']; ?>" />
			</li>
			<li>
				<label for="rca-password">RCA API Password:</label>
				<input type="text" name="password" id="rca-password" value="<?php echo $rca_settings['password']; ?>" />
			</li>
			<li>
				<label for="rca-password">RCA API Enabled:</label>
				<label><input type="radio" name="rcaapien" value="1" <?php echo $rca_settings['rcaapien'] ? 'checked' : ''; ?> /> Yes</label>
				<label><input type="radio" name="rcaapien" value="0" <?php echo $rca_settings['rcaapien'] ? '' : 'checked'; ?> /> No</label>
			</li>
			<li>
				<label for="rca-insurancer">RCA Issue Insurances:</label>
				<input type="checkbox" name="insurancer" id="rca-insurancer" value="1" <?php echo $rca_settings['insurancer'] ? 'checked' : ''; ?> />
			</li>
		</ul>
		<h2>Payment Settings</h2>
		<hr />
		<ul>
			<li>
				<label for="rca-signature">Mobilpay Signature:</label>
				<input type="text" name="signature" id="rca-signature" value="<?php echo $rca_settings['signature']; ?>" />
			</li>
			<li>
				<label for="rca-mobilpay">Mobilpay Enabled:</label>
				<label><input type="radio" name="mobilpay" value="1" <?php echo $rca_settings['mobilpay'] ? 'checked' : ''; ?> /> Yes</label>
				<label><input type="radio" name="mobilpay" value="0" <?php echo $rca_settings['mobilpay'] ? '' : 'checked'; ?> /> No</label>
			</li>
		</ul>
		<h2>E-Mail Settings</h2>
		<hr />
		<ul>
			<li>
				<label for="rca-smtphost">RCA SMTP Host:</label>
				<input type="text" name="smtphost" id="rca-smtphost" value="<?php echo $rca_settings['smtphost']; ?>" />
			</li>
			<li>
				<label for="rca-smtpport">RCA SMTP Host Port:</label>
				<input type="text" name="smtpport" id="rca-smtpport" value="<?php echo $rca_settings['smtpport']; ?>" />
			</li>
			<li>
				<label for="rca-smtpauth">RCA SMTP Requires Authentication:</label>
				<input type="checkbox" name="smtpauth" id="rca-smtpauth" value="1" <?php echo $rca_settings['smtpauth'] ? 'checked' : ''; ?> />
			</li>
			<li>
				<label for="rca-smtpuser">RCA SMTP Username:</label>
				<input type="text" name="smtpuser" id="rca-smtpuser" value="<?php echo $rca_settings['smtpuser']; ?>" />
			</li>
			<li>
				<label for="rca-smtppass">RCA SMTP Password:</label>
				<input type="text" name="smtppass" id="rca-smtppass" value="<?php echo $rca_settings['smtppass']; ?>" />
			</li>
			<li>
				<label for="rca-smtpssl">RCA SMTP Requires SSL:</label>
				<input type="checkbox" name="smtpssl" id="rca-smtpssl" value="1" <?php echo $rca_settings['smtpssl'] ? 'checked' : '' ; ?> />
			</li>
			<li>
				<label for="rca-smtpmail">RCA Sender E-Mail:</label>
				<input type="text" name="smtpmail" id="rca-smtpmail" value="<?php echo $rca_settings['smtpmail']; ?>" />
			</li>
			<li>
				<label for="rca-smtpmail">RCA Sender Name:</label>
				<input type="text" name="smtpname" id="rca-smtpname" value="<?php echo $rca_settings['smtpname']; ?>" />
			</li>
		<h2>Messages</h2>
		<hr />
			<li>
				<label>RCA Successful Quotation Mail:</label>
			</li>
			<li>
				<label for="rca-subject">Subject:</label>
				<input type="text" name="subject_qm" id="rca-subject" value="<?php echo $subject_qm; ?>" />
			</li>
			<li>
				<?php wp_editor ($content_qm, 'content_qm', array ()); ?>
			</li>
			<li>
				<label>RCA Successful Payment Mail:</label>
			</li>
			<li>
				<label for="rca-subject">Subject:</label>
				<input type="text" name="subject_pm" id="rca-subject" value="<?php echo $subject_pm; ?>" />
			</li>
			<li>
				<?php wp_editor ($content_pm, 'content_pm', array ()); ?>
			</li>
			<li>
				<label>RCA Successful Insurance Mail (Insurance is attached by default):</label>
			</li>
			<li>
				<label for="rca-subject">Subject:</label>
				<input type="text" name="subject_im" id="rca-subject" value="<?php echo $subject_im; ?>" />
			</li>
			<li>
				<?php wp_editor ($content_im, 'content_im', array ()); ?>
			</li>
			<li>
				<input type="hidden" name="update" value="1" />
				<button class="button-primary">Update</button>
			</li>
		</ul>
	</form>
<?php
	}

function rca_edit_request () {
?>
	<h2>Verifica Datele Politei</h2>
<?php
	if ($_GET['record_id']) {
		rca_confirm ((int) $_GET['record_id']);
		}
	else {
?>
		<p>Poti verifica numai datele unei polite selectate din lista de cotatii. Apasa <a href="<?php menu_page_url ('rca-broker'); ?>">aici</a> pentru a te intoarce la lista cu cotatii.</p>
<?php
		}
	}

function rca_view_quotation () {
	global $wpdb;

	include (dirname (__FILE__) . '/data/fuel.php');
	include (dirname (__FILE__) . '/data/counties.php');
	include (dirname (__FILE__) . '/data/siruta.php');
?>
	<h2>Vezi Datele Politei</h2>
<?php
	if ($_GET['record_id']) {
		$sql = $wpdb->prepare ('select * from `' . $wpdb->prefix . 'rcadata` where id=%d', $_GET['record_id']);
		$data = $wpdb->get_row ($sql);
?>
		<table>
			<tbody>
				<tr>
					<th>Polita incepand cu</th>
					<td><?php echo $data->start; ?></td>
				</tr>
				<tr>
					<th>Nume</th>
					<td><?php echo $data->last_name; ?></td>
				</tr>
				<tr>
					<th>Prenume</th>
					<td><?php echo $data->first_name; ?></td>
				</tr>
				<tr>
					<th>Telefon</th>
					<td><?php echo $data->phone; ?></td>
				</tr>
				<tr>
					<th>E-Mail</th>
					<td><?php echo $data->email; ?></td>
				</tr>
				<tr>
					<th>Nr. de inmatriculare</th>
					<td><?php echo $data->regno; ?></td>
				</tr>
				<tr>
					<th>VIN</th>
					<td><?php echo $data->vin; ?></td>
				</tr>
				<tr>
					<th>Stare vehicul</th>
					<td><?php echo $data->state; ?></td>
				</tr>
				<tr>
					<th>Marca</th>
					<td><?php echo $data->manufacturer; ?></td>
				</tr>
				<tr>
					<th>Model</th>
					<td><?php echo $data->model; ?></td>
				</tr>
				<tr>
					<th>Capacitea cilindrica</th>
					<td><?php echo $data->cylinder; ?> cc</td>
				</tr>
				<tr>
					<th>Puterea</th>
					<td><?php echo $data->power; ?> kW</td>
				</tr>
				<tr>
					<th>Nr. de locuri</th>
					<td><?php echo $data->seats; ?></td>
				</tr>
				<tr>
					<th>Combustibil</th>
					<td><?php echo $fuel[$data->fuel]; ?></td>
				</tr>
				<tr>
					<th>Utilizare</th>
					<td><?php echo $data->utility; ?></td>
				</tr>
				<tr>
					<th>Tip proprietar</th>
					<td>persoana <?php echo $data->owner; ?></td>
				</tr>
				<tr>
					<th>CNP/CUI</th>
					<td><?php echo $data->uin; ?></td>
				</tr>
				<tr>
					<th>Judet</th>
					<td><?php echo $counties[$data->county]; ?></td>
				</tr>
				<tr>
					<th>Localitate</th>
					<td><?php echo $siruta[$data->county][$data->city]; ?></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><a href="<?php menu_page_url ('rca-broker'); ?>" class="button button-primary">Lista Cotatii</a></p>
<?php
		}
	else {
?>
		<p>Poti vizualiza numai datele unei polite selectate din lista de cotatii. Apasa <a href="<?php menu_page_url ('rca-broker'); ?>">aici</a> pentru a te intoarce la lista cu cotatii.</p>
<?php
		}
	}

function rca_init () {
	global $rca_settings;
	$rca_settings = get_option ('rca_settings', array (
		'rcaapien' => '',
		'wsdlurl' => '',
		'loginurl' => '',
		'username' => '',
		'password' => '',
		'insurancer' => '',
		'signature' => '',
		'mobilpay' => '',
		'smtphost' => '',
		'smtpport' => '',
		'smtpauth' => '',
		'smtpuser' => '',
		'smtppass' => '',
		'smtpssl' => '',
		'smtpmail' => '',
		'smtpname' => '',
		));

	if ($rca_settings['rcaapien']) {
		$rca_settings['wsdlurl'] = 'http://ws-rca.24broker.ro/?wsdl';
		$rca_settings['loginurl'] = 'http://ws-rca.24broker.ro/';
		}
	else {
		$rca_settings['wsdlurl'] = 'http://ws-rca-dev.24broker.ro/?wsdl';
		$rca_settings['loginurl'] = 'http://ws-rca-dev.24broker.ro/';
		}

	add_menu_page ('RCA Broker', 'RCA Broker', 'manage_options', 'rca-broker', 'rca_broker');
	add_submenu_page ('rca-broker', 'RCA Broker Opts', 'RCA Broker Opts', 'manage_options', 'rca-broker-opts', 'rca_settings');
	add_submenu_page ('rca-broker', 'RCA Broker Edit', 'RCA Broker Edit', 'manage_options', 'rca-broker-edit-request', 'rca_edit_request');
	add_submenu_page ('rca-broker', 'RCA Broker View', 'RCA Broker View', 'manage_options', 'rca-view-quotation', 'rca_view_quotation');
	}

register_activation_hook (__FILE__, 'rca_install');

add_shortcode ('rcaform', 'rca_get_quotation');

add_action ('wp_enqueue_scripts', 'rca_scripts');
add_action ('admin_menu', 'rca_init');
?>
