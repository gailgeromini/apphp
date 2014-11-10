<?php
/**
 * CAuthentication helper class file
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
 * handleLogin
 * handleLogged
 * getLoggedId
 * isLoggedIn
 * isGuest
 * 
 */	  

class CAuth
{
    
    /**
     * Handles access for non-logged users (block access)
     */
    public static function handleLogin()
    {
        if(APPHP_MODE == 'test') return '';
        if(A::app()->getSession()->get('loggedIn') == false){
            session_destroy();
            header('location: '.A::app()->getRequest()->getBaseUrl().'login/index');
            exit;
        }
    }

    /**
     * Handles access for logged users (redirect logged in users)
     * @param string $location
     */
    public static function handleLogged($location = '')
    {
        if(APPHP_MODE == 'test') return '';
        if(A::app()->getSession()->get('loggedIn') == true){
            header('location: '.A::app()->getRequest()->getBaseUrl().$location);
            exit;
        }
    }
    
    /**
     * Returns ID of logged user
     * @return string
     */
    public static function getLoggedId()
    {
        return A::app()->getSession()->get('loggedId');
    }

    /**
     * Checks if user is logged in and returns a result
     * @return bool
     */
    public static function isLoggedIn()
    {
        return (A::app()->getSession()->get('loggedId') == true) ? true : false;
    }
    
    /**
     * Checks if user is a guest (not logged in)
     * @return bool
     */
    public static function isGuest()
    {
        return (A::app()->getSession()->get('loggedId') == false) ? true : false;
    }    
    
}