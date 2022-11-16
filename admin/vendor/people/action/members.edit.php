<?php
/*************************************************
 * Module:			Edit User
 * Description:		Show page for editing a member
 */


// Process edit a user form
if( isset( $_POST['update'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$name = $sanitize->for_db($_POST['username']);
	$email = $sanitize->for_db($_POST['email']);
	$url = $sanitize->for_db($_POST['url']);
	$refer = $sanitize->for_db($_POST['refer']);
	$status = $sanitize->for_db($_POST['status']);
	$prejoiner = $sanitize->for_db($_POST['prejoiner']);
	$level = $sanitize->for_db($_POST['level']);
	$collecting = $sanitize->for_db($_POST['collecting']);
	$memcard = $sanitize->for_db($_POST['memcard']);
	$mastered = $sanitize->for_db($_POST['mastered']);
	$mcard = $sanitize->for_db($_POST['mcard']);
	$ecard = $sanitize->for_db($_POST['ecard']);
	$role = $sanitize->for_db($_POST['role']);
	$cards = $sanitize->for_db($_POST['cards']);
	$money = $sanitize->for_db($_POST['money']);
	$mstone = $sanitize->for_db($_POST['milestone']);
	$trdp = $sanitize->for_db($_POST['trd_points']);
	$trdr = $sanitize->for_db($_POST['trd_redeems']);
	$trdt = $sanitize->for_db($_POST['trd_turnins']);
	$discord = $sanitize->for_db($_POST['discord']);
	$twitter = $sanitize->for_db($_POST['twitter']);
	$auto = $sanitize->for_db($_POST['auto-trade']);
	$rand = $sanitize->for_db($_POST['rand-trade']);
	$birthday = $_POST['date'];
	$about = $_POST['about'];
	$about = nl2br($about);
	$about = str_replace("'","\'",$about);

	function trim_value(&$value) { $value = trim($value); }
	$mcard = explode(', ',$mcard);
	$ecard = explode(', ',$ecard);
	$mstone = explode(', ',$mstone);

	array_walk($mcard, 'trim_value');
	array_walk($ecard, 'trim_value');
	array_walk($mstone, 'trim_value');

	usort($mcard, 'strnatcasecmp');
	sort($ecard); sort($mstone);

	$mcard = implode(', ',$mcard);
	$ecard = implode(', ',$ecard);
	$mstone = implode(', ',$mstone);

	$update = $database->query("UPDATE `user_list` SET `usr_name`='$name', `usr_email`='$email', `usr_url`='$url', `usr_refer`='$refer', `usr_bday`='$birthday', `usr_status`='$status', `usr_pre`='$prejoiner', `usr_level`='$level', `usr_deck`='$collecting', `usr_mcard`='$memcard', `usr_bio`='$about', `usr_role`='$role', `usr_discord`='$discord', `usr_twitter`='$twitter', `usr_rand_trade`='$rand', `usr_auto_trade`='$auto' WHERE `usr_id`='$id'");

	if( !$update  )
	{
		$error[] = "Sorry, there was an error and the member was not updated. ".mysqli_error($update);
	}

	else
	{
		$database->query("UPDATE `user_items` SET `itm_masteries`='$mastered', `itm_milestone`='$mstone', `itm_mcard`='$mcard', `itm_ecard`='$ecard', `itm_cards`='$cards', `itm_currency`='$money' WHERE `itm_id`='$id'");

		$database->query("UPDATE `user_trades_rec` SET `trd_points`='$trdp', `trd_redeems`='$trdr', `trd_turnins`='$trdt' WHERE `trd_name`='$name'");

		$success[] = "The member has been successfully updated!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a member form
else
{
	$gal = $database->get_assoc("SELECT * FROM `user_items` WHERE `itm_id`='$id'");
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
	$trd = $database->get_assoc("SELECT * FROM `user_trades_rec` WHERE `trd_name`='".$row['usr_name']."'");

	echo '<h1>Edit a Member</h1>
	<p>Use this form to edit a member in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod=members&action=add">add</a> form to add new members.</p>

	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<input type="hidden" name="about" value="'.$row['usr_bio'].'" />
	
	<div class="row">
		<div class="col">
			<div class="box">
				<h4>Personal Info</h4>
				<div class="row">
					<div class="col">';
						$field->Name('manage');
					echo '</div>
					
					<div class="col">';
						$field->Birthday('manage');
					echo '</div>
				</div>';
				$field->Email('manage');
				$field->Website('manage');
				echo '<div class="row">
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-discord" role="image" title="Discord" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="discord" value="'.$row['usr_discord'].'" class="form-control" />
						</div>
					</div>

					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-twitter" role="image" title="Twitter" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="twitter" value="'.$row['usr_twitter'].'" class="form-control" />
						</div>
					</div>
				</div>

				<b>Short Biography:</b>
				<textarea name="about" rows="2" class="form-control">'.$row['usr_bio'].'</textarea>
			</div><!-- box -->
		</div><!-- col -->

		<div class="col">
			<div class="box">
				<h4>Player Info</h4>';
				$field->Status('manage');

				echo '<b>Prejoiner?</b> &nbsp; ';
				if( $row['usr_pre'] == "Beta" )
				{
                    echo '<input type="radio" name="prejoiner" value="Beta" checked /> Beta &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="prejoiner" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
                    <input type="radio" name="prejoiner" value="No" /> No<br />';
                }
                
                elseif( $row['usr_pre'] == "Yes" )
                {
                    echo '<input type="radio" name="prejoiner" value="Beta" /> Beta &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="prejoiner" value="Yes" checked /> Yes &nbsp;&nbsp;&nbsp; 
                    <input type="radio" name="prejoiner" value="No" /> No<br />';
				}
				
				else
				{
                    echo '<input type="radio" name="prejoiner" value="Beta" /> Beta &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="prejoiner" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
                    <input type="radio" name="prejoiner" value="No" checked /> No<br />';
				}

				echo '<b>With member card?</b> &nbsp; ';
				if( $row['usr_mcard'] == "Yes" ) 
				{
                    echo '<input type="radio" name="memcard" value="Yes" checked /> Yes &nbsp;&nbsp;&nbsp; 
                    <input type="radio" name="memcard" value="No" /> No<br /><br />';
				}
				else
				{
                    echo '<input type="radio" name="memcard" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
                    <input type="radio" name="memcard" value="No" checked /> No<br /><br />';
				}

				echo '<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="bi-bar-chart-line" role="image" title="Level" data-toggle="tooltip" data-placement="bottom"></i></span>
					</div>
					<select name="level" class="form-control">';
					$l = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='".$row['usr_level']."'");
					echo '<option value="'.$row['usr_level'].'">Current: Level '.$l['lvl_id'].' - '.$l['lvl_name'].'</option>';
					$l = $database->query("SELECT * FROM `tcg_levels`");
					while( $lvl = mysqli_fetch_assoc( $l ) )
					{
						echo '<option value="'.$lvl['lvl_id'].'">'.$lvl['lvl_id'].' - '.$lvl['lvl_name']."</option>\n";
					}
					echo '</select>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="bi-person-lines-fill" role="image" title="Member Role" data-toggle="tooltip" data-placement="bottom"></i></span>
					</div>
					<select name="role" class="form-control">';
					$role = $database->get_assoc("SELECT * FROM `user_role` WHERE `role_id`='".$row['usr_role']."'");
					echo '<option value="'.$row['usr_role'].'">Current: '.$role['role_title'].'</option>';
					$r = $database->query("SELECT * FROM `user_role`");
					while( $role = mysqli_fetch_assoc( $r ) )
					{
						echo '<option value="'.$role['role_id'].'">'.$role['role_title'].'</option>';
					}
					echo '</select>
				</div>';

				$field->Referral('manage');
			echo '</div><!-- box -->
		</div><!-- col -->

		<div class="col">
			<div class="box">
				<h4>TCG Info</h4>';
				$field->Collecting('manage');

				echo '<div class="row">
					<div class="col-5">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-images" role="image" title="Card Worth" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="cards" value="'.$gal['itm_cards'].'" class="form-control" />
						</div>
					</div>

					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-coin" role="image" title="Currencies" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="money" value="'.$gal['itm_currency'].'" class="form-control" />
						</div>
					</div>
				</div>

				<hr>
				<h4>Trading Info</h4>
				<div class="row">
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-star" role="image" title="Trading Points" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="trd_points" value="'.$trd['trd_points'].'" class="form-control" />
						</div>
					</div>

					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-box2-heart" role="image" title="Redeemed Points" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="trd_redeems" value="'.$trd['trd_redeems'].'" class="form-control" />
						</div>
					</div>

					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="bi-arrow-through-heart" role="image" title="Turned-in Points" data-toggle="tooltip" data-placement="bottom"></i></span>
							</div>
							<input type="text" name="trd_turnins" value="'.$trd['trd_turnins'].'" class="form-control" />
						</div>
					</div>
				</div>

				<div class="row">
                    <div class="col">
                        Accept random trades?<br />';
                        if( $row['usr_rand_trade'] == "1" )
                        {
                            echo '<input type="radio" name="rand-trade" value="1" checked /> Yes &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rand-trade" value="0" /> No';
                        }

                        else
                        {
                            echo '<input type="radio" name="rand-trade" value="1" /> Yes &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rand-trade" value="0" checked /> No';
                        }
                    echo '</div>

                    <div class="col">
                        Allow trades through?<br />';
                        if( $row['usr_auto_trade'] == "1" )
                        {
                            echo '<input type="radio" name="auto-trade" value="1" checked /> Yes &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="auto-trade" value="0" /> No';
                        }

                        else
                        {
                            echo '<input type="radio" name="auto-trade" value="1" /> Yes &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="auto-trade" value="0" checked /> No';
                        }
                    echo '</div>
                </div>
			</div><!-- box -->
		</div><!-- col -->
	</div><!-- row -->

	<div class="row">
		<div class="col">
			<div class="box">
				<h4>Collections</h4>
				<div class="row">
					<div class="col">
						<b>Mastered Decks:</b>
						<textarea name="mastered" rows="5" class="form-control">'.$gal['itm_masteries'].'</textarea><br />

						<b>Achievements:</b>
						<textarea name="milestone" rows="5" class="form-control">'.$gal['itm_milestone'].'</textarea>
					</div><!-- col -->

					<div class="col">
						<b>Member Cards:</b>
						<textarea name="mcard" rows="5" class="form-control">'.$gal['itm_mcard'].'</textarea><br />

						<b>Event Cards:</b>
						<textarea name="ecard" rows="5" class="form-control">'.$gal['itm_ecard'].'</textarea>
					</div><!-- col -->
				</div><!-- row -->
			</div><!-- box -->

			<div style="margin-top:20px; text-align:right;">
				<input type="submit" name="update" class="btn btn-success" value="Edit Member" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div><!-- col -->
	</div><!-- row -->
	</form>';
}
?>