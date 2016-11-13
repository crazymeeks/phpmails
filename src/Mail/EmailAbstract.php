<?php namespace Crazymeeks\Mailer\Mail;
/**
 * Abstract Class for all email providers
 * All email classes should extend this class
 *
 * @author Jeff Claud<jeffclaud17@gmail.com>
 * @license MIT
 * @copyright 2016
 */


use Crazymeeks\Mailer\Mail\EmailBaseTrait;
use Crazymeeks\Mailer\Exceptions\PHPMailerExceptions;

abstract class EmailAbstract{
	use EmailBaseTrait;

	/**
	 * Send email
	 */
	public function mailsend(){
		$this->doSendEmail($this->email_instance, $this->html);
	}
}