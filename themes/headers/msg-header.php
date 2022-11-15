<?php
echo '<table width="100%">
    <tr>
        <td width="20%" valign="top" class="box">
            <div class="gameUpdate">
                <a href="'.$tcgurl.'account.php">My Account</a>
            </div>
            <hr>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'messages.php?id='.$id.'&page=create">Compose</a>';
                // Count unread messages
				$unread = $database->num_rows("SELECT * FROM `user_mbox` WHERE `msg_see_to`=1 AND `msg_recipient`='$id'");
				if( $unread == 0 )
                {
                    echo '<a href="'.$tcgurl.'messages.php?id='.$id.'">Inbox (0)</a>';
                }

                else
                {
                    echo '<a href="'.$tcgurl.'messages.php?id='.$id.'">Inbox ('.$unread.')</a>';
                }
                
                // Count messages in outbox
				$out = $database->num_rows("SELECT * FROM `user_mbox` WHERE `msg_box_from`='Out' AND `msg_del_from`=0 AND `msg_sender`='$id'");
				if( $out == 0 )
                {
                    echo '<a href="'.$tcgurl.'messages.php?id='.$id.'&page=outbox">Outbox (0)</a>';
                }

                else
                {
                    echo '<a href="'.$tcgurl.'messages.php?id='.$id.'&page=outbox">Outbox ('.$out.')</a>';
                }
            echo '</div>
        </td>
        
        <td width="2%"></td>
        
        <td width="78%" valign="top" class="box">';
?>