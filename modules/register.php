<?php
	
	$tmpl = "register";
	$configs['title']				= $template->load_lang_strings("msg_menu_register");
	$configs['content']				= $template->load_lang_strings("msg_menu_register");
	
	$configs['errors']				= "";
	$configs['success']				= "";
	$configs['user']				= "";
	$configs['email']				= "";
	
	
	if(!empty($_POST["submit"])) 
	{
		list($errors, $configs['success']) = $site->register($_POST);
		if(gettype($errors) == "array") 
		{
			$configs['errors'] = "<ul>";
			foreach($errors as $error) { $configs['errors'] .= "<li>{$error}</li>"; }
			$configs['errors'] .= "</ul>";
		}
		foreach($_POST as $key => $value) { $configs[$key] = $value; }
	}
	
	$template->render($tmpl,$configs);
?>