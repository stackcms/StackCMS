<?php
/*
 * Class library for general functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			General
 * Description:		Functions to use for TCG contents
 */
class General
{
	function randtype( $stat, $worth )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$stat = $sanitize->for_db($stat);
		$worth = $sanitize->for_db($worth);

		$result = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_status`='$stat' AND `card_worth`='$worth' ORDER BY RAND() LIMIT 1");
		$name = $result['card_filename'];
		$digits = rand(01,$result['card_count']);

		if($digits < 10)
		{
			$digit = "0$digits";
		}

		else
		{
			$digit = $digits;
		}

		$card = "$name$digit";
		echo $card;
	}


	function getItem( $item )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$item = $sanitize->for_db($item);

		$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
		$player = $row['usr_name'];

		$query = $database->query("SELECT * FROM `user_items` WHERE `itm_name`='$player'");
		$row = mysqli_fetch_assoc($query);
		return $row[$item];
	}


	function gamePrize( $set, $game, $sub, $random, $choice, $currency )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$general = new General;
		$tcgimg = $settings->getValue( 'file_path_img' );
		$tcgcards = $settings->getValue( 'file_path_cards' );

		$set = $sanitize->for_db($set);
		$sub = $sanitize->for_db($sub);
		$game = $sanitize->for_db($game);
		$random = $sanitize->for_db($random);
		$choice = $sanitize->for_db($choice);
		$currency = $sanitize->for_db($currency);
		$tcgext = $settings->getValue('cards_file_type');

		$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
		$player = $row['usr_name'];

		$gameData = $database->get_assoc("SELECT * FROM `tcg_games` WHERE `game_title`='$game'");
		$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1'");

		// Explode all bombs
		$curValue = explode(' | ', $currency);
		$curItem = explode(' | ', $general->getItem( 'itm_currency' ));
		$curName = explode(', ', $settings->getValue( 'tcg_currency' ));

		// Declare empty strings
		$choices = '';
		$rewards = '';
		$newSet = '';
		$curLog = '';
		$curImg = '';
		$curCln = '';
		$cW = '';
		$rW = '';

		for( $i = 0; $i < count($curValue); $i++ )
		{
			// Pluralize the currencies if more than 1
			$cn = substr_replace($curName[$i],"",-4);
			if( $curValue[$i] > 1 )
			{
				$var = substr($cn, -1);
				if( $var == "y" )
				{
					$vtn = substr_replace($cn,"ies",-1);
				}
				else if( $var == "o" )
				{
					$vtn = substr_replace($cn,"oes",-1);
				}
				else
				{
					$vtn = $cn.'s';
				}
			}
			else
			{
				$vtn = $cn;
			}

			if( $curValue[$i] != 0 )
			{
				$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i]);
				$curImg .= '<img src="'.$tcgimg.''.$curName[$i].'"> [x'.$curValue[$i].']';
				$curCln .= ', +'.$curValue[$i].' '.$vtn;
				$curItem[$i] += $curValue[$i];
			}
			else {}
		}
		$total = implode(" | ", $curItem);

		// Process choice cards option if applicable
		if( $choice != 0 )
		{
			if( isset( $_POST['submit'] ) )
			{
				$cards = $sanitize->for_db($_POST['cards']);
				$currency = $sanitize->for_db($_POST['currency']);

				// Count choice cards if applicable
				for( $i=1; $i<=$choice; $i++ )
				{
					$ccard = "choice$i";
					$ccard2 = "num$i";
					echo '<img src="'.$tcgcards.''.$_POST[$ccard].''.$_POST[$ccard2].'.'.$tcgext.'" /> ';
					$choices .= $_POST[$ccard].$_POST[$ccard2].", ";

					$cX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='".$_POST[$ccard]."'");
					$cW .= $cX['card_worth'].', ';
				}

				// Generate rewards
				$min = 1; $max = mysqli_num_rows($query);
				for( $i = 0; $i < $random; $i++)
				{
					mysqli_data_seek($query,rand($min,$max)-1);
					$row = mysqli_fetch_assoc($query);
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
				$choices = substr_replace($choices,"",-2);
				$cW = substr_replace($cW,"",-2);
				$rW = substr_replace($rW,"",-2);
				$cArr = explode(", ", $cW);
				$rArr = explode(", ", $rW);

				$cSum = 0; $rSum = 0;
				foreach( $cArr as $val ) { $cSum += $val; }
				foreach( $rArr as $val ) { $rSum += $val; }
				$tCards = $cSum + $rSum;

				// Rewards log without 0 values but with choice
				if( $choice != 0 )
				{
					echo $curImg;
					echo '<p><strong>'.$game; if (!empty($sub)) { echo ' '.$sub.''; } echo ':</strong> '.$choices.', '.$rewards.''.$curCln;
					$newSet = $choices.', '.$rewards.''.$curLog;
				}

				// Insert acquired data
				$today = date("Y-m-d", strtotime("now"));
				$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$tCards' WHERE `itm_name`='$player'");
				$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','$set','$game','$sub','$newSet','$today')");
			}

			else
			{
				// Show form for choice cards
				$cards = isset( $cards ) ? $cards : null;
				echo '<p>Use this form to get your choice cards. You will be redirected to the page with your other set of rewards upon submission.</p>
				<form method="post" action="'.$_SERVER['HTTP_REFERER'].'&go=prize">
				<input type="hidden" name="cards" value="'.$cards.'" />
				<input type="hidden" name="currency" value="'.$currency.'" />';
				for( $i = 1; $i <= $choice; $i++ )
				{
					echo '<select name="choice'.$i.'" style="width: 83%;">
					<option value="">---</option>';
					$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
					while( $row2 = mysqli_fetch_assoc( $query ) )
					{
						$filename = stripslashes($row2['card_filename']);
						echo '<option value="'.$filename.'">'.$row2['card_deckname'].' ('.$filename.')</option>';
					}
					echo '</select> 
					<input type="text" name="num'.$i.'" placeholder="00" size="1" maxlength="2" /><br />';
				}
					echo '<br />
					<input type="submit" name="submit" class="btn-success" value="Claim Rewards!" /> 
					<input type="reset" name="reset" class="btn-cancel" value="Reset" />
				</form>';
			}
		}

		// Process rewards without choice cards
		else
		{
			// Generate rewards
			$min = 1; $max = mysqli_num_rows($query);
			for( $i = 0; $i < $random; $i++ )
			{
				mysqli_data_seek($query,rand($min,$max)-1);
				$row = mysqli_fetch_assoc($query);
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
			$rW = substr_replace($rW,"",-2);
			$rArr = explode(", ", $rW);

			$rSum = 0;
			foreach( $rArr as $val ) { $rSum += $val; }

			// Rewards log with 0 values
			echo $curImg;
			if( $choice == 0 )
			{
				echo '<p><strong>'.$game; if (!empty($sub)) { echo ' '.$sub.''; } echo ':</strong> '.$rewards.''.$curCln;
				$newSet = $rewards.''.$curLog;
			}

			else
			{
				echo '<p><strong>'.$game; if (!empty($sub)) { echo ' '.$sub.''; } echo ':</strong> '.$choices.', '.$rewards.''.$curCln;
				$newSet = $choices.', '.$rewards.''.$curLog;
			}
			echo "</p></center>";

			// Insert acquired data
			$today = date("Y-m-d", strtotime("now"));
			$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$player'");
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','$set','$game','$sub','$newSet','$today')");
		}
	}


	function displayRewards( $game )
	{
		$database = new Database;
        $settings = new Settings;
        $sanitize = new Sanitize;
        $games = new Games;
        $game = $sanitize->for_db($game);
		$tcgimg = $settings->getValue( 'file_path_img' );
		$tcgcards = $settings->getValue( 'file_path_cards' );
		$tcgext = $settings->getValue( 'cards_file_type' );

        $gameSet = $games->gameSet($game);
        $gameTitle = $games->gameTitle($game);
        $gameSub = $games->gameSub($game);

        // Check logged player
        $login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
        $row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
        $player = $row['usr_name'];

        $range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='$gameSet'");
        $subChk = $database->get_assoc("SELECT `log_subtitle` FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='$gameTitle' AND `log_date` >= '".$range['gup_date']."'");
        if( empty( $gameSub ) )
		{
            $logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='$gameTitle' AND `log_date` >= '".$range['gup_date']."'");
        }

		else
		{
            $logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='$gameTitle' AND `log_subtitle`='".$subChk['log_subtitle']."' AND `log_date` >= '".$range['gup_date']."'");
        }

        $rewards = explode(', ',$logChk['log_rewards']);
        $curName = explode(', ', $settings->getValue('tcg_currency'));

        // Put currency names in an array
        foreach( $curName as $c )
		{
            $currencyNames[] = substr($c, 0, -4);
        }

        // Declare empty strings
        $imgString = ''; 
        $txtString = ''; 
        $curString = ''; 
        $curImgString = '';
        
        // Display images for each reward if NOT a currency
        foreach( $rewards as $r )
		{
            if( !in_array($r, $currencyNames) )
			{
                $imgString .= '<img src="'.$tcgcards.''.$r.'.'.$tcgext.'" title="'.$r.'"> ';
                $txtString .= $r.', ';
            }
        }

        // Get count of how many of each reward is present
        $values = array_count_values($rewards);

        // Fisplay currencies that are in rewards and quantity only if exists in rewards
        foreach( $currencyNames as $cn )
		{
            if( array_key_exists($cn, $values) )
			{
                $curImgString .= '<img src="'.$tcgimg.''.$cn.'.'.$tcgext.'" title="'.$cn.'"> [x'.$values[$cn].'] ';
                            
                // Pluralize the currencies if more than 1
                if( $values[$cn] > 1 )
				{
                    $var = substr($cn, -1);
                    if( $var == "y" ) {
                        $vtn = substr_replace($cn,"ies",-1);
                    } else if( $var == "o" ) {
                        $vtn = substr_replace($cn,"oes",-1);
                    }
                    else { $vtn = $cn.'s'; }
                }

				else { $vtn = $cn; }

                $curString .= '+'.$values[$cn].' '.$vtn.', ';
            }
        }

        //display images and text of rewards
        $curString = substr_replace($curString,"",-2);
        echo $imgString.' '.$curImgString;
        echo '<br /><b>'.$logChk['log_title'];
        if( $logChk['log_subtitle'] == "" ) { echo ':</b> '; }
        else { echo ' '.$logChk['log_subtitle'].':</b> '; }
        echo $txtString.' '.$curString;
	}

	function member( $stat )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$stat = $sanitize->for_db($stat);
		$tcgurl = $settings->getValue( 'tcg_url' );
		$tcgcards = $settings->getValue( 'file_path_cards' );
		$tcgext = $settings->getValue( 'cards_file_type' );

        if( $stat == "Retired" )
        {
            $ret = $database->query("SELECT * FROM `user_list_quit`");
            $countRET = mysqli_num_rows($ret);

            if( $countRET === 0 )
            {
                echo "<p><center>There are currently no members with this status.</center></p>";
            }
                
            else
            {
                while( $r = mysqli_fetch_assoc( $ret ) ) {
                    echo '<div class="memList2">
                        <table width="300">
                        <tr>
                            <td width="135" align="center"><img src="'.$tcgcards.''.$r['usr_mcard'].'.'.$tcgext.'" /></td>
                            <td width="175"><b>Member:</b> '.$r['usr_name'].'<br /><b>Joined:</b> '.date("F d, Y", strtotime($r['usr_joined'])).'<br /><b>Retired:</b> '.date("F d, Y", strtotime($r['usr_quit'])).'</td>
                        </tr></table>
                    </div>';
                }
            }
        }
            
        else
        {
            $get = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='$stat' ORDER BY `usr_name` ASC");
            $count = mysqli_num_rows($get);

            if( $count === 0 )
            {
                echo "<p><center>There are currently no members with this status.</center></p>";
            }

            else
            {
                echo '<center>';
                while( $row = mysqli_fetch_assoc( $get ) )
                {
                    echo '<div class="memList">
                    <table width="190">
                    <tr><td colspan="2" class="memName"><a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'">'.$row['usr_name'].'</a></td></tr>
                    <tr><td width="135" align="center">';

                    if( $row['usr_mcard'] == "Yes" )
                    {
                        echo '<a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'"><img src="'.$settings->getValue('file_path_cards').'mc-'.$row['usr_name'].'.'.$settings->getValue('cards_file_type').'" /></a>';
                    }

                    else
                    {
                        echo '<a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'"><img src="'.$settings->getValue('file_path_cards').'filler.'.$settings->getValue('cards_file_type').'" /></a>';
                    }

                    echo '<br />
                    <div class="socIcon">';
                    if( $row['usr_pre'] == "Beta" )
                    {
                        echo '<li><font color="#e81a33"><span class="fas fa-star" aria-hidden="true" title="Beta Tester"></span></font></li>';
                    }

                    else if( $row['usr_pre'] == "Yes" )
                    {
                        echo '<li><font color="gold"><span class="fas fa-star" aria-hidden="true" title="Prejoiner"></span></font></li>';
                    }

                    else
                    {
                        echo '<li><font color="#636363"><span class="fas fa-star" aria-hidden="true" title="Non-Prejoiner"></span></font></li>';
                    }

                    echo '<li><a href="'.$row['usr_url'].'" target="_blank" title="Visit Trade Post"><span class="fas fa-home" aria-hidden="true"></span></a></li>';

                    if( $row['usr_rand_trade'] == "0" )
                    {
                        echo '<li><font color="#d9a3a9"><span class="fas fa-bell-slash" aria-hidden="true" title="I don\'t accept random trades!"></span></font></li>';
                    }

                    else
                    {
                        echo '<li><font color="#a4c8de"><span class="fas fa-bell" aria-hidden="true" title="Send me any random trades, please!?"></span></font></li>';
                    }

                    if( $row['usr_auto_trade'] == "0" )
                    {
                        echo '<li><font color="#d9a3a9"><span class="fas fa-toggle-off" aria-hidden="true" title="Please don\'t put your trades through!"></span></font></li>';
                    }

                    else
                    {
                        echo '<li><font color="#a4c8de"><span class="fas fa-toggle-on" aria-hidden="true" title="Feel free to put all your trades through!"></span></font></li>';
                    }
                    echo '</div>
                    </td></tr>
                    </table>
                    </div>';
                }
                echo '</center>';
            }
		}
		echo "<br /><br />\n\n";
	}

	function cardSearch( $table, $prefix, $stat )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$prefix = $sanitize->for_db($prefix);
		$table = $sanitize->for_db($table);
		$stat = $sanitize->for_db($stat);
		$tcgurl = $settings->getValue( 'tcg_url' );

		// BEGIN SEARCH FORM
		echo '<center><form method="post" action="">
		<input type="text" name="term" placeholder="Search released decks..." size="30" /> <input type="submit" name="search" class="btn-primary" value="Search!" />
		</form><br />';

		// DO SEARCH HERE
		if( isset( $_REQUEST['term'] ) )
		{
			$term = $sanitize->for_db($_POST['term']);
			$sql = $database->query("SELECT * FROM `tcg_".$table."` WHERE `".$prefix."_status`='$stat' AND (`".$prefix."_deckname` LIKE '%".$term."%' OR `".$prefix."_filename` LIKE '%".$term."%' OR `".$prefix."_set` LIKE '%".$term."%' OR `".$prefix."_donator` LIKE '%".$term."%' OR `".$prefix."_maker` LIKE '%".$term."%') ORDER BY `".$prefix."_deckname` ASC");

			if( mysqli_num_rows( $sql ) == 0 )
			{
				echo '<div class="box-warning"><b>Error!</b> Your search query didn\'t match any data from the database.</div>';
			}

			else
			{
				echo '<div class="box-success"><b>Success!</b> The data below shows any matches from your search query: <b>'.$term.'</b>.</div><br />
				<table width="80%" cellspacing="3" class="border">
				<tr>
					<td class="headLineSmall" width="30%">Deckname</td>
					<td class="headLineSmall" width="15%">Donator</td>
					<td class="headLineSmall" width="15%">Maker</td>
				</tr>';

				while( $search = mysqli_fetch_assoc( $sql ) )
				{
					echo '<tr>
					<td class="tableBodySmall"><a href="'.$tcgurl.'cards.php?view=released&deck='.$search[''.$prefix.'_filename'].'">'.$search[''.$prefix.'_deckname'].'</a></td>
					<td class="tableBodySmall"><a href="'.$tcgurl.'members.php?id="'.$search[''.$prefix.'_donator'].'">'.$search[''.$prefix.'_donator'].'</a></td>
					<td class="tableBodySmall"><a href="'.$tcgurl.'members.php?id='.$search[''.$prefix.'_maker'].'">'.$search[''.$prefix.'_maker'].'</a></td>
					</tr>';
				}

				echo '</table><br />';
			}
		}
		echo '</center>';
		return true;
	}
}
?>