<?php namespace Crazymeeks\Mailer\Core;
/**
 * Concrete Mailer Repository interface
 * @author Jeff Claud<jefferson.claud@nuworks.ph>
 * @since 2016
 * @package phpmailer
 */
interface MailerRepositoryInterface{

	/**
	 * The email sender
	 * @param string $receptient
	 * @return $this
	 */
	public function from($sender);

	/**
	 * The email receptient
	 * @param string $receptient
	 * @return $this
	 */
	public function to($receptient);

	/**
	 * The email receptient as carbon copy
	 *
	 * @return $this
	 */
	public function cc();

	/**
	 * The email receptient as blind carbon copy
	 * @return $this
	 */
	public function bcc();

	/**
	 * The email receptient
	 * @param string $template
	 * @param $vars         The variable to be pass to the view
	 * @return mixed
	 */
	public function send($template = null, $vars);

}