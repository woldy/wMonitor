<?php
	class mSystem{

		public static function monitor($system_config){
			global $mLog;
			global $mReport;
			if($system_config['enable']){		
				foreach ($system_config as $item => $value) {
					$i=0;
					for($i=0;$i<$system_config['retry'];$i++){
						$alert=self::alert($item,$value);
						if($alert){
							if($i==$system_config['retry']-1){
								$mReport->report($item,$system_config[$item]); //报告
								$mLog->info('report');
							}else{
								$mLog->info($item.'-'.$alert);
								sleep($system_config['sleep']);
								continue; //再来
							}
						}else{
							break;
						}
					}
				}
			}
		}


		public static  function alert($item,$value){
			if(method_exists('mSystem','get'.ucfirst($item))){
				//return 90+rand();
				$func='get'.ucfirst($item);
				$now=mSystem::$func();
				// var_dump($now);
				// var_dump($value);
				if($now>$value){
					return $now;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public  static  function getMemory(){
			$str=shell_exec("more /proc/meminfo");
			$mode="/(.+):\s*([0-9]+)/";
			preg_match_all($mode,$str,$arr);
			$pr=bcdiv($arr[2][1],$arr[2][0],3);
			$pr=$pr*100;
			//echo $pr."\n";
			return $pr;
		}

		public  static  function getCpu(){
			$mode = "/(cpu)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)/";
			$string=shell_exec("more /proc/stat");
			preg_match_all($mode,$string,$arr);
			//print_r($arr);
			$total1=$arr[2][0]+$arr[3][0]+$arr[4][0]+$arr[5][0]+$arr[6][0]+$arr[7][0]+$arr[8][0]+$arr[9][0];
			$time1=$arr[2][0]+$arr[3][0]+$arr[4][0]+$arr[6][0]+$arr[7][0]+$arr[8][0]+$arr[9][0];

			sleep(1);
			$string=shell_exec("more /proc/stat");
			preg_match_all($mode,$string,$arr);
			$total2=$arr[2][0]+$arr[3][0]+$arr[4][0]+$arr[5][0]+$arr[6][0]+$arr[7][0]+$arr[8][0]+$arr[9][0];
			$time2=$arr[2][0]+$arr[3][0]+$arr[4][0]+$arr[6][0]+$arr[7][0]+$arr[8][0]+$arr[9][0];
			$time=$time2-$time1;
			$total=$total2-$total1;
			//echo "CPU amount is: ".$num;
			$percent=bcdiv($time,$total,3);
			$percent=$percent*100;
			//echo $percent."\n";
			return $percent;
		}

		public  static  function getDisk(){
			  $fp = popen('df -lh',"r");
			  $rs = fread($fp,1024);
			  pclose($fp);
			  $rs = preg_replace("/\s{2,}/",' ',$rs);  //把多个空格换成 “_”
			  $hd = explode(" ",$rs);
			  $used=0;
			  foreach ($hd as $value) {
			  	if(strpos($value,'%')>0){
			  		if(explode('%',$value)[0]>$used){
			  			$used=explode('%',$value)[0];
			  		}
			  	}
			  }
			  //echo $used."\n";
			  return $used;
		}
	}