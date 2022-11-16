<?php
/***************************************************
 * Tab:				Others Settings
 * Description:		Show main tab of others settings
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Other Settings</h1>';

// Process edit others form
if( isset( $_POST['action'] ) == 'edit-others' )
{
	$settings->update_settings( $_POST );
	echo '<div class="alert alert-success" role="alert"><b>Settings updated.</b></div>';
}

echo '<form action="'.$tcgurl.'admin/settings.php?mod='.$mod.'" method="post">
<input type="hidden" name="action" value="edit-others" />
<div class="row">
	<div class="col">
		<div class="box">
			<div class="row">
				<div class="col">
					<b>Price Tag:</b><br />
					<small><i>'.$settings->getDesc( 'shop_minimum' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'shop_minimum' ).'" value="'.$settings->getValue( 'shop_minimum' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Blog Pagination:</b><br />
					<small><i>'.$settings->getDesc( 'post_per_page' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'post_per_page' ).'" value="'.$settings->getValue( 'post_per_page' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Comment Pagination:</b><br />
					<small><i>'.$settings->getDesc( 'comment_per_page' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'comment_per_page' ).'" value="'.$settings->getValue( 'comment_per_page' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Item Pagination:</b><br />
					<small><i>'.$settings->getDesc( 'item_per_page' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'item_per_page' ).'" value="'.$settings->getValue( 'item_per_page' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Deck Release:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_decks' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'xtra_decks' ).'" value="'.$settings->getValue( 'xtra_decks' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Deck Donation:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_deck_cards' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'xtra_deck_cards' ).'" value="'.$settings->getValue( 'xtra_deck_cards' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Granted Wishes:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_wishes' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'xtra_wishes' ).'" value="'.$settings->getValue( 'xtra_wishes' ).'" class="form-control" required />
				</div>
			</div>
		</div><!-- box -->
	</div><!-- col -->

	<div class="col">
		<div class="box">
			<div class="row">
				<div class="col">
					<b>Melting Pot:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_mpot' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'xtra_mpot' ).'" value="'.$settings->getValue( 'xtra_mpot' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Card Claim:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_cclaim' ).'</i></small>
					<input type="text" name="'.$settings->getName( 'xtra_cclaim' ).'" value="'.$settings->getValue( 'xtra_cclaim' ).'" class="form-control" required />
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Chat Box:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_chatbox' ).'</i></small> &nbsp;&nbsp;&nbsp; ';
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
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<b>Member Feature:</b><br />
					<small><i>'.$settings->getDesc( 'xtra_motm' ).'</i></small> &nbsp;&nbsp;&nbsp; ';
					if( $settings->getValue( 'xtra_motm' ) == "1" )
					{
						echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="'.$settings->getValue( 'xtra_motm' ).'" checked /> Enable ';
						echo ' &nbsp;&nbsp;&nbsp; ';
						echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="0" /> Disable';
					}
			
					else
					{
						echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="'.$settings->getValue( 'xtra_motm' ).'" /> Enable ';
						echo ' &nbsp;&nbsp;&nbsp; ';
						echo '<input type="radio" name="'.$settings->getName( 'xtra_motm' ).'" value="0" checked /> Disable';
					}
					echo '<br /><br />

					<div class="row">
						<div class="col">
							'.$settings->getDesc( 'xtra_motm_scope' ).'
							<select name="'.$settings->getName( 'xtra_motm_scope' ).'" class="form-control" required>
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
					</div><!-- row -->
				</div>
			</div>';

			// Check for member decks table
			$db_name = $db_database;
			$sql = $database->num_rows("SHOW TABLES FROM $db_name LIKE 'tcg_cards_user'");
			if( $sql == 0 ) {}
			else
			{
				echo '<hr>

				<div class="row">
					<div class="col">
						<b>Member Decks:</b><br />
						<small><i>'.$settings->getDesc( 'xtra_mdeck' ).'</i></small> &nbsp;&nbsp;&nbsp; ';
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
						echo '<br /><br />

						<small><i>Maximum card count and image break for your member decks</i></small>
						<div class="input-group">
							<input type="text" name="'.$settings->getName(' xtra_mdeck_count' ).'" value="'.$settings->getValue( 'xtra_mdeck_count' ).'" class="form-control" />
							<input type="text" name="'.$settings->getName(' xtra_mdeck_break' ).'" value="'.$settings->getValue( 'xtra_mdeck_break' ).'" class="form-control" />
						</div><br />

						<small><i>Width and height of your member deck cards in pixels</i></small>
						<div class="input-group">
							<input type="text" name="'.$settings->getName(' xtra_mdeck_width' ).'" value="'.$settings->getValue( 'xtra_mdeck_width' ).'" class="form-control" />
							<input type="text" name="'.$settings->getName(' xtra_mdeck_height' ).'" value="'.$settings->getValue( 'xtra_mdeck_height' ).'" class="form-control" />
						</div><br />
					</div>
				</div>';
			}
		echo '</div><!-- box -->
	</div><!-- col -->
</div><!-- row -->
<br />

<input type="submit" class="btn btn-success" value="Edit other settings">
</form>';
?>