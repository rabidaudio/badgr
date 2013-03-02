<?php

include('db.inc.php');

function get_user_info($userid, $eventid){
//Take a userid and eventid, and return an array
	
	$get_table = "select `event_db` from `events` where `events`.`event_id`=$eventid limit 0,1";

	$result = mysql_query($get_table) or die('Query 1 failed: ' . mysql_error());

	$event_db = mysql_fetch_array($result, MYSQL_NUM);

	$get_users = "select * from $event_db[0] where user_id=$userid limit 0,1";

	$result = mysql_query($get_users) or die('Query 2 failed: ' . mysql_error());

	return mysql_fetch_array($result, MYSQL_ASSOC);
}

