<?php
	
	$tmpl = "test";
	$configs['title']          = 'Имя сайта - О нас';
	$configs['content']        = 'Тут сопливая история про вылупление этого проекта из пальца.';
	
	$template->render($tmpl,$configs);
?>