<?php

require_once('./vendor/autoload.php');


use Crazymeeks\Mailer\MailManager;

$mailer = MailManager::initialize();

$name = 'John Doe';
$mailer->to('john.doe@example.com')->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));
exit;