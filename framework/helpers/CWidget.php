<?php
/**
 * CWidget helper class file
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
 * menu
 * breadCrumbs
 * message
 * form
 * formValidation
 * pagination
 * languageSelector
 * 
 */	  

class CWidget
{
    const NL = "\n";
    
    /**
     * Draws menu
     * @param array $param
     */
    public static function menu($params = array())
    {
        $output = '';
        $tagName = 'ul';
        $htmlOptions = array();        
        $return = isset($params['return']) ? $params['return'] : true;
        $items = isset($params['items']) ? $params['items'] : '';        
        $class = isset($params['class']) ? $params['class'] : 'menu';
        $id = isset($params['id']) ? $params['id'] : 'menu';
        $selected = isset($params['selected']) ? $params['selected'] : '';        
        
        if(is_array($items)){
            $htmlOptions['class'] = $class;   
            $htmlOptions['id'] = $id;
            $output .= CHtml::openTag($tagName, $htmlOptions).self::NL;
            foreach($params['items'] as $item => $val){
                if(empty($val)) continue;
                $url = isset($val['url']) ? $val['url'] : '';
                $label = isset($val['label']) ? $val['label'] : '';
                $readonly = isset($val['readonly']) ? $val['readonly'] : false;            
                $active = (!strcasecmp($selected, $url)) ? 'active' : '';

                $output .= CHtml::openTag('li', array('class'=>$active));
                $output .= (!$readonly) ? CHtml::link($label, $url) : CHtml::label($label);
                $output .= CHtml::closeTag('li').self::NL;
            }
            $output .= CHtml::closeTag($tagName).self::NL;
        }
        
        if($return) return $output;
        else echo $output;
    }
    
    /**
     * Draws breadcrumbs
     * @param array $params
     */
    public static function breadCrumbs($params = array())
    {
        $output = '';
        $class = 'breadcrumbs';
        $separator = ' &raquo; ';
        $tagName = 'div';
        $htmlOptions = array('class'=>$class);
        $return = isset($params['return']) ? $params['return'] : true;
        $links = isset($params['links']) ? $params['links'] : '';        
        
        if(is_array($links)){            
            $output .= CHtml::openTag($tagName, $htmlOptions).self::NL;
            $counter = 0;
            foreach($params['links'] as $item => $val){
                $url = isset($val['url']) ? $val['url'] : '';
                $label = isset($val['label']) ? $val['label'] : '';                

                if($counter) $output .= $separator;
                if(!empty($url)) $output .= CHtml::link($label, $url);
                else $output .= CHtml::tag('span', array(), $label).self::NL;
                
                $counter++;
            }
            $output .= CHtml::closeTag($tagName).self::NL;
        }
        
        if($return) return $output;
        else echo $output;
    }    

    /**
     * Draws message
     * @param string $type
     * @param string $text
     * @param array $params
     */
    public static function message($type = '', $text = '', $params = array())
    {
        $output = '';
        $tagName = 'p';
        $htmlOptions = array();        
        $allowedTypes = array('info', 'success', 'error', 'warning', 'validation');
        $return = isset($params['return']) ? $params['return'] : true;
        $button = isset($params['button']) ? $params['button'] : false;
        
        if(in_array($type, $allowedTypes)){
            $htmlOptions = array('class'=>'alert alert-'.$type);
            $output .= CHtml::openTag($tagName, $htmlOptions);
            $output .= $text;
            if($button) $output .= '<button class="close" type="button">&times;</button>';
            $output .= CHtml::closeTag($tagName).self::NL;            
        }
        
        if($return) return $output;
        else echo $output;
    }
    
