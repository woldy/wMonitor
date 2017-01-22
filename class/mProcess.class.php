<?php
	class mProcess{

		public static function monitor($process_config){
			global $mLog;
			global $mReport;
			if($process_config['enable']){		
				foreach ($process_config['proc_list'] as $process => $command) {
					$alert=self::alert($item,$value);
					if($alert){
						$mReport->report($item,$process_config[$item]); //报告
						$mLog->info('report');
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