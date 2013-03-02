<?php

include('db.inc.php');

function get_db_name($eventid){
	//helper function for db backend

	$get_table = "select `event_db` from `events` where `events`.`event_id`=$eventid limit 0,1;";

	$result = mysql_query($get_table) or die('Query 1 failed: ' . mysql_error());

	$event_db = mysql_fetch_array($result, MYSQL_NUM);
	return $event_db[0];
}

function get_user_info($userid, $eventid){
//Take a userid and eventid, and return an array of user data

	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);
	$get_users = "select * from `$eventdb` where user_id='$userid' limit 0,1";

	$result = mysql_query($get_users) or die('Query 2 failed: ' . mysql_error());
	return mysql_fetch_array($result, MYSQL_ASSOC);
}


function log_connection($eventid,$targetid,$scannerid, $comments){
	//log every time someone scans another id
	$eventdb=get_db_name($eventid);
	$ip_addr=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$user_agent=mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$comments = mysql_real_escape_string($comments);
	$targetid=mysql_real_escape_string($targetid);
	if ($scannerid != NULL){
		$cannerid=mysql_real_escape_string($scannerid);
	}

	$insert_query = "INSERT into `$eventdb` (`scanner_id`,`target_id`,`ip_addr`, `timestamp`, `user_agent`, `comments`) VALUES ('$scannerid', '$targetid', '$ip_addr', NOW(), '$user_agent','$comments')";
	mysql_query($insert_query) or die('could not log: '.mysql_error());
}

function has_user_updated($userid, $eventid){
	//boolean to test if user has updated their info yet
	$userinfo=get_user_info($userid, $eventid);
	return $userinfo['user_updated'];
}

function user_has_updated($userid, $eventid){
	//update the db when user updates their info
	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);
	$update_query = "UPDATE `$eventdb` SET `user_updated`='1' WHERE `user_id`='$userid'";

	mysql_query($update_query) or die('could not update: '.mysql_error());
}

function update_info($userid, $eventid, $user_info){
//takes an array of user data and updates database (generically based on array keys)
	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);

	$update_query = "UPDATE `$eventdb` SET";

	foreach($user_info as $key => $value){
		$update_query .= " `".$key."` = '".mysql_real_escape_string($value)."',";
	}
	$update_query=rtrim($update_query, ',');
	$update_query.= " WHERE `user_id`='$userid'";

	echo "update query:" . $update_query;

	mysql_query($update_query) or die('could not update: '.mysql_error());
}
