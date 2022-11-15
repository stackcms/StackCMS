<?php
/*
 * Class library for content plugins functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Plugins
 * Description:		Functions to use for displaying complex code for page content
 */
class Plugins
{
	function plugLevels()
	{
		$database = new Database;
		$settings = new Settings;

		// Run arrays of rewards
		$choice = explode(", ", $settings->getValue( 'prize_level_choice' ));
		$random = explode(", ", $settings->getValue( 'prize_level_reg' ));
		$money = explode(", ", $settings->getValue( 'prize_level_cur' ));
		$array_count = count($choice);
		$array_count .= count($random);
		$array_count .= count($money);
		for( $t = 0; $t < ($array_count -1); $t++ )
		{
			isset( $choice[$t] );
			isset( $random[$t] );
			isset( $money[$t] );
		}

		echo '<table width="100%" cellspacing="3" class="table table-striped">
		<thead>
		<tr>
			<th scope="row" width="30%" align="center">Level</th>
			<th scope="row" width="20%" align="center">Cards</th>
			<th scope="row" width="20%" align="center">Difference</th>
			<th scope="row" width="30%" align="center">Rewards</th>
		</tr>
		</thead>

		<tbody>';

		$lvl = $database->query("SELECT * FROM `tcg_levels`");
		while( $row = mysqli_fetch_assoc( $lvl ) )
		{
			$x = $row['lvl_tier'];
			$arrayCur = [];

			// Explode bombs
			$curValue = explode(' | ', $money[$x]);
			$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
			foreach( $curValue as $key => $value )
			{
				$tn = substr_replace($curName[$key],"",-4);
				if( $curValue[$key] > 1 )
				{
					$var = substr($tn, -1);
					if( $var == "y" )
					{
						$tn = substr_replace($tn,"ies",-1);
					}
					else if( $var == "o" )
					{
						$tn = substr_replace($tn,"oes",-1);
					}
					else
					{
						$tn = $tn.'s';
					}
				}

				else
				{
					$tn = $tn;
				}

				if( $curValue[$key] == 0 ) {}
				else
				{
					$arrayCur[] = $curValue[$key].' '.$tn.', ';
				}
			}
			// Fix all bombs after explosions
			$arrayCur = implode(" ", $arrayCur);
			$arrayCur = substr_replace($arrayCur,"",-2);

			echo '<tr><td align="center">'.$row['lvl_name'].' <i>( Level '.$row['lvl_id'].' )</i></td>
			<td align="center">'.$row['lvl_cards'].'</td>
			<td align="center">'.$row['lvl_interval'].'</td>';

			if( $row['lvl_id'] == 1 )
			{
				echo '<td align="center">-----</td>';
			}

			else
			{
				echo '<td align="center">'.$choice[$x].' choice, '.$random[$x].' random, '.$arrayCur.'</td>';
			}
			echo '</tr>';
		}

		echo '</tbody>
		</table>';
	} // end levels plugin function


