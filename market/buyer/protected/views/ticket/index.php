<div class="grid9">
		<article class="panel">
				<header>
						<h3>Ticket</h3>
						<br/>
					</header>
					<table class="table ">
						<colgroup>
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
			<div class="grid3">
		<div class="block">
			<h2>Support Online</h2>
			<div class="container">
			<div class="row">
			
			</div>
			</div>
		
		</div>
		</div>