    /**
     * Draws HTML form
     * @param array $params
     * 
     * Note:
     *   - to prevent double quotes issue use 'encode'=>true in htmlOptions
     *   
     * Usage: (in view)
     *   echo CWidget::form(array(
     *       'action'=>'page/contact',
     *       'method'=>'post',
     *       'htmlOptions'=>array(
     *           'name'=>'frmContact'
     *           'enctype'=>'multipart/form-data'
     *       ),
     *       'fields'=>array(
     *           'field_1'=>array('type'=>'hidden', 'value'=>'', 'htmlOptions'=>array()),
     *           'field_2'=>array('type'=>'textbox', 'label'=>'Field 2', 'value'=>'', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'50')),
     *           'field_3'=>array('type'=>'password', 'label'=>'Field 3', 'value'=>'', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20')),
     *           'field_3_confirm'=>array('type'=>'password', 'label'=>'Confirm Field 3', 'value'=>'', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20')),
     *           'field_4'=>array('type'=>'textarea', 'label'=>'Field 4', 'value'=>'', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'250')),
     *           'field_5'=>array('type'=>'file', 'label'=>'Field 5', 'value'=>'', 'mandatoryStar'=>true, 'htmlOptions'=>array()),
     *           'field_6'=>array('type'=>'image', 'label'=>'Field 6', 'src'=>'', 'alt'=>'Field 6', 'htmlOptions'=>array()),
     *           'field_7'=>array('type'=>'label', 'label'=>'Field 7', 'value'=>'', 'htmlOptions'=>array()),
     *           'field_8'=>array('type'=>'select', 'label'=>'Field 8', 'value'=>'', 'data'=>array(), 'htmlOptions'=>array()),
     *       ),
     *       'checkboxes'=>array(
     *           'remember'=>array('type'=>'checkbox', 'label'=>'Remember me', 'value'=>'1'),
     *       ),
     *       'buttons'=>array(
     *          'cancel'=>array('type'=>'button', 'value'=>'Cancel', 'htmlOptions'=>array('name'=>'', 'onclick'=>"$(location).attr('href','categories/index');")),
	 *          'reset' =>array('type'=>'reset', 'value'=>'Reset', 'htmlOptions'=>array()),
     *          'submit'=>array('type'=>'submit', 'value'=>'Send', 'htmlOptions'=>array('name'=>''))
     *       ),
     *       'events'=>array(
     *           'focus'=>array('field'=>$errorField)
     *       ),
     *       'return'=>true,
     *   ));
     */
    public static function form($params = array())
    {
        $output = '';
        $action = isset($params['action']) ? $params['action'] : '';
        $method = isset($params['method']) ? $params['method'] : 'post';
        $htmlOptions = isset($params['htmlOptions']) ? $params['htmlOptions'] : array();
        $formName = isset($htmlOptions['name']) ? $htmlOptions['name'] : '';
        $fields = isset($params['fields']) ? $params['fields'] : array();
        $checkboxes = isset($params['checkboxes']) ? $params['checkboxes'] : array();
        $buttons = isset($params['buttons']) ? $params['buttons'] : array();
        $events = isset($params['events']) ? $params['events'] : array();
        $return = isset($params['return']) ? $params['return'] : true;
        
        $output .= CHtml::openForm($action, $method, $htmlOptions);        
        // draw fields
        foreach($fields as $field => $fieldInfo){
            $type = isset($fieldInfo['type']) ? $fieldInfo['type'] : 'textbox';
            $value = isset($fieldInfo['value']) ? $fieldInfo['value'] : '';
            $label = isset($fieldInfo['label']) ? $fieldInfo['label'] : false;
            $mandatoryStar = isset($fieldInfo['mandatoryStar']) ? $fieldInfo['mandatoryStar'] : false;
            $htmlOptions = isset($fieldInfo['htmlOptions']) ? $fieldInfo['htmlOptions'] : array();
            // force removing of ID if not specified
            if(!isset($htmlOptions['id'])) $htmlOptions['id'] = false;
            // highlight error field
            if(isset($events['focus']['field']) && $events['focus']['field'] == $field){
                if(isset($htmlOptions['class'])) $htmlOptions['class'] .= ' field-error';
                else $htmlOptions['class'] = 'field-error';                     
            } 

            switch($type){
                case 'label':
                    $for = isset($htmlOptions['for']) ? (bool)$htmlOptions['for'] : false;
                    $field = CHtml::label($value, $for, $htmlOptions = array());                    
                    break;
                case 'hidden':
                    $field = CHtml::hiddenField($field, $value, $htmlOptions);
                    break;
                case 'password':
                    $field = CHtml::passwordField($field, $value, $htmlOptions);
                    break;
                case 'select':
                case 'dropdown':
                case 'dropdownlist':
                    $data = isset($fieldInfo['data']) ? $fieldInfo['data'] : array();
                    $field = CHtml::dropDownList($field, $value, $data, $htmlOptions);
                    break;
                case 'file':
                    $field = CHtml::fileField($field, $value, $htmlOptions);
                    break;
                case 'image':
                    $src = isset($fieldInfo['src']) ? $fieldInfo['src'] : '';
                    $alt = isset($fieldInfo['alt']) ? $fieldInfo['alt'] : '';
                    if(!isset($htmlOptions['name'])) $htmlOptions['name'] = $field;
                    $field = CHtml::image($src, $alt, $htmlOptions);
                    break;
                case 'textarea':
                    $field = cHtml::textArea($field, $value, $htmlOptions);
                    break;
                case 'textbox':
                default:
                    $field = CHtml::textField($field, $value, $htmlOptions);
                    break;
            }
            if($type == 'hidden'){
                $output .= $field.self::NL;    
            }else{
                $output .= CHtml::openTag('div', array('class'=>'row'));
                if($label){
                    $output .= CHtml::label($label.(($mandatoryStar) ? CHtml::$afterRequiredLabel : '').(trim($label) !== '' ? ':' : ''), false);
                }
                $output .= $field;
                $output .= CHtml::closeTag('div').self::NL;                
            }
        }        
        
        // draw buttons
        if(count($buttons) > 0){
            $output .= CHtml::openTag('div', array('class'=>'buttons-wrapper'));
            foreach($buttons as $button => $buttonInfo){
                $type = isset($buttonInfo['type']) ? $buttonInfo['type'] : '';
                $value = isset($buttonInfo['value']) ? $buttonInfo['value'] : '';
                $htmlOptions = isset($buttonInfo['htmlOptions']) ? $buttonInfo['htmlOptions'] : array();
                if(!isset($htmlOptions['value'])) $htmlOptions['value'] = $value;
                switch($type){
                    case 'button':
                        $htmlOptions['type'] = 'button';
                        $output .= CHtml::button('button', $htmlOptions).self::NL;
                        break;
                    case 'reset':
                        $output .= CHtml::resetButton('reset', $htmlOptions).self::NL;
                        break;
                    case 'submit':
                    default:
                        $output .= CHtml::submitButton('submit', $htmlOptions).self::NL;
                        break;
                }                        
            }            
            $output .= CHtml::closeTag('div').self::NL;
        }
        
        // draw checkboxes
        if(count($checkboxes) > 0){
            $output .= CHtml::openTag('div', array('class'=>'checkboxes-wrapper'));
            foreach($checkboxes as $checkbox => $checkboxInfo){
                $label = isset($checkboxInfo['label']) ? $checkboxInfo['label'] : false;
                $htmlOptions = isset($buttonInfo['htmlOptions']) ? $buttonInfo['htmlOptions'] : array();
                $output .= CHtml::checkBox($checkbox, false, $htmlOptions).self::NL;
                if($label){                    
                    $output .= CHtml::label($label, $checkbox);
                }
            }
            $output .= CHtml::closeTag('div').self::NL;
        }
        
        $output .= CHtml::closeForm();
        
        // attach events
        foreach($events as $event => $eventInfo){
            $field = isset($eventInfo['field']) ? $eventInfo['field'] : '';
            if($event == 'focus'){
                if(!empty($field)){
                    A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$field.'.focus();', 2);
                }
            }
        }
        
