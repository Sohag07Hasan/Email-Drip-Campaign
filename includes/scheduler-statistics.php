<style>
	.template-with-status img{
		cursor: pointer;
	}
	
	.scheduler-statistics td{
		vertical-align: middle;		
	}
</style>

<div class="wrap">
<?php 
	$id = (int) $_GET['id'];
	$responder = get_post($id);
		
	if($responder) :
		if(isset($_GET['act'])){
			emaildripcampaign_responders::toggle_subscription();
		}
		
		$leads = emaildripcampaign_responders::get_leads($id);
		$sorted_leads = array();
		if($leads){
			foreach($leads as $lead){
				$sorted_leads[$lead->lead_id][] = $lead;			
			}			
		}
			
		$status_images = emaildripcampaign_responders::get_images();
		//var_dump($status_images);	
?>


	<h2> <?php echo $responder->post_title ;?>	</h2>
	
	<?php 
		if(isset($_GET['act'])){
			$message = "Successfully unscribed";
			if($_GET['act'] == 's') $message = "Successfully subscribed";
			echo "<div class='updated'><p> $message </p></div>";
		}
	?>
	
	<?php if(isset($sorted_leads) && count($sorted_leads) > 0) : ?>
		<?php $unscribe_url = admin_url('edit.php?post_type=email&page=autoresponder&action=stat&id=' . $id); ?>
		
		<table class='widefat scheduler-statistics'>
			<thead>
				<tr> <th>Name</th> <th>Email</th> <th>Templates</th> <th>Remove?</th> </tr>
			</thead>
			<tbody>
			<?php foreach($sorted_leads as $key => $slead) : ?>
				<?php $info = emaildripcampaign_responders::get_the_lead((int) $key); ?>
				<tr>
					<td> <?php echo $info->name; ?> </td>
					<td> <?php echo $info->email; ?> </td>
					<td>
						<?php foreach($slead as $l){
							$template = emaildripcampaign_templates::get_single_template($l->template_id);
							
							if($l->status==1 && $info->status==1){
								$title = "Sent and Open";
								$image = $status_images['t'];
							}
							if($l->status==1 && $info->status==0){
								$title = "Sent and Blocked";
								$image = $status_images['p'];
							}
							if($l->status==0 && $info->status==1){
								$title = "Scheduled";
								$image = $status_images['s'];
							}							
							if($l->status==0 && $info->status==0){
								$title = "Not sent and Blocked";
								$image = $status_images['b'];
							}

							?>
							
							<p class="template-with-status">
								<?php echo $template->post_title; ?>  <img title="<?php echo $title; ?>" alt="<?php echo $title; ?>" src="<?php echo $image; ?>">
							</p>
							
							<?php 
							
						} ?>					
					</td>
					
					<td> 
						<?php if($info->status == 1): ?>
							<a href="<?php echo $unscribe_url . '&act=u&lead_id=' . $info->ID; ?>" > Remove </a>
						<?php else: ?>
							<a href="<?php echo $unscribe_url . '&act=s&lead_id=' . $info->ID; ?>" > Add </a>
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach;;?>
			</tbody>
		</table>
	<?php else:?>
	
		<div class="error"><p> No Record Found </p></div>	
	
	<?php endif;?>
	

	<?php else: ?>
		<div class="error"><p>Invalid Responder Selected</p></div>
	<?php endif;?>

</div>