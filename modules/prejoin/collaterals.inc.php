<?php
/*******************************************************
 * Module:			Collaterals
 * Description:		Process prejoin collateral donations
 */


if( $sub == "group" )
{
	// Fetch selected group
	$fetch1 = $_POST['group'];

	// Process full donation form
	if( $act == "sent" )
	{
		if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
		{
			exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
		}

		else
		{
			$donator = $sanitize->for_db($_POST['player']);
			$group = $sanitize->for_db($_POST['group']);

			$upload->collateral( $group );
		}
	}

	// Show donation form
	else
	{
		// Get group names
		$group = $database->get_assoc("SELECT * FROM `tcg_collateral` WHERE `collateral_group`='$fetch1'");
		$levels = $database->num_rows("SELECT * FROM `tcg_levels`");

		echo '<h1>Donate '.$group['collateral_name'].'</h1>
		<p>Use the form below to submit your '.$group['collateral_name'].' donations. Please keep in mind the exclusive guidelines before donating any TCG items.</p>
		<ul>
			<li>Button links must be 3 images per set and can be in any of these sizes: 100x35 or 88x31 pixels.</li>
			<li>Level badges must be '.$levels.' images for '.$levels.' levels per set.</li>
			<li>[Add your stamp cards donation guideline here]</li>
			<li>[Add your bingo cards (if available) donation guideline here]</li>
			<li>Zipped files must only contain the file images, no folders, etc.</li>
			<li>Allowed image extensions/file types are: <code>gif</code>, <code>jpeg</code>, <code>jpg</code> and <code>png</code> only.</li>
			<li>Rewards may vary depending on the group of your donation.</li>
		</ul>

		<h2>Following naming conventions for each files per set</h2>
		<p><i>You can change these file naming convention according to your liking.</i></p>
		<b>Link buttons:</b> <code>YourNameSet#-ButtonSize-File#</code> (e.g. Player01-100x35-01)<br />
		<b>Level badges:</b> <code>YourNameSet#-Level#</code> (e.g. Player01-10)<br />
		<b>Stamp cards:</b> <code>YourNameSet#-Trade#</code> (e.g. Player01-25)<br /><br />

		<form method="post" action="'.$tcgurl.'prejoin.php?form='.$form.'&sub=group&action=sent" enctype="multipart/form-data">
		<input type="hidden" name="group" id="group" value="'.$fetch1.'" />
		<div class="row">
			<div class="col">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroup-sizing-default">Your Name</span>
				</div>
				<input type="text" name="player" id="player" placeholder="Jane Doe" class="form-control">
				</div>
			</div>
			<div class="col">
			<div class="custom-file">
				<input type="file" name="file" class="custom-file-input" id="file" aria-describedby="inputGroupFileAddon01">
				<label class="custom-file-label" for="file">Choose file</label>
			</div>
		</div>
		</div>
		<input type="submit" name="submit" class="btn btn-success" value="Donate '.$group['collateral_name'].'" />
		</form>';
	}
}


else
{
	// Show collateral group selector form
	echo '<h1>Choose a Collateral Group</h1>
	<p>Please select the collateral group that you are going to donate using the form below:</p>
	<form method="post" action="'.$tcgurl.'prejoin.php?form='.$form.'&sub=group">
	<div class="row">
		<div class="col-8">
			<select name="group" id="group" class="form-control">
				<option value="">----- Select a group -----</option>';
				$c = $database->query("SELECT * FROM `tcg_collateral` WHERE `collateral_id` >= 2 ORDER BY `collateral_id` ASC");
				while( $col = mysqli_fetch_assoc( $c ) )
				{
					echo '<option value="'.$col['collateral_group'].'">'.$col['collateral_name'].'</option>';
				}
				echo '</select>
		</div>
		<div class="col-4">
			<input type="submit" name="submit" class="btn btn-success" value="Proceed to donation form" /> 
			<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
		</div>
	</div>
	</form>';
}