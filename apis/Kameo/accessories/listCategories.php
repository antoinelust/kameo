<?php
global $conn;
$stmt = $conn->prepare("SELECT * from accessories_categories");
if ($stmt)
{
	$stmt->execute();
    $response['response']="success";
    $response['categories']=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($response);
	$stmt->close();
}else
	error_message('500', 'Unable to retrieve catalog of accessories');
?>