<?php
	
	//stat
	$start_time = microtime();
	$start_array = explode(" ",$start_time);
	$start_time = $start_array[1] + $start_array[0];
	//stat
	
	//Site settings
	define("site_default_lang",				"ru",       		true);
	define("site_default_template",			"prototype",		true);
	
	//Database Settings
	define("mysql_host",					"127.0.0.1:3306",	true);
	define("mysql_user",					"root",				true);
	define("mysql_password",				"",				true);
	define("mysql_ragnarok_database",		"san",		true);
	define("mysql_site_database",			"san",		true);
	
	//Server Setttings
	define("server_host",					"127.0.0.1",		true);
	define("server_map",					5121,				true);
	define("server_char",					6121,				true);
	define("server_login",					6900,				true);
	
	//Other
	define("emblem_lifetime",				43200,				true); // update guild emblems once at 12h (`guild` folder)
	
	//configuration was edited
	define("config_edited",					false,				true);
	
?>
