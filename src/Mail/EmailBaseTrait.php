<?php namespace Crazymeeks\Mailer\Mail;

/**
 * Base Trait for all Email classes
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 * @package phpmailer
 */
use Closure;
use Crazymeeks\Mailer\Exceptions\PHPMailerExceptions;
trait EmailBaseTrait{
	use EmailMimeTrait;
	/**
	 * Location of email data
	 * @var
	 */
	protected $emaildata;
	
	/**
	 * Email content type(html, or plain text only)
	 */
	protected $mime = array();

	/**
	 * Monolog instance
	 * @var
	 */
	protected $logger;

	/**
	 * The email service instance
	 * @var
	 */
	protected $email_instance;

	/**
	 * Contains data to be to the email template
	 * @var array
	 */
	protected $view_data;

	/**
	 * Holds data and html
	 * @var
	 */
	protected $html;

	/**
	 * The email template file
	 * @var
	 */
	protected $template = null;

	/**
	 * Location of overloaded data
	 */
	private $data = array();

	protected $_from = null;

	protected $_to = null;

	protected $_cc = null;

	protected $_bcc = null;

	protected $_bearer = null;

	protected $_version = '5.1.0';

	protected $_mailer_host;

	protected $_endpoint;

	protected $subject =  'New email';

	protected $_username;

	protected $_password;

	/**
	 * The email category. This can be see
	 * in the sendgrid dashboard
	 * @var array
	 */
	protected $category = array();


	/**
	 * The email sender
	 * @param string $receptient
	 * @return $this
	 */
	public function from($sender){
		$this->_from = $sender;
		return $this;
	}

	/**
	 * Get the sender's email
	 *
	 * @return string
	 */
	public function getFrom(){
		return $this->_from;
	}

	/**
	 * The email receptient
	 * @param string $receptient       Receptient can also be in an array format
	 * @return $this
	 */
	public function to($receptient){
		if(is_array($receptient)){
			$this->logger->error("to() method receives data with type: " . gettype($receptient) . ", this has been removed. This method only accepts string");
			throw new PHPMailerExceptions("to() method receives data with type: " . gettype($receptient) . ", this has been removed. This method only accepts string");
		}
		
		$this->_to = $receptient;
		return $this;
	}

	/**
	 * Get the receiver's email address
	 *
	 * @return string
	 */
	public function getTo(){
		return $this->_to;
	}

	/**
	 * The email receptient as carbon copy
	 *
	 * @return $this
	 */
	public function cc(){
		$args = func_get_args();
		$cc = array();
		foreach($args as $arg){
			$cc[] = array('email' => $arg);
		}

		$this->_cc = $cc;

		return $this;
	}

	/**
	 * Get the cc emails
	 *
	 * @return array
	 */
	public function getCC(){
		return $this->_cc;
	}

	/**
	 * The email receptient as blind carbon copy
	 * @param array $receptients
	 * @return $this
	 */
	public function bcc(){
		$args = func_get_args();
		$bcc = array();
		foreach($args as $arg){
			$bcc[] = array('email' => $arg);
		}

		$this->_bcc = $bcc;

		return $this;
	}

	/**
	 * Get the Bcc emails
	 *
	 * @return array
	 */
	public function getBcc(){
		return $this->_bcc;
	}

	/**
	 * Set email service endpoint
	 * 
	 * @param string $endpoint
	 * @return $this
	 */
	public function setEmailEndpoint($endpoint){
		$this->_endpoint = $endpoint;
		return $this;
	}

	/**
	 * Get the email service endpoint
	 *
	 * @return string
	 */
	public function getEmailEndpoint(){
		return $this->_endpoint;
	}

	/**
	 * Get the email category
	 *
	 * @return array
	 */
	public function category($categories){
		if(!is_array($categories) && empty($categories)){
			throw new PHPMailerExceptions('Invalid category. Array expected, ' . gettype($categories) . ' given.');
		}
		$this->category = $categories;
	}

	public function getCategory(){
		return $this->category;
	}

	/**
	 * Set the mail service api url
	 *
	 * @param string $url
	 * @return $this
	 */
	public function setMailerServiceHost($url){
		$this->_mailer_host = $url;
		return $this;
	}

	public function getMailerServiceHost(){
		return $this->_mailer_host;
	}

