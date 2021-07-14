<?php namespace Tests\MailService\SendGrid;
use PHPUnit_Framework_TestCase;
use Crazymeeks\Mailer\MailService\Sendgrid\Email;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class EmailTest extends PHPUnit_Framework_TestCase{
	
	public function testIfPrepareReturnClass(){

		
		$config = array('from' => 'from@mail.com', 'fromName' => 'LMS', 'bearer' => 'sendgridapi key', 'subject' => 'dafd');

		$logger = new Logger('test');
		$email = new Email($config, $logger);
		$email->to('jefferson.claud@nuworks.ph');
		
		$this->assertEquals($email, $email->prepare(null, array('name' => 'test')));
	}
}