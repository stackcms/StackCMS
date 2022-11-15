<?php
/***********************************************************************
 * Catalog:				Card Packs
 * Description:			Show full page and form processing of card packs
 */


$id = $_POST['shopID'];
$amount = $sanitize->for_db($_POST['amount']);

$shopItem = $database->get_assoc("SELECT * FROM `shop_items` WHERE `shop_id`='$id'");
$shopValue = explode(" | ", $shopItem['shop_currency']);
$curNames = explode(", ", $settings->getValue('tcg_currency'));
$curItem = explode(" | ", $general->getItem('itm_currency'));
$cards = $shopItem['shop_amount'] * $amount;

// Declare empty strings
$curCln2 = '';
$curSpc = '';
$curCln = '';
$total = '';
$diff = '';
$orgc = '';

for( $j=0; $j<count($shopValue); $j++ )
{
	$cn = substr_replace($curNames[$j],"",-4);
	// Pluralize the currencies if more than 1
	if( $shopValue[$j] > 1 ) {
		$var = substr($cn, -1);
		if( $var == "y" ) {
			$vtn = substr_replace($cn,"ies",-1);
		}
		else if( $var == "o" ) {
			$vtn = substr_replace($cn,"oes",-1);
		}
		else { $vtn = $cn.'s'; }
	}
	else { $vtn = $cn; }

	$orgc .= $curItem[$j];
	$curItem[$j] -= $shopValue[$j] * $amount;
	$diff .= $shopValue[$j] * $amount;

	// Check if currency is 0 or not
	if( $shopValue[$j] != 0 ) {
		$curCln .= '<b>'.$shopValue[$j] * $amount.'</b> '.$vtn.', ';
		$curCln2 .= $shopValue[$j] * $amount.' '.$vtn.', ';
		$curSpc .= ucfirst($vtn);
	}
	else {}

	// Check if there are enough currencies
	if ( $orgc < $diff ) {
		echo '<h1>Shoppe : Halt!</h1><p>Sorry, but it seems like you don\'t have the enough currency on your account to purchase this pack. Play more games to earn more currencies before making a purchase!</p>';
		exit;
	}
}
$total = implode(" | ", $curItem);
$curCln = substr_replace($curCln,"",-2);
$curCln2 = substr_replace($curCln2,"",-2);

// Check if `itm_$column` is None
if( $general->getItem( 'itm_'.$page.'' ) == "None" )
{
	if( $amount < 2 ) {
		// Log text
		$items = str_repeat(substr_replace($shopItem['shop_file'],"",-4), $amount);
	}
	else
	{
		// Log text
		$items = str_repeat(substr_replace(', '.$shopItem['shop_file'],"",-4), $amount);
		$items = ltrim($items, ', ');
	}

	// Display image
	$itemsIMG = str_repeat('<img src="'.$tcgurl.'shoppe/images/'.$shopItem['shop_file'].'" /> ', $amount);
}
else
{
	// Log Text
	$items = str_repeat(substr_replace(', '.$shopItem['shop_file'],"",-4), $amount);

	// Display image
	$itemsIMG = str_repeat('<img src="'.$tcgurl.'shoppe/images/'.$shopItem['shop_file'].'" /> ', $amount);
}



// Show CHOICE pack form
if( strpos( $shopItem['shop_item'], 'choice' ) || strpos( $shopItem['shop_item'], 'Choice' ) )
{
	// Show form for choice list
	if( !isset( $_POST['exchange'] ) )
	{
		// Show form for choice cards
		echo '<h1>Shoppe : '.$shopItem['shop_item'].'</h1>
		<p>Kindly use the form below to get your choice cards! A total of '.$curCln.' will be deducted from your account upon checkout.</p>

		<form method="post" action="'.$tcgurl.'shoppe.php?page='.$page.'&id='.$shopItem['shop_id'].'&action=sent">
		<input type="hidden" name="shopID" value="'.$shopItem['shop_id'].'" />
		<input type="hidden" name="amount" value="'.$amount.'" />
		<table width="100%" class="table table-sliced table-striped">
		<tbody>';
			for( $i=1; $i<=$amount; $i++ )
			{
				echo '<tr>
				<td width="25%" align="right"><b>Choice '.$i.':</b></td>
				<td width="75%">
					<select name="choice'.$i.'" style="width:80%;">';
					$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' ORDER BY `card_filename` ASC");
					while ( $card = mysqli_fetch_assoc( $sql ) )
					{
						echo '<option value="'.$card['card_filename'].'">'.$card['card_deckname'].' ('.$card['card_filename'].')</option>';
					}
					echo '</select><input type="text" name="num'.$i.'" placeholder="00" size="1" />
				</td>
				</tr>';
			}
		echo '</tbody>
		</table>
		<input type="submit" name="exchange" class="btn-success" value="Checkout" /> 
		<input type="reset" name="reset" class="btn-danger" value="Reset" />
		</form>';
	}

	// Process choice exchange form
	else
	{
		echo '<h1>Shoppe : Exchanged</h1>
		<p>Thank you for exchanging your '.$curCln.' for '.$amount.' cards! You may now take your choice cards below.</p>

		<center>';
		$choices = null; $cW = null;
		for( $i=1; $i<=$amount; $i++ )
		{
			$card = "choice$i";
			$card2 = "num$i";
			echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.png" />';
			$choices .= $_POST[$card].$_POST[$card2].", ";

			$cX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='".$_POST[$card]."'");
			$cW .= $cX['card_worth'].', ';
		}

		// Calculate card worth for choice and random
		$cW = substr_replace($cW,"",-2);
		$cArr = explode(", ", $cW);
		$cSum = 0;
		foreach( $cArr as $val ) { $cSum += $val; }
		$choices = substr_replace($choices,"",-2);
		echo '<p><strong>'.$curSpc.' Exchange (x'.$amount.'):</strong> -'.$curCln2.' for '.$choices.'</p>
		</center>';

		// Insert acquired data
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Exchanges','$curSpc Exchange','(x$amount)','-$curCln2 for $choices','$today')");
		$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$cSum' WHERE `itm_name`='$player'");
	}
}


