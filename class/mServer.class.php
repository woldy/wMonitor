<?php
	class mServer{

		public static function monitor($server_config){
			global $mLog;
			global $mReport;
			if($server_config['enable']){		
				foreach ($server_config['svr_list'] as $server => $ports) {
					$ports=explode(',',$ports);
					foreach ($ports as $port) {
						$i=0;
						for($i=0;$i<$server_config['retry'];$i++){
							$alert=self::alert($server,$port);
							if($alert){
								if($i==$server_config['retry']-1){
									$mReport->report("$server:$port",0); //报告
									$mLog->info('report');
								}else{
									$mLog->info("$server:$port");
									sleep($server_config['sleep']);
									continue; //再来
								}
							}else{
								break;
							}
						}
					}

				}
			}
		}


		public static  function alert($server,$port){
			if($port==0){
				return false;
			}
			$fp=@fsockopen($server,$port,$errno,$errstr,3);
			if($fp){
				return false;
			}else{
				return true;
			}
		}
	}
