#!/usr/bin/env php
<?php
	if(in_array('init',$argv)){
		error_reporting(0);
	}
	
	require_once('./class/mSystem.class.php');
	require_once('./class/mProcess.class.php');
	require_once('./class/mServer.class.php');
	require_once('./class/mWeb.class.php');
	require_once('./class/mReport.class.php');
	require_once('./class/mLog.class.php');

	$system_config=include_once('./config/system.config.php');
	$process_config=include_once('./config/process.config.php');
	$server_config=include_once('./config/server.config.php');
	$web_config=include_once('./config/web.config.php');
	$report_config=include_once('./config/report.config.php');
	$log_config=include_once('./config/log.config.php');

	global $mReport;
	global $mLog;

	$mReport=new mReport($report_config,$system_config['name']);
	$mLog = new mLog($log_config);
 	$mCore=new mCore();

 	if(in_array('stop',$argv)){
 		$mCore->stop();
 	}else if(in_array('restart',$argv)){
 		$mCore->restart();
 	}else{
 		$mCore->start();
 	}
 	
	if(count($argv)>3 && $argv[1]=='config'){
		 if(isset(${$argv[2].'_config'})){
		 	$config=${$argv[2].'_config'};

		 	$type=$argv[2];
		 	$value=end($argv);
		 	$keys=$argv;
		 	array_pop($keys);
		 	array_shift($keys);
		 	array_shift($keys);
		 	array_shift($keys);

		 	$var='';
		 	foreach ($keys as $key) {
		 		$var="{$var}['{$key}']";
		 	}

		 	eval ("\$config$var={$value};");


		 	$config_text="<?php \nreturn ".var_export($config,true).';'; 
		 	$file_path="./config/{$type}.config.php";

		 	file_put_contents($file_path, $config_text);

		 	echo "{$type}_config_now:\n";
		 	var_dump($config);
		 }
		 exit;
	}else if(in_array('init',$argv)){
		if(!file_exists('./config')){
			system('cp -r ./config_default/ ./config/');
		}
		echo "init done!\n";
		exit;
	}else{
		ignore_user_abort(true);
		while(true){
			mSystem::monitor($system_config);
			mProcess::monitor($process_config);
			mServer::monitor($server_config);
			mWeb::monitor($web_config);

			sleep(30);
		}			
	}


