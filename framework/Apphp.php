<?php
/**
 * Apphp bootstrap file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					    PRIVATE:		
 * ----------               ----------                      ----------
 * __construct              registerCoreComponents          autoload
 * run                      onBeginRequest                
 * setComponent             registerAppComponents 
 * getComponent
 * getRequest
 * getSession
 * setTimeZone
 * getTimeZone
 * attachEventHandler
 * detachEventHandler
 * hasEvent
 * hasEventHandler
 * raiseEvent
 * mapAppModule
 * setResponseCode
 * getResponseCode
 * setLanguage
 * getLanguage
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * init
 * app
 * powered
 * getVersion
 * t
 * 
 */

class A
{
    /**	@var object View */
	public $view;
	/** @var string */
	public $charset = 'UTF-8';
	/** @var string */
    public $sourceLanguage = 'en';
    
	/** @var object */
	private static $_instance;
	

	/** @var array */
	private static $_classMap = array(
		'Controller'    => 'controllers',
		'Model'         => 'models',
        ''              => 'models',
	);
	/** @var array */
	private static $_coreClasses = array(
		'CConfig'       => 'collections/CConfig.php',

        'CComponent'     => 'components/CComponent.php',
        'CClientScript'  => 'components/CClientScript.php',
		'CHttpRequest'   => 'components/CHttpRequest.php',
		'CHttpSession'   => 'components/CHttpSession.php',
        'CMessageSource' => 'components/CMessageSource.php',

        'CController'   => 'core/CController.php',
        'CDebug'        => 'core/CDebug.php',
        'CModel'        => 'core/CModel.php',
		'CRouter'       => 'core/CRouter.php',
		'CView'         => 'core/CView.php',

        'CActiveRecord' => 'db/CActiveRecord.php',
        'CDatabase'     => 'db/CDatabase.php',

		'CAuth'         => 'helpers/CAuth.php',
		'CFilter'       => 'helpers/CFilter.php',
        'CHash'         => 'helpers/CHash.php',
        'CHtml'    	    => 'helpers/CHtml.php',
        'CMailer'    	=> 'helpers/CMailer.php',
		'CCaptcha'    	=> 'helpers/CCaptcha.php',
		'CValidator'    => 'helpers/CValidator.php',
		'CWidget'       => 'helpers/CWidget.php',
		
		// refactor class helpers define
		
		'CRefactorProfile'       => 'helpers/CRefactorProfile.php', 
		'CRefactorValidator'  	 => 'helpers/CRefactorValidator.php',
		'CRefactorPagination' 	 => 'helpers/CRefactorPagination.php',
		'CRefactorUltilities' 	 => 'helpers/CRefactorUltilities.php',
		'CRefactorCURL' 	 	 => 'helpers/CRefactorCURL.php',
		'CRefactorWriteLogs' 	 => 'helpers/CRefactorWriteLogs.php',
		'CRefactorPerfectMethod' 	 => 'helpers/CRefactorPerfectMethod.php',
		'CRefactorBitcoinMethod' 	 => 'helpers/CRefactorBitcoinMethod.php',
		'CRefactorAdmin' 	 => 'helpers/CRefactorAdmin.php',
    );
	/** @var array */
	private static $_coreComponents = array(
		'session' 	   => array(
            'class' => 'CHttpSession'
        ),
		'request' 	   => array(
            'class' => 'CHttpRequest'
        ),
        'clientScript' => array(
            'class' => 'CClientScript'
        ),
        'coreMessages' => array(
            'class'    => 'CMessageSource',
            'language' => 'en',
        ),
        'messages'     => array(
            'class'    => 'CMessageSource',
        ),
	);
	/** @var array */
	private static $_coreModules = array(        
        // 'GeneralCleaning'   => '/core/modules/GeneralCleaning.php'
    );    
	/** @var array */
	private static $_appClasses = array(
        // empty
    );
	/** @var array */
	private static $_appComponents = array(
        // empty
    );
	/** @var array */
	private static $_appModules = array(
        'setup'         => 'modules/setup/'        
    );
	/** @var array */	
	private $_components = array(); 
	/** @var array */	
	private $_events;
	/** @var boolean */	
	private $_setup = false;
	/** @var string */
	private $_responseCode = '';
	/** @var string */    
    private $_language;	

