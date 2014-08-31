<?php

class GithubCalendarGenerator {
    
    /* lightest color */
    const BASE_R  = 0xD6;
    const BASE_G  = 0xE6;
    const BASE_B  = 0x85;
    
    /* Controls Color offset */
    const MULT_R  = 1.1;
    const MULT_G  = 0.5;
    const MULT_B  = 0.8;
    
    const TILE_BG = '#EEE';
    
    public $username;
    private $url = "http://github.com/users/%USERNAME%/contributions";
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
    
    private $css = '
    <style>
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
            background: %BG_COLOR%;
        }
        
        .blankBox {
            margin: 2px;
            width: 11px;
            height: 11px;
            background: #FFF;
        }
        
    </style>';
    
    public function __construct($username) {
        $this->username = $username;
    }
    
    public function __toString() {
        $this->getDataFromGithub();
        return $this->render();
    }
    
    public function render() {
    
        $html = "<div class='calCont'>\n";
        $html .= "<div class='calCol'>\n";
        $weekDayCount = 0;
        $totalDayCount = 0;
        
        foreach ($this->data as $element) {
        
            if ($totalDayCount == 0) {
                $firstDate = date('l', strtotime($element[0]));
                $weekDayCount = $this->days["$firstDate"];
                for ($j = 0; $j < $weekDayCount; $j ++) {
                    $html .= "<div class='blankBox'></div>\n";
                }
            }
            
            if ($element[1] != 0) {
            
                $html .= "<div class='calBox' style='background: #";
                $html .= self::formatHexColor(self::BASE_R, $element[1], self::MULT_R, $this->largestCommitCount);
                $html .= self::formatHexColor(self::BASE_G, $element[1], self::MULT_G, $this->largestCommitCount);
                $html .= self::formatHexColor(self::BASE_B, $element[1], self::MULT_B, $this->largestCommitCount);
                $html .= "'></div>\n";
                
            } else {
                $html .= "<div class='calBox'></div>\n";
            }
            
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
        
        return $this->getCSS() . $html;
    }
    
    private function getLargestCommitCount() {
    
        $this->largestCommitCount = 0;
        
        foreach ($this->data as $element) {
            $this->largestCommitCount = ($element[1] > $this->largestCommitCount) ? $element[1] : $this->largestCommitCount;
        }
        
        return $this->largestCommitCount;
    }
    
    private function getDataFromGithub() {
    
        $this->data = json_decode(file_get_contents($this->getUrl()));
        $this->getLargestCommitCount();
        
        return $this->data;
    }
    
    private function getUrl() {
        return str_replace("%USERNAME%", $this->username, $this->url);
    }
    
    private function getCSS() {
        return str_replace("%BG_COLOR%", self::TILE_BG, $this->css);
    }
    
    private static function calculateColorValue($base, $weekDayCount, $mult, $cap, $bound) {
    
        if ($weekDayCount != 0) {
            $val = $base - round($base * $mult * (floor((1 / ($bound / $weekDayCount)) * 10) / 10) );
            return base_convert(($val > $cap ? $cap : $val), 10, 16);
        } else {
            return base_convert(0xEE, 10, 16);
        }
    }

    private static function formatHexColor($baseColor, $commitCount, $colorMultiplier, $largestCommitCount) {
        return str_pad(self::calculateColorValue($baseColor, $commitCount,  $colorMultiplier, 0xFF, $largestCommitCount), 2, "0", STR_PAD_LEFT);
    }
}
?>
