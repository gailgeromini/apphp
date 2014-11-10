<?php
/**
 * CDebug core class file
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
 * init                                                 getFormattedMicrotime 
 * addMessage
 * getMessage
 * displayInfo
 * 
 */	  

class CDebug
{
	/** @var string */
    private static $_startTime;
	/** @var string */
    private static $_endTime;
	/** @var array */
	private static $_arrGeneral;
	/** @var array */
    private static $_arrParams;
	/** @var array */
    private static $_arrWarnings;    
	/** @var array */
    private static $_arrErrors;
	/** @var array */
	private static $_arrQueries;
    

    /**
     * Class init constructor
     */
    public static function init()
    {
        if(APPHP_MODE != 'debug') return false;
        
        self::$_startTime = self::getFormattedMicrotime();
    }

    /**
     * Add message to the stack
     * @param string $type
     * @param string $key
     * @param string $val
     * @param string $storeType
     */
    public static function addMessage($type = 'params', $key = '', $val = '', $storeType = '')
    {
        if(APPHP_MODE != 'debug') return false;
        
        if($storeType == 'session'){
            A::app()->getSession()->set('debug-error', $val);
        }
		
        if($type == 'general') self::$_arrGeneral[$key][] = CFilter::sanitize('string', $val);
		else if($type == 'params') self::$_arrParams[$key] = CFilter::sanitize('string', $val);
        else if($type == 'errors') self::$_arrErrors[$key][] = CFilter::sanitize('string', $val);
		else if($type == 'warnings') self::$_arrWarnings[$key][] = CFilter::sanitize('string', $val);
		else if($type == 'queries') self::$_arrQueries[$key][] = CFilter::sanitize('string', $val);
    }    

    /**
     * Get message from the stack
     * @param string $type
     * @param string $key
     * @return string 
     */
    public static function getMessage($type = 'params', $key = '')
    {
		$output = '';
		
        if($type == 'errors') $output = isset(self::$_arrErrors[$key]) ? self::$_arrErrors[$key] : '';

		return $output;
    }    
    
    /**
     * Display debug info on the screen
     */
    public static function displayInfo()
    {
        if(APPHP_MODE != 'debug') return false;
		
        self::$_endTime = self::GetFormattedMicrotime();        

		$nl = "\n";
        
        // retrieve stored error messages and show them, then remove
        if($debugError = A::app()->getSession()->get('debug-error')){
            self::addMessage('errors', 'debug-error', $debugError);
            A::app()->getSession()->remove('debug-error');
        }        
		
		echo $nl.'<script type="text/javascript">				
			function appToggleTabs(key){
				var arrTabs = ["General","Params","Warnings","Errors","Queries"];
				document.getElementById("content"+key).style.display = "";
				document.getElementById("tab"+key).style.cssText = "color:#000;text-decoration:none;font-weight:normal;";
				for(var i = 0; i < arrTabs.length; i++) {
					if(arrTabs[i] != key){
						document.getElementById("content"+arrTabs[i]).style.display = "none";
						document.getElementById("tab"+arrTabs[i]).style.cssText = "color:#ccc;font-weight:normal;text-decoration:none;";
					}
				} 	
			}				
		</script>
		<div style="font:12px tahoma, verdana, sans-serif; color:#000;">
		<fieldset class="debug" style="background-color:#fff;border:1px solid #ccc;width:96%;margin:50px auto;text-align:left;">
		<legend>
			<b>DEBUG:&nbsp;</b>
			<span style="color:#999;">
				&nbsp;<a id="tabGeneral" style="color:#000;text-decoration:none;font-weight:normal;" href="javascript:void(\'General\')" onclick="javascript:appToggleTabs(\'General\')">'.A::t('core', 'General').'</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabParams" style="color:#ccc;text-decoration:none;font-weight:normal;" href="javascript:void(\'Params\')" onclick="javascript:appToggleTabs(\'Params\')">'.A::t('core', 'Params').' ('.count(self::$_arrParams).')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabWarnings" style="color:#ccc;text-decoration:none;font-weight:normal;" href="javascript:void(\'Warnings\')" onclick="javascript:appToggleTabs(\'Warnings\')">'.A::t('core', 'Warnings').' ('.count(self::$_arrWarnings).')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabErrors" style="color:#ccc;text-decoration:none;font-weight:normal;" href="javascript:void(\'Errors\')" onclick="javascript:appToggleTabs(\'Errors\')">'.A::t('core', 'Errors').' ('.count(self::$_arrErrors).')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabQueries" style="color:#ccc;text-decoration:none;font-weight:normal;" href="javascript:void(\'Queries\')" onclick="javascript:appToggleTabs(\'Queries\')">'.A::t('core', 'SQL Queries').' ('.count(self::$_arrQueries).')</a>
			</span>				
		</legend>
		<div id="contentGeneral" style="display:;padding:10px;">
			'.A::t('core', 'Total running time').': '.round((float)self::$_endTime - (float)self::$_startTime, 6).' sec.<br>
			'.A::t('core', 'Framework v').A::getVersion().'<br>';
			if(count(self::$_arrGeneral) > 0){
				echo '<pre>';
				print_r(self::$_arrGeneral);
				echo '</pre>';            
			}			
		echo '</div>
	
		<div id="contentParams" style="display:none;padding:10px;">';
			if(count(self::$_arrParams) > 0){
				echo '<pre>';
				print_r(self::$_arrParams);
				echo '</pre>';            
			}
		echo '</div>
	
		<div id="contentWarnings" style="display:none;padding:10px;">';
			if(count(self::$_arrWarnings) > 0){
				echo '<pre>';
				print_r(self::$_arrWarnings);
				echo '</pre>';            
			}
		echo '</div>
	
		<div id="contentErrors" style="word-wrap:break-word;display:none;padding:10px;">';
			if(count(self::$_arrErrors) > 0){
				//echo '<pre>';
				foreach(self::$_arrErrors as $msg){
                    print_r($msg);
                    echo '<br>';
                }               
				//echo '</pre>';            
			}
		echo '</div>
	
		<div id="contentQueries" style="display:none;padding:10px;">';
			if(count(self::$_arrQueries) > 0){
				echo '<pre>';
				print_r(self::$_arrQueries);
				echo '</pre>';            
			}
		echo '</div>
	
		</fieldset>
		</div>';
    }
    
    /**
     * Get formatted microtime
     * @return float
     */
    private static function getFormattedMicrotime()
    {
        if(APPHP_MODE != 'debug') return false;
        
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }    

}