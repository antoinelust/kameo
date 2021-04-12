<?php
global $conn;
$response=execSQL("SELECT factures.ID, BENEFICIARY_COMPANY, DATE, factures.AMOUNT_HTVA, FILE_NAME, (SELECT COUNT(customer_bikes.BIKE_PRICE) FROM customer_bikes, bills_catalog_bikes_link WHERE factures.ID=bills_catalog_bikes_link.FACTURE_ID AND bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) as countBikes,
(SELECT SUM(customer_bikes.BIKE_PRICE) FROM customer_bikes, bills_catalog_bikes_link WHERE factures.ID=bills_catalog_bikes_link.FACTURE_ID AND bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) as sumBikes,
(SELECT SUM(BUYING_PRICE) FROM bills_catalog_bikes_link WHERE bills_catalog_bikes_link.FACTURE_ID=factures.ID) as sumBikesCatalog,
(SELECT COUNT(1) FROM bills_catalog_bikes_link WHERE bills_catalog_bikes_link.FACTURE_ID=factures.ID) as countBikesCatalog
FROM factures WHERE (BENEFICIARY_COMPANY IN ('Ange et demon (GEEBEE)', 'Velo decathlon', 'Bzen', 'Bike Avenue', 'Cycle Me', 'Hartje', 'Galerie du cycle', 'HNF Nicolai', 'Evobikes', 'Ahooga', 'Guru Sport', 'Firma', 'Douze Cycles', 'DOUZE FACTORY', 'La roue Libre') AND AMOUNT_HTVA<(-1000)) OR ID='626'
ORDER BY `sumBikesCatalog`  DESC", array(), false);
echo json_encode($response);
die;
$stmt->close();
?>
