<?php
// Adding gmail branch

require_once('./vendor/autoload.php');

use Crazymeeks\Mailer\MailManager;

// Initialized MailManager
$mailer = MailManager::initialize();


/*****************
 * USAGE EXAMPLE *
 *****************
 * You can send an email using this:
 * Note: this type of usage will be removed in version 1.1. Please example 2 instead
 * 
 	Example 1:

 	$name = 'JohnC';
	$email = array('user1@gmail.com', 'user2@example.com');

   for($a = 0; $a < count($email); $a++){
	   $mailer->to($email[$a])->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));	
   }
 
   Example 2:

  $mailer->prepare(null, $data = ['name' => 'John'], function($mail){
 	$mail->to('user1@gmail.com')
 		 ->cc('user1@gmail.com', 'user2@gmail.com')
 		 ->bcc('user3@gmail.com', 'user4@gmail.com')
 		 ->subject('Test email');
 })->mailsend();
 */
 $mailer->prepare(null, $data = ['name' => 'John'], function($mail){
 	$mail->to('jeffersonclaud23@gmail.com')
 		 ->subject('Test if working');
 })->mailsend();