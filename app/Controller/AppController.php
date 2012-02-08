<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* universal app functions
*
*/

App::uses('Controller', 'Controller');

class AppController extends Controller {
	
	//set up the Activity model
    var $uses = array('Activity');
	
	//function for logging all db-based interactions
	function recordActivity(){

		//make it work with mod_rewrite
		$pages = str_replace("/index.php",'', $_SERVER['PHP_SELF']);
		$pages = explode("/", $pages);
		
		//our data
		$this->request->data['Activity']['action'] = $this->request->url;
		//these might not be set server side, so we're protecting them with an if wrap.  Mmm, if wrap.
		if(isset($_SERVER['REMOTE_ADDR'])){
			$this->request->data['Activity']['user_ip'] = $_SERVER['REMOTE_ADDR'];
		}
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$this->request->data['Activity']['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
		}
		if(isset($_SERVER['HTTP_REFERER'])){
			$this->request->data['Activity']['clicked_from'] = $_SERVER['HTTP_REFERER'];
		}
		//'created' is automatic!
		
		//our query data
		$noLogs = !isset($logs);
		if($noLogs){
			$sources = ConnectionManager::sourceList();
			$logs = array();
			foreach($sources as $source){
				$db = ConnectionManager::getDataSource($source);
				if (!method_exists($db, 'getLog')):
					continue;
				endif;
				$logs[$source] = $db->getLog();
			}
		}
		$this->request->data['Activity']['sql'] = serialize($logs);
		
		//set it
		$this->Activity->save($this->request->data);
		
		//Nuke the data, just in case.
		unset($this->request->data['Activity']);
	}
}
