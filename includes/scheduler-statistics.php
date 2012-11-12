<div class="wrap">
<?php 
	$id = (int) $_GET['id'];
	$responder = get_post($id);
	
	if($responder) :	
?>


	<h2> <?php echo $responder->post_title ;?>	</h2>
	under construction ...

	<?php else: ?>
		<div class="error"><p>Invalid Responder Selected</p></div>
	<?php endif;?>

</div>