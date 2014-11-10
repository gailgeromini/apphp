<div class="grid12"  style="margin-left: 0px;">
<?php echo CHtml::scriptFile('templates/micro/js/validate.form.req.js'); ?>
<script type="text/javascript">
		var reqForm = new Validate();
	</script>
<article class="panel">
<?php
    $this->_pageTitle = 'In A Restricted Area';
?>
<header class="divider">
<h3><?php echo $header; ?></h3><br/>
</header>
<?php if(!empty($Messages)){?>
<div class="msg <?php echo $Mtype?>">
		<p><?php echo $Messages; ?></p>
		<a class="close">&times;</a>
</div>
<?php }?>
<div class="grid9">
<?php echo $text; ?>
<form action="" method="POST">
					<fieldset class="form">
					<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'search'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
<legend><small>Your email <strong><?php echo $email?></strong> has not been confirmed yet</small></legend>
<div class="field" id="field_p4">
<div class="controls wcontrols">
<input type="text" id="act_email" class="input" value="<?php echo $email?>" name="act_email" onblur="reqForm.validEmailReq(this.id,this.value,this.name,'validate/act/active','Email field can not be blank,Please enter your email','Your entered email invalid (example@wikishop.sx)','Your email must be at least 8 characters in length',8,'#field_p4','#field_email','Your entered email is already associated with another account !','Your email entered is valid and not in use !')">
<p id="field_email"></p>
</div>
<div class="controls"><input type="submit" class="btn action" value="Active Code"></div>
</div>
</fieldset>
</form>
</div>
</article>
</div>