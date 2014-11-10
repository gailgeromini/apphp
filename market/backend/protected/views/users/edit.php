<div id="container">
		<article class="panel">
				<header>
						<h3>Edit <code><?php echo $this->user_name?></code></h3><br/>
					</header>
					<?php if(!empty($Messages)){?>
					<div class="msg <?php echo $Mtype?>">
								<p><?php echo $Messages; ?></p>
							<a class="close">&times;</a>
					</div>
					<?php }?>
					<form action="<?php echo $targetPath;?>" method="POST">
					<fieldset class="form">
					<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'save'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
						<div class="field">
						<label for="username"><small>User Login</small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo $this->user_name?>" class="input" name="name" readonly>
						</div>
						</div>
						<div class="field">
						<label for="email"><small>Email</small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo $this->user_email?>" class="input" name="email" >
						</div>
						</div>
						<div class="field">
						<label for="password"><small>Password </small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="password" value="" class="input" name="password" placeholder="Leave it blank if dont want to change ?" >
						</div>
						</div>
						<div class="field">
						<label for="credit"><small>Credits </small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo $this->user_credits?>" class="input" name="credit" >
						</div>
						</div>
						
						<div class="field">
						<label for="status"><small>Status</small></label>
						<div class="controls">&nbsp
							<select id="select" name="user_role">
								<?php echo $sDroplist;?>
							</select>
							<select id="select" name="user_status">
								<?php echo $this->aDroplist;?>
							</select>
						</div>
						</div>
					<div class="actions">
						<div class="controls">
							&nbsp&nbsp<input type="submit" value="Save" class="btn action"> <a href="users" class="btn">Cancel</a>
						</div>
					</div>
					</fieldset>
					</form>
					
				</article>
			</div>