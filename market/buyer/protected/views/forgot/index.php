<body class="animated">
<div id="page">
			<?php 
			if(!empty($actionMessage)){ ?>
				<div class="msg error">
				<?php echo $actionMessage; ?>
				<a class="close">&times;</a>
				</div>
			<?php }
			?>
			<form action="forgot/run" method="post" name="frmLogin">
				<fieldset class="form">
				<?php
				    // draw login form
				        echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'send'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
					<div class="field" id="field_p14">
						<label for="email"><small><b>E-Mail</b> Remember your password? <a href="login">Sign in</a>&hellip;</small></label>
						<div class="controls">
							<input type="text" id="femail" class="input" name="femail" placeholder="example@wikishop.sx" onblur="reqForm.validEmailReq(this.id,this.value,this.name,'validate/act/register','Email field can not be blank,Please enter your email','Your entered email invalid (example@azvalid.sx)','Your email must be at least 8 characters in length',8,'#field_p14','#field_email','Your entered email is not associated with any members !','Your entered email is already associated with a member account !')">
						</div>
						<p id="field_email"></p>
					</div>
					<div class="actions">
						<div class="controls">
							<input type="submit" value="Send email" class="btn action">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
</body>