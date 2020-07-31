<?php
if(!isset($_SESSION))
    session_start();
if(!isset($_SESSION['langue']))
    $_SESSION['langue']="fr";

function errorMessage($MSGNUM) {
    include 'connexion.php';
	$sql = "SELECT * FROM error_messages where MSGNUM='$MSGNUM' ";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($_SESSION['langue']=='fr')
	{
		$response = array ('response'=>'error', 'message'=> $row["TEXT_FR"]);
	}
	elseif ($_SESSION['langue']=='en')
	{
		$response = array ('response'=>'error', 'message'=> $row["TEXT_EN"]);		
	}
	elseif ($_SESSION['langue']=='nl')
	{
		$response = array ('response'=>'error', 'message'=> $row["TEXT_NL"]);		
	}
	else
	{
		$response = array ('response'=>'error', 'message'=> $row["TEXT_FR"]);		
	}
	echo json_encode($response);
	die;
}

function successMessage($MSGNUM) {
    include 'connexion.php';
	$sql = "SELECT * FROM success_messages where MSGNUM='$MSGNUM' ";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($_SESSION['langue']=='fr')
	{
		$response = array ('response'=>'success', 'message'=> $row["TEXT_FR"]);
	}
	elseif ($_SESSION['langue']=='en')
	{
		$response = array ('response'=>'success', 'message'=> $row["TEXT_EN"]);		
	}
	elseif ($_SESSION['langue']=='nl')
	{
		$response = array ('response'=>'success', 'message'=> $row["TEXT_NL"]);		
	}
	else
	{
		$response = array ('response'=>'success', 'message'=> $row["TEXT_FR"]);		
	}
	echo json_encode($response);
	die;
}

function getAPIData($url1){
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$url1);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
    curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($curl_handle, CURLOPT_STDERR, $verbose);
	$query = curl_exec($curl_handle);
    if(curl_errno($curl_handle)){
		$response = array ('response'=>'error', 'message'=> curl_error($curl_handle));
		echo json_encode($response);
		die;
    }
    return $query;
	curl_close($curl_handle);
}



function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $errors = curl_error($curl);
    $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);        
    curl_close($curl);
    return $result;
}

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}

function last_day_month($month){
    $lastDay=[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    return $lastDay[($month-1)];
}

function execute_sql_query($sql, $conn){
    $result = $conn->query($sql);
    if ($result === FALSE) {
        $response = array ('response'=>'error', 'message'=> $sql->error);
        echo json_encode($response);
        die;
    }
    
    return $result->fetch_all();
}

function error_message($type){
	switch($type)
	{
		case '401':
			header("HTTP/1.0 401 Unauthorized");
            $response = array ('error'=>'invalid_token', 'error_message'=> 'The access token is invalid');
            echo json_encode($response);
            die;
			break;
		case '403':
			header("HTTP/1.0 403 Forbidden");
            $response = array ('error'=>'insufficient_privileges', 'error_message'=> "Your access token doesn't allow you to perfom this action");
            echo json_encode($response);
            die;
			break;
		case '405':
			header("HTTP/1.1 405 Method Not Allowed");
            $response = array ('error'=>'unallowed_method', 'error_message'=> 'This method is not allowed on this endpoint');
            echo json_encode($response);
            die;            
			break;
    }
}

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length = 32,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}
?>