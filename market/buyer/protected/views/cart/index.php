<div id="container">
		<article class="panel">
				<header>
					<h2><?php echo CRefactorProfile::getTotalCarts(CAuth::getLoggedId())?> Item(s)</h2>
					</header>
					<?php if(!empty($Messages)){?>
					<div class="msg <?php echo $Mtype?>">
								<p><?php echo $Messages; ?></p>
							<a class="close">&times;</a>
					</div>
					<?php }?>
					<?php if(!empty($Messages) && $Messages == 'Your cart has successfully completed its checkout.'){?>
						<script type="text/javascript">
						// redirect to order after 3 seconds
						window.setTimeout(function() {
						    window.location.href = 'order';
						}, 3000);
						</script>
					<?php }?>
					<br/>
					<h4>Credit Cards</h4><br/>
					<table class="table ">
						<colgroup>
							<col class="grid2">
							<col class="grid1_1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid2">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>DIGITAL</th>
								<th>COUNTRY</th>
								<th>STATE</th>
								<th>DOB</th>
								<th>SSN</th>
								<th>FULL</th>
								<th>DATE</th>
								<th>COST($)</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
					<?php
					$cards = $this->cards;
						foreach ($cards as $row)
							{ $item = Cart::getDetailByCartItemId($row['cart_item'], '1');?>	
							<tr>
								<td><?php echo CRefactorUltilities::replSOject($item['card_digital'],5,' - XXXX - XXXX - XXXX')?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($item['card_country']))." ".$item['card_country']?></td>
								<td><?php echo $item['card_state']?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['card_dob'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['card_ssn'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['card_fullz'])?></td>
								<td><?php echo $row['cart_date']?></td>
								<td><?php echo $row['cart_price']?>$</td>
								<td><a class="btn" href="cart/delete/id/<?php echo $row['cart_id'];?>" onclick="javascript: if (!confirm('Are you sure you want to delete this item from cart?')) { return false; }">Remove</a></td>
							</tr>
						<?php }?>
						</tbody>
					</table><br/><br/>
					<h4>Paypal Account</h4><br/>
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid1_1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
							<col class="grid2">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>EMAIL</th>
								<th>COUNTRY</th>
								<th>ACCOUNT</th>
								<th>BALANCE</th>
								<th>EMAIL</th>
								<th>BANK</th>
								<th>CARD</th>
								<th>DATE</th>
								<th>COST($)</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
					<?php
					$paypals = $this->paypals;
						foreach ($paypals as $row)
							{ $item = Cart::getDetailByCartItemId($row['cart_item'], '2');?>	
							<tr>
								<td><?php echo CRefactorUltilities::replSOject($item['paypal_email'],5,' ****')?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($item['paypal_country']))." ".$item['paypal_country']?></td>
								<td><?php echo $item['paypal_type']?></td>
								<td><?php echo $item['paypal_balance']?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['paypal_is_email'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['paypal_is_bank'])?></td>
								<td><?php echo CRefactorUltilities::replIObject($item['paypal_is_card'])?></td>
								<td><?php echo $row['cart_date']?></td>
								<td><?php echo $row['cart_price']?>$</td>
								<td><a class="btn" href="cart/delete/id/<?php echo $row['cart_id'];?>" onclick="javascript: if (!confirm('Are you sure you want to delete this item from cart?')) { return false; }">Remove</a></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
					<br/><br/>
					<div class="actions">
						<div class="controls">
							<a href="cart/checkout" class="btn action" onclick="javascript: if (!confirm('Are you sure you want to checkout all items from cart?')) { return false; }">Checkout</a>
							<span>or</span>
							<a href="cart/delete/id/all" class="btn" onclick="javascript: if (!confirm('Are you sure you want to delete all items from cart?')) { return false; }">Remove All Cart</a>
						</div>
					</div>
					<div class="results">
					<p></p>
					
					</div>
				</article>
			</div>