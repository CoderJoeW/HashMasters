<?php
class DBManager{
    private $credentials = array(
        "host" => "localhost",
        "user" => "tester",
        "pass" => "l00k0u1",
        "db" => "hashes"
    );

    function DBManager(){
        $this->ConnectToDBServer();
    }

    function ConnectToDBServer(){
        mysql_connect($this->credentials["host"],$this->credentials["user"],$this->credentials["pass"]) or die(mysql_error());
        $this->ConnectToDB();
        session_start();
    }

    function ConnectToDB(){
        mysql_select_db($this->credentials["db"]) or die(mysql_error());
    }

    function Insert($tableName,$data,$custom = null){
		$query = "";
		
		if($custom == null){
			$parameters = '';

        	$len = count($data);
        	$i = 0;
        	foreach($data as $key => $value){
            	if(++$i === $len){
                	$parameters .= $key . "='$value'";
            	}else{
                	$parameters .= $key . "='$value'" . ", ";
            	}
        	}

        	$query = "INSERT INTO $tableName SET $parameters";
		}else{
			$query = $custom;
		}

        mysql_query($query);
    }
	
	function Update($tableName,$data,$where){
		$parameters = '';
		
		$len = count($data);
		$i = 0;
		foreach($data as $key => $value){
			if(++$i === $len){
				$parameters .= $key . "='$value'";
			}else{
				$parameters .= $key . "='$value'" . ", ";
			}
		}
		
		$whereAt = '';

        foreach($where as $key => $value){
            $whereAt .= $key . "='$value'";
        }
		
		$query = "UPDATE $tableName SET $parameters WHERE $whereAt";
		$result = mysql_query($query);
	}
	
	function Delete($tableName,$where){
		$parameters = '';
		
		$whereAt = '';

        foreach($where as $key => $value){
            $whereAt .= $key . "='$value'";
        }
		
		$query = "DELETE FROM $tableName WHERE $whereAt";
		$result = mysql_query($query);
	}

    function GetRow($tableName,$select,$where,$order = null,$custom = null){
		$query = "";
		
		if($custom == null){
			$selection = '';

        	$len = count($select);
        	$i = 0;
        	foreach($select as $key){
        	    if(++$i === $len){
        	        $selection .= $key;
        	    }else{
        	        $selection .= $key . ",";
        	    }
        	}
			
        	$whereAt = '';
			
			if($where != null){
				foreach($where as $key => $value){
        	    	$whereAt .= $key . "='$value'";
        		}
			}
			
			if($order != null && $where != null){
				$query = "SELECT $selection FROM $tableName WHERE $whereAt $order";
			}else if($order != null && $where == null){
				$query = "SELECT $selection FROM $tableName $order";
			}else{
				$query = "SELECT $selection FROM $tableName WHERE $whereAt";
			}
		}else{
			$query = $custom;
		}
		
        $result = mysql_query($query);

        while($row = mysql_fetch_array($result)){
            return $row;
        }
    }
	
	function GetResults($tableName,$select,$where = null){
		$data = array();
		
		$selection = '';

        $len = count($select);
        $i = 0;
        foreach($select as $key){
            if(++$i === $len){
                $selection .= $key;
            }else{
                $selection .= $key . ",";
            }
        }
		
		if($where != null){
			$whereAt = '';

        	foreach($where as $key => $value){
            	$whereAt .= $key . "='$value'";
        	}
		
			$query = "SELECT $selection FROM $tableName WHERE $whereAt";
		}else{
			$query = "SELECT $selection FROM $tableName";
		}
        
        $result = mysql_query($query);

        while($row = mysql_fetch_array($result)){
            array_push($data,$row);
        }
		
		return $data;
	}
}
?>