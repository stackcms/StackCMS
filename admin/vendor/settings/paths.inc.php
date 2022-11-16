<?php
/**************************************************
 * Module:			Paths Settings
 * Description:		Show main tab of paths settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>File Path Settings</h1>';

// Process edit paths form
if( isset( $_POST['action'] ) == 'edit-paths' )
{
	$settings->update_settings( $_POST );
	echo '<div class="alert alert-success" role="alert"><b>Settings updated.</b></div>';
}

echo '<div class="box" style="width: 700px;">
<form action="'.$tcgurl.'admin/settings.php?mod='.$mod.'" method="post">
<input type="hidden" name="action" value="edit-paths" />
<div class="row">
	<div class="col">
		<b>Absolute Path:</b><br />
		<small><i>'.$settings->getDesc( 'file_path_absolute' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'file_path_absolute' ).'" value="'.$settings->getValue( 'file_path_absolute' ).'" class="form-control" required />
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Layout Header:</b><br />
		<small><i>'.$settings->getDesc( 'file_path_header' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'file_path_header' ).'" value="'.$settings->getValue( 'file_path_header' ).'" class="form-control" required />
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Layout Footer:</b><br />
		<small><i>'.$settings->getDesc( 'file_path_footer' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'file_path_footer' ).'" value="'.$settings->getValue( 'file_path_footer' ).'" class="form-control" required />
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Cards URL:</b><br />
		<small><i>'.$settings->getDesc( 'file_path_cards' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'file_path_cards' ).'" value="'.$settings->getValue( 'file_path_cards' ).'" class="form-control" required />
	</div>
</div><br />

<div class="row">
	<div class="col">
		<b>Images URL:</b><br />
		<small><i>'.$settings->getDesc( 'file_path_img' ).'</i></small>
		<input type="text" name="'.$settings->getName( 'file_path_img' ).'" value="'.$settings->getValue( 'file_path_img' ).'" class="form-control" required />
	</div>
</div><br />

<input type="submit" class="btn btn-success" value="Edit file path settings">
</form>
</div>';
?>