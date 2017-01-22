<?php
	class mLog{
		private $config;
		public  function __construct($_config){
			$this->config=$_config;
		}

		public function info($log){
			if(is_array($log) || is_object($log)){
				$log=json_encode($log);
			}

			$time=date("y-m-d h:I:s");
			if($this->config['console']){
				echo "wMonitor|info|{$time}|{$log}\n";
			}
		}

	}