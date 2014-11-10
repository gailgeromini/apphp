<?php
/**
 * CCaptcha Controller
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

class CaptchaController extends CController
{

	public function __construct()
	{
    	 A::app()->view->setTemplate('none'); // none display html
    }

   	public function parseAction()
	{
		CCaptcha::parseCaptcha(); // usage captcha 
    }

}