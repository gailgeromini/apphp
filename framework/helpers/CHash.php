<?php
/**
 * CHash helper class file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * 
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * create
 * getRandomString
 * 
 */	  

class CHash
{    
    /**
     * Creates hash for given password 
     * @param string $algorithm (md5, sha1, sha256, whirlpool, etc)
     * @param string $data 
     * @param string $salt (should be the same throughout the system probably)
     * @return string (hashed/salted data)
     */
    public static function create($algorithm, $data, $salt=null)
    {        
    	if($salt){
			$context = hash_init($algorithm, HASH_HMAC, $salt);
    	}else{
    		$context = hash_init($algorithm); // Modified for encrypt without salt_key /* developer@blzone.ru -  April 24, 2013  */
    	}
    	hash_update($context, $data);
    	return hash_final($context);
    }

    /**
     * Creates random string
     * @param integer $length
     */
    public static function getRandomString($length = 10)
    {
        $template = '1234567890abcdefghijklmnopqrstuvwxyz';
        settype($template, 'string');
        settype($length, 'integer');
        settype($output, 'string');
        settype($a, 'integer');
        settype($b, 'integer');           
        for($a = 0; $a < $length; $a++){
            $b = rand(0, strlen($template) - 1);
            $output .= $template[$b];
        }       
        return $output;       
    }
   
}
