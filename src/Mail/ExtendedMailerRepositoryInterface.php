<?php namespace Crazymeeks\Mailer\Mail;
/**
 * Concrete Mailer Repository interface
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 * @package phpmailer
 */

use Crazymeeks\Mailer\Core\MailerRepositoryInterface;

interface ExtendedMailerRepositoryInterface extends MailerRepositoryInterface{

	/**
	 * The email content the user should receive
	 * @param mixed $data
	 */
	public function setContent($data);

}