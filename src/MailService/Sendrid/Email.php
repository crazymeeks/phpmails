<?php namespace Crazymeeks\Mailer\MailService\Sendgrid;

/**
 * Email class
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 * @package phpmailer
 */
use Crazymeeks\Mailer\Exceptions\PHPMailerExceptions;
use Crazymeeks\Mailer\Mail\EmailAbstract;
use Crazymeeks\Mailer\Mail\ExtendedMailerRepositoryInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Email extends EmailAbstract implements ExtendedMailerRepositoryInterface{

	public function __construct($configs = array(), Logger $logger){
		try{
			if(!isset($configs['from'])){
				error_log("Email sender not found");
				throw new PHPMailerExceptions('Please provide email \'from\'');
			}
			if(!isset($configs['bearer'])){
				error_log("API Key not found. Please provide your sendgrid api key.");
				throw new PHPMailerExceptions('Please sendgrid api key not found');
			}

			if(!isset($configs['username'])){
				error_log("Sendgrid username not found. Please provide your sendgrid username.");
				throw new PHPMailerExceptions('Please provide sendgrid username');
			}

			if(!isset($configs['password'])){
				error_log("Sendgrid password not found. Please provide your sendgrid password.");
				throw new PHPMailerExceptions('Please provide sendgrid password');
			}

			// set the email sender
			$this->from($configs['from']);
		
			$this->setBearer($configs['bearer']);
			
			// set provider version
			$this->setProviderVersion('5.1.0');

			/**
		 	 * @todo Future improvement - _mailer_host should be definable in configuration file
			 */
			$this->setMailerServiceHost('https://api.sendgrid.com/');
			
			/**
		 	 * @todo Future improvement - _endpoint should be definable in configuration file
			 */
			$this->setEmailEndpoint('v3/mail/send');
			

			$this->subject = (isset($configs['subject']) && !empty($configs['subject'])) ? $configs['subject'] : $configs['subject'];

			// $this->_username = $configs['username'];
			// $this->_password = $configs['password'];

			$this->logger = $logger;
			$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../../storage/logs/sendgrid.log', Logger::WARNING));
			$this->email_instance = $this;
		}catch(PHPMailerExceptions $e){
			error_log('Can\'t proceed. ' . $e->getMessage());
			$this->logger->error('Can\'t proceed. ' . $e->getMessage());
			throw new PHPMailerExceptions('Can\'t proceed. ' . $e->getMessage());
		}
	}

	/**
	 * Email sender
	 * @param \Crazymeeks\Mailer\Core\MailerRepositoryInterface
	 * @param mixed $html
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

}