	function plugMastery()
	{
        $database = new Database;
		$settings = new Settings;

		// Run array of rewards
		$choiceDeck = explode(", ", $settings->getValue( 'prize_master_choice' ));
		$randomDeck = explode(", ", $settings->getValue( 'prize_master_reg' ));
		$moneyDeck = explode(", ", $settings->getValue( 'prize_master_cur' ));
		$randomSpc = explode(", ", $settings->getValue( 'prize_special_reg' ));
		$moneySpc = explode(" | ", $settings->getValue( 'prize_special_cur' ));

		echo '<table width="100%" cellspacing="3" class="table table-striped">
		<thead>
		<tr>
			<th scope="row" width="40%" align="center">Mastery</th>
			<th scope="row" width="60%" align="center">Rewards</th>
		</tr>
		</thead>

		<tbody>';

        // Show rewards for normal deck type
		foreach( $moneyDeck as $disp => $value )
		{
            $curValue = explode(' | ', $moneyDeck[$disp]);
            $curName = explode(', ', $settings->getValue( 'tcg_currency' ));
            $arrayCur = array();
            foreach( $curValue as $key => $value )
            {
                $tn = substr_replace($curName[$key],"",-4);
                if( $curValue[$key] > 1 )
                {
                    $var = substr($tn, -1);
                    if( $var == "y" )
                    {
                        $tn = substr_replace($tn,"ies",-1);
                    }
                    else if( $var == "o" )
                    {
                        $tn = substr_replace($tn,"oes",-1);
                    }
                    else
                    {
                        $tn = $tn.'s';
                    }
                }

                else
                {
                    $tn = $tn;
                }

                if( $curValue[$key] == 0 ) {}
                else
                {
                    $arrayCur[] = $curValue[$key].' '.$tn.', ';
                }
            }
            $arrayCur = implode(" ", $arrayCur);
            $arrayCur = substr_replace($arrayCur,"",-2);

			// Add tr lines for each deck mastery type
			if( $disp == 0 ) { $mast = 'Regular'; }
			elseif( $disp == 1 ) { $mast = 'Special'; }
			elseif( $disp == 2 ) { $mast = 'Rare'; }

			echo '<tr>
                <td align="center">'.$mast.' Decks</td>
                <td align="center">'.$choiceDeck[$disp].' choice, '.$randomDeck[$disp].' random, '.$arrayCur.'</td>
            </tr>';
		}

		// Show rewards for special deck type (event/member cards)
		if( $settings->getValue( 'prize_special_reg' ) == "0" || $settings->getValue( 'prize_special_cur' ) == "0" ) {}
		else
		{
            foreach( $randomSpc as $disp2 => $value )
            {
                foreach( $moneySpc as $key => $value )
                {
                    $tn = substr_replace($curName[$key],"",-4);
                    if( $moneySpc[$key] > 1 )
                    {
                        $var = substr($tn, -1);
                        if( $var == "y" )
                        {
                            $tn = substr_replace($tn,"ies",-1);
                        }
                        else if( $var == "o" )
                        {
                            $tn = substr_replace($tn,"oes",-1);
                        }
                        else
                        {
                            $tn = $tn.'s';
                        }
                    }

                    else
                    {
                        $tn = $tn;
                    }

                    if( $moneySpc[$key] == 0 ) {}
                    else
                    {
                        $arrayCur2[] = $moneySpc[$key].' '.$tn.', ';
                    }
                }
                $arrayCur2 = implode(" ", $arrayCur2);
                $arrayCur2 = substr_replace($arrayCur2,"",-2);

                echo '<tr>
                <td align="center">Event/Member Cards</td>
                <td align="center">'.$randomSpc[$disp2].' random, '.$arrayCur2.'</td>
                </tr>';
            }
		}

		echo '</tbody>
		</table>';
	} // end masteries plugin function


	function plugCurrency( $plugged )
	{
		$settings = new Settings;

		// Explode all bombs
		$curValue = explode(' | ', $settings->getValue( $plugged ));
		$curName = explode(', ', $settings->getValue( 'tcg_currency' ));

		for( $i=0; $i<count($curValue); $i++ )
		{
			$tn = substr_replace($curName[$i],"",-4);
			if( $curValue[$i] > 1 )
			{
				$var = substr($tn, -1);
				if( $var == "y" )
				{
					$tn = substr_replace($tn,"ies",-1);
				}
				else if( $var == "o" )
				{
					$tn = substr_replace($tn,"oes",-1);
				}
				else
				{
					$tn = $tn.'s';
				}
			}
			else
			{
				$tn = $tn;
			}

			if( $curValue[$i] != 0 )
			{
				$arrayCur = isset( $arrayCur ) ? $arrayCur : null;
				$arrayCur .= "<b>" . $curValue[$i] . "</b> " . $tn . ", ";
			}
		}

		// Fix all bombs after explosions
		$cleanCur = substr_replace($arrayCur,"",-2);
		echo $cleanCur;
	}


