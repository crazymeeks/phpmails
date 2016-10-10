<?php namespace Crazymeeks\Mailer\Mail;

use Crazymeeks\Mailer\Mail\EmailBaseTrait;
use Crazymeeks\Mailer\Exceptions\PHPMailerExceptions;

abstract class EmailAbstract{
	use EmailBaseTrait;
	/**
	 * Email sender
	 * @param \Crazymeeks\Mailer\Core\MailerRepositoryInterface
	 * @return mixed
	 */
	protected function doSendEmail(ExtendedMailerRepositoryInterface $mailer, $html){
		try{
			if($mailer instanceof $this){
				
				$data = array('api_user' => $mailer->_username,
							  'api_key' => $mailer->_password,
							  'to' => $mailer->_to,
							  //'toname' => 'Destination',
							  'subject' => $mailer->_subject,
							  'html' => $html,
							  'from' => $mailer->_from
				);

				$ch = curl_init();

				// // endpoint url
				curl_setopt($ch, CURLOPT_URL, $mailer->_mailer_host);

				// set request as regular post
				curl_setopt($ch, CURLOPT_POST, true);

				// set data to be send
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

				// set header
				// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Bearer: ' . $mailer->_bearer, 'application/json'));

				// return transfer as string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($ch);

				curl_close($ch);
				header('Content-Type: application/json');
				echo json_encode($response);exit;
			}
			//return false;
		}catch(PHPMailerExceptions $e){
			throw new PHPMailerExceptions(__METHOD__ . ' parameter should be an instance of ExtendedMailerRepositoryInterface');
		}
		
		
		
	}
}