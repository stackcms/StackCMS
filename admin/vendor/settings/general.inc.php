<?php
/****************************************************
 * Module:			General Settings
 * Description:		Show main tab of general settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>General Settings</h1>';

// Process edit general settings form
if( isset( $_POST['action'] ) == 'edit-general' )
{
	$settings->update_settings( $_POST );
	echo '<div class="alert alert-success" role="alert"><b>Settings updated.</b></div>';
}

echo '<div class="box">
	<form action="'.$tcgurl.'admin/settings.php?mod='.$mod.'" method="post">
	<input type="hidden" name="action" value="edit-general" />
	<div class="row">
		<div class="col">
			<b>TCG Name:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_name' ).'" value="'.$settings->getValue( 'tcg_name' ).'" class="form-control" required />
			<small><i>'.$settings->getDesc( 'tcg_name' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Owner:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_owner' ).'" value="'.$settings->getValue( 'tcg_owner' ).'" class="form-control" required />
			<small><i>'.$settings->getDesc( 'tcg_owner' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Email:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_email' ).'" value="'.$settings->getValue( 'tcg_email' ).'" class="form-control" required />
			<small><i>'.$settings->getDesc( 'tcg_email' ).'</i></small>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col">
			<b>TCG Website:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_url' ).'" value="'.$settings->getValue( 'tcg_url' ).'" class="form-control" required />
			<small><i>'.$settings->getDesc( 'tcg_url' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Discord:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_discord' ).'" value="'.$settings->getValue( 'tcg_discord' ).'" placeholder="e.g. JSYdZ3WF" class="form-control" />
			<small><i>'.$settings->getDesc( 'tcg_discord' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Twitter:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_twitter' ).'" value="'.$settings->getValue( 'tcg_twitter' ).'" placeholder="e.g. shizentcg" class="form-control" />
			<small><i>'.$settings->getDesc( 'tcg_twitter' ).'</i></small>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col">
			<b>TCG Registration:</b><br />';
			if( $settings->getValue( 'tcg_registration' ) == "1" )
			{
				echo '<input type="radio" name="'.$settings->getName( 'tcg_registration' ).'" value="'.$settings->getValue( 'tcg_registration' ).'" checked /> Open ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'tcg_registration' ).'" value="0" /> Close ';
			}

			else
			{
				echo '<input type="radio" name="'.$settings->getName( 'tcg_registration' ).'" value="1" /> Open ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'tcg_registration' ).'" value="'.$settings->getValue( 'tcg_registration' ).'" checked /> Close ';
			}
			echo '<br /><small><i>'.$settings->getDesc( 'tcg_registration' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Status:</b><br />
			<select name="'.$settings->getName( 'tcg_status' ).'" class="form-control">
				<option value="'.$settings->getValue( 'tcg_status' ).'">'.$settings->getValue( 'tcg_status' ).'</option>
				<option value="">----- Select a status -----</option>
				<option value="Open">Open</option>
				<option value="Prejoin">Prejoin</option>
				<option value="Upcoming">Upcoming</option>
				<option value="Hiatus">Hiatus</option>
				<option value="Inactive">Inactive</option>
				<option value="Closed">Closed</option>
			</select>
			<small><i>'.$settings->getDesc( 'tcg_status' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Currencies:</b><br />
			<input type="text" name="'.$settings->getName( 'tcg_currency' ).'" value="'.$settings->getValue( 'tcg_currency' ).'" placeholder="e.g. vial.png, gold.png" class="form-control" required />
			<small><i>'.$settings->getDesc( 'tcg_currency' ).'</i></small>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col">
			<b>TCG Local Timezone:</b><br />
			<select name="'.$settings->getName( 'tcg_timezone' ).'" class="form-control" required>
			<option value="'.$settings->getValue( 'tcg_timezone' ).'">'.str_replace("_", " ", $settings->getValue( 'tcg_timezone' )).'</option>
			<option>----- Select a timezone -----</option>';
			$t = $database->query("SELECT * FROM `tcg_timezones` ORDER BY `tzone_region` ASC");
			while( $row = mysqli_fetch_assoc( $t ) )
			{
				$tz = str_replace("_", " ", $row['tzone_region']);
				echo '<option value="'.$row['tzone_region'].'">'.$tz."</option>\n";
			}
			echo '</select>
			<small><i>'.$settings->getDesc( 'tcg_timezone' ).'</i></small>
		</div>

		<div class="col">
			<b>TCG Weekly Update:</b><br />
			<select name="'.$settings->getName( 'update_scope' ).'" class="form-control" required>
				<option value="'.$settings->getValue( 'update_scope' ).'">'.$settings->getValue( 'update_scope' ).'</option>
				<option>----- Select a day -----</option>
				<option value="Sunday">Sunday</option>
				<option value="Monday">Monday</option>
				<option value="Tuesday">Tuesday</option>
				<option value="Wednesday">Wednesday</option>
				<option value="Thursday">Thursday</option>
				<option value="Friday">Friday</option>
				<option value="Saturday">Saturday</option>
			</select>
			<small><i>'.$settings->getDesc( 'update_scope' ).'</i></small>
		</div>

		<div class="col">
			<b>Admin Panel Skin:</b><br />';
			if( $settings->getValue( 'admin_skin' ) == "monochrome" )
			{
				echo '<input type="radio" name="'.$settings->getName( 'admin_skin' ).'" value="'.$settings->getValue( 'admin_skin' ).'" checked /> Monochrome ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'admin_skin' ).'" value="coffee" /> Coffee ';
			}

			else
			{
				echo '<input type="radio" name="'.$settings->getName( 'admin_skin' ).'" value="monochrome" /> Monochrome ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'admin_skin' ).'" value="'.$settings->getValue( 'admin_skin' ).'" checked /> Coffee ';
			}
			echo '<br /><small><i>'.$settings->getDesc( 'admin_skin' ).'</i></small>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-4">
			<b>TCG Placeholder Title:</b><br />
			<input type="text" name="'.$settings->getName( 'update_title' ).'" value="'.$settings->getValue( 'update_title' ).'" placeholder="e.g. Placeholder Only" class="form-control" required />
			<small><i>'.$settings->getDesc( 'update_title' ).'</i></small><br /><br />';
			if( $settings->getValue( 'update_status' ) == "Published" )
			{
				echo '<input type="radio" name="'.$settings->getName( 'update_status' ).'" value="'.$settings->getValue( 'update_status' ).'" checked /> Published ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'update_status' ).'" value="Draft" /> Draft ';
			}

			else
			{
				echo '<input type="radio" name="'.$settings->getName( 'update_status' ).'" value="Published" /> Published ';
				echo ' &nbsp;&nbsp;&nbsp; ';
				echo '<input type="radio" name="'.$settings->getName( 'update_status' ).'" value="'.$settings->getValue( 'update_status' ).'" checked /> Draft ';
			}
			echo '<br /><small><i>Set the status for your automatic weekly blog post.</i></small>
		</div>

		<div class="col">
			<b>TCG Placeholder Text:</b><br />
			<textarea name="'.$settings->getName( 'update_text' ).'" rows="4" class="form-control">'.$settings->getValue( 'update_text' ).'</textarea>
			<small><i>'.$settings->getDesc( 'update_text' ).'</i></small>
		</div>
	</div><br />

	<input type="submit" class="btn btn-success" value="Edit general settings">
	</form>
</div><!-- box -->';
?>