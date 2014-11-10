<br/>
<div class="grid12">
	<ul id="console">
	<?php $info = CRefactorProfile::getProfile(CAuth::getLoggedId());?>
 	<?php
      if(!empty($console)){
           foreach ($console as $row) {
			echo "<li><span>.\user\\".$info['user_name']."> ".$row["logs_date"]."</span> : <b>".$row["user_name"]."</b> ".$row["logs_message"]."</li>";
                ?>
    <?php } 
		}else {
				?>
				<li>No Logs Found</li>
   		<?php }?>
	</ul>
	<br/>
</div>