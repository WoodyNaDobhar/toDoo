<!-- File: /app/View/Tasks/show_complete.ctp -->

<h1>Completed Task List</h1>
<?php 
	
	//link bar
	echo $this->Html->link('Add Task', array('controller' => 'tasks', 'action' => 'add'));
	echo ' | ';
	echo $this->Html->link('Show Current', array('controller' => 'tasks', 'action' => 'index'));
	echo ' | ';
	echo $this->Html->link('Show All', array('controller' => 'tasks', 'action' => 'showAll'));
	
?>
<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Status</th>
		<th>Created</th>
		<th>Deadline</th>
		<th>Completed</th>
	</tr>
	<?php 
	
		$x = 1;
	
		foreach ($tasks as $task):
		
			//change the color based on due date compared to completed date
			$due = strtotime($task['Task']['deadline']);
			$completed = strtotime($task['Task']['completed_on']);
			$x++;
			
			if($due < $completed){
				$bgColor = ' bgcolor="#FF0000"';
			}else{
				if($x%2 == 0){
					$bgColor = ' bgcolor="#F9F9F9"';
				}else{
					$bgColor = '';
				}
			}
	?>
	<tr <?php echo $bgColor; ?>>
		<td><?php echo $task['Task']['id']; ?>:
			<?php echo $this->Html->link('Edit', array('action' => 'edit', $task['Task']['id']));?>
			&nbsp;/&nbsp; 
			<?php echo $this->Form->postLink(
				'Delete',
				array('action' => 'delete', $task['Task']['id']),
				array('confirm' => 'Are you sure?'));
			?>
		</td>
		<td>
			<?php echo $this->Html->link($task['Task']['title'], array('controller' => 'tasks', 'action' => 'view', $task['Task']['id'])); ?>
		</td>
		<td><?php echo $statuses[$task['Task']['status_id']]; ?></td>
		<td><?php echo $task['Task']['created_on']; ?></td>
		<td><?php echo $task['Task']['deadline']; ?></td>
		<td><?php echo $task['Task']['completed_on']; ?></td>
	</tr>
	<?php endforeach; ?>

</table>