    /**
	 * Class constructor
	 * @param array $configDir
	 */
	public function __construct($configDir)
	{
		spl_autoload_register(array($this, 'autoload'));        
        // include interfaces
        require(dirname(__FILE__).DS.'core'.DS.'interfaces.php');    
        
        $configMain = $configDir.'main.php';
        $configDb = $configDir.'db.php';
        
		if(is_string($configMain) && is_string($configDb)){
            // check if main configuration file exists
            if(!file_exists($configMain)){
                $arrConfig = array(
                    'defaultTemplate' => 'setup',
                    'defaultController' => 'Setup',
                    'defaultAction' => 'index',                                   
                );
                // block access to regular files when application is not properly installed
                $url = isset($_GET['url']) ? $_GET['url'] : '';
                if(!preg_match('/setup\//i', $url)){
                    $_GET['url'] = 'setup/index';
                }
                $this->_setup = true;
            }else{
                $arrConfig = require($configMain);
                // check if db configuration file exists and marge it with a main config file
                if(file_exists($configDb)){
                    $arrDbConfig = require($configDb);
                    $arrConfig = array_merge($arrConfig, $arrDbConfig);
                }
            }
            
            // save configuration array in config class
			CConfig::set($arrConfig);
		}
	}	
 
	/**
	 * Runs application
	 */
	public function run()
	{
		// specify error settings
        if(APPHP_MODE == 'debug' || APPHP_MODE == 'test'){
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }else{
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', APPHP_PATH.DS.'protected'.DS.'tmp'.DS.'logs'.DS.'error.log');
        }

		
		if(CConfig::get('session.cacheLimiter') == 'private,must-revalidate'){
			// to prevent 'Web Page exired' message on using submission method 'POST'
			session_cache_limiter('private, must-revalidate');    
		}
		
		// set default timezone
		$this->setTimeZone(CConfig::get('defaultTimeZone', 'UTC'));
		
		// initialize Debug class
        CDebug::init(); 
        
		// load view (must do it before app components registration)
		$this->view = new CView(); 
        $this->view->setTemplate(CConfig::get('defaultTemplate'));   
        
		// register framework core components
		$this->registerCoreComponents(); 

		// register application components
		$this->registerAppComponents();
        
		// run events
		if($this->hasEventHandler('onBeginRequest')) $this->onBeginRequest();
		
		$router = new CRouter(); 
		$router->route();
		
        CDebug::displayInfo();        
	}
 
	/**
	 * Class init constructor
	 * @param array $config
	 * @return Apphp
	 */
	public static function init($config = array())
	{
		if(self::$_instance == null) self::$_instance = new self($config);
		return self::$_instance;
	}
	
	/**
	 * Returns A object
	 * @param array $config
	 * @return Apphp
	 */
	public static function app()
	{
		return self::$_instance;
	}
	 
	/**
	 * Returns the version of ApPHP framework
	 * @return string 
	 */
	public static function getVersion()
	{
		return '0.2.4';
	}

	/**
	 * Returns a string that can be displayed on your Web page showing Powered-by-ApPHP
	 * @return string 
	 */
	public static function powered()
	{
		return self::t('core', 'Advanced Power of').' <a href="http://php.net/" rel="external">PHP</a>';
	}
	public static function copyright()
	{
		return self::t('core', '&copy;'.date('Y').' ').'<a href="terms" rel="external">wWw.Opps.Sx</a>';
	}
	/**
	 * Translates a message to the specified language
	 * @param string $category
	 * @param string $message
	 * @param array $params
	 * @param string $source
	 * @param string $language
	 * @return string 
	 */
	public static function t($category = 'app', $message, $params = array(), $source = null, $language = null)
	{
		if(self::$_instance !== null){
			if($source === null) $source = ($category === 'core') ? 'coreMessages' : 'messages';
			if(($source = self::$_instance->getComponent($source)) !== null){
				$message = $source->translate($category, $message, $language);
			}
		}
        
		if($params === array()){
			return $message;
        }else{
            if(!is_array($params)) $params = array($params);
            return $params !== array() ? strtr($message, $params) : $message;
        }
	}


