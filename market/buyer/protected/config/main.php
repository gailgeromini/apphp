<?php

return array(
		// application data
		'name'=>'buyer',
		
		'version'=>'1.0(beta.1)',

		'installationKey' => 'k0ad651k141',

		'encryptKey' => '1wew3e4r5t', // encrypt , decrypt (for items - don't change it )

		// password keys settings (for database passwords only - don't change it)

		'fee' => '0.2', // encrypt , decrypt (for items - don't change it )
		
		// md5, sha1, sha256, whirlpool, etc
		'password' => array(
				'encryption' => true,
				'encryptAlgorithm' => 'md5',
				'hashKey' => '',
		),

		'breadcrumbs'=> array(
				'index' =>array(
						array('label'=>'News', 'url'=>'index'),
						array('label'=>'Affiliate', 'url'=>'javascript:;'),
						array('label'=>'Bugs/Abuse', 'url'=>'javascript:;'),
				),
				'buy' =>array(
						array('label'=>'Credit Cards', 'url'=>'buy/cards'),
						array('label'=>'Paypal Account', 'url'=>'buy/paypals'),
						array('label'=>'Account Login', 'url'=>'buy/accounts'),
						
				),
				'deposit' =>array(
						array('label'=>'Add Funds', 'url'=>'deposit'),
						array('label'=>'Transaction History', 'url'=>'deposit/history'),
				),
				'order' =>array(
						array('label'=>'Credit Cards', 'url'=>'order/cards'),
						array('label'=>'Paypal Account', 'url'=>'order/paypals'),
						array('label'=>'Account Login', 'url'=>'order/accounts'),
						
				),
				'ticket' =>array(
					
						array('label'=>'My Tickets', 'url'=>'ticket'),
				),
				'terms' =>array(
						array('label'=>'Terms And Conditions', 'url'=>'terms/tos'),
						array('label'=>'Frequently Asked Questions', 'url'=>'terms/faq'),
				),
		),

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
				'btc_address' => '1CsaF11Qo96BzV1nEsAHwkPyHqSbBjAjXi',
				'bitcoin_min_pay' => 5,
		),
		'help' => array(
				'yahoo_support_1' => '',
				'yahoo_support_2' => '',
				'icq_1' => '687280773',
				'icq_2' => '687280773',
				'email_support'=>'',
		),
		'pm_method' => array(
				'perfect_min_pay' => 5,
				'perfect_account' => 'U4084297',
		),
		
		'app_uri' => array(
				'app_cronjob' => 'http://www.opps.sx/apps/cronjob',
				'app_payment' => 'http://www.opps.sx/apps/index',
				
		),
		
		'setting' => array (
				 'time_exp' => '20' 
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