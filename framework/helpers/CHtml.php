<?php
/**
 * CHtml is a static class that provides a collection of helper methods for creating HTML elements
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
 * tag                      inputField                  renderAttributes 
 * openTag                  clientChange
 * closeTag
 * link
 * label
 * encode
 * decode
 * css
 * cssFile
 * script
 * scriptFile
 * form
 * openForm
 * closeForm
 * hiddenField
 * textField
 * passwordField
 * fileField
 * getIdByName
 * dropDownList
 * listBox
 * listOptions
 * textArea
 * checkBox
 * submitButton
 * resetButton
 * image
 * button
 * convertFileSize
 * 
 */	  

class CHtml
{
    /** @const string */
    const ID_PREFIX = 'ap';
    /** @var string */
    public static $afterRequiredLabel = ' <span class="required">*</span>';
    /** @var string */
    public static $count = 0;
    
	/**
	 * Generates an HTML tag
	 * @param string $tag
	 * @param array $htmlOptions
	 * @param bool $content
	 * @param bool $closeTag
	 * @return string - HTML tag
	 */
	public static function tag($tag, $htmlOptions = array(), $content = false, $closeTag = true)
	{
		$html = '<'.$tag.self::renderAttributes($htmlOptions);
		if($content === false){
			return $closeTag ? $html.' />' : $html.'>';
		}else{
			return $closeTag ? $html.'>'.$content.'</'.$tag.'>' : $html.'>'.$content;
		}
	}

	/**
	 * Generates an open HTML tag
	 * @param string $tag
	 * @param array $htmlOptions
	 * @return string - HTML tag
	 */
	public static function openTag($tag, $htmlOptions = array())
	{
		return '<'.$tag.self::renderAttributes($htmlOptions).'>';
	}

	/**
	 * Generates a close HTML tag
	 * @param string $tag
	 * @return string - HTML tag
	 */
	public static function closeTag($tag)
	{
		return '</'.$tag.'>';
	}

	/**
	 * Generates a hyperlink tag
	 * @param string $text
	 * @param string $url
	 * @param array $htmlOptions
	 * @return string - HTML tag
	 */
	public static function link($text, $url = '#', $htmlOptions = array())
	{
		if($url !== '') $htmlOptions['href'] = $url;
		return self::tag('a', $htmlOptions, $text);
	}

	/**
	 * Generates a label tag
	 * @param string $label
	 * @param string $for
	 * @param array $htmlOptions
	 * @return string - HTML tag
	 */
	public static function label($label, $for = false, $htmlOptions = array())
	{
		if($for === false) unset($htmlOptions['for']);
		else $htmlOptions['for'] = $for;        
		return self::tag('label', $htmlOptions, $label);
	}

	/**
	 * Encodes special characters into HTML entities
	 * @param string $text
	 * @return string 
	 */
	public static function encode($text)
	{
		return htmlspecialchars($text, ENT_QUOTES, A::app()->charset);
	}

