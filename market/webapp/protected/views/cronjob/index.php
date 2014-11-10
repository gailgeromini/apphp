<?php 
$time = explode(' ', microtime());
echo json_encode(
		array('finish'=>$time[0].' seconds','msg'=>'cronjob success')
)?>
