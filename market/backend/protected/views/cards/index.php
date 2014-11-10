<script type="text/javascript">
function addtocart()
{
	document.getElementById("cart").submit();
}
</script>
		<article class="panel">
					<header>
						<h3>Search</h3><br/>
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
						<label for="cardsType"><small>Options</small></label>
									<div class="controls cards">
										<select id="select" name="type">
											<?php echo $tDroplist;?>
										</select>
										<select id="select" name="country">
											<?php echo $cDroplist;?>
										</select>
										<select id="select" name="category">
											<?php echo $ctDroplist;?>
										</select>
										<select id="select" name="card_used">
											<?php echo $uDroplist;?>
										</select>
										<select id="select" name="card_status">
											<?php echo $sDroplist;?>
										</select>
						</div>
					</div>
					<div class="field">
						<label for="extension"><small>Extension</small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('caextension')?>" id="extension" name="extension" class="input" placeholder=" example : jaydevil" >
							
						</div>
						
					</div>
				
					<div class="actions">
						<div class="controls">
							&nbsp&nbsp<input type="submit" value="Search" class="btn action">
												</div>
					</div>
					</fieldset>
					</form>
					<form id="cart" action="<?php echo $targetPath;?>" method="POST">
					<input type="hidden" name="act" value="addcarts" >
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1_1">
							<col class="grid1_1">
							<col class="grid1_1">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr> 
								
								<th>TYPE</th>
								<th>DIGITAL</th>
								<th>EXP</th>
								<th>STATE</th>
								<th>COUNTRY</th>
								<th>USED BY</th>
								<th>DATE USED</th>
								<th>STATUS</th>
								<th><input type="checkbox" id="all"></th>
								
							</tr>
						</thead>
						<tbody>
					<?php
						$cards = $this->cards;
						foreach ($cards as $row)
						{?>
							<tr>

								<td><?php echo CHtml::image("templates/default/files/images/".$row['image_map_uri'])?></td>
								<td><?php echo $row['card_digital'];?></td>
								<td><?php echo $row['card_exp_month']."/".$row['card_exp_year']?></td>
								<td><?php echo $row['card_state']?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($row['card_country']))." ".$row['card_country']?></td>
								<td><?php 
								 if(!empty($row['user_name'])){
										echo  $row['user_name'];
									}else echo "None"
								?>
								</td>
								
								<td><?php if(!empty($row['card_used_date'])){
									echo $row['card_used_date'];
								   }else echo "0000-00-00 00:00:00"?></td>
								<td>
								<?php
								switch($row['card_used_status']){
									case 0:
										echo " None";
										break;
									case 1:
										echo CHtml::image("templates/default/files/images/pendding.gif")." Pendding";
										break;
									case 2:
										echo CHtml::image("templates/default/files/images/live.png")." Approved";
										break;
									case 3:
										echo CHtml::image("templates/default/files/images/die.png")." Declined";
										break;
									default:
										echo "<a href='ticket/view/id/".$row['card_used_status']."'>".CHtml::image("templates/default/files/images/report.png")."</a>";
										break;
								}?>
								</td>
								<td><input type="checkbox" name="cards[]" id="checkbox" value="<?php echo $row['card_id']?>"></td>
								
							</tr>
						
						<?php }?>
					
					</tbody>
					</table>
					</form>
				
					<div class="results">
					
					<p></p>
					<?php 
					echo $this->pagination;
					?>
					</div>
					
				</article>
