<?php

include('db.inc.php');

function get_db_name($eventid){
#helper function for db backend
	#$eventid=mysql_real_escape_string($eventid);
	$get_table = "select `event_db` from `events` where `events`.`event_id`='$eventid'";

	$result = mysql_query($get_table) or mysql_error_redirect("get_db_name:".mysql_error() );

	$event_db = mysql_fetch_array($result, MYSQL_NUM);
	return $event_db[0];
}

function get_user_info($userid, $eventid){
#Take a userid and eventid, and return an array of user data
	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);
	$get_users = "select * from `$eventdb` where user_id='$userid' limit 0,1";

	$result = mysql_query($get_users) or mysql_error_redirect(mysql_error() );
	return mysql_fetch_array($result, MYSQL_ASSOC);
}


function log_connection($eventid,$targetid, $comments){
#log every time someone scans another id
	$scannerid=$_SESSION['userid'];
	$eventdb=get_db_name($eventid);
	$ip_addr=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$user_agent=mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$comments = mysql_real_escape_string($comments);
	$targetid=mysql_real_escape_string($targetid);
	if ($scannerid != NULL){
		$cannerid=mysql_real_escape_string($scannerid);
	}

	$insert_query = "INSERT into `".$eventdb."_log` (`scanner_id`,`target_id`,`ip_addr`, `timestamp`, `user_agent`, `comments`) VALUES ('$scannerid', '$targetid', '$ip_addr', NOW(), '$user_agent','$comments')";
	mysql_query($insert_query) or mysql_error_redirect(mysql_error() );
}

function has_user_updated($userid, $eventid){
#boolean to test if user has updated their info yet
	$userinfo=get_user_info($userid, $eventid);
	return $userinfo['user_updated'];
}

function user_has_updated($userid, $eventid){
#update the db when user updates their info
	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);
	$update_query = "UPDATE `$eventdb` SET `user_updated`='1' WHERE `user_id`='$userid'";

	mysql_query($update_query) or mysql_error_redirect(mysql_error() );
}

function update_info($userid, $eventid, $user_info){
#takes an array of user data and updates database (generically based on array keys)
	$eventdb=get_db_name($eventid);
	$cuserid=mysql_real_escape_string($userid);

	$update_query = "UPDATE `$eventdb` SET";

	foreach($user_info as $key => $value){
		$update_query .= " `".$key."` = '".mysql_real_escape_string($value)."',";
	}
	$update_query=rtrim($update_query, ',');
	$update_query.= " WHERE `user_id`='$cuserid'";

	mysql_query($update_query) or mysql_error_redirect(mysql_error() );
	user_has_updated($userid,$eventid);

	#finally, add the email address the user submitted if we don't have one
	if($_SESSION['email_address']==NULL && $user_info['email_address']!=NULL){
		$_SESSION['email_address']=$user_info['email_address'];
	}
	if($_SESSION['userid']==NULL){
		$_SESSION['userid']=$userid;
	}
}

function get_event_fields($eventid){
#return a list of all fields for this event
	$eventdb=get_db_name($eventid);
	$get_fields = "SHOW COLUMNS FROM `badger`.`$eventdb`;";

	$fields=array();
	$result = mysql_query($get_fields) or mysql_error_redirect(mysql_error() );
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
		$fields[] = $row['Field'];
	}
	return $fields;
}

function mysql_error_redirect($errormsg){
#if some query fails, redirect to an error page.
	#$_SESSION['error_msg']=$errormsg; #TODO Why didn't this work?
	#Header('Location: error.php');
	#exit();

	die($errormsg);
	#die('<META HTTP-EQUIV="Refresh" CONTENT="0;URL=error.php">');
}

function encode_id($uid, $eid){
	$alphabet = "bcdfghjklmnpqrstvwxyz0123456789BCDFGHJKLMNPQRSTVWXYZ";
	$base=strlen($alphabet);
	$usize=3;
	$esize=3;
	$maxlen=$usize+$usize;

	$n=$eid + $uid*round(pow(10,$esize));

	$pad=$maxlen-1;
	$n += pow($base, $pad);
	$t=round(log($n,$base));
	while($t>=0){
		$bcp= round(pow($base, $t) );
		$a= ($n/$bcp) % $base;
		$s.= $alphabet[$a];
		$n=$n - ($a*$bcp);
		$t--;
	}

	return strrev($s);

}

function decode_id($n){
	$alphabet = "bcdfghjklmnpqrstvwxyz0123456789BCDFGHJKLMNPQRSTVWXYZ";
	$base=strlen($alphabet);
	$usize=3;
	$esize=3;
	$maxlen=$usize+$usize;

	$n=strrev($n);
	$s=0;
	$l=strlen($n)-1;
	$t=0;
	while($t<=$l){
		$bcpow = round( pow($base, ($l - $t) ) );
		$s += strpos($alphabet, $n[$t]) * $bcpow;
		$t++;
	}
	$pad = $maxlen-1;
	$s = round($s - pow($base, $pad) );
	$uid=substr($s,$usize);
	$eid=strrev(substr(strrev($s),$esize));
	return array($uid, $eid);
}

function generate_password(){
	$alphabet="abcdefghijkmnpqrstuvwxyz";
	$l=strlen($alphabet)-1;
	$passwordlength=5;

	$pass="";

	for($i=1; $i<=$passwordlength;$i++){
		$index=rand(0,$l);
		$pass.=$alphabet[$index];
	}
	return $pass;
}

function check_password($userid, $eventid, $password){
	$eventdb=get_db_name($eventid);
	$userid=mysql_real_escape_string($userid);
	$get_password = "select `password` from `$eventdb` where user_id='$userid'";
	echo $get_password;
	$result = mysql_query($get_password) or mysql_error_redirect("check_password".mysql_error() );
	$result = mysql_fetch_array($result, MYSQL_NUM);
	if($password==$result[0]){
		return 1;
	}else{
		return 0;
	}
}

function generate_entity($eventid){
#Does all the work for adding a new user
	$password=generate_password();
	$eventdb=get_db_name($eventid);
	$insert_query = "INSERT into `$eventdb` (`password`) VALUES ('$password')";
	mysql_query($insert_query) or mysql_error_redirect(mysql_error() );
	#now we need to find what userid that was. should be the latest one. DON'T PARALLELIZE THIS PROCESS!!
	$id_select = "SELECT user_id from `$eventdb` ORDER BY user_id DESC LIMIT 0 , 1";
	$result = mysql_query($id_select) or mysql_error_redirect(mysql_error() );
	$result = mysql_fetch_array($result, MYSQL_NUM);
	$userid = $result[0];
	#now we have a userid and an eventid, so let's make a hash
	$hash=encode_id($userid, $eventid);
	$url = "http://api.qrserver.com/v1/create-qr-code/?size=400x400&data="."http://getbadgr.com/".$hash;
	return "<tr><td><h2>getbadgr.com/$hash</h2></td><td><img src='$url'/></td></tr>";
}
?>
