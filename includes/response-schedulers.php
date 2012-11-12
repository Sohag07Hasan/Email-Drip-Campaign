
<style type="text/css">
	.autoresponder-schedulers tr{
		padding: 10px;
	}
</style>


<div class="wrap">
	<h2> Auto Responder Schedules <a class="add-new-h2" href="edit.php?post_type=email&page=autoresponder&action=new">Add New</a> </h2>
	<br/>
	
	<?php 
		if($_GET['msg'] == 1){
			echo "<div class='updated'><p> Scheduler is Deleted  </p></div>" ;
		}
		
		if($_GET['msg'] == 2){
			echo "<div class='error'><p> Could not be Deleted  </p></div>" ;
		}
	?>
	
	<table class="widefat autoresponder-schedulers">
		<thead>
			<tr>
				<th>Name</th> 
				<th>Emails</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach($auto_responders as $responder){
					$edit = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&action=new&id='.$responder->ID);
					$del = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&action=del&id='.$responder->ID);
					$stat = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&action=stat&id='.$responder->ID);
?>
					<tr>
						<td><a href="<?php echo $stat; ?>" title="view statistics"><?php echo $responder->post_title; ?></a></td>
						<td><?php echo "email"; ?></td>
						<td><a href="<?php echo $edit;?>">edit</a></td>
						<td><a href="<?php echo $del;?>">delete</a></td>						
					</tr>					
<?php 
				}
			?>
		</tbody>
	</table>
</div>