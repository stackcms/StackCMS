<?php
/****************************************************
 * Module:			Shop Catalogs
 * Description:		Process all shop catalogs actions
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/shoppe/action/catalog.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/shoppe/action/catalog.'.$act.'.php');
}
?>