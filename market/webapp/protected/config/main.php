<?php

return array(
		// application data
		'name'=>'apps',
		
		'version'=>'1.0(beta.1)',

		'installationKey' => 'k0ad651k141',
		
		'email' => array(
				'mailer' => 'smtpMailer', /* phpMail | phpMailer | smtpMailer */
				'from'   => 'OPPS',
				'isHtml' => true,
				'smtp' => array(
						'secure' => true,
						'host' => 'smtp.gmail.com',
						'port' => '465',
						'username' => 'ccarterwilliam7@gmail.com',
						'password' => 'mrcUKxdAeC',
				),
		),
		'validation' => array(
				'csrf' => true
		),

		'btc_method' => array(
		),
		
		'proxy_ip'=>'80.193.214.231:3128',
		
		'min_withdaw' => 18,
		
		'fee_withdaw' => 3, 
		

		'checker_api' => array(
				'default' => 'apino1',
				'elect_api_uri' => 'http://electronicpromo.net/services.php',
				'elect_api_user' => 'magicn',
				'elect_api_password' => 'ts4PoOnO',
				'elect_api_gate' => 'ccv76',
				'apino1_api_uri' => 'http://apino1.net/api/',
				'apino1_api_user' => 'magicn',
				'apino1_api_password' => 'ts4PoOnO',
				'apino1_api_gate' => 'ccv2',
		),


		'defaultTimeZone' => 'UTC',
		'defaultTemplate' => 'default',
		'defaultController' => 'Index',
		'defaultAction' => 'index',

		'urlManager' => array(
				'urlFormat' => 'path',  /* get | path | shortPath */
				'rules' => array(
						'param1/param2/../'=>'paramA/param1/paramB/param2/../',
				),
		),

);