<div id="container">
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
                                <?php echo $cDroplist;?>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label for="extension"><small>Search payment by Username</small></label>
                        <div class="controls">&nbsp
                            <input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('payextension')?>" id="extension" name="extension" class="input" placeholder=" example : jaydevil" >
                        </div>
                    </div>

                    <div class="actions">
                        <div class="controls">
                            &nbsp&nbsp<input name="action" type="submit" value="Search" class="btn action">
                            <span>or</span>
                            <input name="action" type="submit" value="Show All Payments" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all paypal without any condition?')) { return false; }">
                        </div>
                    </div>
                </fieldset>
            </form>
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