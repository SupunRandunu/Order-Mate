<?php


	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: *");
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
		
		$name=$request->n;  
		$description=$request->des; 		
		$price=$request->pri; 
		$category=$request->cate;
		$location=$request->loc;
		$user=$request->usr; 
		
		
		$conn = new mysqli("localhost", "root", "", "foodkart");
		
		// To protect MySQL injection for Security purpose
		$name = stripslashes($name);
		$description = stripslashes($description);
		$price= stripslashes($price);
		$category = stripslashes($category);
		$location = stripslashes($location);
		$user = stripslashes($user);
		
		$name = $conn->real_escape_string($name);
		$description = $conn->real_escape_string($description);
		$price = $conn->real_escape_string($price);
		$category = $conn->real_escape_string($category);
		$location = $conn->real_escape_string($location);
		$user = $conn->real_escape_string($user);
		
		
		// $check="SELECT count(*) FROM products WHERE p_id = '$name' AND p_user = '$user'";
		// $rs = mysqli_query($conn,$check);
		// $data = mysqli_fetch_array($rs, MYSQLI_NUM);
		
		//print_r($data);
		// if($data[0] > 0) {
		// 	$outp='{"result":{"created": "0" , "exists": "1" } }';
		// }
		// else{	
			$sql = "INSERT INTO products VALUES ('','$name', '$description', '$category', '','$price' ,'1','100','$location','$user')";		
			if ($conn->query($sql) === TRUE) {
				$outp='{"result":{"created": "1", "exists": "0" } }';
			} 
		// }
		
		echo $outp;
		
		// $conn->close();	
	
}

?> 