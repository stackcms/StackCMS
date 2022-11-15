<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);
@include($tcgpath.'themes/headers/acct-header.php');


// Begin module inclusions
if( empty( $do ) )
{
	@include($tcgpath.'modules/account/index.php');
}

else
{
	@include($tcgpath.'modules/account/'.$do.'.inc.php');
}


@include($tcgpath.'themes/headers/acct-footer.php');
@include($footer);
?>