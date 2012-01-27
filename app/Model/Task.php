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
			'with' => 'StatusesTasks',
		),
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
	
	//custom validate datetime
	function datetime($data){
		$value = array_values($data);
		$value = $value[0];
		$regex = '%^((?:2|1)\\d{3}(?:-|\\/)(?:(?:0[1-9])|(?:1[0-2]))(?:-|\\/)(?:(?:0[1-9])|(?:[1-2][0-9])|(?:3[0-1]))(?:T|\\s)(?:(?:[0-1][0-9])|(?:2[0-3])):(?:[0-5][0-9]):(?:[0-5][0-9]))$%';
		return preg_match($regex, $value);
	}
}

?>