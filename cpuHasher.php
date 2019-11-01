<?php
require_once("server/DBManager.php");

$db = new DBManager;

if(!empty($_POST["string"])){
	$string = $_POST["string"];
	$md5_hash = $_POST["hash"];
	
	$values = "";
	for($i = 0; $i < count($string);$i++){
		if($i+1 == count($string)){
			$values .= "('".$string[$i]."','".$md5_hash[$i]."')";
		}else{
			$values .= "('".$string[$i]."','".$md5_hash[$i]."'),";
		}
	}
	
	
	
	$query = "INSERT INTO hashes(string,md5_hash) VALUES $values";
	mysql_query($query) or die(mysql_error());
	echo "Record Has been inserted\n\n\n\n\nValues: $values";
	
	//Check if string exists in the database
	//$res = $db->GetRow(null,null,null,null,"SELECT string,md5_hash FROM hashes WHERE string='$string'");
	
	//if($res["md5_hash"] == $md5_hash){
		//String exist move on
		//echo "Record already existed\n";
	//}else{
		//string does not exist insert it
		
		/*$data = array(
			"string" => $string,
			"md5_hash" => $md5_hash,
			"sha1_hash" => $sha1_hash
		);*/
		
		//$db->Insert(null,null,"INSERT DELAYED INTO hashes SET string='$string',md5_hash='$md5_hash'");
		
		//$db->Insert("hashes",$data);
		//echo "Record Has been inserted\n";
	//}
}else{
	echo "One or more post values were not set.... Json: " . $_POST["json"];
}
echo "Script Finished Execution";
?>