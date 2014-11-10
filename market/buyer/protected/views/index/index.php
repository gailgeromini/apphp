<div class="row">
						
					<div class="grid6">
						<article class="panel">
							<header class="divider">
							<h3>News</h3><br/>
							</header>
							<?php foreach ($records as $block){
							?>
							<h6><?php echo $block['news_title']?></h6>
							<p><?php echo $block['news_content']?></p>
							<?php }?>
						</article>
					</div>
					<div class="grid6">
						<article class="panel">
							<header class="divider">
								<h3>Notification</h3><br/>
							</header>
							<blockquote>
								<!--<p> 
								
								<a href="ymsgr:sendim?<?php echo CConfig::get('help.yahoo_support_1')?>"> <img border="0" style="border: 0px none; vertical-align: middle; margin-right: 2px; margin-left: 2px;" alt="Chat with us" src="http://opi.yahoo.com/online?u=<?php echo CConfig::get('help.yahoo_support_1')?>&amp;m=g&amp;t=1"></a>
								<a href="ymsgr:sendim?<?php echo CConfig::get('help.yahoo_support_2')?>"> <img border="0" style="border: 0px none; vertical-align: middle; margin-right: 2px; margin-left: 2px;" alt="Chat with us" src="http://opi.yahoo.com/online?u=<?php echo CConfig::get('help.yahoo_support_2')?>&amp;m=g&amp;t=1"></a>
								</p> -->
								<p>
								 <code><b>ICQ :<?php echo CConfig::get('help.icq_2')?></b></code>
								 <code><b>ICQ :<?php echo CConfig::get('help.icq_1')?></b></code>
								</p>
							</blockquote>
							<blockquote>
								<p>
								   Since 07/22/2014, Opps service officially changed from the <code>Gate Checker</code>.
								   We apologize for any inconvenience.<br>Sincerely,
								</p>
								<small>Opps - 07/22/2014</small>
							</blockquote>
							<blockquote>
								<p>
								   Since 11/18/2013, BLZONE service officially changed from the domain  <code>www.blzone.ru</code> to <code>www.opps.sx</code>.
								   Therefore members will automatically access to the new domain while access on the <code>www.opps.sx</code>.<br>
								   We apologize for any inconvenience.<br>Sincerely,
								</p>
								<small>Opps - 11/18/2013</small>
							</blockquote>
						</article>
						
					</div>
</div>