<?php
require_once("server/DBManager.php");

$db = new DBManager;

ignore_user_abort(true);

function sampling($chars, $size, $combinations = array()) {

    # if it's the first iteration, the first set 
    # of combinations is the same as the set of characters
    if (empty($combinations)) {
        $combinations = $chars;
    }

    # we're done if we're at size 1
    if ($size == 1) {
        return $combinations;
    }

    # initialise array to put new values in
    $new_combinations = array();

    # loop through existing combinations and character set to create strings
    foreach ($combinations as $combination) {
        foreach ($chars as $char) {
            $new_combinations[] = $combination . $char;
        }
    }

    # call same function again for the next iteration
    return sampling($chars, $size - 1, $new_combinations);

}

for($i = 1; $i < 6;$i++){
	$chars = array(
		'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
		'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		'1','2','3','4','5','6','7','8','9',
		'.',',','!','@','#','$','%','^','&','*','(',')','-','_','=','+','|','[',']','{','}','?',';',':','<','>'
	);
	$permutations = sampling($chars,$i);
	
	$values = "";
	
	for($x = 0; $x < count($permutations);$x++){
		$md5 = md5($permutations[$x]);
		$sha1 = sha1($permutations[$x]);
		
		//Check if string exists in the database
		$res = $db->GetRow(null,null,null,null,"SELECT string,md5_hash FROM hashes WHERE string='$permutations[$x]'");
	
		if($res["md5_hash"] == $md5){
			//String exist move on
			//echo "Record already existed\n";
		}else{
			//string does not exist insert it
		
			$db->Insert(null,null,"INSERT INTO hashes SET string='$permutations[$x]',md5_hash='$md5',sha1_hash='$sha1'");
		
			//$db->Insert("hashes",$data);
			//echo "Record Has been inserted\n";
		}
		
		/*if($x+1 == count($permutations)){
			$values .= "('".$permutations[$x]."','".$md5."','".$sha1."')";
		}else{
			$values .= "('".$permutations[$x]."','".$md5."','".$sha1."'),";
		}*/
		//$query = "INSERT INTO hashes(string,md5_hash,sha1_hash) VALUES ('".$permutations[$x]."','$md5','$sha1')";
	}
}
echo "Done";
?>