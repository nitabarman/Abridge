<?php

class DbOperation {

    private $con;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
	
	  //Method to let a Employee log in
    public function EmployeeLogin($EmailAddress, $Password) {
        $Pword = md5($Password); 
        $stmt = $this->con->prepare("SELECT * FROM t_usertable WHERE EmailAddress=? and Password=?");
        $stmt->bind_param("ss", $EmailAddress, $Pword);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	public function employeeRegistration($FirstName, $LastName, $EmailAddress, $Password) { 
	
        if (!$this->isEmployeeEmailExists($EmailAddress)) { 
			try {
				 
				$Pword = md5($Password);  
				$stmt = $this->con->prepare("INSERT INTO t_usertable(FirstName, LastName, EmailAddress, Password) values(?, ?, ?, ?)");
				$stmt->bind_param("ssss", $FirstName, $LastName, $EmailAddress, $Pword );
				$result = $stmt->execute();

				$this->con->commit();
				$stmt->close();
				$retval['retval'] = 0; 
				return $retval;
			} catch (Exception $e) {
				$retval['retval'] = 2; 
				return $retval;
				$this->con->rollback();
			}
             
        } else { 
            $retval['retval'] = 3; 
            return $retval;
        }
    }
	
       //Method to check the Email Address already exist or not
    private function isEmployeeEmailExists($EmailAddress) { 
	 
        $stmt = $this->con->prepare("SELECT Id from t_usertable WHERE EmailAddress = ?"); 
        $stmt->bind_param("s", $EmailAddress);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	 //Method to fetch all comment from database
    public function getComments($search) {
		//$sql="SELECT a.CommentsId,a.Id,a.Comments,a.CommentsDate,b.FirstName,b.LastName,b.EmailAddress ";
		//$sql.=" FROM t_comments a INNER JOIN t_usertable b ON a.Id=b.Id ";
		$sql="SELECT a.TopicId,a.Id,a.Topics, b.Comments,DATE_FORMAT(LastEdited, '%d/%m/%Y') LastEdited, CONCAT(c.FirstName,' ',c.LastName) username ";
		$sql.=" ,CONCAT(d.FirstName,' ',d.LastName) commentusername, b.Id commentuserid,CommentsId ";
		$sql.=" FROM t_topic a LEFT JOIN t_comments b ON a.TopicId=b.TopicId ";
		$sql.="  INNER JOIN t_usertable c ON a.Id=c.Id  ";
		$sql.="  LEFT JOIN t_usertable d ON b.Id=d.Id  ";
		
		if($search!='')
		{
		$sql.="  WHERE (b.Comments LIKE \"%".$search."%\" ";
		$sql.="  OR a.Topics LIKE \"%".$search."%\") ";
		}
		
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $comments = $stmt->get_result();
        $stmt->close();
        return $comments;
    }
	 //Method to post a new topic
    public function createNewTopic($Comment,$id) {
		 $date=date("Y-m-d h:i:sa");
		$stmt = $this->con->prepare("INSERT INTO t_topic(Topics,Id,LastEdited) values(?, ?, ?)");
		$stmt->bind_param("sis",  $Comment,$id,$date);
		$result = $stmt->execute();
		$retval['lastInsertedId']=$stmt->insert_id; 
		$stmt->close();
		if ($result) {
			$retval['retval'] = 0;
			
			return $retval;
		} else {
			$retval['retval'] = 1;
			return $retval;
		}
			
			 
    }
	
	
	
  public function updateTopic($topic,$Id) {
	 
        $date=date("Y-m-d h:i:sa"); 
        $stmt = $this->con->prepare("UPDATE t_topic SET Topics=?, LastEdited=? WHERE TopicId=?");
        $stmt->bind_param("ssi", $topic,$date, $Id);
        $result = $stmt->execute(); 
        $stmt->close();
        if ($result) {
           $retval['retval'] = 0;
		   return $retval;
        } else {
            $retval['retval'] = 1;
			return $retval;
        }
    }
	
	
  public function deleteTopic($Id) { 
        
		if ($this->deleteComment(0,$Id)) { 
		
		 
				$stmt = $this->con->prepare("DELETE FROM t_topic WHERE TopicId=?");
				$stmt->bind_param("i",$Id );
				$result = $stmt->execute(); 
				$stmt->close();
				if ($result) {
				   $retval['retval'] = 0;
				   return $retval;
				} else {
					$retval['retval'] = 1;
					return $retval;
				}	 
				 
             
        } else { 
            $retval['retval'] = 2; 
            return $retval;
        }
        
      
    }
	
	 //Method to post a new Comment
    public function createNewComment($Comments,$Id,$TopicId) {
		 $date=date("Y-m-d h:i:sa"); 
		$stmt = $this->con->prepare("INSERT INTO t_comments(TopicId,Id,Comments,LastDate) values(?, ?, ?, ?)");
		$stmt->bind_param("iiss",$TopicId, $Id, $Comments, $date);
		$result = $stmt->execute();
		$retval['lastInsertedId']=$stmt->insert_id; 
		$stmt->close();
		if ($result) {
			$retval['retval'] = 0;
			
			return $retval;
		} else {
			$retval['retval'] = 1; 
			return $retval;
		}
			
			 
    }
	
	public function updateComment($Comments,$Id) {
		 $date=date("Y-m-d h:i:sa"); 
		$stmt = $this->con->prepare("UPDATE t_comments SET Comments=?,LastDate=? WHERE CommentsId=? ");
		$stmt->bind_param("ssi",$Comments, $date,$Id);
		$result = $stmt->execute();
		
		$stmt->close();
		if ($result) {
			$retval['retval'] = 0;
			
			return $retval;
		} else {
			$retval['retval'] = 1; 
			return $retval;
		}
			
			 
    }
	
    public function deleteComment($Id,$TopicId) {  
	    $condition=$Id==0? " TopicId=? ":" CommentsId=? ";
		$id=$Id==0? $TopicId:$Id;
		$stmt = $this->con->prepare("DELETE FROM t_comments WHERE ".$condition."");
		$stmt->bind_param("i", $id);
		$result = $stmt->execute();
		$stmt->close();
		if ($result) {
			$retval['retval'] = 0; 
			return $retval;
		} else {
			$retval['retval'] = 1; 
			return $retval;
		}
    }
	
	
	 public function getTopic($Id) {
        $stmt = $this->con->prepare("SELECT t_usertable.Id,  t_usertable.EmailAddress, t_usertable.FirstName,t_usertable.LastName  
		FROM t_usertable WHERE Id=?");
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $EmailAddress = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $EmailAddress;
    }
	
	
	 //Method to get Employee details
    public function getEmployee($EmailAddress) {
        $stmt = $this->con->prepare("SELECT t_usertable.Id,  t_usertable.EmailAddress, t_usertable.FirstName,t_usertable.LastName  
		FROM t_usertable WHERE EmailAddress=?");
        $stmt->bind_param("s", $EmailAddress);
        $stmt->execute();
        $EmailAddress = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $EmailAddress;
    }
	
	
	
	
	
	
	
	

}
