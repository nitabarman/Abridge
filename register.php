<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />  
         <link rel="stylesheet" href="css/custom-css.css" type="text/css" />  
        <title>ABridge - Register</title>
		<?php include('global_variables.php'); ?>
        
    </head>

    <body class="stretched"> 
        <div id="wrapper" class="clearfix"> 
            <?php include('header.php'); ?> 
            <section id="content">
                <div class="content-wrap" id="registerform">
                    <div class="container clearfix">
                        <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 550px;">
                            <div class="acctitle">START YOUR FREE REGISTRATION NOW</div>
                            <div class="acc_content clearfix">
                                <form id="register-form" name="register-form" method="post" onsubmit="return onConfirmWhenRegister();">
                                    <p class="invalid" id="invalidRegistration"></p> 
									<div class="col_full">
                                        <label for="FirstName">First Name:<spam class="invalid">*</spam></label>
                                        <input type="text" id="FirstName" name="FirstName" class="form-control" />
										<p class="invalid" id="invalidFirstName"></p>  
									</div>
                                    <div class="col_full">
                                        <label for="LastName">Last Name:<spam class="invalid">*</spam></label>
                                        <input type="text" id="LastName" name="LastName" class="form-control" />
										<p class="invalid" id="invalidLastName"></p>   
									</div>

                                    <div class="col_full">
                                        <label for="EmailAddress">Email:<spam class="invalid">*</spam></label>
                                        <input type="text" id="EmailAddress" name="EmailAddress" class="form-control" />
										<p class="invalid" id="invalidEmail"></p>   
									</div>

                                    <div class="col_full">
                                        <label for="Password">Password:<spam class="invalid">*</spam></label>
                                        <input type="password" id="Password" name="Password" class="form-control" />
										<p class="invalid" id="invalidPassword"></p>  
									</div>
                                    
                                    <div class="col_full">
                                        <label for="ReTypePass">Re-Type Password:<spam class="invalid">*</spam></label>
                                        <input type="password" id="ReTypePass" name="ReTypePass" class="form-control" />
										<p class="invalid" id="invalidReTypePass"></p> 
									</div>   
                                    <div class="col_full nobottommargin">
									     <input type="hidden"  name="operation" value="registration"  />
										 <button type="submit" class="button button-3d button-black nomargin" >Create Account</button>
									</div>
                                </form>
                            </div>

                        </div>


                    </div>

                </div> 

            </section>  
        </div>  
		
       <?php include('require_function.php'); ?> 
		<script> 
		function onConfirmWhenRegister() {
			 
			var FirstName = document.getElementById("FirstName").value; 
			var LastName = document.getElementById("LastName").value;
			var EmailAddress = document.getElementById("EmailAddress").value;
			var Password = document.getElementById("Password").value;
			var ReTypePass = document.getElementById("ReTypePass").value; 
			
			var error = 0;
			
			error = checkNullStr('invalidFirstName',FirstName,'Please enter First Name'); 
			error = checkNullStr('invalidLastName',LastName,'Please enter Last Name'); 
			error = checkNullStr('invalidEmail',EmailAddress,'Please enter your email address');
			error = checkNullStr('invalidPassword',Password,'Please enter Password');		
		    error = checkNullStr('invalidReTypePass',ReTypePass,'Please re-type Password');					
			 
			 if (!validateEmail(EmailAddress)) {
				document.getElementById("invalidEmail").innerHTML ='Please enter valid email address';
				error = 1;
			}else {
				document.getElementById("invalidEmail").innerHTML ='';
			} 
			 
			if(Password!=ReTypePass){
				document.getElementById("invalidReTypePass").innerHTML ='Please re-type Password';
				error = 1;
			}else 
				document.getElementById("invalidReTypePass").innerHTML =''; 

			 
			if (error == '1') { 
				return false;
			}
			else { 

			
			
			var xhr = new XMLHttpRequest();
			 
			  xhr.onload = function(){
				   var response= JSON.parse(xhr.responseText);
					   if(response['error'])
						document.getElementById("invalidRegistration").innerHTML=response['message']; 
					   else 
					   {  
						   localStorage.USERID = response['Id']; 
						   localStorage.EMAILADDRESS = response['EmailAddress']; 
						   document.getElementById("invalidRegistration").innerHTML=''; 
						   window.location = "index.php";
					   } 
					  
				  
				  }
			 
			  xhr.open ("POST", "api/index.php", true); 
			  var oFormElement=document.getElementById("register-form");
			  xhr.send ( new FormData (oFormElement) ); 
			  
			}
			return false;
		}
		</script>
    </body>
</html>