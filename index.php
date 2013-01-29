<?php
	ini_set('display_errors', 1);
	include("./include/config.php");
	
	include("./classes/class.templating.php");
	include("./classes/class.ragnarok.php");
	include("./classes/class.mysql.php");
	
	include("./language/".site_default_lang."_lang.php");
	//
	$pdb = new MySQL();
	$pro = new ragnarok();
	
	if(config_edited === true) 
	{
		
		if(isset($_GET["p"]))
		{
			$page = $_GET["p"];
		} else 
		{ 
			$page = "home"; 
		}
		
		if(file_exists("modules/{$page}.php"))
		{ 
			include "modules/{$page}.php"; 
		} else 
		{ 
			include "modules/404.php"; 
		}
		include("./static/main.php");
		new html_generator($configs, site_default_template, $tmpl);
		
	} else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf8" />';
		echo msg_error_config_not_set;
		
	}
	
	function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

	
?>