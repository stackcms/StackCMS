<?php
/***********************************************
 * Module:			Chat Box
 * Description:		Process all chat box modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/content/page/chatbox.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/content/action/chatbox.'.$act.'.php');
}
?>