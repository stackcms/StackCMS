<?php
/************************************************
 * Module:			Post
 * Description:		Process all blog post modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	header("Location: content.php");
}

else{
	@include($tcgpath.'admin/modules/content/action/post.'.$act.'.php');
}
?>