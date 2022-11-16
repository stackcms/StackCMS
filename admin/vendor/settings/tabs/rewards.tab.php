<?php
/****************************************************
 * Tab:				Rewards Settings
 * Description:		Show main tab of rewards settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


// Process edit rewards form
if( isset( $_POST['action'] ) == 'edit-rewards' )
{
	$settings->update_settings( $_POST );
	echo '<p class="success"><code>Settings updated.</code></p>';
}

echo '<p>If a field is not applicable for this section, just put <code>0</code>.</p>
<form action="'.$tcgurl.'admin/settings.php" method="post">
<input type="hidden" name="action" value="edit-rewards" />
<div class="row">
    <div class="col-2"><b>Starter Pack</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_start_choice' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'prize_start_choice' ).'" value="'.$settings->getValue( 'prize_start_choice' ).'" class="form-control" required />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_start_reg' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'prize_start_reg' ).'" value="'.$settings->getValue( 'prize_start_reg' ).'" class="form-control"  required />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_start_bonus' ).'</i></small><br />
        <input type="text" name="'.$settings->getName( 'prize_start_bonus' ).'" value="'.$settings->getValue( 'prize_start_bonus' ).'" class="form-control" required />
    </div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_start_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_start_cur' ).'" value="'.$settings->getValue( 'prize_start_cur' ).'" class="form-control" required />
    </div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Deck Masteries</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_master_choice' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_master_choice' ).'" value="'.$settings->getValue( 'prize_master_choice' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_master_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_master_reg' ).'" value="'.$settings->getValue( 'prize_master_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_master_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_master_cur' ).'" value="'.$settings->getValue( 'prize_master_cur' ).'" class="form-control" required />
	</div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Level Ups</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_level_choice' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_level_choice' ).'" value="'.$settings->getValue( 'prize_level_choice' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_level_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_level_reg' ).'" value="'.$settings->getValue( 'prize_level_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_level_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_level_cur' ).'" value="'.$settings->getValue( 'prize_level_cur' ).'" class="form-control" required />
	</div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Trading Rewards</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_trade_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_trade_reg' ).'" value="'.$settings->getValue( 'prize_trade_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_trade_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_trade_cur' ).'" value="'.$settings->getValue( 'prize_trade_cur' ).'" class="form-control" required />
	</div>
	<div class="col-2"><b>Special Masteries</b><br /><small><i>EC/MC masteries if you have this for your TCG.</i></small></div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_special_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_special_reg' ).'" value="'.$settings->getValue( 'prize_special_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_special_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_special_cur' ).'" value="'.$settings->getValue( 'prize_special_cur' ).'" class="form-control" required />
	</div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Daily Login Bonus</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_daily_en' ).'</i></small><br />';
		if( $settings->getValue( 'prize_daily_en' ) == "1" )
		{
			echo '<input type="radio" name="'.$settings->getName( 'prize_daily_en' ).'" value="'.$settings->getValue( 'prize_daily_en' ).'" checked /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'prize_daily_en' ).'" value="0" /> Disable ';
		}

		else
		{
			echo '<input type="radio" name="'.$settings->getName( 'prize_daily_en' ).'" value="1" /> Enable ';
			echo ' &nbsp;&nbsp;&nbsp; ';
			echo '<input type="radio" name="'.$settings->getName( 'prize_daily_en' ).'" value="'.$settings->getValue( 'prize_daily_en' ).'" checked /> Disable ';
		}
	echo '</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_daily_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_daily_reg' ).'" value="'.$settings->getValue( 'prize_daily_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_daily_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_daily_cur' ).'" value="'.$settings->getValue( 'prize_daily_cur' ).'" class="form-control" required />
	</div>
</div>

<hr>

<div class="row">
    <div class="col-2"><b>Deck Donation</b></div>
    <div class="col">
        <small><i>'.$settings->getDesc( 'prize_deck_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_deck_reg' ).'" value="'.$settings->getValue( 'prize_deck_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_deck_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_deck_cur' ).'" value="'.$settings->getValue( 'prize_deck_cur' ).'" class="form-control" required />
	</div>
	<div class="col-2"><b>Deck Making</b></div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_deckmaker_reg' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_deckmaker_reg' ).'" value="'.$settings->getValue( 'prize_deckmaker_reg' ).'" class="form-control" required />
	</div>
	<div class="col">
        <small><i>'.$settings->getDesc( 'prize_deckmaker_cur' ).'</i></small><br />
		<input type="text" name="'.$settings->getName( 'prize_deckmaker_cur' ).'" value="'.$settings->getValue( 'prize_deckmaker_cur' ).'" class="form-control" required />
	</div>
</div>

<input type="submit" class="btn-success" value="Edit Rewards">
</form>';
?>