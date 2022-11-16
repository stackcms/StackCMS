<?php
/**********************************************
 * Module:			Members
 * Description:		Process all members modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/people/page/members.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/people/action/members.'.$act.'.php');
}
?>