<?php
/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }else{
        if(!empty($_SESSION['bearerToken'])){
            return $_SESSION['bearerToken'];
        }
    }
    return null;
}

function authenticate($token){
    if($token != NULL){
        include 'connexion.php';
        $stmt = $conn->prepare("SELECT * from customer_referential WHERE TOKEN = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();    
		$conn->close();
        return ($result->num_rows===1);
    }else{
        return false;
    }
}

function get_user_permissions($accessDemand, $token){
	if ($token != NULL)
		if(isset($_SESSION['permissions']) && $_SESSION['permissions'] !== ""){
			if (is_array($accessDemand))
				return !empty(array_intersect($accessDemand, $_SESSION['permissions']));
			else
				return (in_array($accessDemand, $_SESSION['permissions'], TRUE));
		}else{
			include 'connexion.php';
			$stmt = $conn->prepare("SELECT ACCESS_RIGHTS from customer_referential WHERE TOKEN = ?");
			$stmt->bind_param("s", $token);
			$stmt->execute();    
			$permissions = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ACCESS_RIGHTS'];
			$permissions=explode(",", $permissions);
			$_SESSION['permissions']=$permissions;
			$stmt->close();    
			$conn->close();
			if (is_array($accessDemand))
				return !empty(array_intersect($accessDemand, $_SESSION['permissions']));
			else
				return (in_array($accessDemand, $permissions, TRUE));
		}
	else
		return false;
}

?>