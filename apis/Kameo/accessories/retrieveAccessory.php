<?php
global $conn;
$ID=isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : NULL;

if($ID){
    include '../connexion.php';
    $stmt = $conn->prepare("SELECT ID, BRAND, DESCRIPTION, CATEGORY, ACCESSORIES_CATEGORIES, BUYING_PRICE, PRICE_HTVA, STOCK, SHOW_ACCESSORIES,DISPLAY, PROVIDER, ARTICLE_NBR FROM accessories_catalog WHERE ID=?");
    
    if ($stmt)
    {
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $response['response']="success";
        $response['accessory']=$stmt->get_result()->fetch_array(MYSQLI_ASSOC);

        //$file=__DIR__.'/images_accessories/'.$response['ID'].'jpg';
    //if ((file_exists($file))){
    //    $response['img']=__DIR__.'/images_accessories/'.$response['ID'];
    //}else{
    //    $response['img']=$response['ID'];
    //}

        $stmt->close();
        echo json_encode($response);
        die;

    }else
        error_message('500', 'Unable to retrieve accessory');
}else
    error_message('500', 'Unable to retrieve accessory');
?>
