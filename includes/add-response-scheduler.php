<style type="text/css">

		.removeFields
		{
				color:red;
		}
		.addMoreFields
		{
				color:green;
		}		
		
		a {
				color: black;
		}
		a:hover {
				color: maroon;
		}
		.divTag {
				float: right;
				width: 100px;
		}

		.Birds
		{    
				margin:5px;
		}

		.imgBird
		{
				border:1px solid #ccc;
				padding:2px;
				width:240px;
		}

		.addImage
		{
				background: url(add.png) no-repeat 0px 1px;
				padding-left: 17px;    
				margin-bottom: 10px;    
				line-height:20px;
				clear:both:
						
		}
		.removeImage
		{
				background: url(delete.png) no-repeat 0px 1px;
				padding-left: 17px;    
				margin-bottom: 10px;    
				line-height:20px;
		}
		
		.addresponder-with-email-templates-class{
			padding: 10px;
			border: 1px solid #7F7F7F;
			margin-bottom: 10px;		
		}
		
		.addresponder-with-email-templates-class span{
			padding-right: 30px;
		}
		
		.addresponder-with-email-templates-class select{
			background: transparent;
		  /*  width: 268px; */
		    padding: 5px;
		    font-size: 13px;
		    border: 1px solid #ccc; 
		    height: 30px;
		}
		
		.reponder-type{
			width: 150px;
		}				
		
		.responder-template{
			 width: 268px;
		}
		
		.responder-digit{
			width: 70px;
		}
		
		.responder_cform{
			background: transparent;
		    width: 268px;
		    padding: 5px;
		    font-size: 13px;
		    border: 1px solid #ccc; 
		    height: 30px;
		}
		
		.respond-select-guide{
			margin-right: 268px;
			padding: 10px;
		}
		
		#responder_title{
			background: transparent;
			width: 400px;
		    padding: 5px;
		    font-size: 13px;
		    border: 1px solid #ccc; 
		    height: 30px;
		}
				
</style>

<?php 
	if(isset($_GET['id'])) {
		$post_id = (int) $_GET['id'];
		if($post_id){
			$post = get_post($post_id);		
			$post_meta = get_post_custom($post_id);
			$associated_templates = unserialize($post_meta['associated_templates'][0]);
			$cform_id = $post_meta['associated_cform'][0];
			$cforms_dropdown = Cforms_Handler::get_cforms_drop_down($cform_id);
			var_dump($associated_templates);
		}
	}
?>


<div class="wrap">
	<h2> Auto Responder Schedule </h2>
	<br/>
	
	<form class='form-table single-responder' action=<?php echo get_admin_url('', 'edit.php?post_type=email&page=autoresponder&action=new'); ?> method='post'>
		<input type="hidden" name="single-responder-submit" value="Y" />
		<table>
			<tr> 
				<td>
					<span class="guide-texts"><label for="responder_title"> Responder Title <label></span> <br/> 
					<input id="responder_title" type="text" name="responder_title" value="<?php echo $post->post_title; ?>"> 
				</td>
			</tr>
			
			<tr> 
				<td>
					<span class="guide-texts"> <label for="responder_cform">Schedule Responses to Submission of </label></span> <br/> 
					<select class="responder_cform" name="responder_cform">
						<option value="0">Choose a Contact Form</option>
						<?php echo $cforms_dropdown; ?>
					</select>
				</td>
			</tr>
		</table>
		
		<div class="attaching-scheduler-responder">
			<h2> Schedule Automated Email </h2>
			
			<span class="respond-select-guide"> Email Template </span>
			<span class="respond-select-guide"> Send Schedule </span>			 
			 
			 <fieldset class="addresponder-with-email-templates-class" id="addresponder-with-email-templates">
			 		<span class="respond-select">
				 		<select class="responder-template" recname="templateid" name="emailtemplateid[]">
							<option value="0"> Choose a Template </option>
							<?php 
								if($email_templates) :
									foreach($email_templates as $email_template) :
?>
										<option value="<?php echo $email_template->ID?>"> <?php echo $email_template->post_title; ?> </option>
<?php 										
									endforeach;
								endif;
							?>
						</select>
					</span>
					
					<span class="respond-select">
						<select class="responder-digit" recname="scheduleddigit" name="scheduleddigit[]">
							<?php 
								for($i=0; $i<100; $i++){
									echo "<option value='$i'>$i</option>" ;
								}							
							?>
						</select> 
					</span>
					
					<span class="respond-select">
						<select class="reponder-type" recname="scheduledtype" name="scheduledtype[]">
							<option value="1"> Hours </option>
							<option value="2"> Days </option>
							<option value="3"> Weeks </option>
							<option value="4"> Months </option>
						</select>  
					</span>
			 </fieldset>
			 
		</div>
		
		<p> <input class="button-primary" type="submit" value="Save Schedule"> </p>
		
	</form>	
	
</div>

<script type="text/javascript">

	jQuery("#addresponder-with-email-templates").EnableMultiField({
		/*
		data:[{						
			templateid : "1",
			scheduleddigit : "2"
		},
		{
			templateid : "2",
			scheduleddigit: "4"
		}]
		*/

		data:[
			<?php 
				if($associated_templates){
					foreach($associated_templates as $template):
					?>
						{
							templateid : <?php echo $template['t_id']; ?>,
							scheduleddigit : <?php echo $template['digit']; ?>,
							scheduledtype : <?php echo $template['type']; ?>
						},
					<?php 
					endforeach;
				}
			?>
		]
		
	});		
			
</script>

<?php
	//var_dump(get_option('cforms_settings'));
	//var_dump($email_templates);
?>