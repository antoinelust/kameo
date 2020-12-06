<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


if (isset($_POST['action'])) {
    $id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
    $action = isset($_POST["action"]) ? $_POST["action"] : NULL;
    $requestor = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
    $title = isset($_POST["title"]) ? addslashes($_POST["title"]) : NULL;
    $description = isset($_POST["description"]) ? addslashes($_POST["description"]) : NULL;
    $type = isset($_POST["type"]) ? $_POST["type"] : NULL;
    $amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
    $amount_total = isset($_POST["loanTotAmount"]) ? $_POST["loanTotAmount"] : NULL;
    $start = isset($_POST["start"]) ? date($_POST["start"]) : NULL;
    $end = isset($_POST["end"]) ? date($_POST["end"]) : NULL;

    $loanBikeID = isset($_POST["loanBikeID"]) ? $_POST["loanBikeID"] : NULL;
    $loanbikeModel = isset($_POST["bikeBrandModelSel"]) ? $_POST["bikeBrandModelSel"] : NULL;
    $loanFrameNumber = isset($_POST["loanFrameNumber"]) ? $_POST["loanFrameNumber"] : NULL;
    $loanBrand = isset($_POST["loanBrand"]) ? $_POST["loanBrand"] : NULL;
    $loanBikeBuyPrice = isset($_POST["bikepAchat"]) ? $_POST["bikepAchat"] : NULL;

    if (isset($_POST["action"])) {

        if ($type == "monthly" && ($start == NULL || $end == NULL)) {
            errorMessage("ES0054");
        }


        if ($start != NULL) {
            $start = "'" . $start . "'";
        } else {
            $start = 'NULL';
        }

        if ($end != NULL) {
            $end = "'" . $end . "'";
        } else {
            $end = 'NULL';
        }


        if ($_POST["action"] == "add") {
            include 'connexion.php';

            if ($amount_total != NULL) {
                $sql = "INSERT INTO costs (HEU_MAJ, USR_MAJ, TITLE, DESCRIPTION, TYPE, AMOUNT, START, END, STAANN, LOAN_TOTAL) VALUES (CURRENT_TIMESTAMP, '$requestor', '$title', '$description', '$type', '$amount', $start, $end, '', $amount_total)";
                if ($conn->query($sql) === FALSE) {
                    $response = array('response' => 'error', 'message' => $conn->error);
                    echo json_encode($response);
                    die;
                }
            } else {
                $sql = "INSERT INTO costs (HEU_MAJ, USR_MAJ, TITLE, DESCRIPTION, TYPE, AMOUNT, START, END, STAANN) VALUES (CURRENT_TIMESTAMP, '$requestor', '$title', '$description', '$type', '$amount', $start, $end, '')";
                if ($conn->query($sql) === FALSE) {
                    $response = array('response' => 'error', 'message' => $conn->error);
                    echo json_encode($response);
                    die;
                }
            }

            $conn->close();
            $response['sql'] = $sql;
            successMessage("SM0019");
        } else if ($_POST["action"] == "update") {

            include 'connexion.php';

            if ($type == "loan") {
                if ($amount_total == NULL) {
                    errorMessage("Loan amount NULL");
                }
                $sql = "UPDATE costs SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$requestor', TITLE='$title', DESCRIPTION='$description', AMOUNT='$amount', LOAN_TOTAL='$amount_total', START=$start, END=$end, TYPE='$type' WHERE ID='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array('response' => 'error', 'message' => $conn->error);
                    echo json_encode($response);
                    die;
                }

                $i = 0;
                if ($loanBikeID) {
                    while ($i < count($loanBikeID)) {
                        $sql = "REPLACE INTO loan_belfius (ID_BIKE, MODEL, FRAME_NUMBER, BRAND, BUY_PRICE, COST_ID) VALUES ($loanBikeID[$i], '$loanbikeModel[$i]', '$loanFrameNumber[$i]', '$loanBrand[$i]', $loanBikeBuyPrice[$i], $id)";
                        if ($conn->query($sql) === FALSE) {
                            $response = array('response' => 'error', 'message' => $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $i++;
                    }
                }
            } else {
                $sql = "UPDATE costs SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$requestor', TITLE='$title', DESCRIPTION='$description', AMOUNT='$amount', START=$start, END=$end, TYPE='$type' WHERE ID='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array('response' => 'error', 'message' => $conn->error);
                    echo json_encode($response);
                    die;
                }
            }

            $conn->close();
            $response['sql'] = $sql;
            successMessage("SM0020");
        }
    } else {
        errorMessage("ES0012");
    }
} else if (isset($_GET['action'])) {
    $action = isset($_GET["action"]) ? $_GET["action"] : NULL;
    $id = isset($_GET["ID"]) ? $_GET["ID"] : NULL;
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;

    if ($action == "retrieve") {

        if ($id) {
            include 'connexion.php';
            $sql = "SELECT * FROM costs WHERE ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }

            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();

            $response['response'] = "success";
            $response['title'] = $resultat['TITLE'];
            $response['description'] = $resultat['DESCRIPTION'];
            $response['type'] = $resultat['TYPE'];
            $response['amount'] = $resultat['AMOUNT'];
            $response['amount_total'] = $resultat['LOAN_TOTAL'];
            $response['start'] = $resultat['START'];
            $response['end'] = $resultat['END'];

            // retrieve loan data
            include 'connexion.php';
            $sql = "SELECT * FROM loan_belfius WHERE COST_ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);

            $response['loanBikesNumber'] = $result->num_rows;
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {

                $response['loanResponse'] = "success";
                $response['loan'][$i]['idBike'] = $row['ID_BIKE'];
                $response['loan'][$i]['model'] = $row['MODEL'];
                $response['loan'][$i]['frameNumber'] = $row['FRAME_NUMBER'];
                $response['loan'][$i]['brand'] = $row['BRAND'];
                $response['loan'][$i]['buyPrice'] = $row['BUY_PRICE'];

                $i++;
            }
            $sql = "SELECT SUM(BUY_PRICE) as 'totalBuyPrice' FROM loan_belfius WHERE COST_ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }
            $response['sumBikesIncluded'] = round($resultat['totalBuyPrice'], 2);

            $conn->close();


            echo json_encode($response);

            die;
        } else if ($company) {


            include 'connexion.php';
            $sql = "SELECT COMPANY, CONTRACT_START, CONTRACT_END, SUM(LEASING_PRICE) as 'PRICE', COUNT(1) AS 'BIKE_NUMBER' FROM customer_bikes WHERE LEASING='Y'";
            if ($company != "*") {
                $sql = $sql . " AND COMPANY='$company'";
            }
            $sql = $sql . " GROUP BY COMPANY, CONTRACT_START, CONTRACT_END";

            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }


            $result = mysqli_query($conn, $sql);
            $conn->close();

            $response['contractsNumber'] = $result->num_rows;
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {

                $response['response'] = "success";
                $response['contract'][$i]['company'] = $row['COMPANY'];
                $response['contract'][$i]['description'] = $row['BIKE_NUMBER'] . " vélos en leasing";
                $response['contract'][$i]['amount'] = $row['PRICE'];
                $response['contract'][$i]['start'] = $row['CONTRACT_START'];
                $response['contract'][$i]['end'] = $row['CONTRACT_END'];
                $i++;
            }

            include 'connexion.php';
            $sql = "SELECT * FROM offers WHERE STAANN != 'D'";
            if ($company != "*") {
                $sql = $sql . " AND COMPANY='$company'";
            }

            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $response['offersNumber'] = $result->num_rows;
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {

                $response['response'] = "success";
                $response['offer'][$i]['id'] = $row['ID'];
                $response['offer'][$i]['company'] = $row['COMPANY'];
                $response['offer'][$i]['type'] = $row['TYPE'];
                $response['offer'][$i]['title'] = $row['TITRE'];
                $response['offer'][$i]['amount'] = $row['AMOUNT'];
                $response['offer'][$i]['probability'] = $row['PROBABILITY'];
                $response['offer'][$i]['start'] = $row['START'];
                $response['offer'][$i]['end'] = $row['END'];
                $response['offer'][$i]['margin'] = $row['MARGIN'];
                $i++;
            }


            /////////////////////


            include 'connexion.php';
            $sql = "SELECT * FROM costs WHERE STAANN != 'D' AND END>CURRENT_TIMESTAMP";


            if ($conn->query($sql) === FALSE) {
                $response = array('response' => 'error', 'message' => $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $response['costsNumber'] = $result->num_rows;
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {

                $response['response'] = "success";
                $response['cost'][$i]['id'] = $row['ID'];
                $response['cost'][$i]['type'] = $row['TYPE'];
                $response['cost'][$i]['title'] = $row['TITLE'];
                $response['cost'][$i]['description'] = $row['DESCRIPTION'];
                $response['cost'][$i]['amount'] = $row['AMOUNT'];
                $response['cost'][$i]['start'] = $row['START'];
                $response['cost'][$i]['end'] = $row['END'];
                $i++;
            }


            echo json_encode($response);
            die;
        } else {
            errorMessage("ES0012");
        }
    }
} else {
    errorMessage("ES0012");
}
