<?php
global $conn;
$ID=isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : NULL;

if($ID){
    include '../connexion.php';

    $sql="SELECT * FROM accessories_catalog, accessories_categories WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID='$ID'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    $response['brand']=$row['BRAND'];
    $response['description']=$row['DESCRIPTION'];
    $response['category']=$row['ACCESSORIES_CATEGORIES'];
    $response['provider']=$row['PROVIDER'];
    $response['articleNbr']=$row['ARTICLE_NBR'];
    $response['buyingPrice']=$row['BUYING_PRICE'];
    $response['sellingPrice']=$row['PRICE_HTVA'];
    $response['stock']=$row['STOCK'];
    $response['display']=$row['DISPLAY'];


    $file=__DIR__.'/images_accessories/'.$row['ID'].'jpg';
    if ((file_exists($file))){
        $response['img']=__DIR__.'/images_accessories/'.$row['ID'];
    }else{
        $response['img']=$row['ID'];
    }
    
    
    $stmt = $conn->prepare("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.ACCESSORIES_CATEGORIES, accessories_catalog.BUYING_PRICE, accessories_catalog.PRICE_HTVA, accessories_catalog.STOCK, accessories_catalog.DISPLAY, accessories_catalog.PROVIDER, accessories_catalog.ARTICLE_NBR, accessories_categories.ID, accessories_categories.CATEGORY FROM accessories_catalog, accessories_categories WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID=?");

    if ($stmt)
    {
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $response['response']="success";
        $response['accessory']=$stmt->get_result()->fetch_array(MYSQLI_ASSOC);

        $stmt->close();
        echo json_encode($response);
        die;

    }else
        error_message('500', 'Unable to retrieve accessory');
}else
    error_message('500', 'Unable to retrieve accessory');
?>
