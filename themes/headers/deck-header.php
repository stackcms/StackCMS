<?php
echo '<table width="100%">
    <tr>
        <td width="20%" valign="top" class="box">
            <div class="gameUpdate">
                <a href="'.$tcgurl.'cards.php">View Sets</a>
            </div>
            <hr>
            <h2 class="side-title">Decks</h2>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'cards.php?view=released">Released: '; $count->numCards('Active','1'); echo' decks</a>
                <a href="'.$tcgurl.'cards.php?view=upcoming">Upcoming: '; $count->numCards('Upcoming',''); echo ' decks</a>
                <a href="'.$tcgurl.'cards.php?view=claimed">Claimed: '; $count->numClaimed('Claims', $prefix); echo ' decks</a>
                <a href="'.$tcgurl.'cards.php?view=donated">Donated: '; $count->numClaimed('Donations', $prefix); echo ' decks</a>
            </div>
            <hr>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'cards.php?view=zips">Weekly ZIPs</a>
            </div>
        </td>

        <td width="2%"></td>

        <td width="78%" valign="top" class="box">';
?>