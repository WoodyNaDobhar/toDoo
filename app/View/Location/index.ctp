<!-- File: /app/View/Locations/index.ctp -->

<h1>Location List</h1>
<?php 
	
	//link bar
	echo $this->Html->link('Add Location', array('controller' => 'Locations', 'action' => 'add'));
	echo ' | ';
	echo $this->Html->link('Return to Tasks', array('controller' => 'tasks', 'action' => 'index'));
	
?>
<table>
	<tr>
		<th>Location</th>
		<th>&nbsp;</th>
		<th>Map Points</th>
	</tr>
<?php 

	//var for the color
	$x = 1;

	foreach ($Locations as $Location):
	
		//change the color based on due date, default to switched
		$x++;
		if($x%2 == 0){
			$bgColor = ' bgcolor="#F9F9F9"';
		}else{
			$bgColor = '';
		}
?>
	<tr<?PHP echo $bgColor; ?>>
		<td><?php echo $Location['Location']['name']; ?></td>
        <td><?php echo $this->Html->link('Edit', array('action' => 'edit', $Location['Location']['id']));?>
			&nbsp;/&nbsp; 
			<?php echo $this->Form->postLink(
				'Delete',
				array('action' => 'delete', $Location['Location']['id']),
				array('confirm' => 'Are you sure?'));
			?>
		</td>
        <td>
<?php 
	
	foreach($Location['MapPoint'] as $MapPoint){
	
		echo "|".$this->Html->link('Map', array('controller' => 'map_points', 'action' => 'view', $MapPoint['id']));
		
	}
	
?>|</td>
	</tr>
<?php endforeach; ?>

</table>