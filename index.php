<?php

require_once('./vendor/autoload.php');


use Crazymeeks\Mailer\MailManager;

$mailer = MailManager::initialize();

$name = 'John Doe';
$email = array('email1@example.com', 'email2@example.com');
$mailer->to($email)->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));	

exit;