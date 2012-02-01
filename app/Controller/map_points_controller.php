<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling business logic for map point interaction
*
*/

class MapPointsController extends AppController{
	
	//our vars
	public $name = 'MapPoint';
	public $components = array('Session');
	var $helpers = array('Js');
	
	//default, list the mapPoints in progress, in order of deadline
	public function index(){
		
		//our mapPoints
		$this->set('MapPoints', $this->MapPoint->find('all'));
	}
	
	//default, list the mapPoints in progress, in order of deadline
	public function view($id){
		
		//our mapPoints
		$this->MapPoint->id = $id;
		$this->set('MapPoint', $this->MapPoint->read());
	}
	
	//delete a task
	function delete($id){
		
		if ($this->request->is('get')){
			//uh...it shouldn't be.  This is not allowed.  Seriously.
			throw new MethodNotAllowedException();
		}
		
		//let's do this
		if ($this->MapPoint->delete($id)){
			$this->Session->setFlash('The map point with id: ' . $id . ' has been deleted.');
			$this->redirect(array('controller' => 'tasks', 'action' => 'index'));
		}
	}
}
?>