<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />  
         <link rel="stylesheet" href="css/custom-css.css" type="text/css" /> 
        <title>Abridge - Thread List</title>
		<?php include('global_variables.php'); ?>
    </head>

    <body class="stretched">

        <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">

            <!-- Header
            ============================================= -->
            <?php include('header.php'); ?> 
            <section id="content">
                <div class="content-wrap">
                    <div class="container clearfix">
                        <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 550px;"> 
							 
							 <div id="thread_list_div"> 
							  
							  
							  <div style="width:350px;float:left;">Thread List </div>
							  <div style="height:50px;">
							  <a class="aCSS" href="javascript:AddTopic();">Add New Topic</a>
							  
							  </div>
							  <div style="text-align:right;">
							  
							  <input type="search" class="Search" id="Search" /><a class="aCSS" href="javascript:SearchTopic();">Search</a>
							  </div> 
								<br/>
								
                                 <table id='thread_list' width="500"  > 
								 </table>
                            </div>
                         
							<form id="addtopic_form"  method="post" onsubmit="return onConfirmWhenAddTopic();" style="display:none;"> 
								<div class="col_full"> 
									<textarea id="topic" name="topic" class="form-control" ></textarea>
									<p id="invalidEmail"></p>
									
								</div> 

								<div class="col_full nobottommargin">  
									<input type="hidden"  name="operation" id="operation" value=""  />
									<input type="hidden"  name="Id" id="Id" value=""  />
									<input type="submit" value="Save" class="button button-3d button-black nomargin btn-form-login"   />
									
									<a href="javascript:BacktoListTopic();" class="button button-3d button-black">Back</a>
								</div>
							</form>
                           


                        </div>


                    </div>

                </div>

            </section> 

        </div> 
         
        
       <?php include('require_function.php'); ?> 
		
		<script>  
			
			var table = document.getElementById("thread_list");
			var gTableIndex=-1;
			function onConfirmWhenAddTopic() {
				 
				var topic = document.getElementById("topic").value; 
				 
				  var xhr = new XMLHttpRequest(); 
				  xhr.onload = function(){ 
					   var response= JSON.parse(xhr.responseText);
					   if(response['error'])
						document.getElementById("invalidLogin").innerHTML=response['message']; 
					   else 
					   {  
				           getTableData();
						   BacktoListTopic();
						   
					   } 
					  
					  }
				 
				  xhr.open ("POST", "api/index.php", true); 
				  var oFormElement=document.getElementById("addtopic_form");
				  xhr.send (
				  new FormData (oFormElement)
				  ); 
				  
				 
			 return false;
			}
			
			
			function getTableData()
			{   
				 
				  var xhr = new XMLHttpRequest(); 
				  xhr.onload = function(){ 
					   var response= JSON.parse(xhr.responseText);
					  table.innerHTML=response;  
					  gTableIndex=-1;
					  }
				 
				  xhr.open ("POST", "api/index.php", true); 
				  var data = new FormData();
				  data.append('operation', 'getListData');
				  var userid=localStorage.USERID==null?0:  localStorage.USERID;
				  if(userid==0) document.getElementsByClassName('aCSS')[0].className += " hidebutton"; 
				  else document.getElementsByClassName('aCSS')[0].className += " showbtn"; 
				  
				  data.append('userid', userid);
				  data.append('SearchValue', document.getElementById('Search').value);
				  xhr.send (data); 
				  
			 
			
			}
			 getTableData();
			 
			 function EditTopic(val,comments)
			 {
				 document.getElementById("thread_list_div").style.display='none';
				 document.getElementById("addtopic_form").style.display='block';
				// document.getElementById("addtopic_form").style.display='block';
				 document.getElementById("topic").value=comments; 
				 document.getElementById("operation").value="UpdateTopic"; 	 
				 document.getElementById("Id").value=val; 
			 
			 }
			 function AddTopic()
			 {
				 document.getElementById("topic").value='';
				 document.getElementById("thread_list_div").style.display='none';
				 document.getElementById("addtopic_form").style.display='block';  
				 document.getElementById("operation").value="addtopic"; 
				 document.getElementById("Id").value=localStorage.USERID; 				 
			 }
			 function DeleteTopic(val,operation)
			 {
				 var xhr = new XMLHttpRequest(); 
				  xhr.onload = function(){ 
					   var response= JSON.parse(xhr.responseText); 
						  if(response['error'])
							alert(response['message']); 
						   else 
						   {  
							   getTableData();
							    
						   } 
						  
					  
					  }
				 
				  xhr.open ("POST", "api/index.php", true); 
				  var data = new FormData();
				  data.append('operation', operation);
				  var userid=localStorage.USERID==null?0:  localStorage.USERID;
				  
				  
				  data.append('Id', val);
				  var oFormElement=document.getElementById("login-form");
				  xhr.send (data); 
			 }
			 
			 function BacktoListTopic()
			 {
				 document.getElementById("thread_list_div").style.display='block';
				 document.getElementById("addtopic_form").style.display='none';  
				 document.getElementById("operation").value="addtopic";  
				 
			 }
			 function Replay(val,tableIndex)
			 { 
			    if(gTableIndex!=-1) table.deleteRow(gTableIndex); 
					
					gTableIndex=tableIndex; 
				    var row = table.insertRow(tableIndex); 
					var cell1 = row.insertCell(0);
					cell1.colSpan = "2"; 
					cell1.innerHTML = "<br/><textarea id=\"comment\" class=\"form-control\" ></textarea><a class=\"button\" href=\"javascript:saveComments( "+val+",'addComment');\"  >Save</a>";
                    cell1.innerHTML+= "<a class=\"button\" href=\"javascript:discardReplay();\">Discard</a>";
			 }
			 function discardReplay()
			 {
				   if(gTableIndex!=-1) table.deleteRow(gTableIndex); 
			 }
			function saveComments(val,operation,CommentsId)
			{
				  var xhr = new XMLHttpRequest(); 
				  xhr.onload = function(){ 
					   var response= JSON.parse(xhr.responseText); 
						  if(response['error'])
							alert(response['message']); 
						   else 
						   {  
							   getTableData();
							    
						   } 
						  
					  
					  }
				 
				  xhr.open ("POST", "api/index.php", true); 
				  var data = new FormData();
				  data.append('operation', operation);
				  var userid=localStorage.USERID==null?0:  localStorage.USERID;   
				  data.append('Id', userid); 
				  data.append('CommentsId', CommentsId);
				  data.append('Comment', document.getElementById("comment").value);
				  data.append('TopicId', val); 
				  xhr.send (data); 
			}	
			function EditComment(val,comments,tableIndex,CommentsId)
			 {
				 
				 
				    table.deleteRow(tableIndex); 
					
					gTableIndex=tableIndex; 
				    var row = table.insertRow(tableIndex); 
					var cell1 = row.insertCell(0);
					cell1.colSpan = "2"; 
					cell1.innerHTML = "<br/><textarea id=\"comment\" class=\"form-control\" >"+comments+"</textarea>";
			        cell1.innerHTML+= "<a class=\"button\" href=\"javascript:saveComments( "+val+",'updateComment',"+CommentsId+");\"  >Save</a>";
					cell1.innerHTML+= "<a class=\"button\" href=\"javascript:getTableData();\">Discard</a>";
			 }			
			
			function SearchTopic()
			{
				getTableData();
			}
		</script>
		<style>
		.replayBtn{ 
			text-align:center;
			width:40px;
		}
		 
		.textClass{
			padding:15px;
		}
		.aCSS{
			border:solid 1px;
			border-radius: 5px;
			padding:5px;
			 
		}
		
		.Search{
			border:solid 1px;
			padding:5px;
			 
		}
		 table {
			border-collapse: collapse;
			width: 100%;
		}
		.showbtn{
			visibility: show;
			padding-right:10px;
		}
		.hidebutton{
			visibility: hidden;
		}
		</style>

    </body>
</html>