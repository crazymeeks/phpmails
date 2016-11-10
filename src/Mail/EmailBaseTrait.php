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

	protected $_subject =  'New email';

	protected $_username;

	protected $_password;	


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
	 * The email receptient
	 * @param string $receptient       Receptient can also be in an array format
	 * @return $this
	 */
	public function to($receptient){
		if(is_array($receptient)){
			error_log("to() method receives data with type: " . gettype($receptient) . ", this has been removed. This method only accepts string");
			throw new PHPMailerExceptions("to() method receives data with type: " . gettype($receptient) . ", this has been removed. This method only accepts string");
		}
		
		$this->_to = $receptient;
		return $this;
	}

	/**
	 * The email receptient as carbon copy
	 * @param array $receptients
	 * @return $this
	 */
	public function cc(array $receptients){
		$this->_cc = $receptients;
		return $this;
	}

	/**
	 * The email receptient as blind carbon copy
	 * @param array $receptients
	 * @return $this
	 */
	public function bcc(array $receptients){
		$this->_bcc = $receptients;
		return $this;
	}

	/**
	 * The email receptient
	 * @param string $template
	 * @param $vars   The variable to be pass to the view
	 * @return mixed
	 */
	public function send($template = null, $vars){
		try{
			
			/*if(!is_array($vars)){
				error_log("Invalid parameter. Second parameter should an array.",0);
				throw new PHPMailerExceptions('Invalid parameter. Second parameter should an array.');
			}

			$doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
			$html = '';
			if(is_array($vars)){
				extract($vars);
				if(is_null($template)){
					ob_start();
					include realpath(__DIR__ . '/../Templates/view/email.phtml');
					$html = ob_get_clean();
				}else{
					$template_directory = explode(".", $template);
					$template_file = implode("/", $template_directory) . '.phtml';
					$directory = $doc_root . '/' . $template_file;

					if(!file_exists($directory)){
						error_log("Cannot find the template file.", 0);
						throw new PHPMailerExceptions('Cannot find the template file');
					}
					ob_start();
					include $directory;
					$html = ob_get_clean();
				}
			}
			*/

			$this->setViewData($vars);
			$data = $this->getViewData();

			$this->prepare($template, $data);

			//trigger_error("send() method will be deprecated in version 1.1. Please use prepare() instead");
			
			//$this->doSendEmail($this, $html);
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
			/*if(!empty($template)){
				if(!is_array($vars)){
					error_log("Invalid parameter. Second parameter should an array.",0);
					throw new PHPMailerExceptions('Invalid parameter. Second parameter should an array.');
				}

				$doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
				$html = '';
				if(is_array($vars)){
					extract($vars);
					if(is_null($template)){
						ob_start();
						include realpath(__DIR__ . '/../Templates/view/email.phtml');
						$html = ob_get_clean();
					}else{
						$template_directory = explode(".", $template);
						$template_file = implode("/", $template_directory) . '.phtml';
						$directory = $doc_root . '/' . $template_file;

						if(!file_exists($directory)){
							error_log("Cannot find the template file.", 0);
							throw new PHPMailerExceptions('Cannot find the template file');
						}
						ob_start();
						include $directory;
						$html = ob_get_clean();
					}
				}	
			}*/

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
	 * Set the view template
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template = null){
		if(is_null($template)){
			$this->template = realpath(__DIR__ . '/../Templates/view/email.phtml');
		}else{
			$template_directory = explode(".", $template);
			$template_file = implode("/", $template_directory) . '.phtml';
			$directory = $doc_root . '/' . $template_file;

			if(!file_exists($directory))
				$this->logger->error("Cannot find the template file.");
				throw new PHPMailerExceptions("Cannot find the template file.");
		
			$this->template = $directory;	
		}
	}

	/**
	 * Get the view template
	 * @return
	 */
	protected function getTemplate(){
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