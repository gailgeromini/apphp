
<body class="processor">
	<div id="page" style="margin-top: 5% !important; margin-bottom: 3% !important;">
		<?php if($actionMessage == 'A new password has been sent to your email address'){?>
						<script type="text/javascript">
						// redirect to order after 3 seconds
						window.setTimeout(function() {
						    window.location.href = 'login';
						}, 3000);
						</script>
		<?php }?>
		<?php
		if(!empty($actionMessage)){ ?>
		<div class="msg success">
		<p><strong>Success !</strong>
		<?php echo $actionMessage; ?></p>
			<a class="close">&times;</a>
		</div>
		<?php }
		?>

		
	</div>
</body>
