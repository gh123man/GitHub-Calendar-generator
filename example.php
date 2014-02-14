<?php include "GithubCalendarGenerator.php"; ?>

<html>
    <head>
        <title>GitHub Calendar</title>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
        <style>
        h1,h2,h3,h4,h5,h6, p, input, .btn, th, td, tr, table, tbody {
            font-family: 'Droid Sans', sans-serif;
        }
        </style>
    </head>
    <body>
        <h1>GitHub Calendar</h1>
<?php 

    $cal = new GithubCalendarGenerator("gh123man");
    echo $cal;

?>
    </body>
</html>
