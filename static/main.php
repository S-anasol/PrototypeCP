<?php
	
	$configs['charset']        = 'utf8'; 
	$configs['site_name']      = 'Ragnarok Online';
	
	$configs['menu']       = '
	<li><a href="./">'.msg_menu_home.'</a></li>
	<li>
	<a href="">'.msg_menu_tops.'</a>
	<ul>
	<li><a href="./?p=top">'.msg_menu_top_players.'</a></li>
	<li><a href="">'.msg_menu_top_guilds.'</a></li>
	</ul>
	</li>
	<li>
	<a href="">'.msg_menu_woe.'</a>
	<ul>
	<li><a href="">'.msg_menu_woe_time.'</a></li>
	<li><a href="">'.msg_menu_castles.'</a></li>
	</ul>
	</li>
	<li><a href="./?p=about">'.msg_menu_about.'</a></li>
	<li><a href="">'.msg_menu_contacts.'</a></li>
	<li><a href="./?p=hehey">хехей</a></li>
	';
	
	$configs['online'] = $pro->get_online();
	
?>
