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
					if($value>0){
						$text="[alert]{$this->name}'{$item} > {$value}  ".date("Y-m-d H:i:s");
					}else{
						$text="[alert]{$this->name}'{$item} down! ".date("Y-m-d H:i:s");
					}
					

					$this->$func($text,$rConfig);
				}
			}
		}

 		public function rDing($text,$config){
 			$url  = "https://oapi.dingtalk.com/robot/send?access_token={$this->config['ding']['token']}";  
			$jsonstr = json_encode(['msgtype'=>'text','text'=>['content'=>$text]]);
			list($returncode,$returncontent)=$this->http_post_json($url,$jsonstr);  
 			return true;
 		}


 		public function http_post_json($url, $data) {
 			// var_dump($url);
 			// var_dump($data);
	        $ch = curl_init();  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_POST, 1);  
	        curl_setopt($ch, CURLOPT_URL, $url);  
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
	            'Content-Type: application/json; charset=utf-8',  
	            'Content-Length: ' . strlen($data))  
	        );  
	        ob_start();  
	        curl_exec($ch);  
	        if(curl_errno($ch)){
			    echo 'Curl error: ' . curl_error($ch);
			}

	        $return_content = ob_get_contents();  
	        ob_end_clean();  
	  
	        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
	        // var_dump($return_content);
	        return array($return_code, $return_content);  
    	}  
 
	}