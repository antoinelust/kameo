<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(!isset($_SESSION['langue'])){
    $_SESSION['langue']="fr";
}

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

function checkAccess(){
/* 	include 'connexion.php';
	$user=$_SESSION['userID'];
	$sql = "SELECT * FROM customer_referential where EMAIL='$user'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if( $row["PASSWORD"]==NULL || $_SESSION['UserPassword']<>$row["PASSWORD"])
	{	
		$_SESSION['login']=false;
		header('Location: index.php');
		exit();
	} */
}


function getClientData(){
	include 'connexion.php';
	$sql = "select customer_referential.EMAIL
from customer_referential aa, customer_bikes bb, bike_models cc, handle_model dd, saddle_model ee, tires_model ff, color_proposed gg, color_proposed hh, color_proposed ii 
where aa.EMAIL='antoine.lust@hotmail.fr' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID 
and bb.HANDLE_MODEL=dd.ID and bb.SADDLE_MODEL=ee.ID and bb.TIRES_MODEL=ff.ID 
and bb.PEDAL_COLOR=gg.COLOR_ID and bb.HANDLE_COLOR=hh.COLOR_ID and bb.WIRES_COLOR=ii.COLOR_ID";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if( $row["PASSWORD"]==NULL || $_SESSION['$UserPassword']<>$row["PASSWORD"])
	{	
		$_SESSION['login']=false;
		header('Location: index.php');
		exit();
	}
	$conn->close();	
	return $row;
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
        echo 'Curl error: ' . curl_error($curl_handle);
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

?>