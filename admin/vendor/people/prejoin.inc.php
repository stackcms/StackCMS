<?php
/******************************************************
 * Module:			Prejoin Rewards
 * Description:		Process all prejoin rewards modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/people/page/prejoin.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/people/action/prejoin.'.$act.'.php');
}
?>