<?php

//read-only mysql user that can access the tcadmin2 database
$db_conn_user="";
$db_conn_pass="";
//tcadmin2 database
$db_conn_db="";
//tcadmin2 database host
$db_conn_host="";

$dbconn = mysql_connect($db_conn_host,$db_conn_user,$db_conn_pass);
$db = mysql_select_db($db_conn_db, $dbconn);

$game = htmlentities($_REQUEST['game']);

$sql="SELECT gs.service_id,ls.name FROM tc_game_services gs
INNER JOIN tc_games g ON gs.game_id = g.game_id
INNER JOIN tc_game_service_live_stats ls ON gs.service_id = ls.service_id
WHERE g.display_name = \"".$game."\"
AND gs.private != \"1\"
ORDER BY ls.name;";

$result = mysql_query($sql);

echo "<input value=\"close\" type=\"button\" onclick=\"clearGameList()\"></input>";
echo "<ul>\n";
while ($row = mysql_fetch_assoc($result)) {
        $service_id=$row['service_id'];
        echo "<li><a href=\"overview.php?sid=".$service_id."\">".$row['name']."</a></li>\n";
}
echo "</ul>\n";

?>
