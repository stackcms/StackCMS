<?php
/*****************************************************
 * Module:			Archive Activity Logs
 * Description:		Process archiving of activity logs
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	if( isset( $_POST['submit'] ) )
	{
		$user = $sanitize->for_db($_POST['user']);
		$date = $_POST['date'];
        $period = explode("-", $date);

		$show = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$user' AND `log_date` LIKE '$date-%' ORDER BY `log_date` DESC");

		if( !$show )
		{
			$error[] = "Sorry, there was an error and your activity logs were not exported.";
		}

		else
		{
			// Create user text file if doesn't exist
			$file = $tcgpath.'modules/members/activity/'.$user.'.txt';
			if( file_exists( $file ) )
			{
				$fh = fopen($file, 'a');

				//Put currency names in an array
				$currency = explode(', ', $settings->getValue('tcg_currency'));
				foreach( $currency as $c )
				{
					$currencyNames[] = substr($c, 0, -4);
				}

				$timestamp = '';
				$output = '';
				while( $row = mysqli_fetch_assoc( $show ) )
				{
					$rewards = explode(', ', $row['log_rewards']);

					// Declare empty strings
					$txtString = ''; 
					$curString = ''; 

					// Display cards for each reward if NOT a currency
					foreach( $rewards as $r )
					{
						if( !empty($currencyNames) && !in_array($r, $currencyNames, FALSE) )
						{
							$txtString .= $r.', ';
						}
					}

					// Get count of how many of each reward is present
					$values = array_count_values($rewards);

					// Display currencies that are in rewards and quantity only if exists in rewards
					foreach( $currencyNames as $cn )
					{
						if( array_key_exists($cn, $values) )
						{
							// Pluralize the currencies if more than 1
							if( $values[$cn] > 1 )
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
							$curString .= ', +'.$values[$cn].' '.$vtn;
						}
					}

					// Display text of rewarded cards
					$txtString = substr_replace($txtString,"",-2);

					if( $row['log_date'] != $timestamp )
					{
						$output = $output."\n".date('F d, Y', strtotime($row['log_date']))." -----\n";
						$timestamp = $row['log_date'];
					}
					$output = $output."- ".$row['log_title'];
					if( empty( $row['log_subtitle'] ) ) {}
					else
					{
						$output = $output." ".$row['log_subtitle'];
					}
					$output = $output.": ".$txtString."".$curString."\n";
				} // end while
				fwrite($fh, $output);
				fclose($fh);
			}

			// Otherwise overwrite current text file
			else
			{
				$fh = fopen($file, 'w');
				//Put currency names in an array
				$currency = explode(', ', $settings->getValue('tcg_currency'));
				foreach( $currency as $c )
				{
					$currencyNames[] = substr($c, 0, -4);
				}

				$timestamp = '';
				$output = '';
				while( $row = mysqli_fetch_assoc( $show ) )
				{
					$rewards = explode(', ', $row['log_rewards']);

					// Declare empty strings
					$txtString = ''; 
					$curString = ''; 

					// Display cards for each reward if NOT a currency
					foreach( $rewards as $r )
					{
						if( !empty($currencyNames) && !in_array($r, $currencyNames, FALSE) )
						{
							$txtString .= $r.', ';
						}
					}

					// Get count of how many of each reward is present
					$values = array_count_values($rewards);

					// Display currencies that are in rewards and quantity only if exists in rewards
					foreach( $currencyNames as $cn )
					{
						if( array_key_exists($cn, $values) )
						{
							// Pluralize the currencies if more than 1
							if( $values[$cn] > 1 )
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
							$curString .= ', +'.$values[$cn].' '.$vtn;
						}
					}

					// Display text of rewarded cards
					$txtString = substr_replace($txtString,"",-2);

					if( $row['log_date'] != $timestamp )
					{
						$output = $output."\n".date('F d, Y', strtotime($row['log_date']))." -----\n";
						$timestamp = $row['log_date'];
					}
					$output = $output."- ".$row['log_title'];
					if( empty( $row['log_subtitle'] ) ) {}
					else
					{
						$output = $output." ".$row['log_subtitle'];
					}
					$output = $output.": ".$txtString."".$curString."\n";
				} // end while
				fwrite($fh, $output);
				fclose($fh);
			}

			// Flush logs after being exported
			$delete = $database->query("DELETE FROM `user_logs` WHERE `log_name`='$user' AND YEAR(log_date)='".$period[0]."' AND MONTH(log_date)='".$period[1]."'");

			if( !$delete )
			{
				$error[] = "Your activity logs has been exported successfully but were not flushed from the database.";
			}

			else
			{
				$success[] = "Your activity logs has been exported successfully and has been flushed from the database!";
			}
		}
	}


	// Show export form
	echo '<h1>Export Activity Logs</h1>
	<p>Do you want to export your current activity logs? Please keep in mind that exporting a portion of your activity logs will flush it from the database. You will still be able to see these exported logs via your archived logs and can be downloaded if you need to.<br />
	Select the month and year of your log and then click the button below:</p>

	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'account.php?do='.$do.'">
	<input type="hidden" name="user" value="'.$player.'" />
	<input type="month" id="date" name="date" min="2020-01"><br />
	<input type="submit" name="submit" class="btn-success" value="Export Logs" />
	</form>';
}
?>