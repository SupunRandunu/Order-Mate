<?php


	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
 
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
        exit(0);
    }

 
   $postdata = file_get_contents("php://input");


  if (isset($postdata)) {
		$request = json_decode($postdata);
		
		$id=$request->id;  
		
		
		
		$conn = new mysqli("localhost", "root", "stunt5800.", "foodkart");
		
		// To protect MySQL injection for Security purpose
		$id = stripslashes($id);
		
		
		$id = $conn->real_escape_string($id);
		
		
		
		$check="SELECT count(*) FROM orders WHERE o_id = '$id'";
		$rs = mysqli_query($conn,$check);
		$data = mysqli_fetch_array($rs, MYSQLI_NUM);
		
		//print_r($data);
		if($data[0] > 0) {
			// var_dump("expression");
			$sql = "UPDATE orders SET o_dileverd= 1 WHERE o_id= '$id'";	
			// var_dump($conn->query($sql) === TRUE);
			
			if ($conn->query($sql) === TRUE) {
				$outp='{"result":{"created": "1", "exists": "0" } }';
				// var_dump("expression");
			} 
		}
		else{	
			$outp='{"result":{"created": "0" , "exists": "1" } }';
			// var_dump("expression");
		}
		
		echo $outp;
		
		$conn->close();	
	
}

?> 