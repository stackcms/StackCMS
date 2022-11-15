<?php
/*****************************************
 * Module:			Account Logout
 * Description:		Process user logout
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	$_SESSION = array();
	session_destroy();

	// Redirect to following page after logout
	header("Location: account.php?do=login");
}
?>