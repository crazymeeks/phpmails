<?php

/**
 * Application environment in case .env
 * does not exists
 */
return array(
	'mailer' => 'sendgrid',
	'mail_protocol' => 'TLS',
	'mail_port' => 587,
	'bearer' => 'api key of provider',
	'from' => 'emailsend@example.com',
	'subject' => 'New email',
	'username' => 'your sendgrid username',
	'password' => 'your sendgrid password',

);