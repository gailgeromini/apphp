<div id="container">
		<article class="panel">
				<header>
						<h3>Bulk(s) Email</h3>
					</header>
					<br/>
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

				                'act'     =>array('type'=>'hidden', 'value'=>'buy'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid3">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>Type</th>
								<th>Bulk Comment</th>
								<th>Country</th>
								<th>Price</th>
								<th><input type="checkbox" id="all"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					$bulk = $this->bulk;
						foreach ($bulk as $row)
						{ 
							$data = json_decode($row['m_desc_json'])
						?>
							<tr>
								<td><?php echo $data->mail_type?></td>
								<td><?php echo $data->mail_desc?></td>
								<td><?php echo CRefactorUltilities::flagsObject(strtolower($data->mail_country))." ".$data->mail_country?></td>
								<td><?php echo $row['m_price']?>$</td>
								<td><input type="checkbox" name="bulk[]" id="checkbox" value="<?php echo $row['m_id']?>"></td>
							</tr>
						
						<?php }?>
					
					</tbody>
					</table>
					<br/>
						<div class="actions">
						<div class="controls">
						    <input type="submit" style="float:right;" name="btn" class="btn action" value="Checkout Bulk(s)">
						</div>
						
					</div>
					<div class="results">
					<p></p>
					<?php 
					echo $this->pagination;
					?>
					</div>
					</fieldset>
					</form>
				</article>
			</div>