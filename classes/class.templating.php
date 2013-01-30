<?php 
	
	class html_generator {
		public $buffer_layout, $debug;
		private $buffer_loop, $buffer, $buffer_while, $buffer_if, $configs, $start, $end, $start_if, $end_if, $else_if, $tmpl, $name;
		public $loops = 0;
		
		function start($conf, $tmpl, $name) 
		{
			global $start_time, $pdb;
			$this->configs = $conf;
			$this->tmpl = $tmpl;
			$this->name = $name;
			
			$end_time = microtime();
			$end_array = explode(" ",$end_time);
			$end_time = $end_array[1] + $end_array[0];
			$time = $end_time - $start_time;
			$this->configs["stats"] = "<div style=\"text-align: center; color: #999; line-height: 38px; vertical-align: middle;\">Page generated in <span style=\"color: #00ff00;\">{$time}</span> seconds, DB Query count: <span style=\"color: #00ff00;\">{$pdb->query_count}</span></div>";
			$this->load_vars($this->tmpl, $this->name);
			while(strpos($this->buffer_layout, "{if") >= 1) {
				$this->if_cond();
			}
			$this->load_lang_strings();
			$this->output();
		}
		
		private function load_vars($tmpl, $name) 
		{
			$this->configs['style_dir'] = './templates/'.$tmpl.'/';
			if(file_exists('./templates/'.$tmpl.'/_parts/'.$name.'.html')) 
			{
				$this->buffer = file_get_contents('./templates/'.$tmpl.'/_parts/'.$name.'.html');
			} 
			else 
			{
				$this->buffer = "Шаблон `/templates/{$tmpl}/_parts/{$name}.html` не найден";
				self::ShowDebug("Сообщение шаблонизатора: шаблон `/templates/{$tmpl}/_parts/{$name}.html` не найден");
			}
			
			$this->buffer_layout = file_get_contents('./templates/'.$tmpl.'/layout.html');
			$this->buffer_layout = str_replace('{body}', $this->buffer, $this->buffer_layout);
			foreach($this->configs as $parameter => $value) 
			{
				if(gettype($value) != "array") 
				{
					$this->buffer_layout = str_replace('{'.$parameter.'}', $value, $this->buffer_layout);
				} 
				else 
				{ 
					$this->loop(); 
					$this->loops++;
				}
			}
		}
		
		
		private function loop()
        {
            $this->start = strpos($this->buffer_layout, "{loop=");
            $this->end = strpos($this->buffer_layout, "{/loop}");
            if($this->end > $this->start) {
				$this->buffer_loop = substr($this->buffer_layout, $this->start, ($this->end-$this->start));
				$before = substr($this->buffer_layout, 0, $this->start);
				$after = substr($this->buffer_layout, $this->end);
				
				if (preg_match("/\\{\bloop=(\d+)\\}/", $this->buffer_loop, $matches)) {
					$loop_times = $matches[1];
				}
				
				foreach ($this->configs as $parameter=>$value)
				{   
					if(gettype($value) == "array") 
					{
						if($loop_times > count($value[1])) 
						{
							$loop_times = count($value[1]); 
						}
					}
				}
				$this->buffer_loop = preg_replace("/{loop=(\d+)}/", "",$this->buffer_loop, 1);
				$after = preg_replace("({/loop})", "",$after, 1);
				$rep=0;	
				while($rep <= $loop_times-1)
				{
					$this->buffer_while .= $this->buffer_loop;
					$rep++;
				}
				
				foreach ($this->configs as $parameter=>$value)
				{   
					if(gettype($value) == "array") {
						//print_r($value);
						$looped=0;
						while($looped <= count($value[1][0])-1) {
							$i=0;
							foreach ($value[1] as $parameter_a=>$value_a)
							{   
								$this->buffer_while = preg_replace("/\{\b$parameter\[(i)\]\.$looped\}/", $value[1][$i][$looped],$this->buffer_while, 1);
								$i++;
							}
							$looped++;
						}
						
					}
				}
				
				$this->buffer_layout = $before.$this->buffer_while.$after;
				
				$before="";
				$after="";
				$this->buffer_while="";
				
			}
		}
		
		private function if_cond()
        {
            $this->start_if = strpos($this->buffer_layout, "{if");
			$this->else_if = strpos($this->buffer_layout, "{else}");
            $this->end_if = strpos($this->buffer_layout, "{/if}");
			
            if($this->end_if > $this->else_if && $this->else_if > $this->start_if) {
				$this->buffer_if = substr($this->buffer_layout, $this->start_if, ($this->end_if-$this->start_if));
				$before = substr($this->buffer_layout, 0, $this->start_if);
				$after = substr($this->buffer_layout, $this->end_if);
				
				if (preg_match("/\\{\bif (\w+)\\}/", $this->buffer_if, $matches)) {
					$name = $matches[1];
				}
				
				$this->buffer_if = preg_replace("/{if (\w+)}/", "",$this->buffer_if, 1);
				$after = preg_replace("({/if})", "",$after, 1);
				$vars = explode("{else}", $this->buffer_if);
				
				if($this->configs[$name]) {
					//if true
					$result = $vars[0];
					} else {
					//if false
					$result = $vars[1];
				}
				
				
				
				$this->buffer_layout = $before.$result.$after;
				$before="";
				$after="";
				$this->buffer_if="";
				
			}
		}
		
		public function load_lang_strings($string = false)
		{
			if($string)
			{
				if(defined($string))
				{
					return constant($string);
				}
				else 
				{
					self::ShowDebug("Сообщение шаблонизатора: Фраза {$string} не найдена");
					return $string;
				}
			}
			else
			{
				while(strpos($this->buffer_layout, "{msg") >= 1) 
				{
					if (preg_match("/\\{\bmsg (\w+)\\}/", $this->buffer_layout, $matches)) 
					{
						if(defined($matches[1]))
						{
							$this->buffer_layout = preg_replace("/{msg (\w+)}/", constant($matches[1]),$this->buffer_layout, 1);
						}
						else 
						{
							$this->buffer_layout = preg_replace("/{msg (\w+)}/", $matches[1],$this->buffer_layout, 1);
							self::ShowDebug("Сообщение шаблонизатора: Фраза {$matches[1]} не найдена");
						}
					}
				}
			}
		}
		
		private function ShowDebug($string = ""){
			$this->debug .= $string."\r\n";
		}
		
		private function output() 
		{
			if($this->debug) 
			{
				echo "<pre>{$this->debug}</pre>";
			}
			
			echo $this->buffer_layout;
		}
		
	}
	
?>
