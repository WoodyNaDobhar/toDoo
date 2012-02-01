<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling business logic for keyword interaction
*
*/

class KeywordsController extends AppController{
	
	//our vars
	public $name = 'Keyword';
	public $components = array('Session');
	var $helpers = array('Js');
	
	//default, list the keywords in progress, in order of deadline
	public function index(){
		
		//our keywords
		$this->set('Keywords', $this->Keyword->find('all'));
	}
	
	//adding a keyword
	public function add(){
		
		if($this->request->is('post')){
			//save and continue, or fail and tell 'em
			if($this->Keyword->save($this->request->data)){
				$this->Session->setFlash('Your keyword has been saved.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to add your keyword.');
			}
		}else if($this->request->data){
			$this->Session->setFlash('Unable to add your keyword.');
		}
	}
	
	//editing a keyword
	function edit($id = null){
		
		//set the vars
		$this->Keyword->id = $id;
		
		if ($this->request->is('get')){

			//set the vars
			$this->request->data = $this->Keyword->read();
		}else{
			
			//if it saves, continue, otherwise fail and tell 'em
			if ($this->Keyword->save($this->request->data)){
				
				//finish up
				$this->Session->setFlash('Your keyword has been updated.');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash('Unable to update your keyword.');
			}
		}
	}
	
	//delete a keyword
	function delete($id){
		
		if ($this->request->is('get')){
			//uh...it shouldn't be.  This is not allowed.  Seriously.
			throw new MethodNotAllowedException();
		}
		
		//let's do this
		if ($this->Keyword->delete($id)){
			$this->Session->setFlash('The keyword has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
?>