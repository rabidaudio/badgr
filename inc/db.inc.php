<?php
$mysql_host = '';
$mysql_user = '';
$mysql_password = '';
$mysql_database = '';
$db=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die('could not connect:'.mysql_error());
mysql_select_db($mysql_database) or die('could not select database');

?>
