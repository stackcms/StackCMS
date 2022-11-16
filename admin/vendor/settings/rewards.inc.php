<?php
/****************************************************
 * Module:			Rewards Settings
 * Description:		Show main tab of rewards settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Rewards Settings</h1>';

// Process edit rewards form
if( isset( $_POST['action'] ) == 'edit-rewards' )
{
	$settings->update_settings( $_POST );
	echo '<div class="alert alert-success" role="alert"><b>Settings updated.</b></div>';
}

echo '<p>If a field is not applicable for this section, just put <code>0</code>.</p>

<form action="'.$tcgurl.'admin/settings.php?mod='.$mod.'" method="post">
<input type="hidden" name="action" value="edit-rewards" />
<div class="row">
	<div class="col">
		<div class="box">
			<div class="row">
				<div class="col">
					<b>Starter Pack:</b><br />
					<small><i>Choice and random cards for new members starter pack</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_start_choice' ).'" value="'.$settings->getValue( 'prize_start_choice' ).'" class="form-control" placeholder="choice cards" required />
						<input type="text" name="'.$settings->getName( 'prize_start_reg' ).'" value="'.$settings->getValue( 'prize_start_reg' ).'" class="form-control" placeholder="random cards" required />
					</div><br />
					<small><i>Random cards and currencies for starter pack bonus</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_start_bonus' ).'" value="'.$settings->getValue( 'prize_start_bonus' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_start_cur' ).'" value="'.$settings->getValue( 'prize_start_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Deck Masteries:</b><br />
					<small><i>Choice and random cards plus amount of currencies for mastering a deck</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_master_choice' ).'" value="'.$settings->getValue( 'prize_master_choice' ).'" class="form-control" placeholder="choice cards" required />
						<input type="text" name="'.$settings->getName( 'prize_master_reg' ).'" value="'.$settings->getValue( 'prize_master_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_master_cur' ).'" value="'.$settings->getValue( 'prize_master_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Level Ups:</b><br />
					<small><i>Choice and random cards plus amount of currencies for leveling up</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_level_choice' ).'" value="'.$settings->getValue( 'prize_level_choice' ).'" class="form-control" placeholder="choice cards" required />
						<input type="text" name="'.$settings->getName( 'prize_level_reg' ).'" value="'.$settings->getValue( 'prize_level_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_level_cur' ).'" value="'.$settings->getValue( 'prize_level_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Trading Rewards:</b><br />
					<small><i>Random cards and amount of currencies for trading</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_trade_reg' ).'" value="'.$settings->getValue( 'prize_trade_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_trade_cur' ).'" value="'.$settings->getValue( 'prize_trade_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>
		</div><!-- box -->
	</div><!-- col -->

	<div class="col">
		<div class="box">
			<div class="row">
				<div class="col">
					<b>Special Masteries:</b><br />
					<small><i>Event Card/Member Card masteries if you have this for your TCG.</i></small><br />
					<small><i>Random cards and amount of currencies for special masteries</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_special_reg' ).'" value="'.$settings->getValue( 'prize_special_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_special_cur' ).'" value="'.$settings->getValue( 'prize_special_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Daily Login Bonus:</b><br />
					<small><i>Random cards and amount of currencies for daily login</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_daily_reg' ).'" value="'.$settings->getValue( 'prize_daily_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_daily_cur' ).'" value="'.$settings->getValue( 'prize_daily_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div><br />

					<small><i>'.$settings->getDesc( 'prize_daily_en' ).':</i></small> &nbsp;&nbsp;&nbsp; ';
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
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Deck Donation:</b><br />
					<small><i>Random cards and amount of currencies to reward per donated deck</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_deck_reg' ).'" value="'.$settings->getValue( 'prize_deck_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_deck_cur' ).'" value="'.$settings->getValue( 'prize_deck_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Deck Making:</b><br />
					<small><i>Random cards and amount of currencies to reward for deck making</i></small>
					<div class="input-group">
						<input type="text" name="'.$settings->getName( 'prize_deckmaker_reg' ).'" value="'.$settings->getValue( 'prize_deckmaker_reg' ).'" class="form-control" placeholder="random cards" required />
						<input type="text" name="'.$settings->getName( 'prize_deckmaker_cur' ).'" value="'.$settings->getValue( 'prize_deckmaker_cur' ).'" class="form-control" placeholder="amount of currencies" required />
					</div>
				</div>
			</div>
		</div><!-- box -->
	</div><!-- col -->
</div><!-- row -->

<br />
<input type="submit" class="btn btn-success" value="Edit rewards settings">
</form>';
?>