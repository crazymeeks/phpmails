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
class Email extends EmailAbstract implements ExtendedMailerRepositoryInterface{

	public function __construct($configs = array()){
		try{
			if(!isset($configs['from'])){
				throw new PHPMailerExceptions('Please provide email \'from\'');
			}
			if(!isset($configs['bearer'])){
				throw new PHPMailerExceptions('Please sendgrid api key not found');
			}

			if(!isset($configs['username'])){
				throw new PHPMailerExceptions('Please provide sendgrid username');
			}

			if(!isset($configs['password'])){
				throw new PHPMailerExceptions('Please provide sendgrid password');
			}

		}catch(PHPMailerExceptions $e){
			throw new PHPMailerExceptions('Can\'t proceed. ' . $e->getMessage());
		}
		$this->_from = $configs['from'];
		$this->_to = (!isset($configs['to'])) ?: $configs['to']; //: null;
		$this->_cc = (isset($configs['cc'])) ? $configs['cc'] : null;
		$this->_bcc = (isset($configs['bcc'])) ? $configs['bcc'] : null;
		$this->_bearer = $configs['bearer'];
		$this->_mailer_host = 'https://api.sendgrid.com/api/mail.send.json';//'https://api.sendgrid.com/v3/mail/send';
		$this->_subject = (isset($configs['subject']) && !empty($configs['subject'])) ? $configs['subject'] : $configs['subject'];
		$this->_username = $configs['username'];
		$this->_password = $configs['password'];
	}

}