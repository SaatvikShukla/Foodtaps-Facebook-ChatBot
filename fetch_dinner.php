<?php
    $homepage = file_get_contents("http://www.foodtaps.com/foodtaps/action/explorekitchen/daawat-19?ktid=19");
	$searchterm = '<h4>Dinner Menu';
	$temo = "";
	$exploded_page = explode("\n", $homepage);
	
	date_default_timezone_set('Asia/Kolkata');
	$finalDinner = "Dinner for Daawat\u000AFor ".date('j M Y')."\u000A";

	for ($i = 0; $i < sizeof($exploded_page); ++$i)
	{
	    if (strpos($exploded_page[$i], "$searchterm") !== false)
	    {
	    
	        $temo = $exploded_page[$i];
	        $temo = preg_replace( "/Dinner Menu/", "", $temo);
	        $temo = preg_replace( "/\r|\n/", "\u000A", $temo);
	        $temo = preg_replace( "/\(/", "\u000A(", $temo);
	        $temo = preg_replace( "/<li>/", "\u000A-", $temo);
	        $temo = strip_tags($temo);
	    }
	}
    if ($temo == "") {
    	$finalDinner = "Sorry, dinner menu has not yet been updated.";
    } else {
    	$finalDinner = $finalDinner.'\u000A'.$temo;
    }
?>