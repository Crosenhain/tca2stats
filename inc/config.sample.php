<?php

// Load classes
define ("CLASSES","./inc");
function __autoload($class_name)
{
    require_once CLASSES .'/'. strtolower($class_name) . '.class.php';
}

// MYSQL DB query settings
define ("STATS_DB_HOST","stats.database.host");
define ("STATS_DB_NAME","stats");
define ("STATS_DB_USER","stats");
define ("STATS_DB_PASS","password");

define ("TCA2_DB_HOST","tca2.database.host");
define ("TCA2_DB_NAME","tca2");
define ("TCA2_DB_USER","tca2");
define ("TCA2_DB_PASS","password");

?>
