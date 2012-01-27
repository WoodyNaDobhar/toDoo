<!-- File: /app/View/Tasks/show_all.ctp -->

<h1>All Tasks List</h1>
<?php 
	
	//link bar
	echo $this->Html->link('Add Task', array('controller' => 'tasks', 'action' => 'add'));
	echo ' | ';
	echo $this->Html->link('Show Current', array('controller' => 'tasks', 'action' => 'index'));
	echo ' | ';
	echo $this->Html->link('Show Completed', array('controller' => 'tasks', 'action' => 'showComplete'));
	
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
	
		//some vars for date handling
		$now = strtotime(date('Y-m-d H:i:s'));
		$weekFromNow = strtotime(date('Y-m-d H:i:s',time() + (7 * 24 * 60 * 60)));
		$x = 1;
	
		foreach ($tasks as $task):
		
			//change the color based on status and due date, default to switched
			$x++;
			$due = strtotime($task['Task']['deadline']);
			$completed = strtotime($task['Task']['completed_on']);
			
			switch($task['Task']['status_id']){
				case 1:
					//in progress, so let's compare the due date to now
					if($due < $now){
						$bgColor = ' bgcolor="#FF0000"';
					}else if($due < $weekFromNow){
						$bgColor = ' bgcolor="#FFFF00"';
					}else{
						if($x%2 == 0){
							$bgColor = ' bgcolor="#F9F9F9"';
						}else{
							$bgColor = '';
						}
					}
					break;
				case 2:
					//completed, so let's compare it 
					if($due < $completed){
						$bgColor = ' bgcolor="#FF0000"';
					}else{
						if($x%2 == 0){
							$bgColor = ' bgcolor="#F9F9F9"';
						}else{
							$bgColor = '';
						}
					}
					break;
				case 3:
					//deleted...just make it dark grey
					$bgColor = ' bgcolor="#999999"';
					break;
			}
	?>
	<tr<?php echo $bgColor; ?>>
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