<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

$p = isset($_GET['p']) ? $_GET['p'] : null;

if( empty( $p ) )
{
    $sql = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='3' AND `post_status`='Published' AND `post_type`='page'");
    echo '<h1>'.$sql['post_title'].'</h1>';
    $con = $sql['post_content'];
    eval('?>'.$con.'');
}

else
{
    if( empty( $sub ) )
    {
        $sql = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_slug`='$p' AND `post_parent`='3' AND `post_status`='Published' AND `post_type`='page'");
        echo '<h1>'.$sql['post_title'].'</h1>';
        $con = $sql['post_content'];
        eval('?>'.$con.'');
    }
    
    else
    {
        $row = $database->get_assoc("SELECT * FROM `tcg_levels_badge` WHERE `badge_set`='$sub'");
        echo '<h1>Level Badges - '.$row['badge_set'].'</h1>';
        echo '<center>';
        for( $i=1; $i<=$row['badge_level']; $i++ )
        {
            if( $i < 10 )
            {
                $digit = '0'.$i;
            }
            else
            {
                $digit = $i;
            }
            echo '<img src="'.$tcgimg.'badges/'.$row['badge_set'].'-'.$digit.'.'.$tcgext.'" /> ';
        }
        echo '</center>';
    }
}

@include($footer);
?>