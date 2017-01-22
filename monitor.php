<?php
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
 

	while(true){
		//mSystem::monitor($system_config);
		mProcess::monitor();
		// mServer::monitor();
		// mWeb::monitor();

		sleep(1);
	}

