<?php
require_once("server/DBManager.php");

$db = new DBManager;
?>

<!DOCTYPE html>
<html lang ="en">
	<head>
		<title>Hash Search</title>
		<meta charset="utf-8">
	</head>
	
	<body>
		<form method="post">
			<input type="text" name="userInput" placeholder="Text or hash">
			<select name="inputMethod">
				<option value="Text">Text</option>
				<option value="MD5">MD5 Hash</option>
				<option value="SHA1">SHA1 Hash</option>
			</select>
			<input type="submit" name="submit" value="Search">
		</form>
	</body>
</html>

<?php
if(isset($_POST["submit"])){
	$userInput = $_POST["userInput"];
	$inputMethod = $_POST["inputMethod"];
	
	if($inputMethod == "Text"){
		$res = $db->GetResults("hashes",array("*"),array("string" => $userInput));
		
		for($i = 0; $i < count($res);$i++){
			print_r($res[$i]);
			echo "<br><br>";
		}
	}else if($inputMethod == "MD5"){
		$res = $db->GetRow("hashes",array("*"),array("md5_hash" => $userInput));
		
		print_r($res);
	}else if($inputMethod == "SHA1"){
		$res = $db->GetRow("hashes",array("*"),array("sha1_hash" => $userInput));
		
		print_r($res);
	}
}
?>