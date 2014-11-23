<?php
	
	$tmpl = "top";
	$configs['title']				= msg_menu_top_players;
	$configs['content']				= msg_menu_top_players;
	
	$configs['position']			= msg_top_position;
	$configs['status']				= msg_top_status;
	$configs['nick_name']			= msg_top_nick_name;
	$configs['base_level']			= msg_top_base_level;
    $configs['job_level']			= msg_top_job_level;
	$configs['job_name']			= msg_top_job_name;
	$configs['guild']				= msg_top_guild;
    
	$chars = Char::find('all', array('select' => '`name`, `base_level`, `job_level`, `class`, `guild_id`, `online`', 'order' => 'base_exp', 'limit' => 10));
	
	$i = 1;
	$list = array();
	foreach($chars as $char) { 
	
		$list[] = array( 
			iconv("cp1251", "utf-8", $char->name),
			$char->base_level,
			$char->job_level,
			$pro->get_class($char->class),
			$pro->get_guild($char->guild_id),
			($char->online) ? msg_char_status_1:msg_char_status_0,
			$i
		); 
					
		$i++;
	}
	$configs['list'] = $list;
	$template->render($tmpl,$configs);
?>