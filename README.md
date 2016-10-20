## PHP Mail
Send email using Sendgrid. Gmail will also supported soon!  
This library is still under heavy development, so expect the  
changes soon :)  

GMAIL is currently under heavy development. Thanks to  
Jeffrey Mabazza!


## Installation
1. Install using composer  
composer require crazymeeks/phpmails  
  
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

then send email by calling the to()->send()  
## send method requires 2 parameters.  
## Parameter 1.
is the the path to your custom email  
template file delimited by dot(.)  
custom template file should be in .phtml extension  

## Parameter 2.
This is the array of data you need to pass in your view  

## Example 1 without using custom template.  
$mailer->to('receiver@example.com')->send(null, array('name' => $name, 'username' => 'test', 'password' => '1234'));

## Example 2 using custom template file
$mailer->to('receiver@example.com')->send('views.email', array('name' => $name, 'username' => 'test', 'password' => '1234'));

#Note:  
Passing an array in to() method will not work!

## Your email template(email.phtml)
html  
body  
	Name: <?php echo $name;?>  
	Username: <?php echo $username;?>  
	Password: <?php echo $password;?>  
body  
html  


## Author
Jeff Claud[jeffclaud17@gmail.com]

## Contributor
Jeffrey Mabazza[jeffrey_mabazza@yahoo.com.ph]