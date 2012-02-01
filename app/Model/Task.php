<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling efforts related to the tasks database
*
*/

class Task extends AppModel{
	
	//set our vars
	public $name = 'Task';
	var $hasAndBelongsToMany = array(
		'Status' => array(
			'className' => 'Status',
			'joinTable' => 'statuses_tasks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'status_id',
			'with' => 'StatusesTasks'
		),
		'Location' => array(
			'className' => 'Location',
			'joinTable' => 'locations_tasks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'location_id',
			'with' => 'LocationsTasks'
		),
		'Keyword' => array(
			'className' => 'Keyword',
			'joinTable' => 'keywords_tasks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'keyword_id',
			'with' => 'KeywordsTasks',
			'unique' => 'keepExisting'
		)
	);
	
	//form validation
	public $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'required' => true
		),
		'deadline' => array(
				'rule' => array('datetime'),
				'message' => 'This field must be a valid timestamp.'
		)
	);
}

?>