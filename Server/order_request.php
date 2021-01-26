<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "stunt5800.", "foodkart");

	$till=10;
	$user=0;
	
	if(isset($_GET["till"]) && !empty($_GET["till"]) ){
		$till = $_GET["till"];
		$till = $conn->real_escape_string($till);
	}

	if(isset($_GET["user"]) && !empty($_GET["user"]) ){
		$user = $_GET["user"];
		$user = $conn->real_escape_string($user);
	}


	// $q="SELECT p_id,p_name,p_description,p_image_id,p_price,p_location,o_id FROM orders INNER JOIN products ON orders.o_product_id=products.p_id where o_id<=".$till." and o_user=".$user." ";

	
	$r=$conn->query("SELECT o_id FROM orders ORDER BY o_id DESC LIMIT 1");
	$d=$r->fetch_array(MYSQLI_ASSOC);
	$last = $d['o_id'];

	$topResults=$conn->query("SELECT o_id FROM orders ORDER BY o_id ASC LIMIT 1");
	$topData=$topResults->fetch_array(MYSQLI_ASSOC);
	$top = $topData['o_id'];
	$filter = $top+$till;




	//$query="SELECT p_id,p_name,p_description,p_image_id,p_price,p_location,o_id,o_dileverd FROM orders INNER JOIN products ON orders.o_product_id=products.p_id where o_id<=".$filter." and p_user=".$user." ";
	$query="SELECT p_id,p_name,p_description,p_image_id,p_price,p_location,o_id,u_address,o_dileverd,u_phone FROM ((orders INNER JOIN products ON orders.o_product_id=products.p_id) INNER JOIN users ON orders.o_user = users.u_name) where o_id<=".$filter." and o_user=".$user." ";


	// if(isset($_GET["category"]) && !empty($_GET["category"]) ){
		
	// 	$cat = $_GET["category"];
	// 	$cat = stripslashes($cat);
	// 	$cat = $conn->real_escape_string($cat);
	// 	$query=$query."and p_category like ".$cat." ";
		
	// }
	
	if( isset($_GET["sort"]) && !empty($_GET["sort"]) ){
		
		$s = $_GET["sort"];
		if($s=="n"){	$query.="order by p_name";}
		else if($s=="plh"){	$query.="order by p_price";}
		else if($s=="phl"){	$query.="order by p_price desc";}
	}

	
	

$result = $conn->query($query);
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"p_id":"'  . $rs["p_id"] . '",';
    $outp .= '"p_name":"'   . $rs["p_name"]        . '",';
	$outp .= '"p_description":"'   . $rs["p_description"]        . '",';
	$outp .= '"p_image_id":"'   . $rs["p_image_id"]        . '",';
	$outp .= '"p_location":"'   . $rs["p_location"]        . '",';
	$outp .= '"o_id":"'   . $rs["o_id"]        . '",';
	$outp .= '"o_dileverd":"'   . $rs["o_dileverd"]        . '",';
	$outp .= '"u_address":"'   . $rs["u_address"]        . '",';
	$outp .= '"u_phone":"'   . $rs["u_phone"]        . '",';
	$outp .= '"p_price":"'. $rs["p_price"]     . '"}';
}


// Adding has more
$result=$conn->query("SELECT count(*) as total from products");
$data=$result->fetch_array(MYSQLI_ASSOC);
$total = $data['total'];

if((($last-$top)-$till)>0){
	$has_more=($last-$top)-$till;
}else {
	$has_more=0;
}			
				
	$outp ='{"has_more":'.$has_more.',"records":['.$outp.']}';

	
$conn->close();

echo($outp);
?> 