<?php
// Adding gmail branch

require_once('./vendor/autoload.php');

use Crazymeeks\Mailer\MailManager;

// Initialized MailManager
$mailer = MailManager::initialize();

$name = 'John Doe';
//$email = array('user1@gmail.com', 'user2@example.com');
$email = array('jeffersonclaud23@gmail.com');
// Send email one by one
// for($a = 0; $a < count($email); $a++){
// 	$mailer->to($email[$a])->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));	
// }

/**
 * $data = array('name' => 'John Doe', 'tel' => '090987');
 * $mailer->prepare('template', $data, function($mail) use($data){
		$mail->to()
		  	 ->cc()
		  	 ->bcc();
 })->mailsend();
 */

 $mailer->prepare(null, $data = ['name' => 'John'], function($mail){
 	$mail->to('jeffclaud17@gmail.com');
 });