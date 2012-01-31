<!-- File: /app/View/Tasks/add.ctp -->

<h1>Add Task</h1>

<?php

	//make a form
	echo $this->Form->create('Task');
	
	//the fields
	echo $this->Form->input('title');
	echo $this->Form->input('location_id').$this->Html->link(' Add a Location', array('controller' => 'locations', 'action' => 'add'))." | ".$this->Html->link('Edit a Location', array('controller' => 'locations', 'action' => 'index'));
	echo $this->Form->input('deadline');
	
	//the button
	echo $this->Form->end('Save Task');

?>