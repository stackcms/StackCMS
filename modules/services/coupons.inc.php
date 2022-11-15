<?php
/************************************************
 * Module:			Coupons Exchange
 * Description:		Process user coupons exchange
 */

# NOTES TO READ!!!
# This coupons exchange form is coded for choice and randoms only. If you have any other
# variations such as a random/choice coupon for a specific deck type, you will have to
# make a work-around for it.
#
# You can study the pattern of random and choice forms and work your way from there.
#
# Please make sure that your coupons must contain the "random" or "choice" words from the
# shop items! Otherwise, you will have to tweak the line strpos( $item['shop_item'], 'choice')
# or strpos( $item['shop_item'], 'random') to your own coupon name.


// Get active cards
$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_worth`='1' AND `card_status`='Active'") or die("Unable to select from database.");

// Process exchange form
if( $act == "exchanged" )
{
    $name = $sanitize->for_db($_POST['name']);
    $coupon = $sanitize->for_db($_POST['coupon']);
    $amount = $sanitize->for_db($_POST['amount']);

    // Get shop item values according to IDs
    $item = $database->get_assoc("SELECT * FROM `shop_items` WHERE `shop_id`='$coupon'");

    // Process form for random coupons
    if( strpos( $item['shop_item'], 'random' ) !== FALSE || strpos( $item['shop_item'], 'Random' ) !== FALSE )
    {
        echo '<h1>Coupon Exchange : '.$item['shop_item'].'</h1>
        <p>Thank you for exchanging x'.$amount.' of your '.$item['shop_item'].' coupon! Kindly take your random cards below and do not forget to log it. Your used coupons will be removed from your items inventory as well.</p>

        <center>';
        $min=1;
        $max = mysqli_num_rows($result);

        // Declare empty strings
		$rewards = null;
		$rW = null;

        $totVal = $amount * $item['shop_amount'];

        for( $i=1; $i<=$totVal; $i++ )
		{
			mysqli_data_seek($result,rand($min,$max)-1);
			$row = mysqli_fetch_assoc($result);
			$digits = rand(01,$row['card_count']);
			if( $digits < 10 )
			{
				$digit = "0$digits";
			}
			else
			{
				$digit = $digits;
			}
			$card = $row['card_filename'].''.$digit;
			$card2 = $row['card_filename'];
			echo '<img src="'.$tcgcards.''.$card.'.'.$tcgext.'" border="0" /> ';

			$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
			$rW .= $rX['card_worth'].', ';
			$rewards .= $card.", ";
		}
        $rewards = substr_replace($rewards,"",-2);

        // Calculate card worth for choice and random
		$rW = substr_replace($rW,"",-2);
		$rArr = explode(", ", $rW);

		$rSum = 0;
		foreach( $rArr as $val ) { $rSum += $val; }
		$tCards = $rSum;

        echo '<p><strong>Coupon Exchange (x'.$amount.' of '.$item['shop_item'].'):</strong> '.$rewards.'</p>';
        $logTXT = 'Exchanged x'.$amount.' of '.$item['shop_item'].' for '.$rewards.'.';

        // Process coupons algorithms
        $couponCln = substr_replace($item['shop_file'],"",-4).', ';
        $coupons = str_repeat($couponCln, $amount);
        $trimmed = substr_replace($coupons,"",-2);
        $couponsOwned = explode(', ', $general->getItem('itm_coupons'));
        $couponsUsed = explode(', ', $trimmed);

        foreach( $couponsUsed as $used )
        {
            foreach( $couponsOwned as $i=>$owned )
            {
                if( $used == $owned )
                {
                    unset($couponsOwned[$i]);
                    break;
                }
            }
        }
        $couponsParsed = implode(', ', $couponsOwned);

        // Insert and update acquired data if correct
        $today = date("Y-m-d", strtotime("now"));
        $database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$name','Exchanges','Coupons Exchange','(x$amount ".$item['shop_item'].")','$logTXT','$today')");
        $database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$tCards', `itm_coupons`='$couponsParsed' WHERE `itm_name`='$name'");
    }

    // Process form for choice coupons
    else
    {
        if( $stat == "sent" )
        {
            $shopID = $sanitize->for_db($_POST['shopID']);
            $amount2 = $sanitize->for_db($_POST['amount2']);
            $totVal = $sanitize->for_db($_POST['totVal']);
            $user = $sanitize->for_db($_POST['player']);

            // Get shop item values according to IDs
            $item2 = $database->get_assoc("SELECT * FROM `shop_items` WHERE `shop_id`='$shopID'");

            echo '<h1>Coupon Exchange: '.$item2['shop_item'].' (x'.$amount2.')</h1>
            <p>Get your exchanged cards for '.$amount2.' of your '.$item2['shop_item'].' coupon. Don\'t forget to remove the coupon(s) used for this exchange from your trade post!</p>
            <center>';

            // Declare empty strings
            $choices = null;
            $cW = null;

            for( $i=1; $i<=$totVal; $i++ )
            {
                $card = "choice$i";
                $card2 = "num$i";
                echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.'.$tcgext.'" /> ';
                $choices .= $_POST[$card].$_POST[$card2].", ";

                $cX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='".$_POST[$card]."'");
                $cW .= $cX['card_worth'].', ';
            }
            $choices = substr_replace($choices,"",-2);

            // Calculate card worth for choice
            $cW = substr_replace($cW,"",-2);
            $cArr = explode(", ", $cW);

            $cSum = 0;
            foreach( $cArr as $val ) { $cSum += $val; }
            $tCards = $cSum;

            echo '<p><strong>Coupon Exchange (x'.$amount2.' of '.$item2['shop_item'].'):</strong> '.$choices.'</p>';
            $logTXT = 'Exchanged x'.$amount2.' of '.$item2['shop_item'].' for '.$choices.'.';

            // Process coupons algorithms
            $couponCln = substr_replace($item2['shop_file'],"",-4).', ';
            $coupons = str_repeat($couponCln, $amount2);
            $trimmed = substr_replace($coupons,"",-2);
            $couponsOwned = explode(', ', $general->getItem('itm_coupons'));
            $couponsUsed = explode(', ', $trimmed);

            foreach( $couponsUsed as $used )
            {
                foreach( $couponsOwned as $i=>$owned )
                {
                    if( $used == $owned )
                    {
                        unset($couponsOwned[$i]);
                        break;
                    }
                }
            }
            $couponsParsed = implode(', ', $couponsOwned);

            // Insert and update acquired data if correct
            $today = date("Y-m-d", strtotime("now"));
            $database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$user','Exchanges','Coupons Exchange','(x$amount2)','$logTXT','$today')");
            $database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$tCards', `itm_coupons`='$couponsParsed' WHERE `itm_name`='$user'");
        }

        else
        {
            // Show form for choice cards
            echo '<h1>Coupon Exchange : '.$item['shop_item'].'</h1>
            <p>Kindly use the form below to get your choice cards! Your used coupons will be removed from your gallery upon exchange.</p>

            <form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=exchanged&stat=sent">
            <input type="hidden" name="shopID" value="'.$item['shop_id'].'" />
            <input type="hidden" name="amount2" value="'.$amount.'" />
            <input type="hidden" name="totVal" value="'.$amount * $item['shop_amount'].'" />
            <input type="hidden" name="player" value="'.$player.'" />
            <table width="100%" class="table table-sliced table-striped">
            <tbody>';
                $totalCards = $amount * $item['shop_amount'];
                for( $x=1; $x<=$totalCards; $x++ )
                {
                    echo '<tr>
                    <td width="25%" align="right"><b>Choice '.$x.':</b></td>
                    <td width="75%">
                        <select name="choice'.$x.'" style="width:80%;">';
                        $query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1' ORDER BY `card_filename` ASC");
                        while ( $row = mysqli_fetch_assoc( $query ) )
                        {
                            echo '<option value="'.$row['card_filename'].'">'.$row['card_deckname'].' ('.$row['card_filename'].')</option>';
                        }
                        echo '</select><input type="text" name="num'.$x.'" placeholder="00" size="1" />
                    </td>
                    </tr>';
                }
            echo '</tbody>
            </table>
            <input type="submit" name="exchange" class="btn-success" value="Checkout" /> 
            <input type="reset" name="reset" class="btn-danger" value="Reset" />
            </form>';
        }
    }
}


// Show exchange form
else
{
    echo '<h1>Coupon Exchange</h1>
    <p>Kindly fill up the form correctly below. Please keep in mind that you can only exchange 1 coupon per week.</p>
    <center>
    <form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=exchanged">
    <input type="hidden" name="name" value="'.$player.'">
    <table width="100%" border="0" cellspacing="0">
    <tr>
        <td width="49%" valign="top">
            You can put your coupon exchange rules in this block.
        </td>

        <td width="2%"></td>

        <td width="49%" valign="top">
            <table width="100%" cellspacing="0" class="table table-border table-striped">
            <tr>
                <td width="15%" valign="middle"><b>Coupon Type:</b></td>
                <td width="35%">
                    <select name="coupon" style="width:95%;">';
                    $getCoupons = $database->query("SELECT * FROM `shop_items` WHERE `shop_catalog`='2' ORDER BY `shop_item` ASC");
                    while( $row = mysqli_fetch_assoc( $getCoupons ) )
                    {
                        echo '<option value="'.$row['shop_id'].'">'.$row['shop_item'].'</option>';
                    }
                    echo '</select>
                </td>
            </tr>
            <tr>
                <td><b>How many of this?</b></td>
                <td><input type="number" name="amount" placeholder="amount of coupon to exchange" min="1" max="2" style="width:90%;" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" class="btn-success" value="Exchange Coupon" /> 
                    <input type="reset" name="reset" class="btn-danger" value="Reset" />
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </table>';
}
?>