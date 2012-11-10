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



<div class="wrap">
	<h2> Auto Responder Schedule </h2>
	<br/>
	
	<form class='form-table single-responder' action='' method='post'>
		<input type="hidden" name="single-responder-submit" value="Y" />
		<table>
			<tr> 
				<td>
					<span class="guide-texts"><label for="responder_title"> Responder Title <label></span> <br/> 
					<input id="responder_title" type="text" name="responder_title" value="<?php ?>"> 
				</td>
			</tr>
			
			<tr> 
				<td>
					<span class="guide-texts"> <label for="responder_cform">Schedule Responses to Submission of </label></span> <br/> 
					<select class="responder_cform" name="responder_cform">
						<option>Contact Form 1</option>
						<option>Contact Form 2</option>
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
							<option value="1"> template 1 </option>
							<option value="2"> template 2 </option>
						</select>
					</span>
					
					<span class="respond-select">
						<select class="responder-digit" recname="scheduleddigit" name="scheduleddigit[]">
							<option value="1"> 0 </option>
							<option value="2"> 1 </option>
							<option value="3"> 2 </option>
							<option value="4"> 3 </option>
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
	});		
			
</script>