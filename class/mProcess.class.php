<?php
	class mProcess{

		public static function monitor($process_config){
			global $mLog;
			global $mReport;
			if($process_config['enable']){		
				foreach ($process_config['proc_list'] as $process => $command) {
					$i=0;
					for($i=0;$i<$process_config['retry'];$i++){
						$alert=self::alert($process);
						if($alert){
							if($i==$process_config['retry']-1){
								$mReport->report($process,0); //报告
								$mLog->info('report');
								system($command);
							}else{
								$mLog->info($process.'-'.$alert);
								sleep($process_config['sleep']);
								continue; //再来
							}
						}else{
							break;
						}
					}
				}
			}
		}


		public static  function alert($process){
			$x=exec("ps -ef | grep {$process} | wc -l");
			if($x<3){
				return true;
			}else{
				return false;
			}
		}
	}


 