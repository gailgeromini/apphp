<?php
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1>Ready to Install</h1>

<p>
    We are now ready to proceed with installation. At this step we will attempt to create all
    required tables and populate them with data. Should something go wrong, go back to the
    Database Settings step and make sure everything is correct.
</p>

<?php
    if(is_array($componentsList)){
        echo 'The list of components, that will be loaded:';
        echo '<ul>';
        foreach($componentsList as $component){
            $enable = (isset($component['enable']) && $component['enable']) ? 'enabled' : 'disabled';
            $class = isset($component['class']) ? $component['class'] : '';
            if(!empty($class)){                
                echo '<li>'.$class.' - '.$enable.'</li>';          
            }            
        }
        echo '</ul>';
    }
?>

<?php echo ($actionMessage) ? $actionMessage.'<br>' : ''; ?>

<?php
    echo CWidget::form(array(
        'action'=>'setup/ready',
        'method'=>'post',
        'htmlOptions'=>array(
            'name'=>'frmSetup'
        ),
        'fields'=>array(
            'act'=>array('type'=>'hidden', 'value'=>'send'),            
        ),
        'buttons'=>array(
            'back'=>array('type'=>'button', 'value'=>'Previous', 'htmlOptions'=>array('name'=>'', 'onclick'=>"$(location).attr('href','setup/administrator');")),
            'submit'=>array('type'=>'submit', 'value'=>'Next', 'htmlOptions'=>array('name'=>''))
        ),
        'return'=>true,
    ));

?>
<br>    

