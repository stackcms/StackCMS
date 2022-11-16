<?php
/***************************************************
 * Module:			Page
 * Description:		Process all page content modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	header("Location: content.php");
}

else{
	@include($tcgpath.'admin/modules/content/action/page.'.$act.'.php');
}
?>