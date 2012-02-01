<!-- File: /app/View/Tasks/show_complete.ctp -->

<h1>Completed Task List</h1>
<?PHP

	//include our nav bar
	include_once('nav.ctp');

?>
<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Status</th>
		<th>Location</th>
		<th>Keywords</th>
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
		<td><?php echo $locations[$task['Task']['location_id']]; ?></td>
		<td><?php 
		
	$keywordString = '';
		
	foreach($task['Keyword'] as $keyword){
		
		//add to our string of keywords
		$keywordString .= $keyword['name'].', ';
	}
	
	//remove the last ,&nbsp;
	if($keywordString != ''){
		$keywordString = substr($keywordString, 0, -2);
	}
	
	echo $keywordString;

?></td>
		<td><?php echo $task['Task']['created_on']; ?></td>
		<td><?php echo $task['Task']['deadline']; ?></td>
		<td><?php echo $task['Task']['completed_on']; ?></td>
	</tr>
	<?php endforeach; ?>

</table>