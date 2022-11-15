<?php
/**************************************************
 * Module:			Archive Trade Logs
 * Description:		Process archiving of trade logs
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

		$show = $database->query("SELECT * FROM `user_trades` WHERE `trd_name`='$user' AND `trd_date` LIKE '$date-%' ORDER BY `trd_date` DESC");

		if( !$show )
		{
			$error[] = "Sorry, there was an error and your trade logs were not exported.";
		}

        else
        {
            // Create user text file if doesn't exist
			$file = $tcgpath.'modules/members/trade/'.$user.'.txt';
            if( file_exists( $file ) )
			{
				$fh = fopen($file, 'a');
                $timestamp = '';
                $output = '';

                while( $row = mysqli_fetch_assoc( $show ) )
                {
                    if( $row['trd_date'] != $timestamp )
                    {
                        $output = $output."<br />".date('F d, Y', strtotime($row['trd_date']))." -----\n";
                        $timestamp = $row['trd_date'];
                    }
                    $output = $output."- Traded ".$row['trd_trader'].": my ".$row['trd_out']." for ".$row['trd_inc']."\n";
                } // end while
                fwrite($fh, $output);
				fclose($fh);
            }

            // Otherwise overwrite current text file
            else
            {
                $fh = fopen($file, 'w');
                $timestamp = '';
                $output = '';

                while( $row = mysqli_fetch_assoc( $show ) )
                {
                    if( $row['trd_date'] != $timestamp )
                    {
                        $output = $output."<br />".date('F d, Y', strtotime($row['trd_date']))." -----\n";
                        $timestamp = $row['trd_date'];
                    }
                    $output = $output."- Traded ".$row['trd_trader'].": my ".$row['trd_out']." for ".$row['trd_inc']."\n";
                } // end while
                fwrite($fh, $output);
				fclose($fh);
            }

            // Flush logs after being exported
			$delete = $database->query("DELETE FROM `user_trades` WHERE `trd_name`='$user' AND YEAR(trd_date)='".$period[0]."' AND MONTH(trd_date)='".$period[1]."'");

			if( !$delete )
			{
				$error[] = "Your trade logs has been exported successfully but were not flushed from the database.";
			}

			else
			{
				$success[] = "Your trade logs has been exported successfully and has been flushed from the database!";
			}
        }
    }


    // Show export form
	echo '<h1>Export Trade Logs</h1>
	<p>Do you want to export your current trade logs? Please keep in mind that exporting a portion of your trade logs will flush it from the database. You will still be able to see these exported logs via your archived logs and can be downloaded if you need to.<br />
	Select the month and year of your trade log and then click the button below:</p>

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