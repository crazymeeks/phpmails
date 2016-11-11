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

			$this->_from = $configs['from'];
			/*$this->_to = (!isset($configs['to'])) ?: $configs['to']; //: null;
			$this->_cc = (isset($configs['cc'])) ? $configs['cc'] : null;
			$this->_bcc = (isset($configs['bcc'])) ? $configs['bcc'] : null;*/
			$this->_bearer = $configs['bearer'];

			/**
		 	 * @todo Future improvement - _mailer_host should be definable in configuration file
			 */
			$this->_mailer_host = 'https://api.sendgrid.com/';

			/**
		 	 * @todo Future improvement - _endpoint should be definable in configuration file
			 */
			$this->_endpoint = 'v3/mail/send';

			$this->subject = (isset($configs['subject']) && !empty($configs['subject'])) ? $configs['subject'] : $configs['subject'];
			$this->_username = $configs['username'];
			$this->_password = $configs['password'];

			$this->logger = $logger;
			$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../../storage/logs/sendgrid.log', Logger::WARNING));
			$this->email_instance = $this;
		}catch(PHPMailerExceptions $e){
			error_log('Can\'t proceed. ' . $e->getMessage());
			throw new PHPMailerExceptions('Can\'t proceed. ' . $e->getMessage());
		}
	}

}