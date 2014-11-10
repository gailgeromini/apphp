<div class="grid12">
		<article class="panel">
				<header>
					<h3>Card(s) Order</h3><br/>
					</header>
					<div class="help">
							<span><?php echo CHtml::image("templates/default/files/images/live.png");?> Approved</span>
							<span><?php echo CHtml::image("templates/default/files/images/die.png");?> Declined</span>
							<span><?php echo CHtml::image("templates/default/files/images/report.png");?> Ticket Submited</span>
							<span><?php echo CHtml::image("templates/default/files/images/expired.png");?> Expired <code><?php echo CConfig::get('setting.time_exp')?> minute since you got item(s)</code></span>
					</div>
					<?php if(!empty($Messages)){?>
					<div class="msg <?php echo $Mtype?>">
								<p><?php echo $Messages; ?></p>
							<a class="close">&times;</a>
					</div>
					<?php }?>
					<br/>
					<form action="<?php echo $this->targetPath?>" method="post" name="frmLogin">
					<fieldset>
					<?php
				    // draw login form
				        echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'run'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
				    
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid1">
							<col class="grid2">
							<col class="grid5">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>TYPE</th>
								<th>SESSION</th>
								<th>DATE</th>
								<th>INFOR</th>
								<th><input type="checkbox" id="all"></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$cards = $this->cards;
							foreach ($cards as $row)
							{
								$items = Order::getItemByCartId($row['cart_item'],$row['cart_type'])
							?>
							<tr>
								<td>
									<?php echo CHtml::image("templates/default/files/images/".$items['image_map_uri'])?></td>
								<td>
									<?php echo $row['cart_session'] ?>
								</td>
								<td>
									<?php echo $row['cart_date'] ?>
								</td>
								<td>
								<div class="field">
									
									<div class="controls">
									
										<textarea id="<?php echo $row['cart_id']?>"  onClick="document.getElementById('<?php echo $row['cart_id']?>').focus();document.getElementById('<?php echo $row['cart_id']?>').select()" class="textarea" style="width:620px;min-height: 25px!important;height: 25px!important;"><?php echo $items['full_info']?></textarea>
									</div>
								</div>
									
								</td>
								
								<td>
								<?php
								switch($items['item_status']){
									case 0:
										if(CRefactorUltilities::itemExpire($items['time_used'])){
											  echo "<input type='checkbox' name='items[]' id='checkbox' value='".$row['cart_id']."'>";
										}else echo CHtml::image("templates/default/files/images/expired.png");
										break;
									case 1:
										echo CHtml::image("templates/default/files/images/pendding.gif");
										break;
									case 2:
										echo CHtml::image("templates/default/files/images/live.png");
										break;
									case 3:
										echo CHtml::image("templates/default/files/images/die.png");
										break;
									default:
										echo "<a href='ticket/view/id/".$items['item_status']."'>".CHtml::image("templates/default/files/images/report.png")."</a>";
										break;
								}?>
								</td>
							</tr>
						
						<?php }?>
						</tbody>
						</table>
						<div class="results">
						<div style="float:left">
						    <input type="submit" name="btn" class="btn action" value="Cronjob Check" onclick="javascript: if (!confirm('Are you sure you want to added item(s) to check valid ?')) { return false; }">
						</div>
						<p></p>
						<?php 
						echo $this->pagination;
						?>
						</div>
						<br/>
						
						
					</div>
					</fieldset>
					</form>
				
				</article>
				</div>
	

</div>