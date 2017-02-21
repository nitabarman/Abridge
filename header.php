  
    <div id="header-wrap"> 
        <div class="container clearfix">  
            <nav id="primary-menu">

                <ul>               
                    <script type="text/javascript">
					 
					     var htm='<li><a href="./"><div>List</div></a></li>'+
						 '<li><a href="register.php"><div>Register</div></a></li>'
                        if (USERID != null) {
                            document.write(
                                     htm+                                  
                                    '<li><a data-mce-href="#" href="javascript:void(0);" onClick="onConfirmWhenLoginOut()"><div>LogOut</div></a></li>'
                                    );
                        } else {
                            document.write(  
									htm+							
                                    '<li><a href="login.php"><div>Login</div></a></li>'
                                    );
                        }

                        function onConfirmWhenLoginOut() {
                            localStorage.clear();
                            window.location = "login.php";
                        } 
                    </script> 

                </ul>   
            </nav> 

        </div>

    </div> 

