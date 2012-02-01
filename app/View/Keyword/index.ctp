<!-- File: /app/View/Keywords/index.ctp -->

<h1>Keyword List</h1>
<?php 
	
	//link bar
	echo $this->Html->link('Add Keyword', array('controller' => 'Keywords', 'action' => 'add'));
	echo ' | ';
	echo $this->Html->link('Return to Tasks', array('controller' => 'tasks', 'action' => 'index'));
	
?>
<table>
	<tr>
		<th>Keyword</th>
		<th>&nbsp;</th>
	</tr>
<?php 

	//var for the color
	$x = 1;

	foreach ($Keywords as $Keyword):
	
		//change the color based on due date, default to switched
		$x++;
		if($x%2 == 0){
			$bgColor = ' bgcolor="#F9F9F9"';
		}else{
			$bgColor = '';
		}
?>
	<tr<?PHP echo $bgColor; ?>>
		<td><?php echo $Keyword['Keyword']['name']; ?></td>
        <td><?php echo $this->Html->link('Edit', array('action' => 'edit', $Keyword['Keyword']['id']));?>
			&nbsp;/&nbsp; 
			<?php echo $this->Form->postLink(
				'Delete',
				array('action' => 'delete', $Keyword['Keyword']['id']),
				array('confirm' => 'Are you sure?'));
			?>
		</td>
	</tr>
<?php endforeach; ?>

</table>