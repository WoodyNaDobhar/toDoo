<!-- File: /app/View/Locations/add.ctp -->

<h1>Add Location</h1>

<?php

	//make a form
	echo $this->Form->create('Location');
	
	//the fields
	echo $this->Form->input('name');
	
	//the button
	echo $this->Form->end('Save Location');

?>