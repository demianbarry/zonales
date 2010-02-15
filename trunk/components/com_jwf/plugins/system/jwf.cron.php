<?php
/**
* A simple cron job manager
*
* @version		$Id$
* @package		Joomla
* @subpackage	JWF.core
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Crontab text file "Ini formatted", contains our cron job schedule
*/
define('JWF_CRON_CRONTAB', JWF_FS_PATH.DS.'cronjobs'.DS.'crontab.txt');

/**
 * A writable directory that cron job scheduler uses to manage jobs
*/
define('JWF_CRON_WRITEDIR',JWF_FS_PATH.DS.'cronjobs'.DS);

/**
 * Maximum number of jobs to perform per cycle (per page load)
*/
define('JWF_CRON_MAXJOBPERCYCLE', 1);

/**
 * Cron Job Handler
 *
 * @package    Joomla
 * @subpackage JWF.core
*/
class JWFCronJobManager{
	
	/**
	 * Parses a string and determines the amount of $unit in it
	 * for instance getTimeUnit( 's' , '15h4m5s' ) will return 5 because the string had 5s 
	 *
	 * @access	public
	 * @param string $unit the time unit to be detected
	 * @param string $text the string to be parsed
	 * @return int amount of $unit in $text
	 */
	 function getTimeUnit( $unit, $text ){
		if(strpos($text, $unit) === false)return 0;
		list($units) = explode($unit,$text);
		return intval($units);
	}

	/**
	 * Returns that last execution time of a given job
	 *
	 * @access	public
	 * @param string $jobName the name of the job in question
	 * @return int last execution time as unix time stamp
	 */
	 function getLastExecuteTime( $jobName ){
		if(!JFile::exists(JWF_CRON_WRITEDIR.$jobName.'.job'))return 0;
		return intval(JFile::read(JWF_CRON_WRITEDIR.$jobName.'.job'));
	}

	/**
	 * Sets the last execution time of a given job to NOW
	 *
	 * @access	public
	 * @param string $jobName the name of the job in question
	 * @return void
	 */
	function setLastExecuteTime( $jobName ){JFile::write( JWF_CRON_WRITEDIR.$jobName.'.job', time() );}
	
	/**
	 * Performs a check to see if a job is due to be executed
	 *
	 * @access	public
	 * @return void
	 */
	function check(){
		
		if( !file_exists( JWF_CRON_CRONTAB ))return;
		$cronJobs = new JRegistry();
		$cronJobs->loadINI(JFile::read(JWF_CRON_CRONTAB));
		$jobsArray = $cronJobs->toArray();
		$jobsDone  = 0;
		foreach($jobsArray as $jobName => $jobFrequency){
			$seconds = JWFCronJobManager::getTimeUnit('s',$jobFrequency);
			$minutes = JWFCronJobManager::getTimeUnit('m',$jobFrequency);
			$hours   = JWFCronJobManager::getTimeUnit('h',$jobFrequency);
			$days    = JWFCronJobManager::getTimeUnit('d',$jobFrequency);
			$weeks   = JWFCronJobManager::getTimeUnit('w',$jobFrequency);
			$frequency = $seconds + ($minutes * 60) + ($hours * 3600) + ($days * 86400) + ($weeks * 604800);
			$lastExecuteTime = JWFCronJobManager::getLastExecuteTime($jobName);
			$nextExecuteTime = $lastExecuteTime + $frequency;
			$doneEnoughJobs = $jobsDone >= JWF_CRON_MAXJOBPERCYCLE;	
			if(
				($lastExecuteTime == 0 || $nextExecuteTime <= time()) &&
				!$doneEnoughJobs && JWF_CRON_MAXJOBPERCYCLE != 0
				){
					JWFCronJobManager::setLastExecuteTime($jobName);
					call_user_func($jobName);
				$jobsDone++;
			}
		}
	}
}
