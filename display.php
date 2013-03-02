<?php
include('inc/db.inc.php');

$userid=$_GET['userid'];
$eventid=$_GET['eventid'];
//echo $userid."__".$eventid;

$query = "select * from $eventid where user_id=$userid";

//echo $query;

$result = mysql_query($query) or die('Query failed: ' . mysql_error());


while ($line = mysql_fetch_array($result, MYSQL_ASSOC)){
	foreach($line as $key => $value){
		if($key != 'user_id' && $value != NULL){
			echo "<div id=$key>$value</div><br/>";
		}
	}
}

?>
