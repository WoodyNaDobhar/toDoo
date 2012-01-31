<!-- File: /app/View/MapPoints/index.ctp -->

<h1>Map Point</h1>
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
	<tr>
    	<td><?php echo $this->Form->postLink(
				'Delete',
				array('action' => 'delete', $MapPoint['MapPoint']['id']),
				array('confirm' => 'Are you sure?'));
			?></td>
		<td><?php echo $MapPoint['MapPoint']['latitude']; ?></td>
		<td><?php echo $MapPoint['MapPoint']['longitude']; ?></td>
        <td><?php echo $this->Html->link('Google', 'http://maps.google.com/?q='.$MapPoint['MapPoint']['latitude'].','.$MapPoint['MapPoint']['longitude'], array('target' => '_blank'));?></td>
	</tr>
</table>