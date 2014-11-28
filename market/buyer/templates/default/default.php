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
			<ul id="navigation" class="carts">
					<li class="dropdown">
						<a href="javascript:;"><?php echo CRefactorProfile::getTotalCarts(CAuth::getLoggedId())?> Items <span class="caret"></span></a>
						<ul class="menu">
							<li><a href="cart">Checkout Your Cart: <?php echo CRefactorProfile::getPriceCarts(CAuth::getLoggedId())?>$</a></li>
						</ul>
					</li>
				</ul>
				<ul id="navigation">
				<?php $info = CRefactorProfile::getProfile(CAuth::getLoggedId());?>
					<li>Hello <strong><?php echo $info['user_name'];?></strong> logged as <?php echo $info['ac_role_name'];?> <strong>(<?php echo $info['user_credits'];?>$)</strong><span class="divider">&mdash;</span></li>
					<li class="dropdown">
						<a href="profile/edit">Account setting <span class="caret"></span></a>
						<ul class="menu">
							<li><a href="profile/edit" rel="modal">Edit your account</a></li>
							<li class="divider"></li>
							<li><a href="login/logout">Log out</a></li>
						</ul>
					</li>
				</ul>
			</div>
		<div id="panel">
				<h1 class="icon logo">Private Bulk<small>Our quality commitment, freshness guarantee</small></h1>
				<ul id="menu">
					<li class="<?php echo (($activeLink == 'index') ? ' active' : ''); ?>" ><a href="index">Home</a></li>
					<li class="<?php echo (($activeLink == 'buy') ? ' active' : ''); ?>" ><a href="buy">Buy Items</a></li>
					<li class="<?php echo (($activeLink == 'deposit') ? ' active' : ''); ?>" ><a href="deposit">Deposit</a></li>
					<li class="<?php echo (($activeLink == 'order') ? ' active' : ''); ?>" ><a href="order">Orders History</a></li>
					<li class="<?php echo (($activeLink == 'ticket') ? ' active' : ''); ?>" ><a href="ticket">Tickets <small><?php echo CRefactorProfile::getTicketReport(CAuth::getLoggedId())?></small></a></li>
					<li class="<?php echo (($activeLink == 'terms') ? ' active' : ''); ?>" ><a href="terms">Terms & Privacy</a></li>
					<?php if(!CRefactorProfile::isAdmin(CAuth::getLoggedId())){?>
					<li class="dropdown">
						<a href="javascript:;">User's Dashboard <span class="caret"></span></a>
						<ul class="menu">
							<li class="divider heading">User section</li>
							<li><a href="javascript:;">Tracking (1 weeks)</a></li>
							<li><a href="javascript:;">Report Abuse</a></li>
						</ul>
					</li>
					<?php }?>
					<?php if(CRefactorProfile::isAdmin(CAuth::getLoggedId())){?>
					<li class="<?php echo (($activeLink == 'admin') ? ' active' : ''); ?> dropdown" ><a href="javascript:;">Admin Dashboard <span class="caret"></span></a>
					<ul class="menu">
							<li class="divider heading">Manage section</li>
							<li><a href="../backend/index">Admin section</a></li>
							<li><a href="../backend/console">Consoles (Admin only)</a></li>
							<li><a href="../backend/ticket">Ticket Support (Admin only)</a></li>
							<li class="divider heading">Scheduler section</li>
							<li><a href="javascript:;">Checker automation</a></li>
							<li><a href="javascript:;">Reduce automation</a></li>
						</ul>
					</li>
					<?php }?>
				</ul>
		</div>
			<?php 
			isset($activeLink) ? CWidget::buyerBreadCrumbs(CConfig::get('breadcrumbs.'.$activeLink.'')) : "";
			?>
			<?php }else{?>
				<div id="top">
					<ul id="navigation"></ul>
				</div>
			<?php }?>
		<div id="container">
        <?php echo A::app()->view->getContent(); ?>
		</div>
		<?php if($activeLink == 'buy' AND !isset($standalone)){?>
		<div class="fixed-function">
		<div class="carts_box">
			<li>Your cart <?php echo CRefactorProfile::getTotalCarts(CAuth::getLoggedId())?> Item(s) : <?php echo CRefactorProfile::getPriceCarts(CAuth::getLoggedId())?>$</li>
			<input type="button" onclick="addtocart();" value="Add to cart" class="btn">&nbsp&nbsp<a href="cart" class="btn action">Checkout</a>
		</div>
		</div>
		<?php }?>
		</div>
		<footer>
     	   <p><?php echo A::copyright();?>, All rights reserved.</p>
		</footer>
	
	</body>

</html>