	function plugAffiliates( $format )
	{
		$database = new Database;
		$settings = new Settings;
		$upload = new Uploads;
		$tcgimg = $settings->getValue( 'file_path_img' );
		$tcgurl = $settings->getValue( 'tcg_url' );

		if( $format == "general" )
		{
			echo '<center>';
			$sql = $database->query("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='Active' ORDER BY `aff_subject` ASC");
			$num = $database->num_rows("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='Active' ORDER BY `aff_subject` ASC");
			if( $num == 0 )
			{
				echo '<p>There are currently no affiliates, want to become one?</p>';
			}

			else
			{
				while( $row = mysqli_fetch_assoc($sql) )
				{
					echo '<a href="'.$row['aff_url'].'" target="_blank" title="'.$row['aff_subject'].' TCG by '.$row['aff_owner'].'"><img src="/images/aff/'.$row['aff_button'].'" /></a> ';
				}
			}
			echo '</center>';
		}

		else if( $format == "table" )
		{
			echo '<center><table width="100%" class="table table-bordered table-striped">
			<thead><tr>
			<td width="20%" align="center"><b>Image</b></td>
			<td width="60%" align="center"><b>Owner / Subject</b></td>
			<td width="20%" align="center"><b>URL</b></td>
			</tr></thead>
			<tbody>';

			$sql = $database->query("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='Active' ORDER BY `aff_subject` ASC");
			$num = $database->num_rows("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='Active' ORDER BY `aff_subject` ASC");
			if( $num == 0 )
			{
				echo '<p>There are currently no affiliates, want to become one?</p>';
			}

			else
			{
				while( $row = mysqli_fetch_assoc( $sql ) )
				{
					echo '<tr>
					<td align="center><img src="'.$tcgimg.'aff/'.$row['aff_button'].'" /></td>
					<td align="center">'.$row['aff_owner'].' of '.$row['aff_subject'].' TCG</td>
					<td align="center"><a href="'.$row['aff_url'].'" target="_blank" title="'.$row['aff_subject'].' TCG by '.$row['aff_owner'].'">[http://]</a></td>
					</tr>';
				}
			}
			echo '</tbody>
			</table></center>';
		}

		else if( $format == "form" )
		{
			if( isset( $_POST['submit'] ) )
			{
				$upload->affiliates();
			}

			$slug = 'affiliates';

			try
			{
				$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
				// set the PDO error mode to exception
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}

			catch( PDOException $e )
			{
				echo "Error: " . $e->getMessage();
			}
			$stmt = $pdo->prepare("SELECT `post_slug` FROM `tcg_post` WHERE `post_slug` = :slug");
			$stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
			$stmt->execute();

			if( $stmt->rowCount() > 0 )
			{
				$pathINFO = 'site.php?p=affiliates';
			}

			else
			{
				$pathINFO = pathinfo($_SERVER["SCRIPT_FILENAME"], PATHINFO_BASENAME);
			}

			echo '<br /><form method="post" action="'.$tcgurl.''.$pathINFO.'" accept-charset="UTF-8" enctype="multipart/form-data">
			<table width="100%" class="table table-sliced table-striped">
			<input type="hidden" name="status" value="Pending" />
			<tr>
				<td width="15%"><b>Owner:</b></td>
				<td width="35%"><input type="text" name="owner" placeholder="Jane Doe" style="width:86%;"></td>
				<td width="15%"><b>Email:</b></td>
				<td width="35%"><input type="text" name="email" placeholder="username@domain.tld" style="width:86%;"></td>
			</tr>
			<tr>
				<td><b>TCG Name:</b></td>
				<td><input type="text" name="subject" placeholder="e.g. Moonlight Legend" style="width:86%;"></td>
				<td><b>TCG URL:</b></td>
				<td><input type="text" name="url" placeholder="http://" style="width:86%;"></td>
			</tr>
			<tr>
				<td><b>Button:</b></td>
				<td><input type="file" name="file" style="width:86%;"></td>
				<td colspan="2" align="center"><input type="submit" name="submit" class="btn-success" value="Become an affiliate"> <input type="reset" name="reset" class="btn-danger" value="Reset"></td>
			</tr>
			</table>
			</form>';
		}
	} // end affiliates plugin function

	function plugBadges()
	{
		$database = new Database;
		$settings = new Settings;
		$tcgurl = $settings->getValue( 'tcg_url' );
		$tcgimg = $settings->getValue( 'file_path_img' );
		$tcgext = $settings->getValue( 'cards_file_type' );

		echo '<center>';
		$getLVL = $database->get_assoc("SELECT * FROM `tcg_levels_badge`");
		$sql = $database->query("SELECT * FROM `tcg_levels_badge` WHERE `badge_level`='".$getLVL['badge_level']."' ORDER BY `badge_name`");
		if( mysqli_num_rows( $sql ) == 0 )
		{
			echo 'You don\'t have any level badges uploaded yet. Go to your admin panel and then click on the Collaterals tab and select Level Badges or create one if you haven\'t yet.';
		}

		else
		{
			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<div style="display: inline-block; padding: 2px;">
				<table class="border">
				<tr>
					<td class="headLine">'.$row['badge_name'].'</td>
				</tr>
				<tr>
					<td class="tableBody"><a href="'.$tcgurl.'site.php?p=badges&sub='.$row['badge_set'].'"><img src="'.$tcgimg.'badges/'.$row['badge_set'].'-01.'.$tcgext.'" border="0" title="'.$row['badge_feature'].'" /></a></td>
				</tr>
				<tr>
					<td class="tableBody" align="center">'.$row['badge_width'].' x '.$row['badge_height'].' pixels</td>
				</tr>
				</table>
				</div>';
			}
		}
		echo '</center>';
	} // end level badges plugin function


