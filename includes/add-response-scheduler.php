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
		}
		
		.addresponder-with-email-templates-class span{
			padding-right: 30px;
		}
		
		.addresponder-with-email-templates-class select{
			background: transparent;
		    width: 268px;
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
					<select id="responder_cform" name="responder_cform">
						<option>Contact Form 1</option>
						<option>Contact Form 2</option>
					</select>
				</td>
			</tr>
		</table>
		
		<div class="attaching-scheduler-responder">
			<h2>Schedule Automated Email</h2>
			
			<!-- 
			<table>
				<thead>
					<tr>
						<th>Email Template</th>
						<th>Send Schedule</th>						
					</tr>					
										
				</thead>
				<tbody>
					<tr id="addresponder-with-email-templates">
						<td> 
							<select recname="templateid" name="emailtemplateid[]">
								<option value="1"> template 1 </option>
								<option value="2"> template 2 </option>
							</select> 
						</td>
						
						<td>
							<input type="text" name="aweful" rcname="afwfulL" />						
							
						</td>
					</tr>
				</tbody>
				
			</table>
			 -->
			 			 
			 
			 <fieldset class="addresponder-with-email-templates-class" id="addresponder-with-email-templates">
			 		<span class="respond-select">
				 		<select recname="templateid" name="emailtemplateid[]">
							<option value="1"> template 1 </option>
							<option value="2"> template 2 </option>
						</select>
					</span>
					
					<span class="respond-select">
						<select recname="scheduleddigit" name="scheduleddigit[]">
							<option value="1"> 0 </option>
							<option value="2"> 1 </option>
							<option value="3"> 2 </option>
							<option value="4"> 3 </option>
						</select> 
					</span>
					
					<span class="respond-select">
						<select recname="scheduledtype" name="scheduledtype[]">
							<option value="1"> Hours </option>
							<option value="2"> Days </option>
							<option value="3"> Weeks </option>
							<option value="4"> Months </option>
						</select>  
					</span>
			 </fieldset>
			 
		</div>
		
	</form>	
	
</div>

<script type="text/javascript">

	jQuery("#addresponder-with-email-templates").EnableMultiField({
		data:[{						
			templateid : "1",
			scheduleddigit : "2"
		},
		{
			templateid : "2",
			scheduleddigit: "4"
		}]
	});		
			
</script>