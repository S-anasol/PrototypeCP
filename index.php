<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	include("./vendor/autoload.php");
	include("./include/config.php");
	
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
	
	Twig_Autoloader::register(true);
	$loader = new Twig_Loader_Filesystem('templates/'.site_default_template.'/');
    $twig = new Twig_Environment($loader, array(/*'cache' => PATH_CACHE,*/ 'debug' => true));
	$twig->addExtension(new Twig_Extension_Debug());
	
	class Player extends ActiveRecord\Model { static $table_name = 'login'; }
	class Char extends ActiveRecord\Model { static $table_name = 'char'; }
	
	ActiveRecord\Config::initialize(function($cfg)
	{
		$cfg->set_model_directory('.');
		$cfg->set_connections(array('development' => 'mysql://'.mysql_user.':'.mysql_password.'@'.mysql_host.'/'.mysql_ragnarok_database.''));
	});
	
	include("./classes/class.templating.php");
	include("./classes/class.ragnarok.php");
	include("./classes/class.site.php");
	include("./classes/class.mysql.php");
	
	include("./language/".site_default_lang."_lang.php");
	//
	$pdb = new MySQL();
	$pro = new ragnarok();
	$site = new site();
	$template = new Template();
	
	if(config_edited === true) 
	{
		include("./static/main.php");
		
		if(isset($_GET["p"]))
		{
			$page = $_GET["p"];
		} 
		else 
		{ 
			$page = "home"; 
		}
		
		if(file_exists("modules/{$page}.php"))
		{ 
			include "modules/{$page}.php"; 
		} 
		else 
		{ 
			include "modules/404.php"; 
		}
		//$template->start($configs, site_default_template, $tmpl);
		
	} 
	else 
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