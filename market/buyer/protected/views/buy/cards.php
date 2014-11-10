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
						</div>
					</div>
					<div class="field">
						<label for="extension"><small>Extension</small></label>
						<div class="controls">&nbsp
							<input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('cextension')?>" id="extension" autocomplete = "false" name="extension" class="input" placeholder=" 411773 , NY , 11225 , Albany" >
							 &nbsp&nbsp( Bin , State , Zipcode , City ) <span class="fee">( +0.2$ )</span>
						</div>
						
					</div>
				
					<div class="actions">
						<div class="controls">
							&nbsp&nbsp<input type="submit" name="action" value="Search" class="btn action">
							<span>or</span>
							<input type="submit" name="action" value="Show All Cards" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all card without any condition?')) { return false; }" >
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
								<th>DIGITAL</th>
								<th>EXP</th>
								<th>SSN</th>
								<th>DOB</th>
								<th>FULL</th>
								<th>STATE</th>
								<th>ZIPCODE</th>
								<th>COUNTRY</th>
								<th>COST($)</th>
								<th>FEE($)</th>
								<th>COMMENT</th>
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
								<td><?php echo CRefactorUltilities::replSOject($row['card_digital'],5,'*****');?></td>
								<td><?php echo $row['card_exp_month']."/".$row['card_exp_year']?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['card_ssn'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['card_dob'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($row['card_fullz'])?></td>
								<td><?php echo $row['card_state']?></td>
								<td><?php echo $row['card_zipcode']?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($row['card_country']))." ".$row['card_country']?></td>
								<td><?php 
									echo  ($row['card_price'] + A::app()->getSession()->get('fee')) - (($row['card_price'] * $row['discount']) / 100)."$";
									if($row['discount'] != 0 ){
										echo "<span style='font-size:8.5px;color:#E02323;'> -".$row['discount']."%</span>";
									}
								?>
								</td>
								<td><?php 
									if(A::app()->getSession()->get('fee') != ''){
										echo "<span style='font-size:8.5px;color:#E02323;'> ( +".A::app()->getSession()->get('fee')." $ )</span>";
									}else echo "No Fee"?></td>
								<td><?php echo $row['card_comments']?></td>
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