	/**
	 * Set the mail bearer. This is the api key
	 *
	 * @param string $bearer
	 * @return $this
	 */
	public function setBearer($bearer){
		$this->_bearer = $bearer;
		return $this;
	}

	/**
	 * Get the mail bearer/api key
	 *
	 * @return string
	 */
	public function getBearer(){
		return $this->_bearer;
	}

	/**
	 * Set the version of the mail provider
	 *
	 * @param string $version
	 * @return $this
	 */
	public function setProviderVersion($version){
		$this->_version = $version;
		return $this;
	}

	public function getProviderVersion(){
		return $this->_version;
	}

	/**
	 * The email receptient
	 * @param string $template
	 * @param $vars   The variable to be pass to the view
	 * @return mixed
	 * @deprecated send() method will be deprecated in version 1.1. Please use prepare() instead
	 */
	public function send($template = null, $vars){
		try{
			$this->logger->error("send() method will be deprecated in version 1.1. Please use prepare() instead");
			trigger_error("send() method will be deprecated in version 1.1. Please use prepare() instead", E_USER_NOTICE);

			$this->setViewData($vars);
			$data = $this->getViewData();

			$this->prepare($template, $data)->mailsend();
			
		}catch(PHPMailerExceptions $e){
			error_log("Error: ", $e->getMessage());
		}
	}

	/**
	 * Prepare email for send
	 * @param string $template
	 * @param array $data        The data that will be pass to the view
	 * @param Closure $callback
	 * @return $this
	 */
	public function prepare($template = null, array $data = array(), $callback = null){
		
		try{

			// set the template
			$this->setTemplate($template);

			if(empty($data))
				throw new PHPMailerExceptions('Second parameter of method ' . __METHOD__ . ' is expected. None was given');
			// set the data
			$this->setViewData($data);

			// extract the data
			extract($this->getViewData());

			ob_start();
			// get the template
			require $this->getTemplate();

			$this->html = ob_get_clean();

			if($callback instanceof Closure)
				call_user_func($callback, $this);

			return $this;

		}catch(PHPMailerExceptions $e){
			$this->logger->error('Error: ' . $e->getMessage());
		}
	}

	/**
	 * Set the email subject
	 * 
	 * @param string $subject
	 * @return $this
	 */
	public function subject($subject){
		$this->subject = $subject;
		return $this;
	}
	public function getSubject(){
		return $this->subject;
	}

	/**
	 * Set the view template
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template = null){
		if(is_null($template)){
			$this->template = realpath(__DIR__ . '/../Templates/view/email.phtml');
		}else{
			$doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
			$template_directory = explode(".", $template);
			$template_file = implode("/", $template_directory) . '.phtml';
			$directory = $doc_root . '/' . $template_file;
			
			if(!file_exists($directory)){
				$this->logger->error("Cannot find the template file.");
				throw new PHPMailerExceptions("Cannot find the template file.");
			}
			$this->template = $directory;	
		}
	}

	/**
	 * Get the view template
	 * @return
	 */
	public function getTemplate(){
		return $this->template;
	}

	/**
	 * Set data that will be used in our template
	 *
	 * @param array $data
	 * @return $this
	 */
	public function setViewData(array $data){
		try{
			$this->view_data = $data;
			return $this;
		}catch(PHPMailerExceptions $e){
			$this->logger->error("Error: " . $e->getMessage());
		}
	}

	/**
	 * Get the data that will be pass to the view
	 * @return array
	 */
	public function getViewData(){
		return $this->view_data;
	}

	/**
	 * The email content the user should receive
	 */
	public function setContent($data){
		$this->emaildata = $data;
	}

	/**
 	 * Get email content
 	 */
	public function getContent(){
		return $this->emaildata;
	}

	/**
	 * PHP's magic method
	 */
	public function __set($name, $value){
		if($name == 'mime'){

		}
		$this->data[$name] =  $value;
	}

	public function __get($name){
		if(array_key_exists($name, $this->data)){
			if($name == 'mime'){
				// if($this->data['mime'] == ''){

				// }
				//$this->test();exit;
			}


		}
	}
	/**
	 * As of PHP 5.1.0
	 * Triggered when calling isset or empty()
	 */
	public function __isset($name){
		return $this->data[$name];
	}
}