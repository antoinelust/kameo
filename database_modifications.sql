/* !!! Fichier à supprimer avant merge en master*/



/*==========BOX_CATALOG==========*/

/*Creation de la table box_catalog*/
CREATE TABLE `kameobiknq`.`boxes_catalog` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `HEU_MAJ` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `USR_MAJ` VARCHAR(150) NULL , `MODEL` VARCHAR(50) NOT NULL , `PRODUCTION_PRICE` FLOAT NOT NULL , `INSTALLATION_PRICE` FLOAT NOT NULL , `LOCATION_PRICE` FLOAT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Ajout de données*/
INSERT INTO `boxes_catalog` (`ID`, `HEU_MAJ`, `USR_MAJ`, `MODEL`, `PRODUCTION_PRICE`, `INSTALLATION_PRICE`, `LOCATION_PRICE`) VALUES (NULL, CURRENT_TIMESTAMP, NULL, '5 Clés', '500', '500', '200'), (NULL, CURRENT_TIMESTAMP, NULL, '10 Clés', '700', '1000', '250'), (NULL, CURRENT_TIMESTAMP, NULL, '20 Clés', '900', '1500', '325');



/*==========CATEGORIES==========*/

/*Creation de la table accessories_categories*/
CREATE TABLE `kameobiknq`.`accessories_categories` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `CATEGORY` VARCHAR(50) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Ajout des catégories*/
INSERT INTO `accessories_categories` (`ID`, `CATEGORY`) VALUES (NULL, 'sacoche'), (NULL, 'cadenas'), (NULL, 'casque'), (NULL, 'textiles'), (NULL, 'entretien');

/*Creation de la table accessories*/
CREATE TABLE `kameobiknq`.`accessories_catalog` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `NAME` VARCHAR(100) NOT NULL , `BUYING_PRICE` FLOAT NOT NULL , `PRICE_HTVA` FLOAT NOT NULL , `STOCK` BOOLEAN NOT NULL , `SHOW_ACCESSORIES` BOOLEAN NOT NULL , `DESCRIPTION` TEXT NOT NULL , `ACCESSORIES_CATEGORIES` INT(3) NOT NULL, PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Creation de la clé étrangère reliant accessories a accessories_categories*/
ALTER TABLE accessories_catalog
ADD FOREIGN KEY (ACCESSORIES_CATEGORIES) REFERENCES accessories_categories(ID);
