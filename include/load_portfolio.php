<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


try{
    /*$frameType=$_POST['frameType'];
    $utilisation=$_POST['utilisation'];
    $price=$_POST['price'];
    $brand=$_POST['brand'];
    $electric=$_POST['electric'];
    */
    
    $frameType="*";
    $utilisation="*";
    $price="*";
    $brand="*";
    $electric="*";


    $response=array();

    if($frameType != NULL && $utilisation != NULL && $price != NULL && $brand != NULL && $electric != NULL)
    {


        include 'connexion.php';
        $sql="SELECT *  FROM bike_catalog WHERE STOCK > 0";

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
        $sql=$sql." ORDER BY BRAND";
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
            $response['bike'][$i]['brand']=$row['BRAND'];
            $response['bike'][$i]['model']=$row['MODEL'];            
            $response['bike'][$i]['frameType']=$row['FRAME_TYPE'];            
            $response['bike'][$i]['utilisation']=$row['UTILISATION'];
            $response['bike'][$i]['electric']=$row['ELECTRIC'];

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

}  catch (Exception $e) {
    $response['response']="error";
    $response['message']=$e->getMessage();
    echo json_encode($response);
    die;

}

?>