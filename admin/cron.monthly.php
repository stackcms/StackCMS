<?php
@include('class.lib.php');

$database = new Database;
$sanitize = new Sanitize;

$month = date("Y-m", strtotime("now"));

// Update monthly game set first
if( $month === TRUE )
{
	$database->query("UPDATE `tcg_games_updater` SET `gup_date`='$month-01' WHERE `gup_set`='Monthly'");

	// Update Monthly password gate games if there's any
	$allPass = $database->get_assoc("SELECT `game_pass_array` FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");
	$allQues = $database->get_assoc("SELECT `game_ques_array` FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");
	$allClue = $database->get_assoc("SELECT `game_clue_array` FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");
	$curArray = $database->get_assoc("SELECT `game_current_array` FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");

	if( $curArray <= (count($allPass) -1) && $curArray <= (count($allClue) -1) && $curArray <= (count($allPass) -1) )
	{
		$database->query("UPDATE `tcg_games` SET `game_current_array`=$curArray+1 WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");
	}

	else
	{
		$database->query("UPDATE `tcg_games` SET `game_current_array`=0 WHERE `game_set`='Monthly' AND `game_type` IN ('image', 'text')");
	}
}

################################
###### OTHER AUTO UPDATES ######
################################
// Generate new member of the month
if( $settings->getValue( 'xtra_motm' ) == 0 ) {}
else
{
	// Check if MOTM scope is monthly
	if( $settings->getValue( 'xtra_motm_scope' ) == "Month" )
	{
		$getMOTM = $database->get_assoc("SELECT * FROM `game_motm_list` WHERE `motm_vote` >= '2' ORDER BY `motm_vote` DESC LIMIT 1");
		$database->query("INSERT INTO `game_motm_logs` (`motm_name`,`motm_date`) VALUES ('".$getMOTM['motm_name']."','$month-01')");
		// Reset votes to 0 after inclusion
		$database->query("UPDATE `game_motm_list` SET `motm_vote`='0'");
	}
}
?>