// Show CHANCE pack form
else if( strpos( $shopItem['shop_item'], 'chance' ) || strpos( $shopItem['shop_item'], 'Chance' ) )
{
	// Process form if queries are correct
	echo '<h1>Shoppe : '.$shopItem['shop_item'].'</h1>
	<p>Thank you for purchasing x'.$amount.' of '.$shopItem['shop_item'].' from our inventory! A total of '.$curCln.' has been deducted from your account.</p>

	<center>';
	$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Weekly'");
	$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_released`='".$range['gup_date']."'");

	// Declare empty strings
	$rW = '';
	$rewards = '';

	$min = 1; $max = mysqli_num_rows($sql);
	for( $i=0; $i<$cards; $i++ )
	{
		mysqli_data_seek($sql,rand($min,$max)-1);
		$row = mysqli_fetch_assoc($sql);
		$digits = rand(01,$row['card_count']);
		if($digits < 10)
		{
			$digit = "0$digits";
		}

		else
		{
			$digit = $digits;
		}
		$card = $row['card_filename'].''.$digit;
		$card2 = $row['card_filename'];
		echo '<img src="'.$tcgcards.''.$card.'.png" border="0" /> ';

		$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
		$rW .= $rX['card_worth'].', ';
		$rewards .= $card.", ";
	}
	// Calculate card worth for choice and random
	$rW = substr_replace($rW,"",-2);
	$rArr = explode(", ", $rW);

	$rSum = 0;
	foreach( $rArr as $val ) { $rSum += $val; }

	$rewards = substr_replace($rewards,"",-2);
	echo '<p><strong>'.$shopItem['shop_item'].' (x'.$amount.'):</strong> '.$rewards.'</p>
	</center>';

	// Insert acquired data
	$today = date("Y-m-d", strtotime("now"));
	$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Purchases','".$shopItem['shop_item']."','(x$amount)','$rewards','$today')");
	$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$player'");
}


// Show RANDOM pack form
else
{
	// Process form if queries are correct
	echo '<h1>Shoppe : '.$shopItem['shop_item'].'</h1>
	<p>Thank you for purchasing x'.$amount.' of '.$shopItem['shop_item'].' from our inventory! A total of '.$curCln.' has been deducted from your account.</p>

	<center>';
	$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1'");

	// Declare empty strings
	$rW = '';
	$rewards = '';

	$min = 1; $max = mysqli_num_rows($sql);
	for( $i=0; $i<$cards; $i++ )
	{
		mysqli_data_seek($sql,rand($min,$max)-1);
		$row = mysqli_fetch_assoc($sql);
		$digits = rand(01,$row['card_count']);
		if($digits < 10)
		{
			$digit = "0$digits";
		}

		else
		{
			$digit = $digits;
		}
		$card = $row['card_filename'].''.$digit;
		$card2 = $row['card_filename'];
		echo '<img src="'.$tcgcards.''.$card.'.png" border="0" /> ';

		$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
		$rW .= $rX['card_worth'].', ';
		$rewards .= $card.", ";
	}
	// Calculate card worth for choice and random
	$rW = substr_replace($rW,"",-2);
	$rArr = explode(", ", $rW);

	$rSum = 0;
	foreach( $rArr as $val ) { $rSum += $val; }

	$rewards = substr_replace($rewards,"",-2);
	echo '<p><strong>'.$shopItem['shop_item'].' (x'.$amount.'):</strong> '.$rewards.'</p>
	</center>';

	// Insert acquired data
	$today = date("Y-m-d", strtotime("now"));
	$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Purchases','".$shopItem['shop_item']."','(x$amount)','$rewards','$today')");
	$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$player'");
}
?>