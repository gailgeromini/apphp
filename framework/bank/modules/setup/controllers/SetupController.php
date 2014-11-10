<?php

class SetupController extends CController
{
    
    private $cSession;
    private $cRequest;
    
	public function __construct()
	{
        parent::__construct();
        
        $this->view->errorField = '';
        $this->cSession = A::app()->getSession();
        $this->cRequest = A::app()->getRequest();
        
        $this->view->_programName = 'Program Name';
        $this->view->_programVersion = '2.0.1';

        // block access to setup files when application is already installed
        $configMain = APPHP_PATH.'/protected/config/main.php';
        if(file_exists($configMain)){            
            $this->view->errorMessage = CWidget::message('error', 'You\'re not authorized to view this page.');
            $this->view->render('setup/error');
            exit;
        }        
    }
    
	public function indexAction()
	{        
        if(A::app()->getRequest()->getPost('act') == 'send'){
            A::app()->getSession()->set('setupStep', 'database');
            
            $this->redirect('setup/database');    
        }
        
        ob_start();
        
        if(function_exists('phpinfo')) @phpinfo(-1);
        $phpinfo = array('phpinfo' => array());
        if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
        foreach($matches as $match){
            $array_keys = array_keys($phpinfo);
            if(strlen($match[1])){
                $phpinfo[$match[1]] = array();
            }else if(isset($match[3])){
                $phpinfo[end($array_keys)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
            }else{
                $phpinfo[end($array_keys)][] = $match[2];
            }
        }
        
        $this->view->notifyMessage = '';
        
        if(version_compare(phpversion(), '5.1.0', '<')){	
            $this->view->notifyMessage = CWidget::message('error', 'This program requires at least <b>PHP version 5.1.0</b> installed. You cannot proceed the installation.');	
        }else if(!is_writable(APPHP_PATH.'/protected/config/')){
            $this->view->notifyMessage = CWidget::message('error', 'The directory <b>'.APPHP_PATH.'/protected/config/</b> is not writable! You must grant "write" permissions (access rights 0755 or 777, depending on your system settings) to this directory before you start the installation!');	
        }else{            
            $this->view->phpversion = function_exists('phpversion') ? '<span class="found">'.phpversion().'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->system = isset($phpinfo['phpinfo']['System']) ? '<span class="found">'.$phpinfo['phpinfo']['System'].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->systemArchitecture = isset($phpinfo['phpinfo']['Architecture']) ? '<span class="found">'.$phpinfo['phpinfo']['Architecture'].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->buildDate = isset($phpinfo['phpinfo']['Build Date']) ? '<span class="found">'.$phpinfo['phpinfo']['Build Date'].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->serverApi = isset($phpinfo['phpinfo']['Server API']) ? '<span class="found">'.$phpinfo['phpinfo']['Server API'].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->vdSupport = isset($phpinfo['phpinfo']['Virtual Directory Support']) ? $phpinfo['phpinfo']['Virtual Directory Support'] : 'Unknown';
            $this->view->vdSupport = ($this->view->vdSupport == 'enabled') ? '<span class="found">'.$this->view->vdSupport.'</span>' : '<span class="disabled">'.$this->view->vdSupport.'</span>';
            $this->view->aspTags   = isset($phpinfo['PHP Core']) ? '<span class="found">'.$phpinfo['PHP Core']['asp_tags'][0].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->safeMode = isset($phpinfo['PHP Core']) ? '<span class="found">'.$phpinfo['PHP Core']['safe_mode'][0].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->shortOpenTag = isset($phpinfo['PHP Core']) ? '<span class="found">'.$phpinfo['PHP Core']['short_open_tag'][0].'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->sessionSupport = isset($phpinfo['session']['Session Support']) ? $phpinfo['session']['Session Support'] : 'Unknown';
            $this->view->sessionSupport = ($this->view->sessionSupport == "enabled") ? '<span class="found">'.$this->view->sessionSupport.'</span>' : '<span class="disabled">'.$this->view->sessionSupport.'</span>';
            
            $this->view->magicQuotesGpc = ini_get('magic_quotes_gpc') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';
            $this->view->magicQuotesRuntime = ini_get('magic_quotes_runtime') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';
            $this->view->magicQuotesSybase = ini_get('magic_quotes_sybase') ? '<span class="found">On</span>' : '<span class="disabled">Off</span>';									
            
            $this->view->smtp = (ini_get("SMTP") != '') ? '<span class="found">'.ini_get('SMTP').'</span>' : '<span class="unknown">Unknown</span>';
            $this->view->smtpPort = (ini_get('smtp_port') != '') ? '<span class="found">'.ini_get('smtp_port').'</span>' : '<span class="unknown">Unknown</span>';
            
            $this->cSession->set('step', 1);    
        }

        $this->view->render('setup/index');
    }

	public function databaseAction()
	{
        if($this->cRequest->getPost('act') == 'send'){
            $this->view->dbType =  $this->cRequest->getPost('dbType', 'string');
            $this->view->dbHost =  $this->cRequest->getPost('dbHost', 'string');
            $this->view->dbName = $this->cRequest->getPost('dbName', 'string');
            $this->view->dbUser = $this->cRequest->getPost('dbUser', 'string');
            $this->view->dbPassword = $this->cRequest->getPost('dbPassword', 'string');
            $this->view->dbPrefix = $this->cRequest->getPost('dbPrefix', 'string');
        }else{
            $this->view->dbType = $this->cSession->get('dbType', 'mysql');
            $this->view->dbHost = $this->cSession->get('dbHost', 'localhost');
            $this->view->dbName = $this->cSession->get('dbName');
            $this->view->dbUser = $this->cSession->get('dbUser');
            $this->view->dbPassword = $this->cSession->get('dbPassword');
            $this->view->dbPrefix = $this->cSession->get('dbPrefix');            
        }
        
        $this->view->actionMessage = '';
        $errorType = '';
        $msg = '';

        $this->view->formFields = array(
            'act'        =>array('type'=>'hidden', 'value'=>'send'),            
            'dbType'     =>array('type'=>'dropdownlist', 'value'=>$this->view->dbType, 'label'=>'Database Type', 'mandatoryStar'=>true, 'data'=>array('mysql'=>'MySql'), 'htmlOptions'=>array(), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbHost'     =>array('type'=>'textbox', 'value'=>$this->view->dbHost, 'label'=>'Database Host', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'32', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbName'     =>array('type'=>'textbox', 'value'=>$this->view->dbName, 'label'=>'Database Name', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbUser'     =>array('type'=>'textbox', 'value'=>$this->view->dbUser, 'label'=>'Database User', 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>true, 'type'=>'text')),
            'dbPassword' =>array('type'=>'textbox', 'value'=>$this->view->dbPassword, 'label'=>'Database Password', 'mandatoryStar'=>false, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off'), 'validation'=>array('required'=>false, 'type'=>'text')),
            'dbPrefix'   =>array('type'=>'textbox', 'value'=>$this->view->dbPrefix, 'label'=>'Database (tables) Prefix', 'mandatoryStar'=>false, 'htmlOptions'=>array('maxLength'=>'10', 'autocomplete'=>'off'), 'validation'=>array('required'=>false, 'type'=>'variable')),
        );            

        // check if previous step was passed
        if($this->cSession->get('step') < 1){
            $this->redirect('setup/index');
        }else if($this->cRequest->getPost('act') == 'send'){

            $result = CWidget::formValidation(array(
                'fields' => $this->view->formFields
            ));
             
            if($result['error']){
                $msg = $result['errorMessage'];
                $this->view->errorField = $result['errorField'];
            }else{

                $model = new Setup(array(
                    'dbType' => $this->view->dbType,
                    'dbHost' => $this->view->dbHost,
                    'dbName' => $this->view->dbName,
                    'dbUser' => $this->view->dbUser,
                    'dbPassword' => $this->view->dbPassword
                ));
                
                if($model->getError()){
                    $this->view->actionMessage = CWidget::message('error', $model->getErrorMessage());
                }else{
                    // go to the next step
                    $this->cSession->set('dbType', $this->view->dbType);
                    $this->cSession->set('dbHost', $this->view->dbHost);
                    $this->cSession->set('dbName', $this->view->dbName);
                    $this->cSession->set('dbUser', $this->view->dbUser);
                    $this->cSession->set('dbPassword', $this->view->dbPassword);
                    $this->cSession->set('dbPrefix', $this->view->dbPrefix);
                    $this->cSession->set('step', 2);
                    
                    $this->redirect('setup/administrator');                    
                }
            }

			if(!empty($msg)){
				$this->view->actionMessage = CWidget::message('validation', $msg);
            }                        
        }
        
        $this->view->render('setup/database');
    }

	public function administratorAction()
	{
        $this->view->email = $this->cSession->get('email');
        $this->view->username = $this->cSession->get('username');
        $this->view->password = $this->cSession->get('password');
        
        $this->view->actionMessage = '';
        $errorType = '';
        $msg = '';
        
        // check if previous step was passed
        if($this->cSession->get('step') < 2){
            $this->redirect('setup/index');
        }else if($this->cRequest->getPost('act') == 'send'){
            $this->view->email = $this->cRequest->getPost('email');
            $this->view->username = $this->cRequest->getPost('username');
            $this->view->password = $this->cRequest->getPost('password');

            $result = CWidget::formValidation(array(
                'fields'=>array(
                    'email'=>array('label'=>'Email', 'validation'=>array('required'=>false, 'type'=>'email')),
                    'username'=>array('label'=>'Username', 'validation'=>array('required'=>true, 'type'=>'username', 'minLength'=>6)),
                    'password'=>array('label'=>'Password', 'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6)),
                ),            
            ));
             
            if($result['error']){
                $msg = $result['errorMessage'];
                $this->view->errorField = $result['errorField'];
            }else{
                // go to the next step                
                $this->cSession->set('email', $this->view->email);
                $this->cSession->set('username', $this->view->username);
                $this->cSession->set('password', $this->view->password);
                $this->cSession->set('step', 3);

                $this->redirect('setup/ready');
            }

			if(!empty($msg)){
				$this->view->actionMessage = CWidget::message('validation', $msg);
            }
        }

        $this->view->render('setup/administrator');
    }

	public function readyAction()
	{
        $this->view->actionMessage = '';
        
        // check if previous step was passed
        if($this->cSession->get('step') < 3){
            $this->redirect('setup/index');
        }else if($this->cRequest->getPost('act') == 'send'){
            // get sql schema
			$sqlDump = file(APPHP_PATH.'/protected/data/schema.mysql.sql');
            if(empty($sqlDump)){
                $this->view->actionMessage = CWidget::message('error', 'Could not read file <b>'.APPHP_PATH.'/protected/data/schema.mysql.sql</b>! Please check if this file exists.');
            }else{
                $mainContent = include(APPHP_PATH.'/protected/data/config.main.tpl');
                $encryption = isset($mainContent['password']['encryption']) ? $mainContent['password']['encryption'] : false;
                $encryptAlgorithm = isset($mainContent['password']['encryptAlgorithm']) ? $mainContent['password']['encryptAlgorithm'] : '';
                $hashKey = isset($mainContent['password']['hashKey']) ? $mainContent['password']['hashKey'] : '';
                $components = isset($mainContent['components']) ? $mainContent['components'] : '';
        
                // replace placeholders
                $sqlDump = str_ireplace('<DB_PREFIX>', $this->cSession->get('dbPrefix'), $sqlDump);
                $sqlDump = str_ireplace('<USERNAME>', $this->cSession->get('username'), $sqlDump);
                $sqlDump = str_ireplace('<PASSWORD>', (($encryption) ? CHash::create($encryptAlgorithm, $this->cSession->get('password'), $hashKey) : $this->cSession->get('password')), $sqlDump);
                $sqlDump = str_ireplace('<EMAIL>', $this->cSession->get('email'), $sqlDump);
    
                $model = new Setup(array(
                    'dbType' => $this->cSession->get('dbType'),
                    'dbHost' => $this->cSession->get('dbHost'),
                    'dbName' => $this->cSession->get('dbName'),
                    'dbUser' => $this->cSession->get('dbUser'),
                    'dbPassword' => $this->cSession->get('dbPassword')
                ));
                
                if($model->getError()){
                    $this->view->actionMessage = CWidget::message('error', $model->getErrorMessage());
                }else{
                    if($model->install($sqlDump)){
                        $this->cSession->set('step', 4);
                        $this->redirect('setup/completed');
                    }else{
                        $this->view->actionMessage = CWidget::message('error', $model->getErrorMessage());
                    }
                }                
            }						
        }else{
            $mainContent = include(APPHP_PATH.'/protected/data/config.main.tpl');
            $this->view->componentsList = isset($mainContent['components']) ? $mainContent['components'] : '';
        }

        $this->view->render('setup/ready');
    }

	public function completedAction()
	{
        // check if previous step was passed
        if($this->cSession->get('step') < 4){
            $this->redirect('setup/index');
        }       
        
        $this->view->username = $this->cSession->get('username');
        $this->view->password = $this->cSession->get('password');
        $this->view->actionMessage = CWidget::message('success', '<b>Congratulation</b>!<br>Installation has been successfully completed and administrator\'s account has been created.');
        
        $dbContent = file_get_contents(APPHP_PATH.'/protected/data/config.db.tpl');
        $dbContent = str_replace('<DB_TYPE>', $this->cSession->get('dbType'), $dbContent);
        $dbContent = str_replace('<DB_HOST>', $this->cSession->get('dbHost'), $dbContent);
        $dbContent = str_replace('<DB_NAME>', $this->cSession->get('dbName'), $dbContent);
        $dbContent = str_replace('<DB_USER>', $this->cSession->get('dbUser'), $dbContent);
        $dbContent = str_replace('<DB_PASSWORD>', $this->cSession->get('dbPassword'), $dbContent);
        $dbContent = str_replace('<DB_PREFIX>', $this->cSession->get('dbPrefix'), $dbContent);

        $mainContent = file_get_contents(APPHP_PATH.'/protected/data/config.main.tpl');
        $mainContent = str_replace('<INSTALLATION_KEY>', CHash::getRandomString(10), $mainContent);
        
        $dbFile = APPHP_PATH.'/protected/config/db.php';
        $mainFile = APPHP_PATH.'/protected/config/main.php';
        
        $dbFileHandler = fopen($dbFile, 'w+');
        $mainFileHandler = fopen($mainFile, 'w+');
        
        $dbFileWrite = fwrite($dbFileHandler, $dbContent);
        $mainFileWrite = fwrite($mainFileHandler, $mainContent);
        
        if($dbFileWrite > 0 && $mainFileWrite > 0){
            $this->view->actionMessage = CWidget::message('success', '<b>Congratulation</b>!<br>Installation has been successfully completed and administrator\'s account has been created.');
            $this->cSession->endSession();
        }else{				
            $this->view->actionMessage = CWidget::message('error', 'Cannot open configuration files in <b>'.APPHP_PATH.'/protected/config/</b>. Please make sure you have the \'write\' permissions on this path.');
        }
        fclose($dbFileHandler);
        fclose($mainFileHandler);
        
        $this->view->render('setup/completed');
    }
   
}
