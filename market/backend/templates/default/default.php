<!doctype html>
<html lang="en">
	
<head>
		<meta charset="utf-8" />
		<title><?php echo CHtml::encode($this->_pageTitle); ?></title>
    
    	<base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
		<?php echo CHtml::faviconFile("templates/favicon.ico"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/styles.css"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/assets/tipsy.css"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/assets/facebox.css"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/assets/tablesorter.css"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/assets/uniform.css"); ?>
    	<?php echo CHtml::cssFile("templates/default/files/assets/prettify.css"); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.tipsy.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.facebox.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.tablesorter.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.flot.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.flot.pie.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/assets/jquery.uniform.js'); ?>
		<?php echo CHtml::scriptFile('templates/default/files/scripts.js'); ?>
		<script type="text/javascript">
		$('document').ready(function(){
		    	$('#all').click(function(){
		    	if(document.getElementById("all").checked == true)
		        {
		    		  $('#uniform-checkbox input[type=checkbox]').attr('checked',true);
			          $('#uniform-checkbox span').addClass('checked');      
		        }
		        else
		        {
			          $('#uniform-checkbox input[type=checkbox]').attr('checked',false);
			          $('#uniform-checkbox span').removeClass('checked');
		        }
		    })
		});
		</script>
		<!--[if lt IE 9]>
		<script src="files/assets/html5shiv.js"></script>
		<script src="files/assets/excanvas.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="page">
			<?php if(CAuth::isLoggedIn() == true){?>
			<div id="top">
				<ul id="navigation">
				<?php $info = CRefactorProfile::getProfile(CAuth::getLoggedId());?>
					<li>Hello <strong><?php echo $info['user_name'];?></strong> logged as <?php echo $info['ac_role_name'];?> <span class="divider">&mdash;</span></li>
					<li class="dropdown">
						<a href="profile/edit">Setting <span class="caret"></span></a>
						<ul class="menu">
							<li><a href="../buyer/index">Back to front-end</a></li>
							<li><a href="console">Consoles (Admin only)</a></li>
						</ul>
					</li>
				</ul>
			</div>
		<div id="panel">
				<h1 class="icon logo">OPPS! ADMIN<small>Our quality commitment, Freshness Guarantee</small></h1>
				<ul id="menu">
					<li class="<?php echo (($activeLink == 'index') ? ' active' : ''); ?>" ><a href="index">Home</a></li>
					<li class="<?php echo (($activeLink == 'users') ? ' active' : ''); ?>" ><a href="users">Users<small><?php echo CRefactorAdmin::getTotalUserActive()?></small></a></li>
					<li class="dropdown <?php echo (($activeLink == 'cards' || $activeLink == 'paypals' || $activeLink == 'accounts') ? ' active' : ''); ?>" >
					<a href="#">Item's Manager <span class="caret"></span></a>
					<ul class="menu">
						<li><a href="cards">Credits Card (<?php echo CRefactorAdmin::getTotalCardUnUsed()?>)</a></li>
						<li><a href="paypals">Paypals (<?php echo CRefactorAdmin::getTotalPaypalUnUsed()?>)</a></li>
	                    <li><a href="accounts">Accounts (<?php echo CRefactorAdmin::getTotalAccountUnUsed()?>)</a></li>
					</ul></li>
					<li class="dropdown <?php echo (($activeLink == 'importing') ? ' active' : ''); ?>" >
					<a href="#">Item's Importing <span class="caret"></span></a>
					<ul class="menu">
						<li><a href="importing/cards">Credits Card</a></li>
						<li><a href="importing/paypals">Paypals</a></li>
	                    <li><a href="importing/accounts">Accounts</a></li>
					</ul></li>
                    <li class="<?php echo (($activeLink == 'deposit') ? ' active' : ''); ?>" ><a href="deposit">Deposit <small><?php echo CRefactorAdmin::getTotalPayment()?></small></a></li>
					<li class="<?php echo (($activeLink == 'ticket') ? ' active' : ''); ?>" ><a href="ticket">Tickets <small><?php echo CRefactorAdmin::getTotalAdminTicket()?></small></a></li>
				</ul>
		</div>
			<?php }else{?>
				<div id="top">
					<ul id="navigation"></ul>
				</div>
			<?php }?>
		<div id="container">
        <?php echo A::app()->view->getContent(); ?>
		</div>
		</div>
		<footer>
     	   <p><?php echo A::copyright();?>, All rights reserved.</p>
		</footer>
	
	</body>

</html>