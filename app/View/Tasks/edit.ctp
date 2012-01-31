<!-- File: /app/View/Tasks/edit.ctp -->

<h1>Edit Task</h1>

<?php

	//make our form
	echo $this->Form->create('Task', array('action' => 'edit'));
	
	//make our fields
	echo $this->Form->input('title');
	echo $this->Form->input('status_id');
	echo $this->Form->input('location_id').$this->Html->link(' Add a Location', array('controller' => 'locations', 'action' => 'add'))." | ".$this->Html->link('Edit a Location', array('controller' => 'locations', 'action' => 'index'));
	echo $this->Form->input('deadline');
	echo $this->Form->input('id', array('type' => 'hidden'));
	
	//our buttons
	echo $this->Form->end('Save Task');

?>