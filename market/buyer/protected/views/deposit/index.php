<div class="grid9">
<?php echo CHtml::scriptFile('templates/micro/js/validate.form.req.js'); ?>
<?php echo CHtml::scriptFile('templates/default/files/deposit.js'); ?>
	<script type="text/javascript">
		var reqForm = new Validate();
	</script>
		<article class="panel">
		<header>
				<h3>Deposit</h3><br/>
			</header>
				<?php if(!empty($Messages)){?>
				<div class="msg <?php echo $Mtype?>">
						<p><?php echo $Messages; ?></p>
						<a class="close">&times;</a>
				</div>
				<?php }?>
			<br/>
			<fieldset class="form">
			<?php $info = CRefactorProfile::getProfile(CAuth::getLoggedId());?>
			<div class="field">
				<div class="controls pcontrols">
					<ul>
						<?php if(CRefactorProfile::handleNewestMember(CAuth::getLoggedId())):?>
						<li><label for="perfect"><input type="radio" name="radio" checked onchange="deposit();">Perfect Money</label></li>
						<?php else:?>
						<li><label for="perfect"><input type="radio" name="radio" checked onchange="deposit();">Perfect Money (e-Voucher) &nbsp</label></li>
						<?php endif;?>
						<li><label for="bitcoin"><input type="radio" name="radio"  onchange="deposit();">BTC (Bitcoin-USD) &nbsp</label></li>
					</ul>
				</div>
			</div>
			<div id="deposit_bitcoin"  style="display:none;">
			<p>	You Can Send First the money you want to address <code><?php echo A::app()->getSession()->get('btc_address')?></code> Then click on Top Up it will be added Automatic<br/>
			<code>MINIMUM PAYMENT: $<?php echo CConfig::get('btc_method.bitcoin_min_pay')?></code><br/>
			<code>1BTC = <?php echo A::app()->getSession()->get('btc_rate')?>$</code>
			<br/>
			I already sent money to (min $<?php echo CConfig::get('btc_method.bitcoin_min_pay')?> / max $1,000.00).</p>
				<form method="POST" action="" method="POST" >
			<div class="field" id="field_p5">
				<div class="controls pcontrols">
				<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'deposit'),
								'BTC_ADDRESS'     =>array('type'=>'hidden', 'value'=>A::app()->getSession()->get('btc_address')),
				            ),
				            'return'=>true,
				        ));    
				    ?>
				    <input type="submit" value="Topup" class="btn action">
				</div>
				
			</div>
			</form>
			</div>
			<?php if(CRefactorProfile::handleNewestMember(CAuth::getLoggedId())):?>
			<div id="deposit_perfectmoney">
			<p>We only accept USD from Perfect Money site: https://perfectmoney.is<br/>
			You Can Send the money you want					
			to our account <code><?php echo CConfig::get('pm_method.perfect_account')?></code> follow step by step .Then click on Top Up it will be added Automatic<br/>
			<code>MINIMUM PAYMENT: $<?php echo CConfig::get('pm_method.perfect_min_pay')?></code><br/>
			I already sent money to  <code><?php echo CConfig::get('pm_method.perfect_account')?></code> (min $<?php echo CConfig::get('pm_method.perfect_min_pay')?> / max $1,000.00).</p>
			<form onsubmit="return returnfrPerfect()" name="frPerfect" method="POST" action="" method="POST" >
			
			<div class="field" id="field_p7">
						<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'deposit'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
				<div class="controls pcontrols">
					<input type="text" class="input" placeholder="Enter your batch number ?" name="BATCH_NUMBER"  AUTOCOMPLETE="OFF" onblur="reqForm.validCompare(this.id,this.value,this.name,'#field_p7','#field_deposit_2','Batch number can not be blank','Enter batch number > 5 character',5)">
				   <p id="field_deposit_2"></p>
				</div>&nbsp
				<div class="controls pcontrols">
				<input type="submit" value="Topup" class="btn action">
				</div>
				</div>
			</form>
			
			<p>
	
			</div>
			<?php else:?>
			<div id="deposit_perfectmoney">
			<p>We only accept USD withdraw code from Perfect Money site: https://perfectmoney.is/withdrawal.html <br/>
			You Can Send the money you want					
			by <code style="background-color:#E10000;color:#fff;">"e-Voucher"</code> follow step by step .Then click on Top Up it will be added Automatic<br/>
			<code>MINIMUM PAYMENT: $<?php echo CConfig::get('pm_method.perfect_min_pay')?></code><br/>
			I already have e-Voucher (min $<?php echo CConfig::get('pm_method.perfect_min_pay')?> / max $1,000.00).</p>
			<form onsubmit="return returnfrPerfect()" name="frPerfect" method="POST" action="" method="POST" >
			
			<div class="field" id="field_p7">
						<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'deposit'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
				<div class="controls pcontrols">
					<input type="text" class="input" placeholder="Enter your e-Voucher number ?" name="EV_NUMBER"  AUTOCOMPLETE="OFF" onblur="reqForm.validCompare(this.id,this.value,this.name,'#field_p7','#field_deposit_2','e-Voucher number can not be blank','Enter e-Voucher number > 5 character',5)">
				   <p id="field_deposit_2"></p>
				</div>&nbsp
				<div class="controls pcontrols">
				 	<input type="text" class="input" placeholder="Enter your e-Voucher activation code ?" name="EV_CODE"  AUTOCOMPLETE="OFF" onblur="reqForm.validCompare(this.id,this.value,this.name,'#field_p7','#field_deposit_3','e-Voucher activation code can not be blank','Enter e-Voucher activation code > 5 character',5)">
					<p id="field_deposit_3"></p>
				</div>&nbsp
				<div class="controls pcontrols">
				<input type="submit" value="Topup" class="btn action">
				</div>
				</div>
			</form>
			
			<p>
			<b>HOW TO MAKE E-VOUCHER Code ON PerfectMoney </b>
			<br/>
				If you have already balance in Your PerfectMoney Account then follow these step:<br/>
				Step 1: Go here : <a target="_blank" href="https://perfectmoney.com/withdrawal.html">https://perfectmoney.com/withdrawal.html</a><br/>
				after then click on e-Voucher code : <br/></p>
				<?php echo CHtml::image('templates/default/files/images/evoucher.gif')?><br/>
				<?php echo CHtml::image('templates/default/files/images/PM1.jpg')?><br/><br/>
			<p>Step 2: Then you will get page <code>e-Voucher deposit</code><br/>
				then click on Purchase a new e-Voucher</p><br/>
				<?php echo CHtml::image('templates/default/files/images/PM2.jpg')?><br/><br/>
			<p>Step 3: Then you will get this Page Purchase e-Voucher<br/>
				Please select the account and the exact e-Voucher amount you want to deposit (same invoice amount).<br/>
				Account to charge from : USD Account<br/>
				Amount of e-Voucher : same invoice amount<br/></p>
				<?php echo CHtml::image('templates/default/files/images/PM3.jpg')?><br/><br/>
			<p>	Step 4: then you will get page for buy click button and your e-Voucher Code ready to creat<br/>
				e-Voucher # 9989148344<br/>
				Activation code 6419541512795387<br/>
				Amount 100.00 USD<br/>
				Step 5: Back to shop and Submit info e-Voucher to complete your order<br/>
			</p>
			</div>
			<?php endif;?>
		</fieldset>
		</article>
	</div>
	<div class="grid3">
		<div class="block">
			<h2>Payments Accepted</h2>
			<div class="container">
			<div class="row">
			<?php echo CHtml::image('templates/default/files/images/payment.png')?>
			</div>
			</div>
		
		</div>
		<div class="block">
			<h2>Support Online</h2>
			<div class="container">
			<div class="row">
	
			</div>
			</div>
		
		</div>
	</div>