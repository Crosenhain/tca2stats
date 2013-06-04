<?php

//Setting things up
require_once('inc/config.php');

print <<<HTML
<!DOCTYPE html>
<head>
<title>Game servers in TCAdmin 2</title>

<style type="text/css">
table.gameServers {
	border-collapse:collapse;
	text-align:left;
	color: #000000;
}

table.gameServers thead tr th {
	background-color: #eeeeee;
	vertical-align:middle;
	font-size:1.0em;
}

table.gameServers tbody tr:nth-child(odd) {
	background-color: #eaeaea;
}

table.gameServers tr:hover {
    	background-color: #dcfac9;
}

table.gameServers tr:nth-child(odd):hover {
    	background-color: #dcfac9;
}

table.gameServers tfoot tr {
	text-align:right;
}

table.gameServers tbody tr.separator {
	background-color: #413143;
	color: #cccccc;
}

table.gameServers td.serverOffline {
	background-color: #aaaaaa;
	color: red;
}

</style>

<script type="text/javascript">
function DoNav(theUrl)
{
    document.location.href = theUrl;
}
</script>

</head>
<body>
HTML;

$dbconn = mysql_connect(STATS_DB_HOST,STATS_DB_USER,STATS_DB_PASS);
$db = mysql_select_db(STATS_DB_NAME, $dbconn);

	$sql = "SELECT s.ip_address,s.game_port,g.display_name,g.query_protocol,ls.online,ls.map,ls.name,ls.max_players,ls.players,ls.service_id
FROM tc_game_services s
INNER JOIN tc_games g ON s.game_id = g.game_id
INNER JOIN tc_game_service_live_stats ls ON s.service_id = ls.service_id
WHERE s.private='0'
ORDER BY g.display_name ASC, ls.name ASC;";

	$query = mysql_query($sql);

	$gameid="0";
	$playerCount=0;
	$totalPlayerCount=0;

		echo "<table class=\"gameServers\">\n";
		echo "<thead>\n";
		echo "<tr><th align=left>Server Name</th><th align=left>Connect to</th><th align=left>Current Map</th><th align=left>Player Count</th></tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";
		while($row = mysql_fetch_assoc($query)) {
			$playerCount=$playerCount+$row['players'];
			$totalPlayerCount=$totalPlayerCount+$row['max_players'];
                	if ($row['display_name'] != $gameid) {
        	                echo "<tr class=\"separator\"><th colspan=\"4\">".$row['display_name']."</th></tr>";
	                }
			echo "<tr onclick=\"DoNav('graph.php?sid=".$row['service_id']."');\">";
			if ($row['online'] == 1) {
				//echo "<td>".$row['display_name']."</td>";
				echo "<td>".$row['name']."</td>\n";
				echo "<td>".$row['ip_address'].":".$row['game_port']."</td>\n";
				echo "<td>".$row['map']."</td>\n";
				echo "<td align=right>".$row['players']."/".$row['max_players']."</td>\n";
			} else {
				echo "<td class=\"serverOffline\" colspan=\"4\">".$row['display_name']." (".$row['ip_address'].") could not be queried</td>\n";
			}
			echo "</tr>\n";
			$gameid = $row['display_name'];
		}
		echo "</tbody>\n";
		echo "<tfoot>\n";
		echo "<tr><td colspan=\"4\">There are ".$playerCount." of a possible ".$totalPlayerCount." players online</td></tr>";
		echo "</tfoot>\n";
		echo "</table>\n";
		echo "<br />\n";
print <<<HTML
</body>
</html>
HTML;
?>
