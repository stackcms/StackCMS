<?php
echo '<table width="100%">
    <tr>';
    if( !empty($login) )
    {
        echo '<td width="20%" valign="top" class="box">
            <div class="gameUpdate">
                <a href="'.$tcgurl.'account.php">Overview</a>
            </div>
            <h3>Services</h3>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'services.php?form=masteries">Masteries</a>
                <a href="'.$tcgurl.'services.php?form=levelup">Level Up</a>
                <a href="'.$tcgurl.'services.php?form=specials">Special Masteries</a>
                <a href="'.$tcgurl.'services.php?form=trading">Trading Rewards</a>
                <a href="'.$tcgurl.'services.php?form=doubles">Doubles Exchange</a>
                <a href="'.$tcgurl.'services.php?form=coupons">Coupons Exchange</a>
                <a href="'.$tcgurl.'services.php?form=claims">Deck Claims</a>';
                $chkMD = $database->num_rows("SHOW TABLES LIKE 'tcg_cards_user'");
                if( $chkMD = 0 ) {}
                else
                {
                    echo '<a href="'.$tcgurl.'services.php?form=member-deck">Member Decks</a>
                    <a href="'.$tcgurl.'services.php?form=submit-task">Submit a Task</a>';
                }
                echo '<a href="'.$tcgurl.'services.php?form=contact">Contact Admin</a>
            </div>
            <hr>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'shoppe.php">Shoppe</a>
                <a href="'.$tcgurl.'rewards.php?name='.$player.'">Rewards ('; $count->numRewards(); echo ')</a>
                <a href="'.$tcgurl.'messages.php?id='.$player.'&page=inbox">Mailbox ('; $count->numMail(); echo ')</a>
            </div>
            <h3>Account</h3>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'account.php?do=profile">Edit Profile</a>
                <a href="'.$tcgurl.'account.php?do=items">Edit Items</a>
                <a href="'.$tcgurl.'account.php?do=resetpass">Reset Password</a>
                <br />
                <a href="'.$tcgurl.'account.php?do=activity">Archive Activity Logs</a>
                <a href="'.$tcgurl.'account.php?do=trade">Archive Trade Logs</a>
            </div>
            <hr>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'account.php?do=quit" class="quit">Quit '.$tcgname.'</a>
                <a href="'.$tcgurl.'account.php?do=logout" class="signout">Logout</a>
            </div>
        </td>
        
        <td width="2%"></td>';
    }

    else {}
        
        echo '<td width="78%" valign="top" class="box">';
?>