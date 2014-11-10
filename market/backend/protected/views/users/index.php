<div id="container">
		<article class="panel">
				<header>
						<h3>Users</h3><br/>
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

				                'act'     =>array('type'=>'hidden', 'value'=>'search'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
						<div class="field">
						<label for="extension"><small>Search</small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('users_ext')?>" id="extension" autocomplete = "false" name="extension" class="input" placeholder=" steve job , steve_job@apple.com" >
							 <code>( username , email )</code>
						</div>
						
					</div>
				
					<div class="actions">
						<div class="controls">
							&nbsp&nbsp<input type="submit" value="Search" class="btn action">
						</div>
					</div>
					</fieldset>
					</form>
					<table class="table ">
						<colgroup>
							<col class="grid2">
							<col class="grid2">
							<col class="grid2">
							<col class="grid1">
							<col class="grid1">
							<col class="grid3">
							<col class="grid3">
						</colgroup>
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Member Type</th>
								<th>Credit</th>
								<th>Active</th>
								<th>Date Create</th>
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
					<?php
					$user = $this->users;
						foreach ($user as $row)
						{?>
							<tr>
								<td><?php echo $row['user_name']?></td>
								<td><?php echo $row['user_email']?></td>
								<td style="<?php echo $row['ac_role_properties']?>"><?php echo $row['ac_role_name']?></td>
								<td><?php if(!empty($row['user_credits'])){
										echo $row['user_credits'];
									}else echo "0";?>$</td>
									
								<td><?php echo CRefactorUltilities::replIObject($row['user_status'])?></td>
								<td><?php echo $row['user_regdate']?></td>
								<td><a href="users/edit/id/<?php echo $row['user_id']?>" class="btn">Edit Member</a> <a href="users/delete/id/<?php echo $row['user_id']?>" class="btn action" onclick="javascript: if (!confirm('Are you sure you want to delete this users <?php echo $row['user_name']?> ?')) { return false; }">Delete</a> </td>
							</tr>
						
						<?php }?>
					
					</tbody>
					</table>
					<div class="results">
					<p></p>
					<?php 
					echo $this->pagination;
					?>
					</div>
				</article>
			</div>