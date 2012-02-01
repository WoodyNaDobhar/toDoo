<!-- File: /app/View/Locations/edit.ctp -->

<h1>Edit Location</h1>

<?php

	//make our form
	echo $this->Form->create('Location', array('action' => 'edit'));
	
	//make our fields
	echo $this->Form->input('name');
	
	//our buttons
	echo $this->Form->end('Save Location');

?>