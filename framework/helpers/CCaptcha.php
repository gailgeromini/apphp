<?php
/**
 * CCaptcha helper class file
 *
 * @project Blzone Marketplace
 * @author TOM <BLZONE MARKETPLACE>
 * @link https://www.blzone.ru/
 * @copyright Copyright (c) 2013 SIMPLE CAPTCHA
 *
 * USAGE:
 * ----------
 * 1. Standard call CCaptcha::parseCaptcha()
 * 2. Direct call CCaptcha::parseCaptcha()
 * 
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * $captcha
 * 
 */	  
include(dirname(__FILE__).'/../vendors/captcha/captcha.php');

class CCaptcha
{
    public static function parseCaptcha()
    {
		$captcha = new SimpleCaptcha();
		return $captcha->CreateImage();
	}

	
	
}