<!-- File: /app/View/Tasks/view.ctp -->

<h1><?php echo $task['Task']['title']?> - <?php echo $task['Task']['status']?></h1>

<p><small>Created On: <?php echo $task['Task']['created_on']?></small></p>

<p><small>Deadline: <?php echo $task['Task']['deadline']?></small></p>

<p><small>Completed On: <?php echo $task['Task']['completed_on']?></small></p>