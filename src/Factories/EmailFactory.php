<?php namespace Crazymeeks\Mailer\Factories;
/**
 * The email class factory
 *
 * @author Jeff Claud<jeffclaud17@gmail.com>
 * @license MIT
 * @copyright 2016
 */

use Crazymeeks\Mailer\Exceptions\PHPMailerExceptions;
class EmailFactory{
	
	/**
	 * Creates an object during runtime
	 *
	 * @param string $class      The class which will be instantiate
	 * @param array $config      The configurations needed during instantiation
	 * @param array $options     The additional options
	 * @return Object
	 */
	public static function make($class, $config = array(), $options = array()){

		if(class_exists($class)){
			return new $class($config, $options);
		}
		throw new PHPMailerExceptions('Cannot create an object. Class does not exists.');
	}
}