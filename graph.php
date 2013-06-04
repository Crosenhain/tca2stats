<?php

//Setting things up
require_once('inc/config.php');

if (is_numeric($_GET['sid'])) {
    $sid = $_GET['sid'];
} else {
    $sid = 10;
}

print <<<HTML
<!DOCTYPE html>
<head>
<title>Graphing game server stats</title>

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

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Date Time', 'Players'],
HTML;

$dbconn = mysql_connect(TCA2_DB_HOST,TCA2_DB_USER,TCA2_DB_PASS);
$db = mysql_select_db(TCA2_DB_NAME, $dbconn);

$sql = "SELECT CONVERT_TZ(h.detailed_date,'+00:00','+10:30') AS query_time,h.players,ls.name,ls.online,ls.max_players,g.display_name
FROM tc_game_service_detailed_stats h
INNER JOIN tc_game_services s ON h.service_id = s.service_id
INNER JOIN tc_game_service_live_stats ls ON h.service_id = ls.service_id
INNER JOIN tc_games g ON s.game_id = g.game_id
WHERE h.service_id = '$sid'
ORDER BY h.historical_date ASC;";

        $query = mysql_query($sql);

        while($row = mysql_fetch_assoc($query)) {
		echo "['".$row["query_time"]."',".$row["players"]."],";
		$server_name = $row["name"];
	}
	echo "]);";
	echo "var options = {";
	echo "title: 'Players on ".$server_name."',";
print <<<HTML
		hAxis: {title: 'Date Time',  titleTextStyle: {color: 'red'}}
	};
	var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	chart.draw(data, options);
        }
</script>

</head>
<body>
<div id="chart_div" style="width: 1200px; height: 500px;"></div>
</body>
</html>
HTML;
?>
