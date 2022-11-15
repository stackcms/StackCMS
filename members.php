<?php
@include($tcgurl.'admin/class.lib.php');
@include($header);


// Begin module inclusions
if( empty( $page ) ) {
	@include($tcgpath.'modules/members/index.php');
}

else {
	@include($tcgpath.'modules/members/members.'.$page.'.inc.php');
}


@include($footer);
?>