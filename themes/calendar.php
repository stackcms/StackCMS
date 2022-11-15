<style type="text/css">
body { margin: 0; }
</style>

<?php
date_default_timezone_set( $settings->getValue('tcg_timezone') );
$monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$upDate = $settings->getValue('update_scope');
$dayNum = date('N', strtotime($upDate));

if( !isset( $_REQUEST["day"] ) )
{
    $_REQUEST["day"] = date("d");
}

if( !isset( $_REQUEST["month"] ) )
{
    $_REQUEST["month"] = date("n");
}

if( !isset( $_REQUEST["year"] ) )
{
    $_REQUEST["year"] = date("Y");
}

$cDay = $_REQUEST["day"];
$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"]; 
 
$prev_year = $cYear;
$next_year = $cYear;
$prev_month = $cMonth-1;
$next_month = $cMonth+1;
    
if( $prev_month == 0 )
{
    $prev_month = 12;
    $prev_year = $cYear - 1;
}

if( $next_month == 13 )
{
    $next_month = 1;
    $next_year = $cYear + 1;
}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="3" class="calendar">
    <tr>
        <td colspan="7" align="center" class="headLine"><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></td>
    </tr>
    <tr>
        <td align="center" class="calDay">S</td>
        <td align="center" class="calDay">M</td>
        <td align="center" class="calDay">T</td>
        <td align="center" class="calDay">W</td>
        <td align="center" class="calDay">T</td>
        <td align="center" class="calDay">F</td>
        <td align="center" class="calDay">S</td>
    </tr>

    <?php
        $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
        $maxday = date("t",$timestamp);
        $thismonth = getdate ($timestamp);
        $startday = $thismonth['wday'];
        $Mon = $monthNames[$cMonth-1];
    

        for( $i=0; $i<($maxday+$startday); $i++ ) {
            if( ($i % 7) == 0 ) echo "<tr>";
            if( $i < $startday ) echo "<td align='center' valign='middle'></td>";
            elseif( ( $i - $startday + 1 ) == $cDay )
            {
                echo "<td class='calFocus' align='center' valign='middle' title='Today is ".date("l").", ".date("F", mktime(0,0,0,$cMonth))." ".($i - $startday + 1).", ".date("Y", mktime(0,0,0,$cMonth))." at ".date("h:i A T", strtotime("now"))."'>". ($i - $startday + 1) ."</td>";
            }
            else
            {
                if( ( $i - $startday + 1 ) == date("j") && ($cMonth-1) == date("F") )
                {
                    echo "<td class='calFocus' align='center' valign='middle'>".  ($i - $startday + 1) . "</td>";
                }
                // add for event or first day of the month
                elseif( date("d",mktime(0,0,0,$cMonth,($i - $startday + 1),$cYear)) == "1" )
                {
                    echo "<td align='center' valign='middle' class='calEvent' title='".date("T", strtotime("now")).": Monthly updates and games updated.'>".  ($i - $startday + 1) . "</td>";
                }
                // determine last day of the month
                elseif( date("d", mktime(0,0,0,$cMonth,($i - $startday + 1),$cYear)) == date("d", strtotime('last day of this month')) )
                {
                    echo "<td align='center' valign='middle' class='calEnd' title='Deadline of donations, monthly games, birthdays, etc!'>".  ($i - $startday + 1) . "</td>";
                }
                // target weekly highlight (e.g. 0 for Sunday, 1 for Monday)
                elseif( date("N",mktime(0,0,0,$cMonth,($i - $startday + 1),$cYear)) == $dayNum )
                {
                    echo "<td align='center' valign='middle' class='calUpdate' title='Friday: Weekly update schedule.'>".  ($i - $startday + 1) . "</td>";
                }
                else
                {
                    echo "<td align='center' valign='middle'>".  ($i - $startday + 1) . "</td>";
                }
                if(($i % 7) == 6 )  echo "</tr>"; 
            }
        }
    ?>
</table>