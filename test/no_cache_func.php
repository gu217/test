<?php
function smarty_insert_ServerTime($str)
{
	return date('Y-m-d H:i:s',$str['str']);
}
?>
