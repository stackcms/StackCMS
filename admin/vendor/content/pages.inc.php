<?php
/***************************************************
 * Module:			Page
 * Description:		Process all page content modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/content/page/pages.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/content/action/pages.'.$act.'.php');
}
?>