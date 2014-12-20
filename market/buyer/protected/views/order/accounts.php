<div class="grid12">
		<article class="panel">
				<header>
					<h3>Account(s) Order</h3><br/>
					</header>
					
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
							</tr>
						</thead>
						<tbody>
						<?php 
							$accounts = $this->accounts;
							foreach ($accounts as $row)
							{
								$items = Order::getItemByCartId($row['cart_item'],$row['cart_type'],$row['cart_id']);
								if(is_array($items)){
									foreach($items as $item){
										$items['full_info'] .= $item['$item'].",";
										$items['image_map_uri'] = $item['image_map_uri'];
									}
								}
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
									</tr>
						
						<?php }?>
						</tbody>
						</table>
						<div class="results">
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