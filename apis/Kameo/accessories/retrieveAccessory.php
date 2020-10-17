<?php
global $conn;
$ID=isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : NULL;

if($ID){
    include '../connexion.php';



    $stmt = $conn->prepare("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.ACCESSORIES_CATEGORIES, accessories_catalog.BUYING_PRICE, accessories_catalog.PRICE_HTVA, accessories_catalog.STOCK, accessories_catalog.DISPLAY, accessories_catalog.PROVIDER, accessories_catalog.ARTICLE_NBR, accessories_categories.ID, accessories_categories.CATEGORY, accessories_catalog.REFERENCE 
    FROM accessories_catalog, accessories_categories
    WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID=?");

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