	/**
	 * Autoloader
	 * @param str $className
	 * @return void
	 */
	private function autoload($className)
	{       
        if(isset(self::$_coreClasses[$className])){
            // include framework core classes
			include(dirname(__FILE__).DS.self::$_coreClasses[$className]);
        }else if(isset(self::$_appClasses[$className])){
            // include application component classes
            $classComponentDir = APPHP_PATH.DS.'protected'.DS.'components'.self::$_coreClasses[$className];
            $classFile = $classComponentDir.DS.$className.'.php';
            include($classFile);
		}else{            
            $classNameItems = preg_split('/(?=[A-Z])/', $className);
            // $classNameItems[0] - 
            // $classNameItems[1] - ClassName
            // $classNameItems[2] - Type (Controller, Model etc..)            
            $pureClassName = isset($classNameItems[1]) ? $classNameItems[1] : '';
            $pureClassType = isset($classNameItems[2]) ? $classNameItems[2] : '';
            
            if(isset(self::$_classMap[$pureClassType])){
                $classCoreDir = APPHP_PATH.DS.'protected'.DS.self::$_classMap[$pureClassType];    
                $classFile = $classCoreDir.DS.$className.'.php';
                if(is_file($classFile)){
                    include($classFile);
                }else{
                    $classModuleDir = APPHP_PATH.DS.'protected'.DS.$this->mapAppModule($pureClassName).self::$_classMap[$pureClassType];
                    $classFile = $classModuleDir.DS.$className.'.php';
                    if(is_file($classFile)){
                        include($classFile);
                    }else{
                        CDebug::addMessage('errors', 'missing-model', A::t('core', 'Unable to find the model class "{model}".', array('{model}'=>$className)), 'session');                        
                        header('location: '.$this->getRequest()->getBaseUrl().'error/index/code/500');
                        exit;
                    }
                }     
                CDebug::addMessage('general', 'classes', $className);
            }else{
                
            }
        }        
	}    

	/**
	 * Puts a component under the management of the application
	 * @param string $id
	 * @param class $component 
	 */
	public function setComponent($id, $component)
	{
		if($component === null){
			unset($this->_components[$id]);		
		}else{
			// for PHP_VERSION >= 5.3.0 you may use
			// $this->_components[$id] = $component::init();
            if($callback = @call_user_func_array($component.'::init', array())){
                $this->_components[$id] = $callback;    
            }else{
                CDebug::addMessage('warnings', 'missing-components', $component);    
            }            
		}
	}
 
	/**
	 * Returns the application component
	 * @param string $id
	 */
	public function getComponent($id)
	{
		return (isset($this->_components[$id])) ? $this->_components[$id] : null;
	}

	/**
	 * Returns the client script component
	 * @return ClientScript component
	 */
	public function getClientScript()
	{
		return $this->getComponent('clientScript');
	}
	
	/**
	 * Returns the request component
	 * @return HttpRequest component
	 */
	public function getRequest()
	{
		return $this->getComponent('request');
	}

	/**
	 * Returns the session component
	 * @return HttpSession component
	 */
	public function getSession()
	{
		return $this->getComponent('session');
	}

	/**
	 * Sets the time zone used by this application
	 * @param string $value 
	 * @see http://php.net/manual/en/function.date-default-timezone-set.php
	 */
	public function setTimeZone($value)
	{
		date_default_timezone_set($value);
	}

	/**
	 * Returns the time zone used by application
	 * @return string
	 * @see http://php.net/manual/en/function.date-default-timezone-set.php
	 */
	public function getTimeZone()
	{
		return date_default_timezone_get();
	}

