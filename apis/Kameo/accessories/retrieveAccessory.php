<?php
global $conn;
$ID=isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : NULL;
if($ID){
    $stmt = $conn->prepare("SELECT * from accessories_catalog WHERE ID=?");
    if ($stmt)
    {
        $stmt->bind_param("i", $ID);    
        $stmt->execute();
        $response['response']="success";
        $response['accessory']=$stmt->get_result()->fetch_array(MYSQLI_ASSOC);
        echo json_encode($response);
        $stmt->close();
    }else
        error_message('500', 'Unable to retrieve accessory');
}else
    error_message('500', 'Unable to retrieve accessory');
?>