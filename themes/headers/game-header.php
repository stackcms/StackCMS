<?php
$getSet = $database->get_assoc("SELECT * FROM `tcg_games` WHERE `game_slug`='$play'");

if( $getSet['game_set'] == "Weekly" )
{
    echo '<h3>Weekly</h3>
    <div class="gameUpdate">';
    $gW = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Weekly' AND `game_status`='Active' ORDER BY `game_title` ASC");
	while( $row = mysqli_fetch_assoc( $gW ) )
    {
		echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'"';
        if( $row['game_slug'] == $getSet['game_slug'] )
        {
            echo ' class="gameUpdateFocus"';
        }
        else {}
        echo '>'.$row['game_title'].'</a>';
    }

	if( $settings->getValue( 'xtra_motm' ) == 0 || $settings->getValue( 'xtra_motm_scope' ) == "Week" || $settings->getValue( 'xtra_motm_vote' ) == 0 ) {}
	else
    {
        echo '<a href="'.$tcgurl.'games.php?play=motw">Member of the Week</a>';
    }
    echo '</div>';
}

if( $getSet['game_set'] == "Set A" )
{
    echo '<h3>Set A</h3>
    <div class="gameUpdate">';
    $gSA = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Set A' AND `game_status`='Active' ORDER BY `game_title` ASC");
	while( $row = mysqli_fetch_assoc( $gSA ) )
    {
		echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'"';
        if( $row['game_slug'] == $getSet['game_slug'] )
        {
            echo ' class="gameUpdateFocus"';
        }
        else {}
        echo '>'.$row['game_title'].'</a>';
    }
    echo '</div>';
}

if( $getSet['game_set'] == "Set B" )
{
    echo '<h3>Set B</h3>
    <div class="gameUpdate">';
    $gSB = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Set B' AND `game_status`='Active' ORDER BY `game_title` ASC");
	while( $row = mysqli_fetch_assoc( $gSB ) )
    {
		echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'"';
        if( $row['game_slug'] == $getSet['game_slug'] )
        {
            echo ' class="gameUpdateFocus"';
        }
        else {}
        echo '>'.$row['game_title'].'</a>';
    }
    echo '</div>';
}

if( $getSet['game_set'] == "Monthly" )
{
    echo '<h3>Monthly</h3>
    <div class="gameUpdate">';
    $gM = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_status`='Active' ORDER BY `game_title` ASC");
	while( $row = mysqli_fetch_assoc( $gM ) )
    {
		echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'"';
        if( $row['game_slug'] == $getSet['game_slug'] )
        {
            echo ' class="gameUpdateFocus"';
        }
        else {}
        echo '>'.$row['game_title'].'</a>';
    }
    echo '</div>';
}

if( $getSet['game_set'] == "Special" )
{
    echo '<h3>Special</h3>
    <div class="gameUpdate">';
    $gSP = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Special' AND `game_status`='Active' ORDER BY `game_title` ASC");
	while( $row = mysqli_fetch_assoc( $gSP ) )
    {
		echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'"';
        if( $row['game_slug'] == $getSet['game_slug'] )
        {
            echo ' class="gameUpdateFocus"';
        }
        else {}
        echo '>'.$row['game_title'].'</a>';
    }
    echo '</div>';
}
?>