<?php

include('db.inc.php');

function get_db_name($eventid){
	#echo "get_db_name called";
	$get_table = "select `event_db` from `events` where `events`.`event_id`=$eventid limit 0,1;";

	$result = mysql_query($get_table) or die('Query 1 failed: ' . mysql_error());

	$event_db = mysql_fetch_array($result, MYSQL_NUM);
	#echo $event_db[0];
	return $event_db[0];
}

function get_user_info($userid, $eventid){
//Take a userid and eventid, and return an array
	#echo "get_user_info called";

	$eventdb=get_db_name($eventid);
	#echo $eventdb;

	$get_users = "select * from `$eventdb` where user_id=$userid limit 0,1";

	$result = mysql_query($get_users) or die('Query 2 failed: ' . mysql_error());
	#echo 'returning results';
	return mysql_fetch_array($result, MYSQL_ASSOC);
}


function log_connection($eventid,$targetid,$scannerid, $comments){

	$eventdb=get_db_name($eventid);
	$ip_addr=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$user_agent=mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$comments = mysql_real_escape_string($comments);
	$targetid=mysql_real_escape_string($targetid);
	if ($scannerid != NULL){
		$cannerid=mysql_real_escape_string($scannerid);
	}

	$insert_query = "INSERT into `$eventdb` (`scanner_id`,`target_id`,`ip_addr`, `timestamp`, `user_agent`, `comments`) VALUES ('$scannerid', '$targetid', '$ip_addr', NOW(), '$user_agent','$comments')";
	echo $insert_query;
	//mysql_query($insert_query) or die('could not log: '.mysql_error());
}
