<?php
	class mWeb{

		public static function monitor($web_config){
			global $mLog;
			global $mReport;
			if($web_config['enable']){		
				foreach ($web_config['web_list'] as $url => $str) {
					$i=0;
					for($i=0;$i<$web_config['retry'];$i++){
						$alert=self::alert($url,$str);
						if($alert){
							if($i==$web_config['retry']-1){
								$mReport->report($url,0); //报告
								$mLog->info('report');
							}else{
								$mLog->info($url.'-'.$str);
								sleep($web_config['sleep']);
								continue; //再来
							}
						}else{
							break;
						}
					}
				}
			}
		}


		public static  function alert($url,$str){
		 	$html=@file_get_contents($url);
		 	if(strpos($html,$str)!==false){
		 		return false;
		 	}else{
		 		return true;
		 	}
 
		}
	}


 