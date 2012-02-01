<!-- File: /app/View/Keywords/add.ctp -->

<h1>Add Keyword</h1>

<?php

	//make a form
	echo $this->Form->create('Keyword');
	
	//the fields
	echo $this->Form->input('name');
	
	//the button
	echo $this->Form->end('Save Keyword');

?>