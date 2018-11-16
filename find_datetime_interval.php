<?php

// echo find_end_date("21-Oct-2018 12:01:00 PM", 1);
echo find_end_date(strtoupper(date('d-M-Y h:i:s A')), 1);

function find_end_date($start_dt, $duration) {
	// $int=1;
	// $start_date=date_create("21-Oct-2018 12:01:00 PM");
	// $end_date=date_create("21-Oct-2018 12:01:00 PM");//initialize
	// $start_init_next=date_create("21-Oct-2018 12:01:00 PM");

	$start_date=date_create($start_dt);
	$end_date=date_create($start_dt);//initialize
	$start_init_next=date_create($start_dt);

	//$interval=date_interval_create_from_date_string('1 days 2 hours 3 minutes 5 seconds');
	//print_r($interval);
	$interval=date_interval_create_from_date_string($duration.' hour');

	/////////////////////

	$difference=$interval;
	$start_init=date_format($start_date,"d-M-Y");
	$start_init_next=date_add($start_init_next,date_interval_create_from_date_string('1 day'));
	$start_init_next=date_format($start_init_next,"d-M-Y");
	if(($start_date>date_create($start_init." 7:00:00 PM") && $start_date<date_create($start_init_next." 10:00:00 AM")) )
	{
	    $start_date=date_create($start_init_next." 10:00:00 AM");
	    $end_date=date_create($start_init_next." 10:00:00 AM");
	}
	elseif($start_date<date_create($start_init." 10:00:00 AM"))
	{
	    $start_date=date_create($start_init." 10:00:00 AM");
	    $end_date=date_create($start_init." 10:00:00 AM"); 
	}
	// print_r($start_date);
	// print_r(date_create($start_init_next." 10:00:00 AM"));
	///////////////////////

	$flag=0;
	$i=0;
	while($flag==0)
	{
	    $i++;
	$cur_start_date=$end_date;

	$cur_start_date=date_format($cur_start_date,"d-M-Y");
	//echo("\n123344444 ".date_format($end_date,"d-M-Y h:i:s A")."\n");
	date_add($end_date,$difference);
	//echo("123344444 ".date_format($end_date,"d-M-Y h:i:s A")."\n =============== ".$cur_start_date);

	$dummy=date_create($cur_start_date." 7:00:00 PM");
	// print_r($dummy);
	//echo("----------".date_format($end_date,"d-M-Y h:i:s A")."---------------------".date_format($dummy,"d-M-Y h:i:s A")."----------------------------------");

	if($end_date<$dummy)
	{
	    // echo("final end date => ".date_format($end_date,"d-M-Y h:i:s A")."\n");
	    // echo("same day\n");
	    $flag=1;
	}
	else
	{   //echo("======================");
	    $end_cur_date=date_format($end_date,"d-M-Y");
	    //$temp_date=date_create($end_cur_date." 7:00:00 PM");
	   //echo("\n0000000000000000".date_format($end_date,"h"));
	    //echo("\n//////////////////////". date_format($end_date,"d-M-Y h:i:s A")."///////////////////////// ".date_format(date_create($end_cur_date." 7:00:00 PM"),"d-M-Y h:i:s A")."//////////////////////////////");
	    
	    if(strtotime($end_cur_date) == strtotime($cur_start_date)){
	       $diff=date_diff($end_date,date_create($end_cur_date." 7:00:00 PM"));
	    }
	    else{
	        
	         $diff=date_diff(date_create($cur_start_date." 7:00:00 PM"),$end_date);
	    }
	    
	    // print_r($diff);
	    $difference=date_interval_create_from_date_string($diff->d.' day '.$diff->h.' hour '.$diff->i.' minutes '.$diff->s.' seconds');
	    // print_r($difference);
	   
	    if(strtotime($end_cur_date) == strtotime($cur_start_date))
	    {
	       $end_date=date_add($end_date,date_interval_create_from_date_string('1 day'));   
	    }
	    $end_cur_date=date_format($end_date,"d-M-Y");
	    $end_date=date_create($end_cur_date." 10:00:00 AM");
	    // echo("\n".date_format($end_date,"d-M-Y h:i:s A")."\n");
	   //echo("======================");
	}

	if($i==10)
	{
	    exit();
	}
	}


	// echo "start date => ".date_format($start_date,"d-M-Y h:i:s A")."<br>end date => ".date_format($end_date,"d-M-Y h:i:s A")."\n";
	return strtoupper(date_format($end_date,"d-M-Y h:i:s A"));
}
?>