<!-- File: /app/View/MapPoints/index.ctp -->

<h1>MapPoint List</h1>
<?php 
	
	//link bar
	echo $this->Html->link('Return to Tasks', array('controller' => 'Tasks', 'action' => 'index'));
	
?>
<table>
	<tr>
    	<th>&nbsp;</th>
		<th>Latitude</th>
		<th>Longitude</th>
		<th>Show It!</th>
	</tr>
<?php 

	//var for the color
	$x = 1;

	foreach ($MapPoints as $MapPoint):
	
		//change the color based on due date, default to switched
		$x++;
		if($x%2 == 0){
			$bgColor = ' bgcolor="#F9F9F9"';
		}else{
			$bgColor = '';
		}
?>
	<tr<?PHP echo $bgColor; ?>>
    	<td><?php echo $this->Form->postLink(
				'Delete',
				array('action' => 'delete', $MapPoint['MapPoint']['id']),
				array('confirm' => 'Are you sure?'));
			?></td>
		<td><?php echo $MapPoint['MapPoint']['latitude']; ?></td>
		<td><?php echo $MapPoint['MapPoint']['longitude']; ?></td>
        <td><?php echo $this->Html->link('Google', 'http://maps.google.com/?q='.$MapPoint['MapPoint']['latitude'].','.$MapPoint['MapPoint']['longitude'], array('target' => '_blank'));?></td>
	</tr>
<?php endforeach; ?>

</table>