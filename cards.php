<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

####### CUSTOMIZING LAYOUTS #######
# This header creates a two-column layout for the account page.
# If you want to make a custom layout (e.g. single column), you
# can simply comment out or remove this deck-header block.
@include($tcgpath.'themes/headers/deck-header.php');
##### END CUSTOMIZING LAYOUTS #####


// Begin module inclusions
if( empty( $view ) )
{
	@include($tcgpath.'modules/cards/index.php');
}

else
{
	@include($tcgpath.'modules/cards/'.$view.'.inc.php');
}


####### CUSTOMIZING LAYOUTS #######
# This header creates a two-column layout for the account page.
# If you want to make a custom layout (e.g. single column), you
# can simply comment out or remove this deck-footer block.
@include($tcgpath.'themes/headers/deck-footer.php');
##### END CUSTOMIZING LAYOUTS #####

@include ($footer);

?>
