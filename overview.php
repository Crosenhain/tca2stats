<?php
if (is_numeric($_GET['sid'])) {
    $sid = $_GET['sid'];
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

#hostnumbers {
        float: left;
        width: 200px;
        border: 1px solid black;
}

#gamenumbers {
        float: left;
        width: 300px;
        border: 1px solid black;
}
#gameplayers {
        float: left;
        width: 450px;
        border: 1px solid black;
}
.clear { clear: both;}
div.container {
    z-index:10;
    position:absolute;
    right:0;
    top:0;
    width:350px;
}
div.individualservers {
    z-index:9;
    position:absolute;
    top:500;
    background-color: #FFFFFF;
    border: 2px solid grey;
}
</style>

<script type="text/javascript" src="ajax.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
        google.load('visualization', '1', {packages: ['annotatedtimeline']});
        function drawVisualization() {
                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Players');

HTML;
$db_conn_user="";
$db_conn_pass="";
$db_conn_db="";
$db_conn_host="";

$dbconn = mysql_connect($db_conn_host,$db_conn_user,$db_conn_pass);
$db = mysql_select_db($db_conn_db, $dbconn);

if ($_GET['sid']) {
        $sql = "SELECT datetime,SUM(players) AS players,SUM(max_players)
                FROM harvest
                WHERE service_id = '".$sid."'
                AND datetime > (ADDDATE(CURDATE(),(-30)))
                GROUP BY datetime
                ORDER BY datetime;";
} elseif ($_GET['game_name']) {
        $game_name_sql = htmlentities($_GET['game_name']);
        $sql = "SELECT s.datetime AS datetime,display_name,SUM(s.players) AS players,SUM(s.max_players) 
                FROM stats.harvest s
                INNER JOIN tcadmin2.tc_game_services gs ON s.service_id = gs.service_id
                INNER JOIN tcadmin2.tc_games g ON gs.game_id = g.game_id
                WHERE g.display_name = '".$game_name_sql."'
                AND s.datetime > (ADDDATE(CURDATE(),(-30)))
                GROUP BY s.datetime
                ORDER BY s.datetime;";
} else {
        $sql = "SELECT datetime,SUM(players) AS players,SUM(max_players)
                FROM harvest
                WHERE service_id != '276'
                AND datetime > (ADDDATE(CURDATE(),(-30)))
                GROUP BY datetime
                ORDER BY datetime;";
}

        echo "data.addRows([\n";
        $query = mysql_query($sql);
        while($row = mysql_fetch_assoc($query)) {
                echo "[new Date('".date("G:i m d, Y", strtotime($row["datetime"]))."'),".$row["players"]."],\n";
                $server_name = $row["name"];
        }
        echo "]);\n";
print <<<HTML
        var annotatedtimeline= new google.visualization.AnnotatedTimeLine(
                document.getElementById('chart_div'));
        annotatedtimeline.draw(data, {'displayAnnotations': true});
        }

        google.setOnLoadCallback(drawVisualization);
</script>

</head>
<body>
<h2><a href="overview.php">Players on TCAdmin 2 game servers:</a></h2>
<div id="chart_div" style="width: 1000px; height: 500px;"></div>
HTML;
if (isset($_GET['sid'])){
        $gamenamesql = "SELECT name FROM `tcadmin2`.`tc_game_service_live_stats` WHERE service_id = '".$sid."';";
        $gamenamequery = mysql_query($gamenamesql);
        while ($row = mysql_fetch_assoc($gamenamequery)) {
            echo $row['name']."<br />\n";
        }
}

if (isset($_GET['game_name'])) {
    echo $_GET['game_name']."<br />\n";
}

$gameservicesql = "SELECT COUNT( ts.service_id ) AS service_count , tg.display_name
FROM  `tcadmin2`.`tc_game_services` AS ts,  `tcadmin2`.`tc_games` AS tg
WHERE ts.game_id = tg.game_id
AND ts.private != '1'
GROUP BY tg.display_name
ORDER BY COUNT( ts.service_id ) DESC;";
$gameservices="";
$gameservicequery=mysql_query($gameservicesql);
while ($row = mysql_fetch_assoc($gameservicequery)) {
        $gameservices .= "<tr><td><a href=\"overview.php?game_name=".$row['display_name']."\">".$row['display_name']."</a></td><td>".$row['service_count']."</td><td><input value=\"+\" type=\"button\" onclick=\"getGameList('".$row['display_name']."')\"></input></td></tr>\n";
        //Maybe for each game, have an expandable list of services, each linking to graphs for that service
}
print <<<HTML
<div class="individualservers" id="individualservers">
</div>

<div class="container" id="container">
<div id="gamenumbers">
<table>
<tr><th>Game</th><th>Service Count</th><th></th></tr>
$gameservices
</table>
</div>
HTML;
$sqlhosts = "SELECT srv.display_name, COUNT(svc.service_id) AS service_count
FROM `tcadmin2`.`tc_services` AS svc RIGHT JOIN `tcadmin2`.`tc_servers` AS srv ON srv.server_id = svc.server_id
GROUP BY srv.server_id ORDER BY COUNT(svc.service_id) DESC;";
$gamehosts="";
//$hostsquery=mysql_query($sqlhosts);
while ($row = mysql_fetch_assoc($hostsquery)) {
        $gamehosts .= "<tr><td>".$row['display_name']."</a></td><td>".$row['service_count']."</td></tr>\n";
}
print <<<HTML
<div id="hostnumbers" style="display: none;">
<table>
<tr><th>Server</th><th>Service Count</th></tr>
$gamehosts
</table>
</div>
HTML;
$gameplayerssql="SELECT display_name,SUM(ls.players) AS playercount,SUM(ls.max_players) AS maxplayercount FROM tcadmin2.tc_game_services s
INNER JOIN tcadmin2.tc_games g ON s.game_id = g.game_id
INNER JOIN tcadmin2.tc_game_service_live_stats ls ON s.service_id = ls.service_id
WHERE s.private = 0
GROUP BY display_name
ORDER BY SUM(ls.players) DESC;";
$gplayers="";
//$gpquery=mysql_query($gameplayerssql) or die ("Fatal Error: Query Failed: ".mysql_error());
while ($row = mysql_fetch_assoc($gpquery)) {
        $gplayers .= "<tr><td><a href=\"overview2.php?game_name=".$row['display_name']."\">".$row['display_name']."</a></td><td>".$row['playercount']."</td><td>".$row['maxplayercount']."</tr>\n";
}
print <<<HTML
<div id="gameplayers" style="display: none;">
<table>
<tr><th>Game</th><th>Player Count</th><th>Max Possible Player Count</th></tr>
$gplayers
</table>
</div>
</div>
<div class="clear"></div>
</body>
</html>
HTML;
?>

