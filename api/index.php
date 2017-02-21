<?php 
require_once 'DbOperation.php'; 
include('../baseurl.php'); 

$case=$_POST['operation'];
switch ($case) { 
	case "login": 
	  login();
	break;
	case "registration": 
	  registration(); 
	break;
	case "addtopic": 
	  addtopic(); 
	break; 
	case "UpdateTopic": 
	  UpdateTopic(); 
	break;
	case "DeleteTopic": 
	  DeleteTopic(); 
	break;
	case "addComment": 
	  addComment(); 
	break; 
	case "updateComment":
	   updateComment();
	   break;
	case "DeleteComments":
	   DeleteComments();
	   break;
	case "getListData":
	  getListData();
	break;
	
		
}

function login(){
	    $EmailAddress=$_POST['EmailAddress'];
		$Password=$_POST['Password'];
		$db = new DbOperation(); 
		$response = array();
		if ($db->EmployeeLogin($EmailAddress, $Password)) {
			$Employee = $db->getEmployee($EmailAddress);
			$response['error'] = false;
			$response['Id'] = $Employee['Id'];
			$response['FirstName'] = $Employee['FirstName'];
			$response['EmailAddress'] = $Employee['EmailAddress'];
			$response['LastName'] = $Employee['LastName'];  
		} else {
			$response['error'] = true;
			$response['message'] = "Invalid Email Address or password";
		}
		echo json_encode($response);
}
 function registration()
 {
	    $FirstName=$_POST['FirstName'];
		$LastName=$_POST['LastName'];
		$EmailAddress=$_POST['EmailAddress']; 
		$Password=$_POST['Password'];  
		
		$db = new DbOperation(); 
		$response = array();
		 $response=$db->employeeRegistration($FirstName,$LastName,$EmailAddress, $Password);
		if ($response['retval']==0) {
			$Employee = $db->getEmployee($EmailAddress);
			$response['error'] = false;
			$response['Id'] = $Employee['Id'];
			$response['FirstName'] = $Employee['FirstName'];
			$response['EmailAddress'] = $Employee['EmailAddress'];
			$response['LastName'] = $Employee['LastName'];  
		} else if ($response['retval']==3) {
			$response['error'] = true;
			$response['message'] = "Email Address Already Registered";
		}
		else {
			$response['error'] = true;
			$response['message'] = "Error Occured";
		}
		echo json_encode($response);
	 
 }
 function addtopic()
 {
	    $topic=$_POST['topic'];
		$Id=$_POST['Id'];  
		
		$db = new DbOperation(); 
		$response = array();
		$response=$db->createNewTopic($topic,$Id); 
		if ($response['retval']==0) {
			$Employee = $db->getTopic($response['lastInsertedId']);
			$response['error'] = false;
			$response['Id'] = $Employee['Id']; 
		} else {
			$response['error'] = true;
			$response['message'] = "Could not save";
		}
		echo json_encode($response);
	 
 }
 function UpdateTopic()
 {
	    $topic=$_POST['topic'];
		$Id=$_POST['Id'];  
		
		$db = new DbOperation(); 
		$response = array(); 
	
		if($db->updateTopic($topic,$Id)) { 
			$Employee = $db->getTopic($Id);
			$response['error'] = false;
			$response['Id'] = $Employee['Id']; 
		} else {
			$response['error'] = true;
			$response['message'] = "Could not save";
		}
		echo json_encode($response);
		
	 
 }
 function DeleteTopic()
 {   
		$Id=$_POST['Id'];  
		$db = new DbOperation(); 
		$response = array(); 
		$response=$db->deleteTopic($Id); 
		if( $response['retval']==0) { 
			$Employee = $db->getTopic($Id);
			$response['error'] = false; 
		 
		} else if( $response['retval']==1) {
			$response['error'] = true;
			$response['message'] = "Could not delete Topic";
		}
		else {
			$response['error'] = true;
			$response['message'] = "Could not delete Comment";
		}
		echo json_encode($response);
		
	 
 }
 
 function addComment()
 {
	    $Comment=$_POST['Comment'];
		$Id=$_POST['Id'];  
		$TopicId=$_POST['TopicId'];  
		
		$db = new DbOperation(); 
		$response = array();
		$response=$db->createNewComment($Comment,$Id,$TopicId); 
		if ($response['retval']==0) {
			$Employee = $db->getTopic($response['lastInsertedId']);
			$response['error'] = false; 
		} else {
			$response['error'] = true;
			$response['message'] = "Could not save";
		}
		echo json_encode($response);
	 
 }
 
 function updateComment()
 {
	    $Comment=$_POST['Comment'];
		$CommentsId=$_POST['CommentsId'];
		
		$db = new DbOperation(); 
		$response = array();
		$response=$db->updateComment($Comment,$CommentsId); 
		if ($response['retval']==0) {
			$Employee = $db->getTopic($CommentsId);
			$response['error'] = false; 
		} else {
			$response['error'] = true;
			$response['message'] = "Could not update";
		}
		echo json_encode($response);
	 
 }

