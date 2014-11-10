<article class="panel">
<header>
		<h2>Edit account</h2>
	</header>
	<?php echo $actionMessage; ?>
	<form action="profile/edit" method="post" name="frmAccount">
	<fieldset class="form">
    <?php
        echo CWidget::form(array(
            'fields'=>array(
                'act'     =>array('type'=>'hidden', 'value'=>'send'),
            ),
            'return'=>true,
        ));
    ?>    
    <div class="field">
			<label for="username">Username:</label>
			<div class="controls">
				<input type="text" id="username" value="<?php echo $username?>" class="input grid4" readonly >
			</div>
		</div>
	<div class="field">
			<label for="email">E-Mail:</label>
			<div class="controls">
				<input type="text" id="email" value="<?php echo $email?>" class="input grid4" readonly >
			</div>
		</div>
	<div class="field">
			<label for="password">Password:</label>
			<div class="controls">
				<input type="password" id="password" name="password"  placeholder="Leave it blank if dont want to change ?" class="input grid4" >
			</div>
		</div>
		<div class="actions">
			<div class="controls">
				<input type="submit" value="Save changes" class="btn action">
				<span>or</span>
				<a class="btn btclose" href="javascript:;" onclick="$.facebox.close();">Leave change</a>
			</div>
		</div>
	</fieldset>
	</form>
</article>

