<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


try{
    if(isset($_GET['action'])){
        $action=isset($_GET['action']) ? $_GET['action'] : NULL;
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
        
        if($action=="list"){
            $frameType="*";
            $utilisation="*";
            $price="*";
            $brand="*";
            $electric="*";
            $type="*";
            
            $response=array();

            if($frameType != NULL && $utilisation != NULL && $price != NULL && $brand != NULL && $electric != NULL)
            {


                include 'connexion.php';
                $sql="SELECT *  FROM bike_catalog WHERE STAANN != 'D'";

                if($ID != NULL){
                    $sql=$sql." AND ID='".$ID."'";
                }

                if($frameType!="*"){
                    $sql=$sql." AND FRAME_TYPE='".$frameType."'";
                }
                if($utilisation!="*"){
                    $sql=$sql." AND UTILISATION='".$utilisation."'";
                }
                if($price!="*"){
                    if(substr($price, 0, 1)=="+")
                    {
                        $sql=$sql." AND PRICE_HTVA>='".$price."'";
                    } else if(substr($price, 0, 1)=="+")
                    {
                        $sql=$sql." AND PRICE_HTVA<='".$price."'";
                    } else if(substr($price, 0, 7) == "between"){
                        list($string, $price1, $price2)=explode('-', $price);
                        $sql=$sql." AND PRICE_HTVA >= '".$price1."' AND PRICE_HTVA < '".$price2."'";
                    } 
                    else
                    {
                        $sql=$sql." AND PRICE_HTVA='".$price."'";
                    }
                }
                $sql=$sql." ORDER BY BRAND, MODEL";
                if($brand!="*"){
                    $sql=$sql." AND UPPER(BRAND)='".strtoupper($brand)."'";
                }
                if($electric!="*"){
                    $sql=$sql." AND ELECTRIC='".$electric."'";
                }


                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $result = mysqli_query($conn, $sql); 
                $length = $result->num_rows;


                $response['sql']=$sql;


                $i=0;
                $response['response']="success";
                $response['bikeNumber']=$length;


                while($row = mysqli_fetch_array($result))
                {
                    $response['bike'][$i]['ID']=$row['ID'];
                    $response['bike'][$i]['brand']=$row['BRAND'];
                    $response['bike'][$i]['model']=$row['MODEL'];            
                    $response['bike'][$i]['frameType']=$row['FRAME_TYPE'];            
                    $response['bike'][$i]['utilisation']=$row['UTILISATION'];
                    $response['bike'][$i]['electric']=$row['ELECTRIC'];
                    $response['bike'][$i]['stock']=$row['STOCK'];
                    $response['bike'][$i]['display']=$row['DISPLAY'];
                    

                    $price=$row['PRICE_HTVA'];
                    $priceTemp=($price+3*75+4*100+4*100);

                    // Calculation of coefficiant for leasing price

                    if($priceTemp<2500){
                        $coefficient=3.289;
                    }elseif ($priceTemp<=5000){
                        $coefficient=3.056;
                    }elseif ($priceTemp<=12500){
                        $coefficient=2.965;
                    }elseif ($priceTemp<=25000){
                        $coefficient=2.921;
                    }elseif ($priceTemp<=75000){
                        $coefficient=2.898;
                    }else{
                        errorMessage(ES0012);
                    }

                    $leasingPrice=round(($priceTemp)*($coefficient)/100); 	

                    $response['bike'][$i]['leasingPrice']=$leasingPrice;
                    $response['bike'][$i]['buyprice']=$row['BUYING_PRICE'];
                    $response['bike'][$i]['price']=$price;
                    $response['bike'][$i]['url']=$row['LINK'];

                    $i++;

                }

                echo json_encode($response);
                die;

            }
            else
            {
                errorMessage("ES0006");
            }    
        }
        if($action=="retrieve"){
            include 'connexion.php';
            $sql="SELECT * FROM bike_catalog WHERE ID='$ID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $result = mysqli_query($conn, $sql);        
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();
            $response['response']="success";
            $response['ID']=$resultat['ID'];
            $response['brand']=$resultat['BRAND'];
            $response['model']=$resultat['MODEL'];
            $response['frameType']=$resultat['FRAME_TYPE'];
            $response['utilisation']=$resultat['UTILISATION'];
            $response['electric']=$resultat['ELECTRIC'];
            $response['buyingPrice']=$resultat['BUYING_PRICE'];
            $response['portfolioPrice']=$resultat['PRICE_HTVA'];
            $response['stock']=$resultat['STOCK'];
            $response['url']=$resultat['LINK'];
            $response['display']=$resultat['DISPLAY'];
            
            echo json_encode($response);
            die;
            
        }
    }else{
        errorMessage("ES0012");
    }


}  catch (Exception $e) {
    $response['response']="error";
    $response['message']=$e->getMessage();
    echo json_encode($response);
    die;

}

?>