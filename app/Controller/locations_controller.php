<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling business logic for location interaction
*
*/

class LocationsController extends AppController{
	
	//our vars
	public $name = 'Location';
	public $components = array('Session');
	var $helpers = array('Js');
	
	//start logging when the data is in but before rendering
	function beforeRender(){
		$this->recordActivity();
	}
	
	//default, list the locations in progress, in order of deadline
	public function index(){
		
		//our locations
		$this->set('Locations', $this->Location->find('all'));
		
		//our map points
		$this->set('mapPoints', $this->Location->MapPoint->find('list'));
	}
	
	//adding a location
	public function add(){
		
		if($this->request->is('post')){
			//save and continue, or fail and tell 'em
			if($this->Location->save($this->request->data)){
				$this->Session->setFlash('Your location has been saved.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to add your location.');
			}
		}else if($this->request->data){
			$this->Session->setFlash('Unable to add your location.');
		}
	}
	
	//editing a location
	function edit($id = null){
		
		//set the vars
		$this->Location->id = $id;
		
		if ($this->request->is('get')){

			//set the vars
			$this->set('mapPoints', $this->Location->MapPoint->find('list'));
			$this->request->data = $this->Location->read();
		}else{
			
			//if it saves, continue, otherwise fail and tell 'em
			if ($this->Location->save($this->request->data)){
				
				//finish up
				$this->Session->setFlash('Your location has been updated.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to update your location.');
			}
		}
	}
	
	//delete a location
	function delete($id){
		
		if ($this->request->is('get')){
			//uh...it shouldn't be.  This is not allowed.  Seriously.
			throw new MethodNotAllowedException();
		}
		
		//let's do this
		if ($this->Location->delete($id)){
			$this->Session->setFlash('The location with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
?>