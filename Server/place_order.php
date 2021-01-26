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
		
		
		// $cart=$request->cart[0]; 		
		// var_dump($request);
		
		$conn = new mysqli("localhost", "root", "stunt5800.", "foodkart");
		
		$user="";
		$qty=0;
		foreach ($request as $item) {
    		
    		// var_dump($item);
    		// $item = stripslashes($item->cart_item_id);
			 // $item->cart_item_owner;
			 // $item->cart_item_qty;
			
			
			
			// $item = $conn->real_escape_string($item);
			// $user = $conn->real_escape_string($user);
			// $qty = $conn->real_escape_string($qty);

			//print_r($data);
				
				
			
				$sql = "INSERT INTO orders(o_product_id,o_user,o_dileverd, o_quntity, o_delivery) VALUES (".(int)$item->cart_item_id." , ".(int)$item->cart_item_owner.", 0,".(int)$item->cart_item_qty." ,0 )";		
				
				

				if ($conn->query($sql) === TRUE) {
					$outp='{"result":{"created": "1", "exists": "0" } }';
					
				} 
				else {
					$outp='{"result":{"created": "0" , "exists": "1" } }';
					
				}

				
		

			

		}
		
		
		echo $outp;
		
		$conn->close();	
	
}

?> 