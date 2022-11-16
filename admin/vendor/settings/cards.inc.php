<?php
/**************************************************
 * Module:			Cards Settings
 * Description:		Show main tab of cards settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Cards Settings</h1>';

// Process edit cards form
if( isset( $_POST['action'] ) == 'edit-cards' )
{
	$settings->update_settings( $_POST );
	echo '<div class="alert alert-success" role="alert"><b>Settings updated.</b></div>';
}

echo '<div class="box" style="width: 700px;">
<form action="'.$tcgurl.'admin/settings.php?mod='.$mod.'" method="post">
<input type="hidden" name="action" value="edit-cards" />
<div class="row">
	<div class="col">
		<b>File Type:</b></divbr />
		<small><i>'.$settings->getDesc( 'cards_file_type' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'cards_file_type' ).'" value="'.$settings->getValue( 'cards_file_type' ).'" placeholder="gif, jpg, jpeg, png" class="form-control" required />
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Card Count/Worth:</b><br />
		<small><i>'.$settings->getDesc( 'cards_total_count' ).' and the iteration of each worth</i></small>
		<div class="input-group">
			<input type="text" name="'.$settings->getName( 'cards_total_count' ).'" value="'.$settings->getValue( 'cards_total_count' ).'" class="form-control" required />
			<input type="text" name="'.$settings->getName( 'cards_total_worth' ).'" value="'.$settings->getValue( 'cards_total_worth' ).'" class="form-control" required />
		</div>
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Card Size:</b><br />
		<small><i>Width and height of the card template in pixels</i></small>
		<div class="input-group">
			<input type="text" name="'.$settings->getName( 'cards_size_width' ).'" value="'.$settings->getValue( 'cards_size_width' ).'" class="form-control" required />
			<input type="text" name="'.$settings->getName( 'cards_size_height' ).'" value="'.$settings->getValue( 'cards_size_height' ).'" class="form-control" required />
		</div>
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Link Button Size:</b><br />
		<small><i>Width and height of the ink button including affiliates in pixels</i></small>
		<div class="input-group">
			<input type="text" name="'.$settings->getName( 'button_size_width' ).'" value="'.$settings->getValue( 'button_size_width' ).'" class="form-control" required />
			<input type="text" name="'.$settings->getName( 'button_size_height' ).'" value="'.$settings->getValue( 'button_size_height' ).'" class="form-control" required />
		</div>
	</div>
</div><br />

<input type="submit" class="btn btn-success" value="Edit cards settings">
</form>
</div>';
?>