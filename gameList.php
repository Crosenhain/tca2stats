<?php

//Setting things up
require_once('inc/config.php');

$dbconn = mysql_connect(TCA2_DB_HOST,TCA2_DB_USER,TCA2_DB_PASS);
$db = mysql_select_db(TCA2_DB_NAME, $dbconn);

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
