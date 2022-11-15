<?php
/**********************************************
 * Module:			Deck Claims
 * Description:		Process prejoin deck claims
 */


if( isset($_POST['submit']) )
{
	$check->Donation();
	$name = $sanitize->for_db($_POST['name']);
	$cat = $sanitize->for_db($_POST['category']);
	$deck = $sanitize->for_db($_POST['deckname']);
	$feat = $sanitize->for_db($_POST['feature']);
	$pass = $sanitize->for_db($_POST['pass']);
	$set = $sanitize->for_db($_POST['set']);
	$ser = $sanitize->for_db($_POST['series']);
	$date = date("Y-m-d", strtotime("now"));

	if( empty( $ser ) )
	{
		$insert = $database->query("INSERT INTO `tcg_donations` (`deck_donator`,`deck_cat`,`deck_filename`,`deck_feature`,`deck_set`,`deck_type`,`deck_pass`,`deck_date`) VALUES ('$name','$cat','$deck','$feat','$set','Claims','$pass','$date')");

		if( !$insert )
		{
			$error[] = '<p>There was an error while processing your donations. Kindly send us your donation details instead at <u>'.$tcgemail.'</u>. Thank you and sorry for the inconvenience!</p> '.mysqli_error($insert);
		}
		else
		{
			$success[] = '<p>Your deck claim has been added to the database!<br />
			You can send the donation link using the <a href="'.$tcgurl.'prejoin.php?form=donations">donations</a> form once ready. Claim <a href="'.$tcgurl.'prejoin.php?form=claims">more?</a></p>';
		}
	}

	else
	{
		$request = $database->query("INSERT INTO `tcg_cards_set` (`set_name`) VALUES ('$ser')");
		$sql = $database->get_assoc("SELECT `set_id` FROM `tcg_cards_set` ORDER BY `set_id` DESC LIMIT 1");

		if( !$request )
		{
			$error[] = '<p>There was an error while processing your donations. Kindly send us your donation details instead at <u>'.$tcgemail.'</u>. Thank you and sorry for the inconvenience!</p> '.mysqli_error($insert);
		}
		else
		{
			$success[] = '<p>Your deck claim has been added to the database!<br />
			You can send the donation link using the <a href="'.$tcgurl.'prejoin.php?form=donations">donations</a> form once ready. Claim <a href="'.$tcgurl.'prejoin.php?form=claims">more?</a></p>';
			$database->query("INSERT INTO `tcg_donations` (`deck_set`,`deck_donator`,`deck_cat`,`deck_filename`,`deck_feature`,`deck_type`,`deck_pass`,`deck_date`) VALUES ('".$sql['set_id']."','$name','$cat','$deck','$feat','Claims','$pass','$date')");
		}
	}
} // end process form

echo '<h1>Deck Claims</h1>
<p>Use the form below to submit your claims. Please make sure that the deck you\'re about to claim hasn\'t been claimed by anyone else. All claims are password-protected by the claimant, so don\'t forget to provide any dummy password that you can use when you\'re going to send your donations.</p>

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

<form method="post" action="'.$tcgurl.'prejoin.php?form='.$form.'">
<table width="100%" cellspacing="3" class="border">
<tr>
	<td class="headLine" width="15%">Name:</td>
	<td class="tableBody"><input type="text" name="name" placeholder="Jane Doe" style="width:98%;"></td>
	<td class="headLine" width="15%">Password:</td>
	<td class="tableBody"><input type="text" name="pass" placeholder="for donation purposes" style="width:98%;"></td>
</tr>
<tr>
	<td class="headLine" width="15%">Category:</td>
	<td class="tableBody" width="35%">
		<select name="category" style="width:97%;">
			<option value="">-----</option>';
			$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
			while( $cat = mysqli_fetch_assoc( $c ) )
			{
				echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].'</option>';
			}
		echo '</select>
	</td>
	<td class="headLine" width="15%">File Name:</td>
	<td class="tableBody" width="35%"><input type="text" name="deckname" style="width:98%;" placeholder="e.g. whitetigers"></td>
</tr>
<tr>
	<td class="headLine">Feature:</td>
	<td class="tableBody"><input type="text" name="feature" placeholder="usually what\'s the deck all about" style="width:98%;"></td>
</tr>
<tr>
	<td class="headLine">Set (or Series):</td>
	<td class="tableBody">
		<select name="set" style="width:97%;">
			<option value="">-----</option>';
			$s = $database->query("SELECT * FROM `tcg_cards_set` ORDER BY `set_name` ASC");
			while( $set = mysqli_fetch_assoc( $s ) )
			{
				echo '<option value="'.$set['set_id'].'">'.$set['set_name'].'</option>';
			}
		echo '</select><br />
		If the set/series you are donating for is not on the list, would you like to add them?<br />
		<input type="radio" name="add-series" id="add-series" value="Yes"> Yes, please! &nbsp;&nbsp;&nbsp; <input type="radio" name="add-series" id="add-series" value="No"> No thanks.<br /><br />
		<div id="additional" style="display:none;">
			<input type="text" name="series" placeholder="e.g. Tokyo Revengers" style="width:99%;">
		</div>';
		?>
		<script>
		$(function() {
			$('input[name="add-series"]').on('click', function() {
				if ($(this).val() == 'Yes') {
					$('#additional').show();
					$('#additional').fadeIn();
				}
				else {
					$('#additional').hide();
					$('#additional').fadeOut();
				}
			});
		});
		</script>
		<?php
	echo '</td>
</tr>
<tr>
	<td class="tableBody" align="center" colspan="4">
		<input type="submit" name="submit" class="btn-success" value="Send Claims" /> 
		<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</td>
</tr>
</table>
</form>';
?>