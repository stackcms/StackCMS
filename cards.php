<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);
@include($tcgpath.'themes/headers/deck-header.php');


// Begin module inclusions
if( empty( $view ) )
{
	@include($tcgpath.'modules/cards/index.php');
}

else
{
	@include($tcgpath.'modules/cards/'.$view.'.inc.php');
}


@include($tcgpath.'themes/headers/deck-footer.php');
@include ($footer);
?>