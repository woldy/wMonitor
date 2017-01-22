<?php
	class mReport{
		private $config;
		private $name;
		public  function __construct($_config,$_name){
			$this->config=$_config;
			$this->name=$_name;
		}


		public  function report($item,$value){
			foreach ($this->config as $rKey => $rConfig) {
				if($rConfig['enable']==true && method_exists('mReport','r'.ucfirst($rKey))){
					$func='r'.ucfirst($rKey);
					$text="[alert]{$this->name}'{$item} > {$value}  ".date("Y-m-d H:i:s");
					$this->$func($text,$rConfig);
				}
			}
		}

 		public function rDing($text,$config){
 			$text=base64_encode($text);
 			file_get_contents("http://ops.100tal.com/ding/api/sendmsg?user=wlcp&password=wlcp@dingdingApi&emails=weizhiwei@100tal.com&content=$text");
 		}
	}