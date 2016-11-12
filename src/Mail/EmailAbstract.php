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

				$params = array('personalizations' => array( 
										array('to' => array(array('email' => $mailer->getTo())),
											  'cc' => $mailer->getCC(),
											  'bcc' => $mailer->getBcc(),
											  'subject' => $mailer->getSubject()
										)),

								'from' => array('email' => $mailer->getFrom()),
								'content' => array(
									array('type' => 'text/html', 'value' => $html)
								)
				);
				if(is_null($params['personalizations'][0]['cc']))
					unset($params['personalizations'][0]['cc']);

				if(is_null($params['personalizations'][0]['bcc']))
					unset($params['personalizations'][0]['bcc']);

				// header("Content-Type: application/json");
				// echo json_encode($params);exit;

				$request_body = json_decode(json_encode($params));

				$url = (rtrim($mailer->getMailerServiceHost(), '/')) . '/' . ltrim($mailer->getEmailEndpoint(), '/');

				$curl = curl_init($url);

				$headers = array(
	            'Authorization: Bearer ' . $mailer->getBearer(),
	            'User-Agent: sendgrid/' . $mailer->getProviderVersion() . ';php',
	            'Accept: application/json'
	            );
				
		        curl_setopt_array($curl, [
		            CURLOPT_RETURNTRANSFER => true,
		            CURLOPT_HEADER => 1,
		            CURLOPT_CUSTOMREQUEST => strtoupper('post'),
		            CURLOPT_SSL_VERIFYPEER => false,
		        ]);

		        $encodedBody = json_encode($request_body);
		        curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedBody);
		            $headers = array_merge($headers, ['Content-Type: application/json']);

		        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		        $response = curl_exec($curl);

		        curl_close($curl);
				//return true;
			}
			//return false;
		}catch(PHPMailerExceptions $e){
			error_log(__METHOD__ . ' parameter should be an instance of ExtendedMailerRepositoryInterface');
			throw new PHPMailerExceptions(__METHOD__ . ' parameter should be an instance of ExtendedMailerRepositoryInterface');
		}
	}

	/**
	 * Send email
	 */
	public function mailsend(){
		$this->doSendEmail($this->email_instance, $this->html);
	}
}