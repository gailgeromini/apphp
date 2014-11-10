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
										<select id="select" name="ptype">
											<?php echo $typeDroplist;?>
										</select>
										<select id="select" name="pcountry">
											<?php echo $cDroplist;?>
										</select>
										<select id="select" name="pcategory">
											<?php echo $catDroplist;?>
										</select>
						</div>
					</div>
					<div class="field">
				<label for="extension"><small>Extension</small></label>
				<div>
				<div class="controls pcontrols">
					<ul>
						<li><label for="Email">
						<input type="checkbox" <?php if(A::app()->getSession()->get('pemail')){
							echo 'checked=""';
						}?> 
						id="box_checked" name="pemail">Email Login</label></li>
						<li><label for="Balance"><input type="checkbox" <?php if(A::app()->getSession()->get('pbalance')){
							echo 'checked=""';
						}?>id="box_checked" name="pbalance">With Balance</label></li>
					</ul>
				</div>
				
					</div>	
					</div>
				
					<div class="actions">
						<div class="controls">
							&nbsp&nbsp<input name="action" type="submit" value="Search" class="btn action">
							<span>or</span>
							<input name="action" type="submit" value="Show All Paypals" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all paypal without any condition?')) { return false; }">
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
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1_1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr> 
								
								<th>TYPE</th>
								<th>EMAIL</th>
								<th>ACCOUNT</th>
								<th>BALANCE</th>
								<th>EMAIL</th>
								<th>CARD</th>
								<th>BANK</th>
								<th>COUNTRY</th>
								<th>COST($)</th>
								<th>FEE($)</th>
								<th>COMMENT</th>
								<th><input type="checkbox" id="all"></th>
								
							</tr>
						</thead>
						<tbody>
					<?php
						$paypals = $this->paypals;
						foreach ($paypals as $row)
						{?>
							<tr>

								<td><?php echo CHtml::image("templates/default/files/images/".$row['image_map_uri'])?></td>
								<td><?php echo CRefactorUltilities::replSOject($row['paypal_email'],5,'*****');?></td>
								<td><?php echo $row['paypal_type']?></td>
								<td><?php echo $row['paypal_balance']?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['paypal_is_email'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['paypal_is_card'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['paypal_is_bank'])?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($row['paypal_country']))." ".$row['paypal_country']?></td>
								<td><?php 
									echo  ($row['paypal_price'] + A::app()->getSession()->get('fee')) - (($row['paypal_price'] * $row['discount']) / 100)."$";
									if($row['discount'] != 0 ){
										echo "<span style='font-size:8.5px;color:#E02323;'> -".$row['discount']."%</span>";
									}
								?>
								</td>
								<td><?php 
									if(A::app()->getSession()->get('fee') != ''){
										echo "<span style='font-size:8.5px;color:#E02323;'> ( +".A::app()->getSession()->get('fee')." $ )</span>";
									}else echo "No Fee"?></td>
								<td><?php echo $row['paypal_comments']?></td>
								<td><input type="checkbox" name="paypals[]" id="checkbox" value="<?php echo $row['paypal_id']?>"></td>
								
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
