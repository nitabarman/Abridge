<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />  
         <link rel="stylesheet" href="css/custom-css.css" type="text/css" />  
        <title>Abridge - User Login</title>
		<?php include('global_variables.php'); ?>
		
    </head>

    <body class="stretched">

        <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">

            <!-- Header
            ============================================= -->
            <?php include('header.php'); ?>
            <!-- #header end -->
            <!-- Content
            ============================================= -->
            <section id="content">
                <div class="content-wrap">
                    <div class="container clearfix">
                        <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 550px;">

                            <div class="acctitle">Login to your Account</div>
                            <div class="acc_content clearfix">
                                <!--<form id="login-form" name="login-form" class="nobottommargin" action="#" method="post">-->
                                <form id="login-form" name="login-form" class="nobottommargin"   method="post" onsubmit="return onConfirmWhenLogin();">
                                    <div class="col_full">
                                        <p class="invalid" id="invalidLogin"></p> 
                                    </div>
                                    
                                    <div class="col_full">
                                        <label for="login-form-email">Email:</label>
                                        <input type="text" id="login-form-email" name="EmailAddress" class="form-control"  />
										<p id="invalidEmail"></p>
                                        
                                    </div>

                                    <div class="col_full">
                                        <label for="login-form-password">Password:</label>
                                        <input type="password" id="login-form-password" name="Password" class="form-control"  />
										<p id="invalidpassword"></p> 
                                    </div>

                                    <div class="col_full nobottommargin">  
									    <input type="hidden"  name="operation" value="login"  />
                                        <input type="submit" value="Sign In" class="button button-3d button-black nomargin btn-form-login"   />
                                    </div>
                                </form>
                            </div>


                        </div>


                    </div>

                </div>

            </section><!-- #content end --> 

        </div><!-- #wrapper end --> 
         
        
       <?php include('require_function.php'); ?> 
		
		<script>  
			 

			function onConfirmWhenLogin() {
				 
				var Email = document.getElementById("login-form-email").value; 
				var password = document.getElementById("login-form-password").value; 
				var error = 0;
				error = checkNullStr('invalidEmail',Email,'Please enter your email address'); 
				if (!validateEmail(Email))  error = checkNullStr('invalidEmail',Email,'Please enter your email address'); 
				else document.getElementById("invalidEmail").innerHTML ='';   
				error = checkNullStr('invalidpassword',password,'Please enter your password'); 
				 
				if (error == '1') { 
					return false;
				}
				else { 
				  var xhr = new XMLHttpRequest(); 
				  xhr.onload = function(){ 
					   var response= JSON.parse(xhr.responseText);
					   if(response['error'])
						document.getElementById("invalidLogin").innerHTML=response['message']; 
					   else 
					   { 
						   localStorage.USERID = response['Id']; 
						   localStorage.EMAILADDRESS = response['EmailAddress']; 
						   document.getElementById("invalidLogin").innerHTML=''; 
						   window.location = "./";
					   } 
					  
					  }
				 
				  xhr.open ("POST", "api/index.php", true); 
				  var oFormElement=document.getElementById("login-form");
				  xhr.send (
				  new FormData (oFormElement)
				  ); 
				  
				}
			 return false;
			}
			
		</script>

    </body>
</html>