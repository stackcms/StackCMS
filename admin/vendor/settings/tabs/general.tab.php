<?php
/****************************************************
 * Tab:				General Settings
 * Description:		Show main tab of general settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Process edit general settings form
if( isset( $_POST['action'] ) == 'edit-general' )
{
	$settings->update_settings( $_POST );
	echo '<p class="success"><code>Settings updated.</code></p>';
}

echo '<form action="'.$tcgurl.'admin/settings.php" method="post">
<input type="hidden" name="action" value="edit-general" />
<div class="row">
    <div class="col-1"><b>TCG Name</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_name' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_name' ).'" value="'.$settings->getValue( 'tcg_name' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>TCG Owner</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_owner' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_owner' ).'" value="'.$settings->getValue( 'tcg_owner' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>TCG Email</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_email' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_email' ).'" value="'.$settings->getValue( 'tcg_email' ).'" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-1"><b>TCG Website</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_url' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_url' ).'" value="'.$settings->getValue( 'tcg_url' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>TCG Discord</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_discord' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_discord' ).'" value="'.$settings->getValue( 'tcg_discord' ).'" placeholder="e.g. JSYdZ3WF" class="form-control" />
    </div>
    <div class="col-1"><b>TCG Twitter</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_twitter' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_twitter' ).'" value="'.$settings->getValue( 'tcg_twitter' ).'" placeholder="e.g. shizentcg" class="form-control" />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-1"><b>TCG Registration</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_registration' ).'</i></small><br />';
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
    echo '</div>
    <div class="col-1"><b>TCG Status</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_status' ).'</i></small><br />
        <select name="'.$settings->getName( 'tcg_status' ).'" class="form-control">
			<option value="'.$settings->getValue( 'tcg_status' ).'">'.$settings->getValue( 'tcg_status' ).'</option>
			<option value="">-----</option>
			<option value="Open">Open</option>
			<option value="Prejoin">Prejoin</option>
			<option value="Upcoming">Upcoming</option>
			<option value="Hiatus">Hiatus</option>
			<option value="Inactive">Inactive</option>
			<option value="Closed">Closed</option>
		</select>
	</div>
	<div class="col-1"><b>TCG Currencies</b></div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'tcg_currency' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'tcg_currency' ).'" value="'.$settings->getValue( 'tcg_currency' ).'" placeholder="e.g. vial.png, gold.png" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-1"><b>TCG Local Timezone</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'tcg_timezone' ).'</i></small><br />
        <select name="'.$settings->getName( 'tcg_timezone' ).'" class="form-control" required>
		<option value="'.$settings->getValue( 'tcg_timezone' ).'">'.str_replace("_", " ", $settings->getValue( 'tcg_timezone' )).'</option>';
		$t = $database->query("SELECT * FROM `tcg_timezones` ORDER BY `tzone_region` ASC");
		while( $row = mysqli_fetch_assoc( $t ) )
		{
			$tz = str_replace("_", " ", $row['tzone_region']);
			echo '<option value="'.$row['tzone_region'].'">'.$tz."</option>\n";
		}
		echo '</select>
	</div>
	<div class="col-1"><b>TCG Weekly Update</b></div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'update_scope' ).'</i></small><br />
        <select name="'.$settings->getName( 'update_scope' ).'" class="form-control" required>
			<option value="'.$settings->getValue( 'update_scope' ).'">'.$settings->getValue( 'update_scope' ).'</option>
			<option value="Sunday">Sunday</option>
			<option value="Monday">Monday</option>
			<option value="Tuesday">Tuesday</option>
			<option value="Wednesday">Wednesday</option>
			<option value="Thursday">Thursday</option>
			<option value="Friday">Friday</option>
			<option value="Saturday">Saturday</option>
		</select>
	</div>
	<div class="col-1"><b>TCG Placeholder Title</b></div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'update_title' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'update_title' ).'" value="'.$settings->getValue( 'update_title' ).'" placeholder="e.g. Placeholder Only" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>TCG Placeholder Text</b></div>
    <div class="col-2">
        <small><i>Set the status for your automatic weekly blog post:</i></small><br />';
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
	echo '</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'update_text' ).'</i></small><br />
        <textarea name="'.$settings->getName( 'update_text' ).'" rows="4" class="form-control">'.$settings->getValue( 'update_text' ).'</textarea>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Admin Panel Skin</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'admin_skin' ).'</i></small><br />';
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
    echo '</div>
</div>

<input type="submit" class="btn-success" value="Edit Settings">
</form>';
?>