<?php
/***************************************************
 * Tab:				Others Settings
 * Description:		Show main tab of others settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Process edit others form
if( isset( $_POST['action'] ) == 'edit-others' )
{
	$settings->update_settings( $_POST );
	echo '<p class="success"><code>Settings updated.</code></p>';
}

echo '<form action="'.$tcgurl.'admin/settings.php" method="post">
<input type="hidden" name="action" value="edit-others" />
<div class="row">
    <div class="col-1"><b>Price Tag</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'shop_minimum' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'shop_minimum' ).'" value="'.$settings->getValue( 'shop_minimum' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>Paginations</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'post_per_page' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'post_per_page' ).'" value="'.$settings->getValue( 'post_per_page' ).'" class="form-control" required />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'comment_per_page' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'comment_per_page' ).'" value="'.$settings->getValue( 'comment_per_page' ).'" class="form-control" required />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'item_per_page' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'item_per_page' ).'" value="'.$settings->getValue( 'item_per_page' ).'" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-1"><b>Level Tier</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'level_tier' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'level_tier' ).'" value="'.$settings->getValue( 'level_tier' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>Granted Wishes</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_wishes' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'xtra_wishes' ).'" value="'.$settings->getValue( 'xtra_wishes' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>Deck Release</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_decks' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'xtra_decks' ).'" value="'.$settings->getValue( 'xtra_decks' ).'" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-1"><b>Deck Donation</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_deck_cards' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'xtra_deck_cards' ).'" value="'.$settings->getValue( 'xtra_deck_cards' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>Melting Pot</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_mpot' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'xtra_mpot' ).'" value="'.$settings->getValue( 'xtra_mpot' ).'" class="form-control" required />
    </div>
    <div class="col-1"><b>Card Claim</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_cclaim' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'xtra_cclaim' ).'" value="'.$settings->getValue( 'xtra_cclaim' ).'" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Chat Box</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_chatbox' ).'</i></small><br />';
        if( $settings->getValue( 'xtra_chatbox' ) == "1" )
        {
            echo '<input type="radio" name="'.$settings->getName( 'xtra_chatbox' ).'" value="'.$settings->getValue( 'xtra_chatbox' ).'" checked /> Enable ';
            echo ' &nbsp;&nbsp;&nbsp; ';
            echo '<input type="radio" name="'.$settings->getName( 'xtra_chatbox' ).'" value="0" /> Disable';
        }

        else
        {
            echo '<input type="radio" name="'.$settings->getName( 'xtra_chatbox' ).'" value="1" /> Enable ';
            echo ' &nbsp;&nbsp;&nbsp; ';
            echo '<input type="radio" name="'.$settings->getName( 'xtra_chatbox' ).'" value="'.$settings->getValue( 'xtra_chatbox' ).'" checked /> Disable';
        }
    echo '</div>
    <div class="col-2"><b>Member Winner</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_motm' ).'</i></small><br />';
        if( $settings->getValue( 'xtra_motm' ) == "1" )
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="'.$settings->getValue( 'xtra_motm' ).'" checked /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="0" /> Disable';
		}

		else
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="1" /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="'.$settings->getValue( 'xtra_motm' ).'" checked /> Disable';
		}
	echo '</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'xtra_motm_scope' ).'</i></small><br />
        <select name="'.$settings->getName( 'xtra_motm_scope' ).'" required>
			<option value="'.$settings->getValue(' xtra_motm_scope' ).'">Member of the '.$settings->getValue( 'xtra_motm_scope' ).'</option>
			<option value="Week">Member of the Week</option>
			<option value="Month">Member of the Month</option>
		</select>
	</div>
	<div class="col">
        Would you like to open the voting phase?<br />';
        if( $settings->getValue( 'xtra_motm_vote' ) == "1" )
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm_vote' ).'" value="'.$settings->getValue( 'xtra_motm_vote' ).'" checked /> Yes ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm_vote' ).'" value="0" /> No';
		}

		else
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm_vote' ).'" value="'.$settings->getValue( 'xtra_motm_vote' ).'" /> Yes ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_motm_vote' ).'" value="0" checked /> No';
		}
	echo '</div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Member Decks</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_mdeck' ).'</i></small><br />';
        if( $settings->getValue( 'xtra_mdeck' ) == "1" )
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_mdeck' ).'" value="'.$settings->getValue( 'xtra_mdeck' ).'" checked /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_mdeck' ).'" value="0" /> Disable';
		}

		else
		{
			echo '<input type="radio" name="'.$settings->getName( 'xtra_mdeck' ).'" value="1" /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'xtra_mdeck' ).'" value="'.$settings->getValue( 'xtra_mdeck' ).'" checked /> Disable';
		}
	echo '</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'xtra_mdeck_count' ).'</i></small><br />
        <input type="text" name="'.$settings->getName(' xtra_mdeck_count' ).'" value="'.$settings->getValue( 'xtra_mdeck_count' ).'" class="form-control" />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'xtra_mdeck_break' ).'</i></small><br />
		<input type="text" name="'.$settings->getName(' xtra_mdeck_break' ).'" value="'.$settings->getValue( 'xtra_mdeck_break' ).'" class="form-control" />
	</div>
</div>

<input type="submit" class="btn-success" value="Edit Other Settings">
</form>';
?>