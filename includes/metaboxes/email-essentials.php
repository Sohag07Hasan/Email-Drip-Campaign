<div class="wrap">
	<?php 
		//var_dump($meta_values);	
	?>
	<table class="form-table">
		<tr>
			<td> <label for="email-from"> Email From </label> </td>
			<td> <input size="50" type="text" name="email-from" id="email-from" value="<?php echo $meta_values['email-from']; ?>"> </td>
		</tr>
		<tr>
			<td> <label for="email-subject"> Email Subject </label> </td>
			<td> <input size="50" type="text" name="email-subject" id="email-subject" value="<?php echo $meta_values['email-subject']; ?>"> </td>
		</tr>
		<tr>
			<td> <label for="email-replay-to"> Email Replay To </label> </td>
			<td> <input size="50" type="text" name="email-replay-to" id="email-replay-to" value="<?php echo $meta_values['email-replay-to']; ?>"> </td>
		</tr>
	</table>
	
</div>