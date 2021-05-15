<?php
if(!isset($_SESSION))
    session_start();
if(!isset($_SESSION['langue']))
    $_SESSION['langue']="fr";

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';


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
  error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - ERROR_MESSAGE - ".$row["TEXT_FR"]."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');
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
  error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - SUCCESS_MESSAGE - ".$row["TEXT_FR"]."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');

	echo json_encode($response);
	die;
}

function getAPIData($url1){
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$url1);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    if(constant('ENVIRONMENT')=="local"){
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
    }

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
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    return $result;
}

function error_message($type, $message = ""){
	header("Content-Type: application/problem+json");
  error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - ERROR_MESSAGE_AUTHORIZATION - ".$type." : ".$message."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');

	switch($type)
	{
		case '400':
			header("HTTP/1.0 400 Bad Request");
			$message = ($message === "") ? "One or several parameters are missing or malformed" : $message;
            $response = array ('error'=>'malformed_syntax', 'error_message'=> $message);
            echo json_encode($response);
            die;
			break;
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
		case '404':
			header("HTTP/1.0 404 Not Found");
            $response = array ('error'=>'not_found', 'error_message'=> "The requested endpoint cannot be found");
            echo json_encode($response);
            die;
			break;
		case '405':
			header("HTTP/1.0 405 Method Not Allowed");
            $response = array ('error'=>'unallowed_method', 'error_message'=> 'This method is not allowed on this endpoint');
            echo json_encode($response);
            die;
			break;
		case '500':
			header("HTTP/1.0 500 Internal Server Error");
			$message = ($message === "") ? "Internal Server Error" : $message;
            $response = array ('error'=>'internal_error', 'error_message'=> $message);
            echo json_encode($response);
            die;
			break;
    }
}

function get_image($id){
    include 'connexion.php';
    $image_url = "";

    $sql="SELECT ID, BRAND, MODEL, FRAME_TYPE FROM bike_catalog WHERE ID='$id'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);


    $file=__DIR__.'/images_bikes/'.$resultat['ID'].'jpg';
    if ((file_exists($file))){
        $image_url =__DIR__.'/images_bikes/'.$resultat['ID'];
    }else{
        $image_url = strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($resultat['FRAME_TYPE']);
    }

    return $image_url;
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


function log_inputs($token = null){
  error_log("\n \n ----------------------------------------------------------------------\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');

  if($token != null){
    error_log(date("Y-m-d H:i:s")." - TOKEN : ".$token."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach($_POST as $key => $value)
    {
      if($value != '' && gettype($value) != 'array'){
        error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - INPUT - ".$key." : ".$value."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');
      }
    }
  }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    foreach($_GET as $key => $value)
    {
      if($value != ''){
        error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - INPUT - ".$key." : ".$value."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');
      }
    }

  }
}
function log_output($response = null){
  if($response != null){
  	error_log(date("Y-m-d H:i:s")." - ".$_SERVER['REQUEST_URI']." - SUCCESS - Response: ".count($response, COUNT_RECURSIVE)."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');
  }
}


function br2nl( $input ) {
    return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
}


function getCondition($company=NULL){
  if($company==NULL){
    $token = getBearerToken();
    require $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
    $sql = "SELECT aa.CONDITION_REFERENCE from specific_conditions aa, customer_referential bb WHERE aa.EMAIL=bb.EMAIL and TOKEN='$token' AND aa.STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    if($length>0){
      $resultat=mysqli_fetch_assoc($result);
      $ID=$resultat['CONDITION_REFERENCE'];
      $stmt = $conn->prepare("SELECT * from conditions WHERE ID=?");
      if ($stmt)
      {
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $response['conditions']=$stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $response;
      } else
            error_message('500', 'Error occured while changing data');
    }else{
      $stmt = $conn->prepare("SELECT * from conditions WHERE NAME='generic' AND COMPANY = (SELECT COMPANY FROM customer_referential WHERE TOKEN=?)");
      if ($stmt)
      {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $response['conditions']=$stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $response;
      } else
            error_message('500', 'Error occured while changing data');
    }
  }else{
    require $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
    $stmt = $conn->prepare("SELECT * from conditions WHERE COMPANY=? AND NAME='generic'");
    if ($stmt)
    {
      $stmt->bind_param("s", $company);
      $stmt->execute();
      $response['conditions']=$stmt->get_result()->fetch_assoc();
      $stmt->close();
      return $response;
    } else
          error_message('500', 'Error occured while changing data');

  }
}

/*Exemples to use for next function:

execSQL("SELECT * FROM table WHERE id = ?", array('i', $id), false);

execSQL("SELECT * FROM table", array(), false);

execSQL("INSERT INTO table(id, name) VALUES (?,?)", array('ss', $id, $name), true);

*/


function execSQL($sql, $params, $close){
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
  $stmt = $conn->prepare($sql) or die ("Failed to prepare the statement : ".$conn->error);
  if($params){
    call_user_func_array(array($stmt, 'bind_param'), refValues($params));
  }
  if( !$stmt->execute() ){
    echo json_encode($stmt->error);
    die;
  }
  if($close){
     $result = $conn->insert_id;
  } else {
    $meta = $stmt->result_metadata();

    while ( $field = $meta->fetch_field() ) {
      $parameters[] = &$row[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), refValues($parameters));

    while ( $stmt->fetch() ) {
       $x = array();
       foreach( $row as $key => $val ) {
          $x[$key] = $val;
       }
       $results[] = $x;
    }
    if(isset($results)){
      $result = $results;
    }else{
      $result = array();
    }
  }

  $stmt->close();
  $conn->close();
  return  $result;
}

function refValues($arr){
  if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
  {
      $refs = array();
      foreach($arr as $key => $value)
          $refs[$key] = &$arr[$key];
      return $refs;
  }
  return $arr;
}

?>
