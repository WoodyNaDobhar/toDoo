<!-- File: /app/View/Keywords/edit.ctp -->

<h1>Edit Keyword</h1>

<?php

	//make our form
	echo $this->Form->create('Keyword', array('action' => 'edit'));
	
	//make our fields
	echo $this->Form->input('name');
	
	//our buttons
	echo $this->Form->end('Save Keyword');

?>