function DeleteComments()
 {   
		$Id=$_POST['Id'];  
		$db = new DbOperation(); 
		$response = array(); 
		$response=$db->deleteComment($Id,0); 
		if( $response['retval']==0) { 
			$Employee = $db->getTopic($Id);
			$response['error'] = false; 
		 
		} else {
			$response['error'] = true;
			$response['message'] = "Could not delete Comment";
		}
		
		echo json_encode($response);
		
	 
 }
 function getListData()
 {      $userid=$_POST['userid'];
        $SearchValue=$_POST['SearchValue']; 
		$db = new DbOperation(); 
		$result = $db->getComments($SearchValue);
		$nbrows =$result->num_rows;
        $data=array(); $topicid='';$html='';$tableIndex=0;$last=1;
		while ($row = $result->fetch_assoc()) { 
		       $addclass=$userid!=0?   $userid==$row['Id']?'showbtn': 'hidebutton':'hidebutton';
			   $addclassReplay=$userid!=0?  'showbtn':'hidebutton';
			   $addclassComment=$userid!=0? $userid==$row['commentuserid']?'showbtn': 'hidebutton':'hidebutton';
		      if($topicid!=$row['TopicId']){
				  
				  if($tableIndex!=0) {
					 $html.='<tr>'; 
					 $html.='<td class="textClass"></td>';
					 $html.='<td class="replayBtn"><a href="javascript:Replay('.$topicid.','.$tableIndex.')" class="'.$addclassReplay.'">Replay</a></td>';
					 $html.='</tr>';
					 $tableIndex++;
				  }
				  $html.='<tr style="border:1px solid red;">';
				  $html.='<td class="textClass"><b>'.$row['Topics'].'</b><br><br><font size="2">by '.$row['username'].' '.$row['LastEdited'].'</font></td>';
				  $html.='<td class="textClass replayBtn"><a href="javascript:EditTopic('.$row['TopicId'].',\''.$row['Topics'].'\');" class="'.$addclass.'" >Edit</a><a href="javascript:DeleteTopic('.$row['TopicId'].',\'DeleteTopic\');" class="'.$addclass.'">Delete</a></td>';
				  $html.='</tr>';
				  $topicid=$row['TopicId'];
				  
				  
				  $tableIndex++;
			  }
			 if($row['Comments']!=null) 
			 {
				 $html.='<tr>'; 
				 $html.='<td class="textClass "><font size="2">'.$row['LastEdited'].'</br><b>'.$row['commentusername'].' says: </b></font></br><font size="1">'.$row['Comments'].'</font></td>';
				 $html.='<td class="replayBtn "><a href="javascript:EditComment('.$row['TopicId'].',\''.$row['Comments'].'\','.$tableIndex.','.$row['CommentsId'].');" class="'.$addclassComment.'">Edit</a><a href="javascript:DeleteTopic('.$row['CommentsId'].',\'DeleteComments\');"  class="'.$addclassComment.'">Delete</a></td>';
				 $html.='</tr>';
				 $tableIndex++;
			 }
			 
			 if($last==$nbrows){
				 $html.='<tr>'; 
				 $html.='<td class="textClass"></td>';
				 $html.='<td class="replayBtn"><a href="javascript:Replay('.$row['TopicId'].','.$tableIndex.')"  class="'.$addclassReplay.'">Replay</a></td>';
				 $html.='</tr>';
				 $last++;
			 }
			 //$tableIndex++;
			 $last++;
			
		}
		
		
		 $data[]=$html;
		echo json_encode($data);
	 
 }
  

 