	function plugMemberTasks()
	{
        $database = new Database;
        $settings = new Settings;

        echo '<table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="5%">Panel</th>
                    <th scope="col" width="50%">Task</th>
                    <th scope="col" width="45%">Completion</th>
                </tr>
            </thead>

            <tbody>';

            $sql = $database->query("SELECT * FROM `user_decks` ORDER BY `task_card` ASC");
            while( $row = mysqli_fetch_assoc( $sql ) )
            {
                echo '<tr>
                <td align="center">'.$row['task_card'].'</td>
                <td><b>'.$row['task_name'].'</b> - '.$row['task_info'].'</td>
                <td>'.$row['task_proof'].'</td>
                </tr>';
            }

            echo '</tbody>
        </table>';
	} // end member deck tasks plugin function


	function plugRSS() {
        global $text, $maxchar, $end;
        function substrwords($text, $maxchar, $end='...') {
            if (strlen($text) > $maxchar || $text == '') {
                $words = preg_split('/\s/', $text);      
                $output = '';
                $i      = 0;
                while (1) {
                    $length = strlen($output)+strlen($words[$i]);
                    if ($length > $maxchar) {
                        break;
                    } else {
                        $output .= " " . $words[$i];
                        ++$i;
                    }
                }
                $output .= $end;
            } else {
                $output = $text;
            }
            return $output;
        }

        $rss = new DOMDocument();
        $rss->load('https://stackcms.dev/feed/'); // <-- Change feed to your site
        $feed = array();
        foreach ($rss->getElementsByTagName('item') as $node) {
            $item = array ( 
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
            );
            array_push($feed, $item);
        }

        $limit = 3; // <-- Change the number of posts shown
        for ($x=0; $x<$limit; $x++) {
            $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
            $link = $feed[$x]['link'];
            $description = $feed[$x]['desc'];
            $description = substrwords($description, 400);
            $date = date('l F d, Y', strtotime($feed[$x]['date']));
            echo '<strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong>';
            echo '<h6>Posted on '.$date.'</h6>';
            echo '<blockquote class="updates">'.$description.'</blockquote>';
        }
    } // end plug RSS function
}
?>