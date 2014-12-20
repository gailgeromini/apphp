<div class="grid12">
		<article class="panel">
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
                            <input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('ticketyextension')?>" id="extension" name="extension" class="input" placeholder=" example : jaydevil" >
                        </div>
                    </div>

                    <div class="actions">
                        <div class="controls">
                            &nbsp&nbsp<input name="action" type="submit" value="Search" class="btn action">
                            <span>or</span>
                            <input name="action" type="submit" value="Show All Types" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all paypal without any condition?')) { return false; }">
                        </div>
                    </div>
                </fieldset>
            </form>
				<header>
						<h3>Ticket</h3>
						<br/>
					</header>
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid1">
							<col class="grid2">
							<col class="grid2">
							<col class="grid1">
							<col class="grid2">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>TYPE</th>
								<th>USER</th>
								<th>SUBJECT</th>
								<th>STATUS</th>
								<th>PRIORITY</th>
								<th>LAST UPDATE</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
					<?php
					$ticket = $this->ticket;
						foreach ($ticket as $row)
						{ $items=Ticket::getItemByTypeId($row['ticket_item_id'],$row['ticket_item_type'])
						?>
						
							<tr>
								<td><?php echo CHtml::image("templates/default/files/images/".$items['image_map_uri'])?></td>
								<td><?php echo $row['user_name']?></td>
								<td><?php echo $row['ticket_subject']?></td>
								<td><?php echo $row['ticket_status_name']?></td>
								<td><?php 
								switch($row['ticket_priority'])
								{
									case 1:
										echo "<code>Medium</code>";
										break;
									case 2:
										echo "<code>High</code>";
										break;
									case 3:
										echo "<code>Low</code>";
										break;
								}
								?></td>
								<td><?php echo $row['date_submited']?></td>
								<td><a class="btn" href="ticket/view/id/<?php echo $row['ticket_id']?>">View</a></td>
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
