
<body class="register">
	<div id="page"	style="margin-top: 5% !important; margin-bottom: 3% !important;">
	<!-- <p>Registration has been disabled by the administrator , contact ICQ : 687280773</p> -->
		
		<?php
		if(!empty($actionMessage)){ ?>
		<div class="msg error">
		<?php echo $actionMessage; ?>
			<a class="close">&times;</a>
		</div>
		<?php }
		?>
		<form action="signup/run" method="post">
			<fieldset class="form">
			<?php
			// draw login form
			echo CWidget::form(array(

				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'signup'),
			),
				            'return'=>true,
			));
			?>
				<div class="field" id="field_p1">
					<label for="user"><small>Your nickname (instead of Login name)</small>
					</label>
					<div class="controls">
						<input type="text" class="input" id="user" name="username"
							value="" placeholder="What your name ?" AUTOCOMPLETE="OFF"
							onblur="reqForm.validInputReq(this.id,this.value,this.name,'validate/act/register','User field can not be blank,Please enter your username','Your name must be at least 4 characters in length',4,'#field_p1','#field_user','Your entered name is already associated with another account !','Your name entered is valid and not in use !')">
					</div>
					<p id="field_user"></p>
				</div>
				<div class="field" id="field_p2">
					<label for="password"><small>Password max 13 chars of letters,
							numbers, _ or -, no spaces</small> </label>
					<div class="controls">
						<input type="password" class="input" value="" id="password"
							name="password" placeholder="What your password ?"
							onblur="reqForm.validPassReq(this.id,this.value,this.name,'password','#field_p2','#field_password','Password field can not be blank,Please enter your password','Your entered password must be least 6 character',6)">
					</div>
					<p id="field_password"></p>
				</div>
				<div class="field" id="field_p3">
					<label for="re-password"><small>Re-Password max 13 chars of
							letters, numbers, _ or -, no spaces</small> </label>
					<div class="controls">
						<input type="password" class="input" id="repass" name="repassword"
							placeholder="Please confirm your password ?"
							onblur="reqForm.validPassReq(this.id,this.value,this.name,'password','#field_p3','#field_repassword','Confirm password field can not be blank','Your entered confirm password must be least 6 character',6)">
					</div>
					<p id="field_repassword"></p>
				</div>
				<div class="field" id="field_p4">
					<label for="email"><small>E-Mail the email address you enter will
							be use to active your ID.</small> </label>
					<div class="controls">
						<input type="text" id="email" class="input" value="" id="email"
							name="email" placeholder="example@wikishop.sx"
							onblur="reqForm.validEmailReq(this.id,this.value,this.name,'validate/act/register','Email field can not be blank,Please enter your email','Your entered email invalid (example@azvalid.sx)','Your email must be at least 8 characters in length',8,'#field_p4','#field_email','Your entered email is already associated with another account !','Your email entered is valid and not in use !')">
					</div>
					<p id="field_email"></p>
				</div>
				<div class="field">
					<label for="captcha"><small>Not recognize symbols on captcha ? <a
							href="javascript:;"
							onclick="
					    document.getElementById('captcha').src='./captcha/parse?'+Math.random();
					    document.getElementById('captcha').focus();">Click here</a>&hellip;</small>
					</label>
					<div class="controls">
						<a href="javascript:;"
							onclick="
					    document.getElementById('captcha').src='./captcha/parse?'+Math.random();
					    document.getElementById('captcha').focus();"
							id="change-image"><img src='./captcha/parse' id="captcha" /> </a>
					</div>
				</div>
				<div class="field">
					<div class="controls">
						<input value="" id="captcha" class="input" name="captcha"
							placeholder="Captcha code ?">
					</div>
				</div>
				<div class="actions field">
					<div class="controls">
						<input type="submit" value="Create Account" class="btn action"> <span>or</span>
						<a href="login" class="btn">Sign in</a>
					</div>
				</div>
			</fieldset>
		</form>
		
	</div>
</body>
