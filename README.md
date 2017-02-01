## PHP Mail
Send email using Sendgrid. Gmail will be supported soon!  
This library is still under heavy development, so expect the  
changes soon :)

## What's new?  
1. You can now add cc and bcc when sending an email.  

## Change log  
* Sending email using $mailer->to('receiver@example.com')->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234')); already deprecated and will completely remove in version 1.1.  

## Requirements
PHP 5.3 or > 

## Installation
1. Install using composer  
composer require crazymeeks/phpmails  dev-master
  
If this command fails, copy this code in 'autoload' of your composer.json  
"crazymeeks/phpmails": "dev-master"  
and execute composer update  

## Configuration

## Using dotenv(.env) file
## Create .env file in the root with the following content: 
MAILER=sendgrid  
MAIL_PROTOCOL=TLS  
MAIL_PORT=587  
MAIL_API_KEY=yourmailerapikey  
MAIL_FROM=emailsender@example.com  
USERNAME=username  
PASSWORD=password  
MAIL_SUBJECT="This subject must enclosed with double quote"

## Using environment.php
## After installation
Find vendor\crazymeeks\phpmail\config\environment.php  
and add your mail service credentials

## Usage

Put this code in your php file  
use Crazymeeks\Mailer\MailManager;  
$mailer = MailManager::initialize();  


## Recommended Usage
$mailer->prepare('your.template.email', $data = ['name' => 'John'], function($mail){  
 	$mail->to('user1@gmail.com')  
 		 ->cc('user1@gmail.com', 'user2@gmail.com')  
 		 ->bcc('user3@gmail.com', 'user4@gmail.com')  
 		 ->category(array('My SendGrid mail category'))  
 		 ->subject('Test email');  
 })->mailsend();


#Note:  
Example 1 & 2 is already deprecated and will be removed in version 1.1. Please use the recommended usage
 
## Send method requires 2 parameters.  
## Parameter 1.
is the the path to your custom email  
template file delimited by dot(.)  
custom template file should be in .phtml extension  

## Parameter 2.
This is the array of data you need to pass in your view  

## Example 1 without using custom template(deprecated)
$mailer->to('receiver@example.com')->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));

## Example 2 using custom template file(deprecated)
$mailer->to('receiver@example.com')->send('views.email', array('name' => $name, 'username' => 'test', 'password' => '1234'));



## Your email template(email.phtml)
html  
body  
	Name: <?php echo $name;?>  
	Username: <?php echo $username;?>  
	Password: <?php echo $password;?>  
body  
html  

You can also have a quick look at index.php file

## Report Bug
Email: jeffclaud17@gmail.com

## Author
Jeff Claud[jeffclaud17@gmail.com]

## Contributor
Jeffrey Mabazza[jeffrey_mabazza@yahoo.com.ph]