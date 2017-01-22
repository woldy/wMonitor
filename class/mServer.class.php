<?php
	class mServer{

		public static function monitor($system_config){
			if($system_config['enable']){
				$system_config_count=[];
				for($i=0;$i<$system_config['retry'];$i++){
					foreach ($system_config as $item => $value) {
						if(!isset($system_config_count[$item])){
							$system_config_count[$item]=0;
						}


						$check=self::check($item,$value);
						if(!$check){
							$system_config_count[$item]=$system_config_count[$item]+1;
						}
					}	

					sleep($system_config['sleep']);
				}

				foreach ($system_config_count as $key => $value) {
					if($value==$system_config['retry']){
						$mReport->report($key,$system_config[$key]);

					}
				}

			}
		}


		public static  function check($item,$value){
			//echo 'get'.ucfirst($item)."\n";
			if(method_exists('mSystem','get'.ucfirst($item))){
				$func='get'.ucfirst($item);
				$now=mSystem::$func();
				// var_dump($now);
				// var_dump($value);
				if($now>$value){
					return false;
				}else{
					return true;
				}
			}else{
				return true;
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