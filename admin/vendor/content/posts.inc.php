<?php
/************************************************
 * Module:			Post
 * Description:		Process all blog post modules
 */


// Begin action inclusions
if( empty( $act ) )
{
	@include($tcgpath.'admin/vendor/content/page/posts.main.php');
}

else
{
	@include($tcgpath.'admin/vendor/content/action/posts.'.$act.'.php');
}
?>