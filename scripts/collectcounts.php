#!/usr/bin/php
<?php
$dbuser="";
$dbpass="";
$dbhost="";
$dbname="stats";
$tca2dbname="tcadmin2";

$datetime = date("Y-m-d H:i:s");

$dbconn = mysql_connect($dbhost,$dbuser,$dbpass);
//$db = mysql_select_db($db_conn_db, $dbconn);

$sql = "SELECT service_id,online,max_players,players,cpu,memory FROM `tcadmin2`.`tc_game_service_live_stats`;";

$query = mysql_query($sql);
$sql2="";
while($row = mysql_fetch_assoc($query)) {
    if ($row['online'] != "") {
	    $sql2 = "INSERT INTO `stats`.`harvest` (`service_id`,`datetime`,`online`,`max_players`,`players`,`cpu`,`memory`) VALUES ('".$row['service_id']."','".$datetime."','".$row['online']."','".$row['max_players']."','".$row['players']."','".$row['cpu']."','".$row['memory']."');\n";
	    $query2=mysql_query($sql2) or die("Fatal Error: Query Failed: ".mysql_error());
    }
}

?>
