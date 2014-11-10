<?php
/**
 * CValidator helper class file
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
 * STATIC:
 * ---------------------------------------------------------------
 * isEmpty          
 * isAlpha                  
 * isNumeric
 * isAlphaNumeric
 * isVariable
 * isMixed
 * isPhone
 * isPassword
 * isUsername
 * isEmail
 * isDate
 * isDigit
 * isInteger
 * isFloat
 * validateLength
 * validateMinlength
 * validateMaxlength
 * 
 */	  

class CValidator
{

	/**
	 * Checks if the given value is empty
	 * @param mixed $value 
	 * @param boolean $trim 
	 * @return boolean whether the value is empty
	 */
	public static function isEmpty($value, $trim = false)
	{
		return $value === null || $value === array() || $value === '' || ($trim && trim($value) === '');
	}

	/**
	 * Checks if the given value is an alphabetic value
	 * @param mixed $value 
	 * @return boolean 
	 */
    public static function isAlpha($value)
	{
        return preg_match('/^[a-zA-Z]+$/', $value);
    }

	/**
	 * Checks if the given value is a numeric value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isNumeric($value)
	{
        return preg_match('/^[0-9]+$/', $value);
    }

	/**
	 * Checks if the given value is a alpha-numeric value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isAlphaNumeric($value)
	{
        return preg_match('/^[a-zA-Z0-9]+$/', $value);
    }
    
	/**
	 * Checks if the given value is a variable name in PHP
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isVariable($value)
	{
        return preg_match('/^[a-zA-Z]+[0-9a-zA-Z_]*$/', $value);
    }

	/**
	 * Checks if the given value is a alpha-numeric value and spaces
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isMixed($value)
	{
        return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
    }

	/**
	 * Checks if the given value is a phone number
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isPhone($value)
	{
        return preg_match('/^[\d]{3}[-]{1}[\d]{3}[-]{1}[\d]{4}$/', $value);
    }

	/**
	 * Checks if the given value is a password 
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isPassword($value)
	{
        return preg_match('/^[a-zA-Z0-9_\-!@#$%^&*()]{6,20}$/', $value);
    }

	/**
	 * Checks if the given value is a username
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isUsername($value)
	{
		if(preg_match('/^[a-zA-Z0-9_\-]{6,20}$/', $value) && !self::isNumeric($value)){
			return true;
		}
        return false;
    }

	/**
	 * Checks if the given value is an email
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isEmail($value)
	{
        return preg_match('/^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/', $value);
    }

	/**
	 * Checks if the given value is a date value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isDate($value)
	{
        return self::isNumeric(strtotime($value));
    }

	/**
	 * Checks if the given value is a digit value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isDigit($value)
    {
		return ctype_digit($value);
    }

	/**
	 * Checks if the given value is an integer value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isInteger($value)
    {
		return is_int($value);
    }

	/**
	 * Checks if the given value is a float value
	 * @param mixed $value
	 * @return boolean
	 */
    public static function isFloat($value)
    {
		return is_float($value);
    }

	/**
	 * Validates the length of the given value
	 * @param string $value
	 * @param int $min
	 * @param int $max
	 * @return boolean
	 */
    public static function validateLength($value, $min, $max)
	{
		$length = strlen($value);
        return ($length >= $min && $length <= $max);
    }
	
	/**
	 * Validates the minimum length of the given value
	 * @param string $value
	 * @param int $min
	 * @return boolean
	 */
    public static function validateMinlength($value, $min)
    {
        return (strlen($value) < $min) ? false : true;
    }
	
	/**
	 * Validates the maximum length of the given value
	 * @param string $value
	 * @param int $max
	 * @return boolean
	 */
    public static function validateMaxlength($value, $max)
    {
        return (strlen($value) > $max) ? false : true;
    }
	
}    
