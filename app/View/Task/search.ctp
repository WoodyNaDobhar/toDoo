<!-- File: /app/View/Tasks/search.ctp -->

<h1>Task Lisk by Keyword: <?php echo $searchedFor; ?></h1>
<?PHP

	//include our nav bar
	include_once('nav.ctp');
	
	//if there's no results, then tell them.
	if(count($tasks) < 1){
		
		echo '<br /><br /><br /><br /><br /><strong>No Tasks were found to match the given keyword.</strong>';
		
	}else{

		//draw up our stats
		$allInProgress = count($tasksInProgress)/count($tasks)*100;
		$allComplete = count($tasksComplete)/count($tasks)*100;
	
?>
<strong>Completion Stats:</strong><br />
<table>
    <tr>
        <th></th>
        <th>In Progress</th>
        <th>Complete</th>
    </tr>
    <tr>
        <th>Overall</th>
        <th><?PHP echo $allInProgress; ?>%</th>
        <th><?PHP echo $allComplete; ?>%</th>
    </tr>
</table>
<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Status</th>
		<th>Location</th>
		<th>Keywords</th>
		<th>Created</th>
		<th>Deadline</th>
	</tr>
	<?php 
	
		//some vars for date handling
		$now = strtotime(date('Y-m-d H:i:s'));
		$weekFromNow = strtotime(date('Y-m-d H:i:s',time() +(7 * 24 * 60 * 60)));
		$x = 1;
	
		foreach($tasks as $task):
		
			//change the color based on due date, default to switched
			$x++;
			$due = strtotime($task['Task']['deadline']);
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
	?>
	<tr<?PHP echo $bgColor; ?>>
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
	</tr>
	<?php endforeach; ?>

</table>
<?PHP

	}

?>