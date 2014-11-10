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
			<form action="login/run" method="post" name="frmLogin">
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
					<div class="field">
						<label for="username"><a class="ui-icon-black ui-icon ui-icon-person ui-icon-login"></a><small>Can't sign in? Maybe <a href="forgot">forgot your username</a>?</small></label>
						<div class="controls">
							<input type="text" id="username" name="username" class="input" placeholder="What your name ?" autocomplete="off">
						</div>
					</div>
					<div class="field">
						<label for="password"><a class="ui-icon-black ui-icon ui-icon-unlocked ui-icon-login"></a><small>Remember your username, but <a href="forgot">forgot password</a>?</small></label>
						<div class="controls">
							<input type="password" value="" id="password" name="password" class="input" placeholder="What your password ?" >
						</div>
					</div>
					<div class="actions">
						<div class="controls">
							<input type="submit" value="Sign in" class="btn action">
							<span>or</span>
							<a class="btn" href="signup">Create New Account</a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
</body>