<div class="grid9">
		<article class="panel">
		<script type="text/javascript">
		function postComment()
		{
			var returnCommentBox = 'no'
				
			if(document.frm_comment.comment.value < 5)
			{
				document.frm_comment.comment.focus();
				returnCommentBox ='yes';
			}
			if(returnCommentBox == 'no')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		</script>
				<header>
					<h6><?php echo $ticketContent['ticket_subject']?>  #<?php echo $this->ticketID?> <?php echo $ticketContent['ticket_status_name']?> </h6>
					</header>
					<?php if(!empty($Messages)){?>
					<div class="msg <?php echo $Mtype?>">
								<p><?php echo $Messages; ?></p>
							<a class="close">&times;</a>
					</div>
					<?php }?>
					<br/>
					<?php if(!empty($ticketContent)){?>
					<blockquote>
					<p><?php echo $ticketContent['ticket_item_info']?> </p>
					<small>Submited on : <?php echo $ticketContent['date_submited']?></small>
					</blockquote>
					
					<?php $comment = $this->comment;
						if(!empty($comment)){
						foreach ($comment as $row){?>
								<div class="msg comment">
								<span style="display:block;"><code>{<?php echo $row['ticket_comment_date']?>} <?php echo $row['user_name'];?> wrote : </code></span>
								<p><?php echo $row['ticket_comment']?><br/></p>
								
								</div>
						<?php }
					}?>
					<?php if($ticketContent['ticket_status_id'] != 4 && $ticketContent['ticket_status_id'] != 5 ){?>
					<form action="" method="POST" name="frm_comment" onsubmit="return postComment()" >
					<fieldset class="form">
					
					<?php
				    // draw search form
				       echo CWidget::form(array(
				        	
				            'fields'=>array(

				                'act'     =>array('type'=>'hidden', 'value'=>'comment'),
				            ),
				            'return'=>true,
				        ));    
				    ?>
					<div class="field" style="border-bottom:none;">
									<div class="controls">
										<textarea name="comment" style="width: 98%;" id="textarea" class="textarea"></textarea>
									</div>
					</div>
						<div style="float:left">
						    <input type="submit" name="btn" class="btn action" value="Comment" onclick="">
						</div>
					</fieldset>
					</form>
					<?php }?>
					<?php }?>
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