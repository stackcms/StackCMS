<?php
/**************************************************
 * Tab:				Paths Settings
 * Description:		Show main tab of paths settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Process edit paths form
if( isset( $_POST['action'] ) == 'edit-paths' )
{
	$settings->update_settings( $_POST );
	echo '<p class="success"><code>Settings updated.</code></p>';
}

echo '<form action="'.$tcgurl.'admin/settings.php" method="post">
<input type="hidden" name="action" value="edit-paths" />
<table width="100%" cellspacing="5" cellpadding="5">
<tr>
	<td width="24%" valign="top">
		<b>Absolute Path</b><br />
		<small><i>'.$settings->getDesc( 'file_path_absolute' ).'</i></small>
	</td>
	<td width="76%">
		<input type="text" name="'.$settings->getName( 'file_path_absolute' ).'" value="'.$settings->getValue( 'file_path_absolute' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Layout Header</b><br />
		<small><i>'.$settings->getDesc( 'file_path_header' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'file_path_header' ).'" value="'.$settings->getValue( 'file_path_header' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Layout Footer</b><br />
		<small><i>'.$settings->getDesc( 'file_path_footer' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'file_path_footer' ).'" value="'.$settings->getValue( 'file_path_footer' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Cards URL</b><br />
		<small><i>'.$settings->getDesc( 'file_path_cards' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'file_path_cards' ).'" value="'.$settings->getValue( 'file_path_cards' ).'" size="40" required />
	</td>
</tr>
<tr>
	<td valign="top">
		<b>Images URL</b><br />
		<small><i>'.$settings->getDesc( 'file_path_img' ).'</i></small>
	</td>
	<td>
		<input type="text" name="'.$settings->getName( 'file_path_img' ).'" value="'.$settings->getValue( 'file_path_img' ).'" size="40" required />
	</td>
</tr>
</table>

<input type="submit" class="btn-success" value="Edit File Paths">
</form>';
?>