<?php

class githubCalendarGenerator {
    public $username;
    private $url = "http://github.com/users/%USERNAME%/contributions_calendar_data";
    private $data;
    private $largestCommitCount;
    private $days = array(
        "Sunday"    => 0,
        "Monday"    => 1,
        "Tuesday"   => 2,
        "Wednesday" => 3,
        "Thursday"  => 4,
        "Friday"    => 5,
        "Saturday"  => 6,
    );
    private $css = "<style>
    .calCont {
        float: left;
        width: 100%;
    }
    .calCol {
        float: left;
        width: 13px;
    }
    
    .calBox {
        margin: 2px;
        width: 11px;
        height: 11px;
    }
</style>";
    
    public function __construct($username){
        $this->username = $username;
    }
    
    public function __toString(){
        $this->getDataFromGithub();
        return $this->render();
    }
    
    public function render(){
        $html = "<div class='calCont'>\n";
        $html .= "<div class='calCol'>\n";
        $weekDayCount = 0;
        $totalDayCount = 0;
        foreach ($this->data as $element) {
            if ($totalDayCount == 0) {
                $firstDate = date('l', strtotime($element[0]));
                $weekDayCount = $this->days["$firstDate"];
                for ($j = 0; $j < $weekDayCount; $j ++) {
                    $html .= "<div class='calBox' style='background: #FFFFFF'></div>\n";
                }
            }
            //Base color: D6E685
            $html .= "<div class='calBox' style='background: #";
            $html .= $this->formatHexColor(0xD6, $element[1], 1.1, $this->largestCommitCount);
            $html .= $this->formatHexColor(0xE6, $element[1], 0.5, $this->largestCommitCount);
            $html .= $this->formatHexColor(0x85, $element[1], 0.8, $this->largestCommitCount);
            $html .= "'></div>\n";
            
            $weekDayCount ++;
            $totalDayCount ++;
            
            if ($weekDayCount == 7) {
                $weekDayCount = 0;
                $html .= "</div>\n";
                $html .= "<div class='calCol'>\n";
            }
        }
        $html .= "</div>\n";
        $html .= "</div>\n";
        return $this->css.$html;
    }
    
    private function getLargestCommitCount(){
        $this->largestCommitCount = 0;
        foreach ($this->data as $element) {
            $this->largestCommitCount = ($element[1] > $this->largestCommitCount) ? $element[1] : $this->largestCommitCount;
        }
        return $this->largestCommitCount;
    }
    
    private function getDataFromGithub(){
        $this->data = json_decode(file_get_contents($this->getUrl()));
        $this->getLargestCommitCount();
        return $this->data;
    }
    
    private function getUrl(){
        return str_replace("%USERNAME%", $this->username, $this->url);
    }
    
    private function calculateColorValue($base, $weekDayCount, $mult, $cap, $bound) {
        if ($weekDayCount != 0) {
            $val = $base - round($base * $mult * (floor((1 / ($bound / $weekDayCount)) * 10) / 10) );
            return base_convert(($val > $cap ? $cap : $val), 10, 16);
        } else {
            return base_convert(0xEE, 10, 16);
        }
    }

    private function formatHexColor($baseColor, $commitCount, $colorMultiplier, $largestCommitCount) {
        return str_pad($this->calculateColorValue($baseColor, $commitCount,  $colorMultiplier, 0xFF, $largestCommitCount), 2, "0", STR_PAD_LEFT);
    }
}
?>
