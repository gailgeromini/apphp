<div id="container">
		<article class="panel">
				<header>
						<h3>Deposit</h3><br/>
					</header>
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid2">
							<col class="grid2">
							<col class="grid1">
							<col class="grid1">
							<col class="grid3">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>Method</th>
								<th>User</th>
								<th>Account</th>
								<th>Batch</th>
								<th>Amount</th>
								<th>Payment Date</th>
								<th>Commision</th>
								<th>Updated</th>
							</tr>
						</thead>
						<tbody>
					<?php
					$history = $this->history;
						foreach ($history as $row)
						{?>
							<tr>
								<td><?php echo CHtml::image($row['payment_method'])?></td>
								<td><?php echo $row['user_name']?></td>
								<td><?php echo $row['payment_account']?></td>
								<td><?php echo $row['payment_batch']?></td>
								<td><?php echo $row['payment_amount']?>$</td>
								<td><?php echo $row['payment_date']?></td>
								<td><?php echo $row['payment_commis']?>$</td>
								<td><?php echo $row['payment_updated']?>$</td>
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