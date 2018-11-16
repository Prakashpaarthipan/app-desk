<?php
        $date1 = date_create("2007-03-24");
        echo "Start date: ".$date1->format("Y-m-d")."<br>";
        $date2 = date_create("2009-06-26");
        echo "End date: ".$date2->format("Y-m-d")."<br>";
        $diff = date_diff($date1,$date2);
        echo "Difference between start date and end date: ".$diff->format("%y years, %m months and %d days")."<br>";
    ?>