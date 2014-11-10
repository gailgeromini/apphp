
<body class="processor">
	<div id="page" style="margin-top: 5% !important; margin-bottom: 3% !important;">
		<?php if(!empty($actionMessage)){?>
						<script type="text/javascript">
						// redirect to order after 3 seconds
						window.setTimeout(function() {
						    window.location.href = 'index';
						}, 3000);
						</script>
		<?php }?>
		<?php
		if(!empty($actionMessage)){ ?>
		<div class="msg <?php echo $Mtype?>">
		<p>
		<?php echo $actionMessage; ?></p>
			<a class="close">&times;</a>
		</div>
		<?php }
		?>
	
	</div>
</body>
