<?php
/*************************************************
 * Module:			Shop Items
 * Description:		Process all shop items actions
 */


// Begin action inclusions
if( empty( $act ) )
{
	header("Location: shoppe.php");
}

else
{
	@include($tcgpath.'admin/vendor/shoppe/action/items.'.$act.'.php');
}
?>