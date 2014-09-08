GitHub Calendar Generator
===================================


It is a sad day! - GitHub has begun rendering the calender svg server side at the following address: https://github.com/users/gh123man/contributions
====================================================================================================================================================
The usefulness of this script is purely for educational purposes now as it serves no purpose. 
---------------------------------------------------------------------------------------------

Builds your github commit calendar in HTML for use on any website

Usage 
-----
    <?php 
        include "GithubCalendarGenerator.php"; 
        $cal = new GithubCalendarGenerator("gh123man");
        echo $cal;
    ?>
or see example.php

Notes
-----
Recommend using this on an ajax call in case github changes the api call used to ge the graph data, or it hangs for some reason. This way if it does fail/hang, your page will still load. 

The colors are not *exact*. I mathematically compute the colors, where as it appears github uses fixed colors. The base color (the lightest one) is exactly correct, but the rest are approximates. The advantage to this is that this graph actually shows more detail in variation!

![first](https://raw.github.com/gh123man/GitHub-Calendar-generator/master/githubcalendar.png)

