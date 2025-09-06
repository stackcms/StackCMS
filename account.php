<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

######## CUSTOMIZING LAYOUTS #######
# This header creates a two-column layout for the account page.
# If you want to make a custom layout (e.g. single column), you
# can simply comment out or remove this acct-header block.
@include($tcgpath.'themes/headers/acct-header.php');
###### END CUSTOMIZING LAYOUT ######


// Begin module inclusions
if( empty( $do ) )
{
	@include($tcgpath.'modules/account/index.php');
}

else
{
	@include($tcgpath.'modules/account/'.$do.'.inc.php');
}


######## CUSTOMIZING LAYOUTS #######
# This header creates a two-column layout for the account page.
# If you want to make a custom layout (e.g. single column), you
# can simply comment out or remove this acct-footer block.
@include($tcgpath.'themes/headers/acct-footer.php');
###### END CUSTOMIZING LAYOUT ######

@include($footer);

?>
