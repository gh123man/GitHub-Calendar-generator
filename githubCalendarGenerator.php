<?php

$username = "thenaterhood";

$json = json_decode(file_get_contents("http://github.com/users/" . $username . "/contributions_calendar_data"));

?>
<style>
    .calCont {
        border: solid 1px;
        float: left;
    }
    .calCol {
        float: left;
        width: 14px;
    }
    
    .calBox {
        margin: 1px;
        width: 12px;
        height: 12px;
    }
</style>

<?

$largestCommitCount = 0;
foreach ($json as $element) {
    if ($element[1] > $largestCommitCount) {
        $largestCommitCount = $element[1];
    }
}

function calculateColorValue($base, $weekDayCountn, $mult, $cap, $bound) {

    if ($weekDayCountn != 0) {
        
        $val = $base - round($base * $mult * (1 / (round($bound / $weekDayCountn) ) ));
        
        return base_convert(($val > $cap ? $cap : $val), 10, 16);
    } else {
        return base_convert(0xEE, 10, 16);
    }
}

$days = array(
    "Sunday"    => 0,
    "Monday"    => 1,
    "Tuesday"   => 2,
    "Wednesday" => 3,
    "Thursday"  => 4,
    "Friday"    => 5,
    "Saturday"  => 6,
);

function formatHexColor($baseColor, $commitCount, $colorMultiplier, $largestCommitCount) {
    return str_pad(calculateColorValue($baseColor, $commitCount,  $colorMultiplier, 0xFF, $largestCommitCount), 2, "0", STR_PAD_LEFT);
}

$weekDayCount = 0;
$totalDayCount = 0;


echo '<div class="calCont">';
echo '<div class="calCol">';

foreach ($json as $element) {
    
    if ($totalDayCount == 0) {
        $firstDate = date('l', strtotime($element[0]));
        $weekDayCount = $days["$firstDate"];
        
        for ($j = 0; $j < $weekDayCount; $j ++) {
            echo '<div class="calBox" style="background: #FFFFFF';
            echo '"></div>';
        }
        
    }
    
    //Base color: D6E685
    echo '<div class="calBox" style="background: #';
    echo formatHexColor(0xD6, $element[1], 1.1, $largestCommitCount);
    echo formatHexColor(0xE6, $element[1], 0.5, $largestCommitCount);
    echo formatHexColor(0x85, $element[1], 0.8, $largestCommitCount);
    echo '"></div>';
    
    $weekDayCount ++;
    $totalDayCount ++;
    
    if ($weekDayCount == 7) {
        $weekDayCount = 0;
        echo '</div>';
        echo '<div class="calCol">';
    }
}

echo '</div>';
echo '</div>';



?>
