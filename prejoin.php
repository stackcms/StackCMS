<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);


// Begin module inclusions
if( empty( $form ) ) {
	@include($tcgpath.'modules/prejoin/index.php');
}

else {
	@include($tcgpath.'modules/prejoin/'.$form.'.inc.php');
}


@include ($footer);
?>