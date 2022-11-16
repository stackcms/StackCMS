<?php
/**********************************************
 * Module:			Plugins
 * Description:		Process all plugins actions
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/settings/page/plugins.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/settings/action/plugins.'.$act.'.php');
}
?>