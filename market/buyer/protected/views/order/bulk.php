<div id="container">
		<article class="panel">
				<header>
						<h3>Bulk(s) Email</h3>
					</header>
					<br/>
					<table class="table ">
						<colgroup>
							<col class="grid1">
							<col class="grid2">
							<col class="grid1">
							<col class="grid1">
							<col class="grid1">
						</colgroup>
						<thead>
							<tr>
								<th>Type</th>
								<th>Bulk Comment</th>
								<th>Country</th>
								<th>Date</th>
								<th>Download Link</th>
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
								<td><?php echo $row['m_used_date']?></td>
								<td>
								<?php if($row['is_download'] == 0):?>
								<a href="<?php echo "order/bulk/file/".$row['m_file_path'];?>"><code><?php echo $data->mail_type."_".$row['m_file_path'];?></code></a>
								<?php else: ?>
								<code>Expiring download links</code>
								<?php endif;?>
								</td>
							</tr>
						
						<?php }?>
					
					</tbody>
					</table>
					<br/>
					<div class="results">
					<p></p>
					<?php 
					echo $this->pagination;
					?>
					</div>
					
				</article>
			</div>