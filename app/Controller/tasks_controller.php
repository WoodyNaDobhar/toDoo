<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling business logic for task interaction
*
*/

class TasksController extends AppController{
	
	//our vars
	public $name = 'Task';
	var $helpers = array('Html', 'Form', 'Js');
	public $components = array('Session', 'RequestHandler');
	
	//start logging when the data is in but before rendering
	function beforeRender(){
		$this->recordActivity();
	}
	
	//default, list the tasks in progress, in order of deadline
	public function index(){
		
		//our tasks
		$this->set('tasks', $this->Task->find('all', array(
			'conditions' => array('Task.status_id' => '1'),
			'order' => array('Task.deadline'),
		)));
		
		//our statuses
		$this->set('statuses', $this->Task->Status->find('list'));
		
		//our locations
		$this->set('locations', $this->Task->Location->find('list'));
		
		//our keywords
		$this->set('keywords', $this->Task->Keyword->find('list'));
		
		//our link locations
		$linkLocation['/foundersFactory/tasks/'] = "Show by Location";
		foreach($this->Task->Location->find('list') as $key => $location){
			$linkLocation['/foundersFactory/tasks/showByLocation/'.$key] = $location;
		}
		$this->set('linkLocations', $linkLocation);
	}
	
	//list the tasks completed, in order of completion
	public function showComplete(){
		
		//our tasks
		$this->set('tasks', $this->Task->find('all', array(
			'conditions' => array('Task.status_id' => '2'),
			'order' => array('Task.completed_on'),
		)));
		
		//our statuses
		$this->set('statuses', $this->Task->Status->find('list'));
		
		//our locations
		$this->set('locations', $this->Task->Location->find('list'));
		
		//our keywords
		$this->set('keywords', $this->Task->Keyword->find('list'));
		
		//our link locations
		$linkLocation['/foundersFactory/tasks/'] = "Show by Location";
		foreach($this->Task->Location->find('list') as $key => $location){
			$linkLocation['/foundersFactory/tasks/showByLocation/'.$key] = $location;
		}
		$this->set('linkLocations', $linkLocation);
	}
	
	//list the tasks by location, in order of deadline
	public function showByLocation($id){
		
		//our tasks
		$this->set('tasks', $this->Task->find('all', array(
			'conditions' => array('Task.location_id' => $id),
			'order' => array('Task.deadline'),
		)));
		
		//our statuses
		$this->set('statuses', $this->Task->Status->find('list'));
		
		//our locations
		$this->set('locations', $this->Task->Location->find('list'));
		
		//our keywords
		$this->set('keywords', $this->Task->Keyword->find('list'));
		
		//our link locations
		$linkLocation['/foundersFactory/tasks/'] = "Show by Location";
		foreach($this->Task->Location->find('list') as $key => $location){
			$linkLocation['/foundersFactory/tasks/showByLocation/'.$key] = $location;
		}
		$this->set('linkLocations', $linkLocation);
	}
	
	//list all the tasks, in order of entry
	public function showAll(){
		
		//our tasks
		$this->set('tasks', $this->Task->find('all', array(
			'order' => array('Task.created_on'),
		)));
		
		//our statuses
		$this->set('statuses', $this->Task->Status->find('list'));
		
		//our locations
		$this->set('locations', $this->Task->Location->find('list'));
		
		//our keywords
		$this->set('keywords', $this->Task->Keyword->find('list'));
		
		//our link locations
		$linkLocation['/foundersFactory/tasks/'] = "Show by Location";
		foreach($this->Task->Location->find('list') as $key => $location){
			$linkLocation['/foundersFactory/tasks/showByLocation/'.$key] = $location;
		}
		$this->set('linkLocations', $linkLocation);
	}
	
	//view just the one
	public function view($id = null){
		$this->Task->id = $id;
		$this->set('task', $this->Task->read());
	}
	
	//adding a task
	public function add(){
		
		//set our vars
		$this->set('locations', $this->Task->Location->find('list'));
		$this->set('keywords', $this->Task->Keyword->find('list'));
		
		if($this->request->is('post')){
			//save and continue, or fail and tell 'em
			if($this->Task->save($this->request->data)){
				$this->Session->setFlash('Your task has been saved.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to add your task.');
			}
		}else if($this->request->data){
			$this->Session->setFlash('Unable to add your task.');
		}
	}
	
