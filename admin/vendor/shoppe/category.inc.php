<?php
/******************************************************
 * Module:			Shop Categories
 * Description:		Process all shop categories actions
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/shoppe/action/category.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/shoppe/action/category.'.$act.'.php');
}
?>