        if($return) return $output;
        else echo $output;       
    }
    
    /**
     * Performs form validation
     * @param array $params
     * 
     * Usage: (in Controller class)
     *  possible validation types:
     *  alpha, numeric, alphanumeric, variable, mixed, phone, username,
     *  password, email, date, integer, float, any, confirm
     * 
     *  $result = CWidget::formValidation(array(
     *     'fields'=>array(
     *          'field_1'=>array('label'=>'Username',        'validation'=>array('required'=>true, 'type'=>'username')),
     *          'field_2'=>array('label'=>'Password',        'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6)),
     *          'field_3'=>array('label'=>'Repeat Password', 'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'field_2')),
     *          'field_4'=>array('label'=>'Email',           'validation'=>array('required'=>true, 'type'=>'email')),
     *          'field_5'=>array('label'=>'Confirm Email',   'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'field_4')),
     *          'field_6'=>array('label'=>'Mixed',           'validation'=>array('required'=>true, 'type'=>'mixed')),
     *          'field_7'=>array('label'=>'Field',           'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
     *          'field_8'=>array('label'=>'Image',           'validation'=>array('required'=>true, 'type'=>'image', 'targetPath'=>'protected/uploaded/', 'maxSize'=>'100k', 'mimeType'=>'image/jpeg, image/png')),
     *          'field_9'=>array('label'=>'File',            'validation'=>array('required'=>true, 'type'=>'file', 'targetPath'=>'protected/uploaded/', 'maxSize'=>'100k', 'mimeType'=>'application/zip, application/xml')),
     *      ),
     *      'source'=>'core'
     *  ));
     *   
     *  if($result['error']){
     *      $msg = $result['errorMessage'];
     *      $this->view->errorField = $result['errorField'];
     *      $errorType = 'validation';                
     *  }else{
     *      // your code here to handle a successful submission...
     *  }
     */
    public static function formValidation($params = array())
    {
        $output = array('error'=>false);
        $cRequest = A::app()->getRequest();
        
        $fields = isset($params['fields']) ? $params['fields'] : array();
        $source = isset($params['source']) ? $params['source'] : 'core';
        foreach($fields as $field => $fieldInfo){
            $fieldValue  = $cRequest->getPost($field);
            $label       = isset($fieldInfo['label']) ? $fieldInfo['label'] : '';
            $required    = isset($fieldInfo['validation']['required']) ? $fieldInfo['validation']['required'] : false;
            $type        = isset($fieldInfo['validation']['type']) ? $fieldInfo['validation']['type'] : 'any';
            $minLength   = isset($fieldInfo['validation']['minLength']) ? $fieldInfo['validation']['minLength'] : '';
            $maxLength   = isset($fieldInfo['validation']['maxLength']) ? $fieldInfo['validation']['maxLength'] : '';
            $maxSize     = isset($fieldInfo['validation']['maxSize']) ? CHtml::convertFileSize($fieldInfo['validation']['maxSize']) : '';
            $targetPath  = isset($fieldInfo['validation']['targetPath']) ? $fieldInfo['validation']['targetPath'] : '';
            $fileMimeType  = isset($fieldInfo['validation']['mimeType']) ? $fieldInfo['validation']['mimeType'] : '';
            $fileMimeTypes = (!empty($fileMimeType)) ? explode(',', str_replace(' ', '', $fileMimeType)) : array();
            $valid        = true;
            $errorMessage = '';
            
			if($type == 'file' || $type == 'image'){
                $fileName     = (isset($_FILES[$field]['name'])) ? $_FILES[$field]['name'] : '';
                $fileSize     = (isset($_FILES[$field]['size'])) ? $_FILES[$field]['size'] : 0;
                $fileTempName = (isset($_FILES[$field]['tmp_name'])) ? $_FILES[$field]['tmp_name'] : '';
                $fileType     = (isset($_FILES[$field]['type'])) ? $_FILES[$field]['type'] : '';
                              if($type == 'image' && !empty($fileTempName)) $fileType = image_type_to_mime_type(exif_imagetype($fileTempName));
                $fileError    = (isset($_FILES[$field]['error'])) ? $_FILES[$field]['error'] : '';
                if($required && empty($fileSize)){
                    $valid = false;
                    $errorMessage = A::t($source, 'The field {label} can\'t be empty! Please re-enter.', array('{label}'=>$label));
                }else if(!empty($fileSize)){
                    if($maxSize !== '' && $fileSize > $maxSize){
                        $valid = false;
                        $sFileSize = number_format(($fileSize / 1024), 2, '.', ',').' Kb';
                        $sMaxAllowed = number_format(($maxSize / 1024), 2, '.', ',').' Kb';
                        $errorMessage = A::t($source, 'Invalid file size for field {label}: {file_size} (max. allowed: {max_allowed})', array('{label}'=>$label, '{file_size}'=>$sFileSize, '{max_allowed}'=>$sMaxAllowed));
                        
                    }else if(!empty($fileMimeTypes) && !in_array($fileType, $fileMimeTypes)){
                        $valid = false;
                        $errorMessage = A::t($source, 'Invalid file type for field {label}: you may only upload {mime_type} files.', array('{label}'=>$label, '{mime_type}'=>$fileMimeType));
                    }else{
                        $targetFileName = basename($fileName);
                        $targetFull = $targetPath.$targetFileName;
                        if(@move_uploaded_file($fileTempName, $targetFull)){
                            // upload ok                        
                        }else{
                            $valid = false;
                            $errorMessage = A::t($source, 'An error occured while uploading your file for field {label}. Please try again.', array('{label}'=>$label));
                            if(version_compare(PHP_VERSION, '5.2.0', '>=')){	
                                $err = error_get_last();
                                if(!empty($err)){
                                    CDebug::addMessage('errors', 'fileUploading', $err['message']);
                                }
                            }else{
                                CDebug::addMessage('errors', 'fileUploading', $fileError);
                            }
                        }
                    }                    
                }
            }else if($required && trim($fieldValue) == ''){
                $valid = false;
                $errorMessage = A::t($source, 'The field {label} can\'t be empty ! Please re-enter.', array('{label}'=>$label));
            }else if($type == 'confirm'){                
                $confirmField = isset($fieldInfo['validation']['confirmField']) ? $fieldInfo['validation']['confirmField'] : '';
                $confirmFieldValue = $cRequest->getPost($confirmField);
                $confirmFieldName = isset($fields[$confirmField]['label']) ? $fields[$confirmField]['label'] : '';
                if($confirmFieldValue != $fieldValue){
                    $valid = false;
                    $errorMessage = A::t($source, 'The {confirm_field} and {label} fields do not match! Please re-enter.', array('{confirm_field}'=>$confirmFieldName, '{label}'=>$label));
                }
            }else if(!empty($fieldValue)){
                if(!empty($minLength) && !CValidator::validateMinlength($fieldValue, $minLength)){
                    $valid = false;
                    $errorMessage = A::t($source, 'The {label} field length must be at least {min_length} characters! Please re-enter.', array('{label}'=>$label, '{min_length}'=>$minLength));                
                }else if(!empty($maxLength) && !CValidator::validateMaxlength($fieldValue, $maxLength)){
                    $valid = false;
                    $errorMessage = A::t($source, 'The {label} field length may be {max_length} characters maximum! Please re-enter.', array('{label}'=>$label, '{max_length}'=>$maxLength));
                }else{                    
                    switch($type){
                        case 'alpha':
                            $valid = CValidator::isAlpha($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid alphabetic value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'numeric':
                            $valid = CValidator::isNumeric($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid numeric value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'alphanumeric':
                            $valid = CValidator::isAlphaNumeric($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid alpha-numeric value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'variable':
                            $valid = CValidator::isVariable($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid label name (alphanumeric, starts with letter and can contain an underscore)! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'mixed':
                            $valid = CValidator::isMixed($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} should include only alpha, space and numeric characters! Please re-enter.', array('{label}'=>$label));
                            break;                        
                        case 'phone':
                            $valid = CValidator::isPhone($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid phone number! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'username':
                            $valid = CValidator::isUsername($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must have a valid username value! Please re-enter.', array('{label}'=>$label));
                            break;
                        case 'password':
                            $valid = CValidator::isPassword($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must have a valid password value! Please re-enter.', array('{label}'=>$label));
                            break;
                        case 'email':
                            $valid = CValidator::isEmail($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid email address! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'date':
                            $valid = CValidator::isDate($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid date value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'integer':
                            $valid = CValidator::isInteger($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid integer value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'float':
                            $valid = CValidator::isFloat($fieldValue);
                            $errorMessage = A::t($source, 'The field {label} must be a valid float value! Please re-enter.', array('{label}'=>$label));
                            break;                                                
                        case 'any':
                        default:                        
                            break;
                    }                    
                }
            }
            
            if(!$valid){
                $output['error'] = true;
                $output['errorMessage'] = $errorMessage;
                $output['errorField'] = $field;
                break;
            }
        }
        return $output;
        
    }
    
    
    /**
     * Draws pagination
     * @param array $params
     * 
     * Usage: (in View file)
     *  echo CWidget::pagination(array(
     *      'targetPath'   => $targetPath,
     *      'currentPage'  => $currentPage,
     *      'pageSize'     => $pageSize,
     *      'totalRecords' => $totalRecords,
     *      'linkType'     => 0,
     *      'paginationType' => 'prevNext'
     *  ));
     */
    public static function pagination($params = array())
    {        
        // how many adjacent pages should be shown on each side?
        $adjacents = 3;
        $targetPath = (isset($params['targetPath'])) ? $params['targetPath'] : '';
        $page = (isset($params['currentPage'])) ? (int)$params['currentPage'] : 1;
        $pageSize = (isset($params['pageSize'])) ? (int)$params['pageSize'] : 1;
        $totalRecords = (isset($params['totalRecords'])) ? (int)$params['totalRecords'] : 0;  
        // link type: 0 - standard, 1 - SEO
        $linkType = (isset($params['linkType'])) ? (int)$params['linkType'] : 1;
        // type: 0 - << prev [][] next >>, 1 - << >>
        $paginationType = (isset($params['paginationType'])) ? $params['paginationType'] : 'fullNumbers';
                
        if($page){
            $start = ($page - 1) * $pageSize;       // first item to display on this page
        }else{
            $start = 0;							    // if no page var is given, set start to 0
        }
        
        // setup page vars for display
        if($page == 0) $page = 1;					 // if no page var is given, default to 1.
        $prev = $page - 1;							 // previous page is page - 1
        $next = $page + 1;							 // next page is page + 1
        $lastpage = ceil($totalRecords / $pageSize); // lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;						 // last page minus 1
        $output = '';
        $middlePart = '';
        $counter = 0;
        $wPrevious = A::t('core', 'previous');
        $wNext = A::t('core', 'next');
        
        if($lastpage > 1){
            $output .= CHtml::openTag('div', array('class'=>'switcher right'));
        
            // draw previous button
            if($page > 1){
                $output.= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$prev : '?page='.$prev).'">&laquo;</a></li>';
            }else{
                $output.= CHtml::tag('li', array('class'=>'active'), '<a class="btn" >&laquo;</a>');
            }
            
            // pages	
            if($lastpage < 7 + ($adjacents * 2)){	
                // not enough pages to bother breaking it up            
                for($counter = 1; $counter <= $lastpage; $counter++){
                    if($counter == $page){
                        $middlePart .= '<li class="active"><a class="btn">'.$counter.'</a></li>';
                    }else{
                        $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$counter : '?page='.$counter).'">'.$counter.'</a></li>';					
                    }
                }
            // enough pages to hide some    
            }else if($lastpage > 5 + ($adjacents * 2)){                
                // close to beginning, only hide later pages
                if($page < 1 + ($adjacents * 2)){
                    for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                        if($counter == $page){
                            $middlePart .= '<li class="active"><a class="btn">'.$counter.'</a></li>';
                        }else{
                            $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$counter : '?page='.$counter).'">'.$counter.'</a></li>';
                        }
                    }
                    $middlePart .= '<li class="active"><a class="btn">...</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$lpm1 : '?page='.$lpm1).'">'.$lpm1.'</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$lastpage : '?page='.$lastpage).'">'.$lastpage.'</a></li>';
                }
                // in middle, hide some front and some back
                else if($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/1' : '?page=1').'">1</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/2' : '?page=2').'">1</a></li>';
                    $middlePart .= '<li class="active"><a class="btn">...</a></li>';
                    for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
                        if($counter == $page){
                            $middlePart .= '<li class="active"><a class="btn">'.$counter.'</a></li>';
                        }else{
                            $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$counter : '?page='.$counter).'">'.$counter.'</a></li>';
                        }
                    }
                    $middlePart .= '<li class="active"><a class="btn">...</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$lpm1 : '?page='.$lpm1).'">'.$lpm1.'</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$lastpage : '?page='.$lastpage).'">'.$lastpage.'</a></li>';
                }
                // close to end, just hide early pages
                else{
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/1' : '?page=1').'">1</a></li>';
                    $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/2' : '?page=2').'">2</a></li>';
                    $middlePart .= '<li class="active"><a class="btn">...</a></li>';
                    for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
                        if($counter == $page){
                            $middlePart .= '<li class="active"><a class="btn">'.$counter.'</a></li>';
                        }else{
                            $middlePart .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$counter : '?page='.$counter).'">'.$counter.'</a></li>';
                        }
                    }
                }
            }            

            // draw middle part
            if($paginationType == 'fullNumbers'){
                $output .= $middlePart;    
            } 
            
            // draw next button
            if($page < $counter - 1){
                $output .= '<li><a class="btn" href="'.$targetPath.(($linkType) ? '/page/'.$next : '?page='.$next).'"> &raquo;</a></li>';                
            }else{
                $output .= '<li class="active"><a class="btn">&raquo;</a></li>';
            }
            $output .= CHtml::closeTag('div').self::NL;       
        }
        
        return $output;
    }
    
    /**
     * Draws language selector
     * @param array $params
     */
    public static function languageSelector($params = array())
    {       
        $output = '';
        $tagName = 'div';
        $languages = isset($params['languages']) ? $params['languages'] : array();
        $currentLanguage = isset($params['currentLanguage']) ? $params['currentLanguage'] : '';
 
        $output .= CHtml::openTag($tagName, array('id'=>'language-select'));
        if(sizeof($languages) < 4){
            $output .= '<a href="#">EN</a> | <a href="#">ES</a> | <a href="#">FR</a> ';
        }else{
            
        }
        $output .= CHtml::closeTag($tagName).self::NL;       
        
        
        return $output;
    }
    /**
     * buyer bread crumbs 
     * @param unknown self::menu
     * @author Tom
     */
	public static function buyerBreadCrumbs($params = array())
    {       
    	if(!empty($params)){
    	return self::menu(array(
                    'type'=>'horizontal',
                    'class'=>'breadcrumbs',	
                    'id'=>'breadcrumbs',		
                    'items'=>$params,
					'return'=>false
        ));
    	}else return false;    
    }
    
}