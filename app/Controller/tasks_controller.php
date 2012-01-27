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
	public $name = 'Tasks';
	public $helpers = array('Html', 'Form');
	public $components = array('Session');
	
	//setup our view
	public function view($id = null){
		$this->Task->id = $id;
		$this->set('task', $this->Task->read());
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
	}
	
	//list all the tasks, in order of entry
	public function showAll(){
		
		//our tasks
		$this->set('tasks', $this->Task->find('all', array(
			'order' => array('Task.created_on'),
		)));
		
		//our statuses
		$this->set('statuses', $this->Task->Status->find('list'));
	}
	
	//adding a task
	public function add(){
		
		if($this->request->is('post')){
			//save and continue, or fail and tell 'em
			if($this->Task->save($this->request->data)){
				$this->Session->setFlash('Your task has been saved.');
				$this->redirect(array('action' => 'index'));
			} else{
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
		
		if ($this->request->is('get')){

			//set the vars
			$this->set('statuses', $this->Task->Status->find('list'));
			$this->request->data = $this->Task->read();
		} else{
			
			//if it saves, continue, otherwise fail and tell 'em
			if ($this->Task->save($this->request->data)){
				
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
			} else{
				$this->Session->setFlash('Unable to update your task.');
			}
		}
	}
	
	//delete a task
	function delete($id){
		
		if ($this->request->is('get')){
			//uh...it shouldn't be.  This is not allowed.  Seriously.
			throw new MethodNotAllowedException();
		}
		
		//let's do this
		if ($this->Task->delete($id)){
			$this->Session->setFlash('The task with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}

?>