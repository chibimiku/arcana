<?php 

if(!defined('IN_ARCANA')){
	exit('Access Denied.');
}

//crontab 定期检查自己有没有未执行的cron并开跑
//这个cron机制写的有点弱智，先凑合着用了
$run_script = array('dayhit', 'weekhit', 'monthhit');
foreach($run_script as $row){
	check_cron_need_run($row);
}

function check_cron_need_run($name){
	$allow_script = array('dayhit', 'weekhit', 'monthhit');
	if(!in_array($name, $allow_script)){
		return false;
	}
	$rs = DB::queryFirstRow('SELECT * FROM '.table('cron')." WHERE m_name=%s", $name);
	if(!$rs){
		return false;
	}
	if(TIMESTAMP - $rs['m_last_run'] > $rs['m_run_interval']){
		//执行cron
		switch($name){
			case 'dayhit':
				cron_clean_dayhit();
				break;
			case 'weekhit':
				cron_clean_weekhit();
				break;
			case 'monthhit':
				 cron_clean_monthhit();
				break;
			default:
				return false;
		}
		return true; //确实执行了的话返回true
	}
	return false;
}

function cron_clean_dayhit(){
	global $config;
	DB::query('UPDATE '.table('data')." SET m_dayhit=0 WHERE 1=1");
	DB::update(table('cron'), array('m_last_run' => TIMESTAMP), 'm_name=%s', 'dayhit');
	return true;
}

function cron_clean_weekhit(){
	global $config;
	DB::query('UPDATE '.table('data')." SET m_weekhit=0 WHERE 1=1");
	DB::update(table('cron'), array('m_last_run' => TIMESTAMP), 'm_name=%s', 'weekhit');
	return true;
}

function cron_clean_monthhit(){
	global $config;
	DB::query('UPDATE '.table('data')." SET m_monthhit=0 WHERE 1=1");
	DB::update(table('cron'), array('m_last_run' => TIMESTAMP), 'm_name=%s', 'monthhit');
	return true;
}

?>