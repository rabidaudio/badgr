<?php
$mysql_host = 'passionpathco.domaincommysql.com';
$mysql_user = 'badger';
$mysql_password = 'InTheKitchen11.';
$mysql_database = 'badger';
$db=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die('could not connect:'.mysql_error());
mysql_select_db($mysql_database) or die('could not select database');


//test
echo "connected";
$result = mysql_query("select * from 3daystartup") or die('Query failed: ' . mysql_error());

echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";
?>
