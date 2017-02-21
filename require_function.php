 <script>
		function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		} 
		function checkNullStr(fieldId,value,msg) {
				if (value == '') {
					  document.getElementById(fieldId).innerHTML =msg; 
				    return 1;
				  }
				  else 
				  {   document.getElementById(fieldId).innerHTML =''; 
					  return 0;
				  }
					  
		}
 </script>