tca2stats
=========

Statistics pages for TCAdmin 2

These are some basic statistics pages that utilise the statistics tcadmin2 spits out - although for the historical graphing I have created a separate table that I insert the live stats into every 15 minutes, the built-in historical statistics table is a bit messy. 

You will find the MySQL table structure in the etc folder.

Use this cron line:
*/15 * * * * /path/to/stats/scripts/collectcounts.php
