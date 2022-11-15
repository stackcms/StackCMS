<?php
/********************************************************************
 * Catalog:				Coupons
 * Description:			Show full page and form processing of coupons
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
if( $general->getItem( 'itm_coupons' ) == "None" || $general->getItem( 'itm_coupons' ) == "" )
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
	$items = $general->getItem( 'itm_coupons' ).''.str_repeat(substr_replace(', '.$shopItem['shop_file'],"",-4), $amount);

	// Display image
	$itemsIMG = str_repeat('<img src="'.$tcgurl.'shoppe/images/'.$shopItem['shop_file'].'" /> ', $amount);
}



// Process form if queries are correct
echo '<h1>Shoppe : '.$shopItem['shop_item'].'</h1>
<p>Thank you for purchasing x'.$amount.' of '.$shopItem['shop_item'].' from our inventory! A total of '.$curCln.' has been deducted from your account.</p>

<center>'.$itemsIMG.'
<p><strong>'.ucfirst($page).' Purchase (x'.$amount.'):</strong> Bought x'.$amount.' of '.$shopItem['shop_item'].' '.$page.'.</p>
</center>';

$logTXT = 'Bought x'.$amount.' of '.$shopItem['shop_item'].' '.$page.'.';

// Insert acquired data
$today = date("Y-m-d", strtotime("now"));
$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Purchases','".$shopItem['shop_item']."','(x$amount)','$logTXT','$today')");
$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_coupons`='$items' WHERE `itm_name`='$player'");
?>