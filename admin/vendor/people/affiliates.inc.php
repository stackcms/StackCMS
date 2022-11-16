<?php
/***********************************************
 * Module:			Affiliates
 * Description:		Process all affiliates modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/people/page/affiliates.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/people/action/affiliates.'.$act.'.php');
}
?>