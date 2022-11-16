<?php
/************************************************
 * Module:			TCG Items
 * Description:		Process all TCG items actions
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/settings/page/tcg-items.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/settings/action/tcg-items.'.$act.'.php');
}
?>