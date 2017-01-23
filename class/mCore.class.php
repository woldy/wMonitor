<?php
	class mCore{
 		private $pidfile='/var/run/wMonitor.pid';

 		public function start(){
			if (file_exists($this->pidfile)) {
				echo "The file $this->pidfile exists.\n";
				exit();
			}
			
			$pid = pcntl_fork();
			if ($pid == -1) {
				 die('could not fork');
			} else if ($pid) {
				exit($pid);
			} else {
				file_put_contents($this->pidfile, getmypid());
				posix_setuid(self::uid);
				posix_setgid(self::gid);
				return(getmypid());
			}
 		}

 		public function stop(){
			if (file_exists($this->pidfile)) {
				$pid = file_get_contents($this->pidfile);
				posix_kill($pid, 9); 
				unlink($this->pidfile);
			}
 		}

 		public function restart(){
 			$this->stop();
 			$this->start();
 		}

	}