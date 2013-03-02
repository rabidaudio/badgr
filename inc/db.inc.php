<?php
$mysql_host = 'passionpathco.domaincommysql.com';
$mysql_user = 'badger';
$mysql_password = 'InTheKitchen11.';
$mysql_database = 'badger';
$db=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die('could not connect:'.mysql_error());
mysql_select_db($mysql_database) or die('could not select database');

?>
