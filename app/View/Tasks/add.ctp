<!-- File: /app/View/Tasks/add.ctp -->

<h1>Add Task</h1>

<?php

	//make a form
	echo $this->Form->create('Task');
	
	//the fields
	echo $this->Form->input('title');
	echo $this->Form->input('deadline');
	
	//the button
	echo $this->Form->end('Save Task');

?>