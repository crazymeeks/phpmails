<?php
/**
 * File helpers
 * @author Jeff Claud<jeffclaud17@gmail.com>
 */

/**
 * Check if file exist
 *
 * @param stirng $filename     The filename of a file you wish to check if exist
 *								it should have the file extension
 * @param null string $path    The full path of file you want to check
 * @return bool
 */
if(!function_exists('hasFile')){
	function hasFile($filename, $path = null){

		$filename = (is_null($path)) ? realpath(__DIR__ . '/../' . $filename) : rtrim($path, "/") . "/$filename";
		
		return file_exists($filename) ? true : false;
	}
}