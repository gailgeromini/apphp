<?php

return array(
		// application data
		'name'=>'backend',
		
		'version'=>'1.0(beta.1)',

		'installationKey' => 'k0ad651k141',

		'validation' => array(
				'csrf' => true
		),

		'password' => array(
				'encryption' => true,
				'encryptAlgorithm' => 'md5',
				'hashKey' => '',
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