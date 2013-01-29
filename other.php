<?php

$db = mysql_connect("localhost:3307", "root", "root") or die("Ошибка подключения к базе данных"); 
mysql_select_db("ragnarok",$db);

$query = mysql_query("select sum(online)/count(*) as av_on from `chart`");
$row = mysql_fetch_array($query);

echo $row[0];
 

?>