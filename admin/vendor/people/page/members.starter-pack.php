<?php
/***************************************************************************
 * Page:			Starter Pack Generator
 * Description:		Generate the owner's starter pack and starter pack bonus
 */


// Show form for generating a starter pack
echo '<h1>Starter Pack Generator</h1>
<p>Hello '.$tcgowner.'! Since your Stack installation forces you to create your own player account, you weren\'t able to get your own starter pack.<br />
This section will give you the opportunity to get your own starter pack, given that you already have at least 20 active decks.<br />
To continue, simply select your collecting deck and then click the button below:</p>
<center>';
if( isset( $error ) )
{
    foreach( $error as $msg )
    {
        echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div>';
    }
}

if( isset( $success ) )
{
    foreach( $success as $msg )
    {
        echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div>';
    }
}
echo '</center>

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
<input type="hidden" name="username" value="'.$player.'" />';
for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++ )
{
	$sql = $database->get_assoc("SELECT * FROM `tcg_cards`");
	$digit = rand(01,$sql['card_count']);
	if( $digit < 10 )
	{
		$digit = "0$digit";
	}

	else
	{
		$digit = $digit;
	}
	echo "<input type=\"hidden\" name=\"choice$i\" value=\"$digit\" />\n";
}

for( $i=1; $i<=$settings->getValue( 'prize_start_reg' ); $i++ )
{
	echo '<input type="hidden" name="random'.$i.'" value="'; $general->randtype('Active','1'); echo "\" />\n";
}
echo '<div class="row">
	<div class="col-4"><b>Collecting Deck:</b></div>
	<div class="col">
		<select name="collecting" class="form-control">';
$active = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active'");
while( $row = mysqli_fetch_assoc( $active ) )
{
	$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['card_set']."' ORDER BY `set_name`");
	echo '<option value="'.$row['card_filename'].'">'.$set['set_name'].' - '.$row['card_deckname'].'</option>';
}
		echo '</select>
	</div>
</div><br />
<input type="submit" name="submit" class="btn btn-success" value="Generate my Starter Pack!" />
</form>
</div>';



// Process starter pack form
if( isset( $_POST['submit'] ) )
{
	echo '<br /><br /><center>';

    $udeck = $sanitize->for_db($_POST['collecting']);
    $uname = $sanitize->for_db($_POST['username']);
	$date = date("Y-m-d H:i:s", strtotime("now"));

    // Declare empty strings
	$choice = null;
	$rand = null;
	$cW = null;
	$rW = null;

    for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++ )
	{
		$card = "choice$i";
		echo '<img src="'.$tcgcards.''.$udeck;
		echo $_POST[$card];
		echo '.'.$tcgext.'" />';
		$choice .= $udeck.$_POST[$card].", ";
	}

	for( $i=1; $i<=$settings->getValue( 'prize_start_reg' ); $i++ )
	{
		$card = "random$i";
		echo '<img src="'.$tcgcards;
		echo $_POST[$card];
		echo '.'.$tcgext.'" />';
		$rand .= $_POST[$card].", ";
	}
	echo '<br /><br />

	<b>Starter Pack:</b> ';
	$choice = substr_replace($choice,"",-2);
	$rand = substr_replace($rand,"",-2);
	echo $choice.', '.$rand.'</center>';

	$total = $settings->getValue('prize_start_choice') + $settings->getValue( 'prize_start_reg' );

	$update = $database->query("UPDATE `user_list` SET `usr_deck`='$udeck' WHERE `usr_name`='$uname'");

    if( $update === TRUE )
    {
        // Set currencies to zero if value is None or blank
		if( $general->getItem( 'itm_currency' ) == 'None' )
		{
			$currSP = explode(", ", $settings->getValue( 'tcg_currency' ));
			$money = '';
			for( $j=0; $j<count($currSP); $j++ )
			{
				$money .= '0 | ';
			}
			$money = substr_replace($money,"",-2);
		}

        // Process database insertion
        $database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$uname','Service','Starter Pack','$choice, $rand','$date')");
		$database->query("UPDATE `user_items` SET `itm_cards`='$total', `itm_currency`='$money' WHERE `itm_name`='$uname'");
		$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$uname','Gift','Yes','".$settings->getValue('prize_start_bonus')."','".$settings->getValue('prize_start_cur')."','$date')");

		$success[] = "Your stater pack has been generated! Your starter pack bonus has been added to your rewards chest!";
    }
	
	else
	{
		$error[] = "Failed to update your collecting deck and your starter pack was not generated. ".mysqli_error($update);
	}

	echo '</center>';
}
?>