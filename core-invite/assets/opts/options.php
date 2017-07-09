<?php
/*
Name: Configuration
Description: 
*/
$options = [
        'header_image'  => [
                'label' => 'Invitation Background Image',
                'type'  => 'image',
                ],
        'invitation_width'    => [
                'label' => 'Invitation Width (mm)',
                ],
        'invitation_height'    => [
                'label' => 'Invitation Height (mm)',
                ],
        'smtp_username'  => [
                'label' => '3rd Button Link',
                'type'  => 'page'
                ],
        'smtp_password'      => [
                'label' => 'Text Box Content',
                'type'  => 'page'
                ],
	'smtp_host'	=> [
		'label'	=> 'SMTP Host'
		],
	'smtp_port'	=> [
		'label' => 'SMTP Port'
		],
	'smtp_security'	=> [
		'label' => 'SMTP Security',
		'type'	=> 'select',
		'options' => [
			'none'	=> 'None',
			'ssl'	=> 'SSL',
			'tls'	=> 'TLS',
			]
		],
	'sender_name'	=> [
		'label'	=> 'Sender Name'
		],
	'message_subject' => [
		'label'	=> 'Message Subject'
		],
	'message_content' => [
		'label'	=> 'Message Content',
		'type'	=> 'richtext'
		]
        ];
?>
