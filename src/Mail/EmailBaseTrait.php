<?php namespace Crazymeeks\Mailer\Mail;
/**
 * Base Trait for all Email classes
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 * @package phpmailer
 */
use Closure;
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
	 * Location of overloaded data
	 */
	private $data = array();

	protected $_from = null;

	protected $_to = null;

	protected $_cc = null;

	protected $_bcc = null;

	protected $_bearer = null;

	protected $_mailer_host;

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
	 * @param string $receptient
	 * @return $this
	 */
	public function to($receptient){
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
		if(!is_array($vars)){
			throw new PHPMailerExceptions('Invalid parameter. Second parameter should an array.');
		}
		$doc_root = realpath($_SERVER['DOCUMENT_ROOT']);

		if(is_array($vars)){
			extract($vars);
			if(is_null($template)){
				ob_start();
				require_once(realpath(__DIR__ . '/../Templates/view/email.phtml'));
				$html = ob_get_contents();
			}else{
				$template_directory = explode(".", $template);
				$template_file = implode("/", $template_directory) . '.phtml';
				$directory = $doc_root . '/' . $template_file;

				if(!file_exists($directory)){
					throw new PHPMailerExceptions('Cannot find the template file');
				}
				ob_start();
				require_once($directory);
				$html = ob_get_contents();
			}
		}

		$this->doSendEmail($this, $html);
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