	/**
	 * Decodes special HTML entities back to the corresponding characters
	 * @param string $text
	 * @return string
	 */
	public static function decode($text)
	{
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	/**
	 * Encloses the passed CSS content with a CSS tag
	 * @param string $text
	 * @param string $media
	 * @return string the CSS tag
	 */
	public static function css($text, $media = '')
	{
		if($media !== '') $media = ' media="'.$media.'"';
		return "<style type=\"text/css\"{$media}>\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n</style>";
	}

	/**
	 * Links to required CSS file
	 * @param string $url 
	 * @param string $media 
	 * @return string - HTML tag
	 */
	public static function cssFile($url, $media = '')
	{
		if($media !== '') $media=' media="'.$media.'"';
		return '<link rel="stylesheet" type="text/css" href="'.self::encode($url).'"'.$media.' />'."\n";
	}
	
	public static function faviconFile($url, $media = '')
	{
		if($media !== '') $media=' media="'.$media.'"';
		return '<link type="image/x-icon" href="'.self::encode($url).'"'.$media.' rel="icon" />'."\n";
	}
	/**
	 * Encloses the passed JavaScript within a Script tag
	 * @param string $text 
	 * @return string the Script tag
	 */
	public static function script($text)
	{
		return "<script type=\"text/javascript\">\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n</script>";
	}

	/**
	 * Includes a JavaScript file
	 * @param string $url 
	 * @return string
	 * @return string - HTML tag
	 */
	public static function scriptFile($url)
	{
		return '<script type="text/javascript" src="'.self::encode($url).'"></script>'."\n";
	}
	
	/**
	 * Generates an open form tag
	 * This is a shortcut to {@link openForm}
	 * @param mixed $action 
	 * @param string $method 
	 * @param array $htmlOptions 
	 * @return string 
	 */
	public static function form($action = '', $method = 'post', $htmlOptions = array())
	{
		return self::openForm($action, $method, $htmlOptions);
	}

	/**
	 * Generates an opening form tag
	 * Only the open tag is generated, a close tag should be placed manually at the end of the form
	 * @param mixed $action 
	 * @param string $method 
	 * @param array $htmlOptions 
	 * @return string 
	 * @see endForm
	 */
	public static function openForm($action = '', $method = 'post', $htmlOptions = array())
	{
		$htmlOptions['action'] = $url = $action;
		$htmlOptions['method'] = $method;
		$form = self::tag('form', $htmlOptions, false, false);		
		$hiddens = array();		
		if(!strcasecmp($method, 'get') && ($pos = strpos($url, '?')) !== false){
			foreach(explode('&', substr($url, $pos+1)) as $pair){
				if(($pos = strpos($pair, '=')) !== false){
					$hiddens[] = self::hiddenField(urldecode(substr($pair, 0, $pos)), urldecode(substr($pair, $pos+1)), array('id'=>false));
				}
			}
		}

		$request = A::app()->getRequest();
		if($request->getCsrfValidation() && !strcasecmp($method, 'post')){
			$hiddens[] = self::hiddenField($request->getCsrfTokenKey(), $request->getCsrfTokenValue(), array('id'=>false));
		}
		if($hiddens !== array()){
			$form .= "\n".implode("\n", $hiddens)."\n";
		}
		
		return $form;
	}

	/**
	 * Generates a closing form tag
	 * @return string 
	 * @see openForm
	 */
	public static function closeForm()
	{
		return '</form>';
	}

	/**
	 * Generates a hidden input
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
	 * @see inputField
	 */
	public static function hiddenField($name, $value = '', $htmlOptions = array())
	{
		return self::inputField('hidden', $name, $value, $htmlOptions);
	}

	/**
	 * Generates a textbox input
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
	 * @see inputField
	 */
	public static function textField($name, $value = '', $htmlOptions = array())
	{
		return self::inputField('text', $name, $value, $htmlOptions);
	}

	/**
	 * Generates a password field
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
	 * @see inputField
	 */
	public static function passwordField($name, $value = '', $htmlOptions = array())
	{
		return self::inputField('password', $name, $value, $htmlOptions);
	}

	/**
	 * Generates a file field
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
	 * @see inputField
	 */
	public static function fileField($name, $value = '', $htmlOptions = array())
	{
		return self::inputField('file', $name, $value, $htmlOptions);
	}    

	/**
	 * Generates a valid HTML ID based on name
	 * @param string $name 
	 * @return string 
	 */
	public static function getIdByName($name)
	{
		return str_replace(array('#', '[]', '][', '[', ']'), array('-', '', '_', '_', ''), $name);
	}

	/**
	 * Renders the HTML tag attributes
	 * @param string $htmlOptions
	 */
	private static function renderAttributes($htmlOptions)
	{
		// attributes that looks like attribute="attribute"
		static $specialAttributes=array(
			'checked'  => 1,
			'declare'  => 1,
			'defer'    => 1,
			'disabled' => 1,
			'ismap'    => 1,
			'multiple' => 1,
			'nohref'   => 1,
			'noresize' => 1,
			'readonly' => 1,
			'selected' => 1,
		);

		if($htmlOptions === array()) return '';

		$output = '';
		$encode = false;
		
		if(isset($htmlOptions['encode'])){
			$encode = (bool)$htmlOptions['encode'];
			unset($htmlOptions['encode']);
		}

		if(is_array($htmlOptions)){
			foreach($htmlOptions as $name => $value){
				if(isset($specialAttributes[$name])){
					if($value) $output .= ' '.$name.'="'.$name.'"';
				}else if($value !== null){
					$output .= ' '.$name.'="'.(($encode) ? self::encode($value) : $value).'"';
				}
			}			
		}
		
		return $output;
	}

	/**
	 * Generates an input HTML tag
	 * This method generates an input HTML tag based on the given name of input tag and value
	 * @param string $type 
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
	 */
	protected static function inputField($type, $name, $value, $htmlOptions)
	{
		$htmlOptions['type'] = $type;
		$htmlOptions['value'] = $value;
		$htmlOptions['name'] = $name;
		if(!isset($htmlOptions['id'])) $htmlOptions['id'] = self::getIdByName($name);
		else if($htmlOptions['id'] === false) unset($htmlOptions['id']);
		return self::tag('input', $htmlOptions, false);
	}
    
    /**
     * Draws textarea
	 * @param string $name 
	 * @param string $value 
	 * @param array $htmlOptions 
	 * @return string 
     */
	public static function textArea($name, $value='', $htmlOptions = array())
	{
		$htmlOptions['name'] = $name;
		if(!isset($htmlOptions['id'])) $htmlOptions['id'] = self::getIdByName($name);
		else if($htmlOptions['id'] === false) unset($htmlOptions['id']);
		return self::tag('textarea', $htmlOptions, isset($htmlOptions['encode']) && !$htmlOptions['encode'] ? $value : self::encode($value));
	}

	/**
	 * Generates a check box
	 * @param string $name
	 * @param boolean $checked 
	 * @param array $htmlOptions 
	 * @see inputField
	 */
	public static function checkBox($name, $checked = false, $htmlOptions = array())
	{
		if($checked){
			$htmlOptions['checked'] = 'checked';
		}else{
			unset($htmlOptions['checked']);
		}

		$value = isset($htmlOptions['value']) ? $htmlOptions['value'] : 1;
		// TODO self::clientChange('click', $htmlOptions);

		if(array_key_exists('uncheckValue', $htmlOptions)){
			$uncheck = $htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		}else{
			$uncheck = null;
		}

		if($uncheck !== null){
			// add a hidden field so that if the checkbox is not selected, it still submits a value
			if(isset($htmlOptions['id']) && $htmlOptions['id'] !== false){
				$uncheckOptions = array('id' => self::ID_PREFIX.$htmlOptions['id']);
			}else{
				$uncheckOptions = array('id' => false);
			}
			$hidden = self::hiddenField($name, $uncheck, $uncheckOptions);
		}else{
			$hidden = '';
		}

		// add a hidden field so that if the checkbox  is not selected, it still submits a value
		return $hidden.self::inputField('checkbox', $name, $value, $htmlOptions);
	}

    /**
     * Draws dropdown list
     * @param string $name
     * @param array $select
     * @param array $data
     * @param array $htmlOptions 
     * @return string 
     */	
	public static function dropDownList($name, $select, $data, $htmlOptions = array())
	{
		$htmlOptions['name'] = $name;
		if(!isset($htmlOptions['id'])) $htmlOptions['id'] = self::getIdByName($name);
		else if($htmlOptions['id'] === false) unset($htmlOptions['id']);
		// TODO self::clientChange('change', $htmlOptions);
		$options = "\n".self::listOptions($select, $data, $htmlOptions);
		return self::tag('select', $htmlOptions, $options);
	}

    /**
     * Draws dropdown list
     * @param string $name
     * @param array $select
     * @param array $data
     * @param array $htmlOptions 
     * @return string 
     */	
	public static function listBox($name, $select, $data, $htmlOptions = array())
	{
		if(!isset($htmlOptions['size'])) $htmlOptions['size'] = 4;
		if(isset($htmlOptions['multiple'])){
			if(substr($name,-2) !== '[]') $name .= '[]';
		}
		return self::dropDownList($name, $select, $data, $htmlOptions);
	}

	/**
	 * Generates the list of options
	 * @param mixed $selection
	 * @param array $listData
	 * @param array $htmlOptions 
	 * @return string 
	 */
	public static function listOptions($selection, $listData, &$htmlOptions)
	{
		$raw = isset($htmlOptions['encode']) && !$htmlOptions['encode'];
		$content = '';
		if(isset($htmlOptions['prompt'])){
			$content .= '<option value="">'.strtr($htmlOptions['prompt'], array('<'=>'&lt;', '>'=>'&gt;'))."</option>\n";
			unset($htmlOptions['prompt']);
		}
		if(isset($htmlOptions['empty'])){
			if(!is_array($htmlOptions['empty'])) $htmlOptions['empty'] = array(''=>$htmlOptions['empty']);
			foreach($htmlOptions['empty'] as $value=>$label){
				$content .= '<option value="'.self::encode($value).'">'.strtr($label,array('<'=>'&lt;', '>'=>'&gt;'))."</option>\n";
			}
			unset($htmlOptions['empty']);
		}
		if(isset($htmlOptions['options'])){
			$options = $htmlOptions['options'];
			unset($htmlOptions['options']);
		}else{
			$options = array();
		}
		$key = isset($htmlOptions['key']) ? $htmlOptions['key'] : 'primaryKey';
		if(is_array($selection)){
			foreach($selection as $i=>$item){
				if(is_object($item)) $selection[$i] = $item->$key;
			}
		}else if(is_object($selection)){
            $selection=$selection->$key;
        }
		foreach($listData as $key=>$value){
			if(is_array($value)){
				$content .= '<optgroup label="'.($raw?$key : self::encode($key))."\">\n";
				$dummy = array('options'=>$options);
				if(isset($htmlOptions['encode'])) $dummy['encode'] = $htmlOptions['encode'];
				$content .= self::listOptions($selection, $value, $dummy);
				$content .= '</optgroup>'."\n";
			}else{
				$attributes = array('value'=>(string)$key, 'encode'=>!$raw);
				if(!is_array($selection) && !strcmp($key,$selection) || is_array($selection) && in_array($key,$selection)){
					$attributes['selected']='selected';
				}
				if(isset($options[$key])){
					$attributes = array_merge($attributes, $options[$key]);
				}
				$content .= self::tag('option', $attributes, $raw?(string)$value : self::encode((string)$value))."\n";
			}
		}
		unset($htmlOptions['key']);
		return $content;
	}

    /**
     * Draws submit button
     * @param string $label
     * @param array $htmlOptions
     * @return string 
     */	
    public static function submitButton($label = 'submit', $htmlOptions = array())
	{
		$htmlOptions['type'] = 'submit';
		return self::button($label, $htmlOptions);
	}
    
	/**
	 * Generates reset button
	 * @param string $label 
	 * @param array $htmlOptions 
	 * @return string 
	 */
	public static function resetButton($label = 'reset', $htmlOptions = array())
	{
		$htmlOptions['type'] = 'reset';
		return self::button($label, $htmlOptions);
	}

    /**
     * Draws button
     * @param string $label
     * @param array $htmlOptions
     * @return string 
     */	
	public static function button($label = 'button', $htmlOptions = array())
	{
		if(!isset($htmlOptions['name'])){
			if(!array_key_exists('name', $htmlOptions)) $htmlOptions['name'] = self::ID_PREFIX.self::$count++;
		}
		if(!isset($htmlOptions['type'])) $htmlOptions['type'] = 'button';
		if(!isset($htmlOptions['value'])) $htmlOptions['value'] = $label;
		return self::tag('input', $htmlOptions);
	}


	/**
	 * Generates an image tag
	 * @param string $src 
	 * @param string $alt 
	 * @param array $htmlOptions 
	 * @return string 
	 */
	public static function image($src, $alt='', $htmlOptions = array())
	{
		$htmlOptions['src'] = $src;
		$htmlOptions['alt'] = $alt;
		return self::tag('img', $htmlOptions);
	}
    /**
     * Returns a file size in bytes from the given string
     * @param mixed $fileSize
     */
    public static function convertFileSize($fileSize)
	{
		$return = $fileSize;
		if(!is_numeric($fileSize)){ 
			if(stripos($fileSize, 'm') !== false){ 
				$return = intval($fileSize) * 1024 * 1024; 
			}else if(stripos($fileSize, 'k') !== false){ 
				$return = intval($fileSize) * 1024; 
			}else if(stripos($fileSize, 'g') !== false){ 
				$return = intval($fileSize) * 1024 * 1024 * 1024;
			}
		}
		return $return;
	}

	/**
	 * Generates JavaScript code with specified client changes
	 * @param string $event 
	 * @param array $htmlOptions 
	 */
	protected static function clientChange($event, &$htmlOptions)
	{
        // TODO
    }

   
}