	/**
	 * Attaches event handler
	 * @param string $name
	 * @param string $handler
	 */
	public function attachEventHandler($name, $handler)
	{
		if($this->hasEvent($name)){
			$name = strtolower($name);
			if(!isset($this->_events[$name])){
				$this->_events[$name] = array();				
			}
			if(!in_array($handler, $this->_events[$name])){
				$this->_events[$name][] = $handler;	
			}
		}else{
			CDebug::addMessage('errors', 'events-attach', A::t('core', 'Event "{class}.{name}" is not defined.', array('{class}'=>get_class($this), '{name}'=>$name)));
    	}
	}

	/**
	 * Detaches event handler
	 * @param string $name
	 */
	public function detachEventHandler($name)
	{
		if($this->hasEvent($name)){
			$name = strtolower($name);
			if(isset($this->_events[$name])){
				unset($this->_events[$name]);
			}
		}else{
			CDebug::addMessage('errors', 'events-detach', A::t('core', 'Event "{class}.{name}" is not defined.', array('{class}'=>get_class($this), '{name}'=>$name)));
		}
	}

	/**
	 * Checks whether an event is defined
	 * An event is defined if the class has a method named like 'onSomeMethod'
	 * @param string $name 
	 * @return boolean 
	 */
	public function hasEvent($name)
	{
		return !strncasecmp($name, 'on', 2) && method_exists($this, $name);
	}
	
	/**
	 * Checks whether the named event has attached handlers
	 * @param string $name 
	 * @return boolean 
	 */
	public function hasEventHandler($name)
	{
		$name = strtolower($name);
		return isset($this->_events[$name]) && count($this->_events[$name]) > 0;
	}

	/**
	 * Raises an event
	 * @param string $name 
	 */
	public function raiseEvent($name)
	{
		$name = strtolower($name);
		if(isset($this->_events[$name])){
			foreach($this->_events[$name] as $handler){
				if(is_string($handler[1])){
					call_user_func_array(array($handler[0], $handler[1]), array());
				}else{
                    CDebug::addMessage('errors', 'events-raising', A::t('core', 'Event "{{class}}.{{name}}" is attached with an invalid handler "{'.$handler[1].'}".', array('{class}'=>$handler[0], '{name}'=>$handler[1])));
				}
			}			
		}		
	}
    
	/**
	 * Maps application modules
	 * @param string $class
	 */
    public function mapAppModule($class)
    {
        return isset(self::$_appModules[strtolower($class)]) ? self::$_appModules[strtolower($class)] : '';
    }

	/**
	 * Sets response code 
	 * @param string $code
	 */
    public function setResponseCode($code = '')
    {
        $this->_responseCode = $code;
    }

	/**
	 * Sets response code 
	 */
    public function getResponseCode()
    {
        return $this->_responseCode;
    }
    
	/**
	 * Specifies which language the application is targeted to
	 * @param string $language 
	 */
	public function setLanguage($language = '')
	{
		$this->_language = $language;
	}

	/**
	 * Returns the language that is used for application
	 * @return string 
	 */
	public function getLanguage()
	{
		return $this->_language === null ? $this->sourceLanguage : $this->_language;
	}
    

	/**
	 * Registers the framework core components
	 * @see setComponent
	 */
	protected function registerCoreComponents()
	{
		foreach(self::$_coreComponents as $id => $component){
			$this->setComponent($id, $component['class']);
		}
	}
 
	/**
	 * Raised before the application processes the request
	 */
	protected function onBeginRequest()
	{
		$this->raiseEvent('onBeginRequest');
	}
    
	/**
	 * Registers the application components
	 * @see setComponent
	 */
	protected function registerAppComponents()
	{
		if(is_array(CConfig::get('components'))){
			foreach(CConfig::get('components') as $id => $component){
				$enable = isset($component['enable']) ? (bool)$component['enable'] : false;
                $class = isset($component['class']) ? $component['class'] : '';
                if($enable && $class){
                    self::$_appComponents[$id] = $class;
                    self::$_appClasses[$class] = 'components/'.$class.'.php';
                    $this->setComponent($id, $class);
				}
			}
		}
	}   
	
}