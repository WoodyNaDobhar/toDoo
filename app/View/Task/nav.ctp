<!-- File: /app/View/Tasks/nav.ctp -->

<div class="keywords formSmall">
    <?php echo $this->Form->create('Task',array('action'=>'search')); ?>
    <?php echo $this->Form->input('name',array('type'=>'text','id'=>'name','label'=>'Search')); ?>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
<?php 

	//link bar
	echo $this->Html->link('Add Task', array('controller' => 'tasks', 'action' => 'add'));
	echo ' | ';
	echo $this->Html->link('Show Current', array('controller' => 'tasks', 'action' => 'index'));
	echo ' | ';
	echo $this->Html->link('Show Completed', array('controller' => 'tasks', 'action' => 'showComplete'));
	echo ' | ';
	echo $this->Html->link('Show All', array('controller' => 'tasks', 'action' => 'showAll'));
	echo ' | ';
	echo $this->Form->select('unimportant', $linkLocations, array('onchange'=>"window.open(this.options[this.selectedIndex].value,'_top')", 'default'=>'/foundersFactory/tasks/'));
	
?>