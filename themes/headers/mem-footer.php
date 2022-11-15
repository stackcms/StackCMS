<?php
        echo '</td>
        
        <td width="2%"></td>
        
        <td width="20%" valign="top" class="box">
            <h3>By Status</h3>
            <div class="gameUpdate">
                <a href="'.$tcgurl.'members.php">Active ('; $count->numAll('user_list','Active','usr',''); echo')</a>
                <a href="'.$tcgurl.'members.php?stat=pending">Pending ('; $count->numAll('user_list','Pending','usr',''); echo')</a>
                <a href="'.$tcgurl.'members.php?stat=hiatus">Hiatus ('; $count->numAll('user_list','Hiatus','usr',''); echo ')</a>
                <a href="'.$tcgurl.'members.php?stat=inactive">Inactive ('; $count->numAll('user_list','Inactive','usr',''); echo ')</a>
                <a href="'.$tcgurl.'members.php?stat=retired">Retired ('; $count->numAll('user_list_quit','','usr',''); echo ')</a>
            </div>
        </td>
    </tr>
</table>';
?>