<?php
/**************************************************
 * Tab:				Cards Settings
 * Description:		Show main tab of cards settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Process edit cards form
if( isset( $_POST['action'] ) == 'edit-cards' )
{
	$settings->update_settings( $_POST );
	echo '<p class="success"><code>Settings updated.</code></p>';
}

echo '<form action="'.$tcgurl.'admin/settings.php" method="post">
<input type="hidden" name="action" value="edit-cards" />
<table width="100%" cellspacing="5" cellpadding="5">
<tr>
	<td width="24%" valign="top">
		<b>File Type</b><br />
		<small><i>'.$settings->getDesc( 'cards_file_type' ).'</i></small>
	</td>
	<td width="76%">
		<input type="text" name="'.$settings->getName( 'cards_file_type' ).'" value="'.$settings->getValue( 'cards_file_type' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Card Count</b><br />
		<small><i>'.$settings->getDesc( 'cards_total_count' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'cards_total_count' ).'" value="'.$settings->getValue( 'cards_total_count' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Card Worth</b><br />
		<small><i>'.$settings->getDesc( 'cards_total_worth' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'cards_total_worth' ).'" value="'.$settings->getValue( 'cards_total_worth' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top"><b>Card Size</b></td>
	<td>
		<small><i>'.$settings->getDesc( 'cards_size_width' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'cards_size_width' ).'" value="'.$settings->getValue( 'cards_size_width' ).'" size="40" required /><br />
		<small><i>'.$settings->getDesc( 'cards_size_height' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'cards_size_height' ).'" value="'.$settings->getValue( 'cards_size_height' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top"><b>Link Button Size</b></td>
	<td>
		<small><i>'.$settings->getDesc( 'button_size_width' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'button_size_width' ).'" value="'.$settings->getValue( 'button_size_width' ).'" size="40" required /><br />
		<small><i>'.$settings->getDesc( 'button_size_height' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'button_size_height' ).'" value="'.$settings->getValue( 'button_size_height' ).'" size="40" required />
	</td>
</tr>
</table>

<input type="submit" class="btn-success" value="Edit Cards">
</form>';
?>