	//editing a task
	function edit($id = null){
		
		//set the vars
		$this->Task->id = $id;
		$timestamp = date('Y-m-d H:i:s');
		
		if($this->request->is('get')){

			//set the vars
			$this->set('statuses', $this->Task->Status->find('list'));
			$this->set('locations', $this->Task->Location->find('list'));
			$this->set('keywords', $this->Task->Keyword->find('list'));
			$this->request->data = $this->Task->read();
		}else{
			
			//if it saves, continue, otherwise fail and tell 'em
			if($this->Task->save($this->request->data)){
				
				//if the status is set to in progress, we need to clear the timestamp
				if($this->request->data['Task']['status_id'] == 1){
					$this->Task->saveField('completed_on', '0000-00-00 00:00:00');
				}
				
				//if the status is set to complete, we need to add the timestamp
				if($this->request->data['Task']['status_id'] == 2){
					$this->Task->saveField('completed_on', $timestamp);
				}
				
				//finish up
				$this->Session->setFlash('Your task has been updated.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to update your task.');
			}
		}
	}
	
	//delete a task
	function delete($id){
		
		if($this->request->is('get')){
			//uh...it shouldn't be.  This is not allowed.  Seriously.
			throw new MethodNotAllowedException();
		}
		
		//let's do this
		if($this->Task->delete($id)){
			$this->Session->setFlash('The task with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}

	//autocomplete search function
	function search(){
		
		//if we've got ajax...
		if( $this->RequestHandler->isAjax() ){
			
			//ja, debug ees gûd
   			Configure::write('debug', 2);
			
			//set it up
   			$this->autoRender = false;
			$keywords = $this->Task->Keyword->find('all',array('conditions'=>array('Keyword.name LIKE'=>'%'.$_GET['term'].'%')));
			$i=0;
			
			//draw it out
			foreach($keywords as $keyword){
				$response[$i]['value']=$keyword['Keyword']['name'];
				$response[$i]['label']="<span class=\"name\">".$keyword['Keyword']['name']."</span>";
				$i++;
			}
			
			//and finally, release
			echo json_encode($response);
		}else{
			
			//no Ajax?  then show our results.
			if(!empty($this->data)){
				
				//our search term
				$this->set('searchedFor', $this->data['Task']['name']);
				
				//log it!
				$this->log($this->data['Task']['name'], 'searched_for');
		
				//set the tasks
				$this->Task->bindModel(array(
					'hasOne' => array(
						'KeywordsTasks',
						'FilterKeyword' => array(
							'className' => 'Keyword',
							'foreignKey' => false,
							'conditions' => array('FilterKeyword.id = KeywordsTasks.keyword_id')
						)
					)
				));
				$this->set('tasks', $this->Task->find('all', array(
					'conditions'=>array('FilterKeyword.name'=>$this->data['Task']['name']),
					'order' => array('Task.deadline')
				)));
				
				//tasks complete
				$this->Task->bindModel(array(
					'hasOne' => array(
						'KeywordsTasks',
						'FilterKeyword' => array(
							'className' => 'Keyword',
							'foreignKey' => false,
							'conditions' => array('FilterKeyword.id = KeywordsTasks.keyword_id')
						)
					)
				));
				$this->set('tasksComplete', $this->Task->find('all', array(
					'conditions'=>array('FilterKeyword.name'=>$this->data['Task']['name'],'status_id'=>'2'),
					'order' => array('Task.deadline')
				)));
				
				//tasks in progress
				$this->Task->bindModel(array(
					'hasOne' => array(
						'KeywordsTasks',
						'FilterKeyword' => array(
							'className' => 'Keyword',
							'foreignKey' => false,
							'conditions' => array('FilterKeyword.id = KeywordsTasks.keyword_id')
						)
					)
				));
				$this->set('tasksInProgress', $this->Task->find('all', array(
					'conditions'=>array('FilterKeyword.name'=>$this->data['Task']['name'],'status_id'=>'1')
				)));
				
				//our statuses
				$this->set('statuses', $this->Task->Status->find('list'));
				
				//our locations
				$this->set('locations', $this->Task->Location->find('list'));
				
				//our keywords
				$this->set('keywords', $this->Task->Keyword->find('list'));
				
				//our link locations
				$linkLocation['/foundersFactory/tasks/'] = "Show by Location";
				foreach($this->Task->Location->find('list') as $key => $location){
					$linkLocation['/foundersFactory/tasks/showByLocation/'.$key] = $location;
				}
				$this->set('linkLocations', $linkLocation);
			}
		}
	}
}

?>