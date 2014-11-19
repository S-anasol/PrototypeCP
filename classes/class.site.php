<?php 
	
	class site {
		
		function __construct() {
			//echo "<pre>Îáúåêò site ñîçäàí\n</pre>";
		}
		
		function __destruct() {
		}
		
		public function register($vars)
		{
			global $pdb;
			
			$error = $success = "";
			$err = 0;
			extract($vars);
			
			if(!preg_match("/^[a-zA-Z0-9_]{3,16}$/", $user, $user_match)) 
			{
				$error[] = msg_reg_user_err;
				$err = 1;
			}
			else if($pdb->Query("select count(*) from `login` where `userid`='{$user}'") >= 1)
			{
				$error[] = msg_reg_user_taken_err;
				$err = 1;
			} 
			
			if(!preg_match("/^((?=.*[^\w\d\s])(?=.*\w)|(?=.*[\d])(?=.*\w)).{8,20}$/", $password, $pass_match)) 
			{
				$error[] = msg_reg_pass_err;
				$err = 1;
			}
			
			if($password != $password_rep) 
			{
				$error[] = msg_reg_pass_rep_err;
				$err = 1;
			}	
			
			if(!preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/", $email, $email_match)) 
			{
				$error[] = msg_reg_email_err;
				$err = 1;
			} 
			else if($pdb->Query("select count(*) from `login` where `email`='{$email}'") >= 1)
			{
				$error[] = msg_reg_email_taken_err;
				$err = 1;
			}
			
			if($err == 0) 
			{
				$password = md5($password);
				$sex = (rand(0,1)) ? "F":"M"; // random sex why not
				$pdb->Query("INSERT INTO `login` (`userid`, `user_pass`, `sex`, `email`) VALUES ('{$user}', '{$password}', '{$sex}','{$email}');");
				$success = msg_reg_success;
				return array($error, $success);
			} 
			else 
			{
				return array($error, $success);
			}
			
		}
		
		public function login($vars)
		{
			global $pdb, $session;
			
			$error = $success = "";
			$err = 0;
			extract($vars);
			$time = time();
			$ip = $_SERVER["REMOTE_ADDR"];
			
			if(!preg_match("/^[a-zA-Z0-9_]{3,16}$/", $user_name, $user_match)) 
			{
				$error[] = msg_login_user_err;
				$err = 1;
			}
			else 
			{
				
				$password = md5($password);
				$result = $pdb->Query("select * from `login` where `userid`='{$user_name}'");
				if($result == null || $password != $result->password) 
				{
					$error[] = msg_login_pas_user_err;
					$err = 1;
				} 
				else 
				{
					if($err != 1) 
					{ 
						$session->set("user", $result);
						$success = msg_login_success;
					}
				}
				
			}
			
			return array($error, $success);
			
			
			
		}
		
		public function logout()
		{
			global $session;
			$session->clean();
		}
		
		public function pager($rpp, $count, $href, $opts = array()) 
		{
			$pages = ceil($count / $rpp);
			
			if (!isset($opts['lastpagedefault']))
			{
				$pagedefault = 0;
			}
			else 
			{
				$pagedefault = floor(($count - 1) / $rpp);
				if ($pagedefault < 0)
				$pagedefault = 0;
			}
			
			if (isset($_GET['num_page'])) 
			{
				$page = 0 + (int) $_GET['num_page'];
				if ($page < 0)
				$page = $pagedefault;
			}
			else
			$page = $pagedefault;
			
			//$pager = "Ñòðàíèöû:";
			
			
			if ($count) {
				$pagerarr = array();
				$page_prev = $page-1;
				$page_next = $page+1;
				$pagerarr[] = "<ul>";
				if($page_prev >= 0) 
				{ 
					$pagerarr[] = "<li><a href=\"{$href}num_page={$page_prev}\" class=\"prev\"></a></li>"; 
				}
				else
				{
					$pagerarr[] = "<li><a class=\"prev\"></a></li>"; 
				}
				$pagerarr[] = "<li><span class=\"position\">{$page_next}/{$pages}</span></li>";
				if($page_next < $pages) 
				{ 
					$pagerarr[] = "<li><a href=\"{$href}num_page={$page_next}\" class=\"next\"></a></li>";
				}
				else
				{
					$pagerarr[] = "<li><a class=\"next\"></a></li>"; 
				}
				$pagerarr[] = "</ul>";
				$pagerstr = join("", $pagerarr);
				$pagertop = $pagerstr;
				$pagerbottom = $pagerstr;
			}
			else {
				$pagertop = $pagerbottom = "";
			}
			
			$start = $page * $rpp;
			
			return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
		}
		
		private function logs($action, $msg) {
			$filename = "log.txt";
			$fh = fopen($filename, "a+");
			fwrite($fh, $action.": ".$msg."\n");
			fclose($fh);
		}
	}
?>
