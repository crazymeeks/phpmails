<?php namespace Crazymeeks\Mailer;

/**
 * MailManager
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 */
use Monolog\Logger;
use Crazymeeks\Mailer\Factories\EmailFactory;
class MailManager{

	/**
	 * Environment configuration object
	 * used vlucas/Dotenv
	 * @var
	 */
	protected static $env;

	/**
	 * Configuration
	 * @var array
	 */
	protected static $configs = array();

	/**
	 * Mailer service instance
	 * @var
	 */
	protected static $instance;

	/**
	 * Initialize the mailer service
	 *
	 */
	public static function initialize(){
		$email = '';
		$doc = realpath($_SERVER['DOCUMENT_ROOT']);
		if(hasFile(".env", $doc)){
			self::$env = new \Dotenv\Dotenv($doc);
			self::$env->load();
			self::$configs = array(
				'mailer' => getenv('MAILER'),
				'mail_protocol' => getenv('MAIL_PROTOCOL'),
				'mail_port' => getenv('MAIL_PORT'),
				'bearer' => getenv('MAIL_API_KEY'),
				'from' => getenv('MAIL_FROM'),
				'fromName' => getenv('MAIL_FROM_NAME') ? getenv('MAIL_FROM_NAME') : getenv('MAIL_FROM') ,
				'username' => getenv('USERNAME'),
				'password' => getenv('PASSWORD'),
				'subject' => getenv('MAIL_SUBJECT'),
			);
			$email = ucfirst(strtolower(getenv('MAILER')));
		}else{
			$environment = require_once((realpath(__DIR__ . '/../config/environment.php')));
			self::$configs = array(
				'mailer' => $environment['mailer'],
				'mail_protocol' => $environment['mail_protocol'],
				'bearer' => $environment['bearer'],
				'from' => $environment['from'],
				'fromName' => $environment['fromName'] ? $environment['fromName'] : $environment['from'],
				'username' => $environment['username'],
				'password' => $environment['password'],
				'subject' => $environment['subject'],
			);
			$email = ucfirst(strtolower($environment['mailer']));
		}

		$logger = new Logger('sendgrid');

		$class = EmailFactory::make(__NAMESPACE__ . '\MailService\\' . $email . '\\Email', self::$configs, $logger);
		return $class;
		//return self::$instance = new $class(self::$configs, $logger);

	}	

}