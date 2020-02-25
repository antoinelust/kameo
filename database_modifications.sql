/* !!! Fichier à supprimer avant merge en master*/



/*==========BOX_CATALOG==========*/

/*Creation de la table box_catalog*/
CREATE TABLE `kameobiknqdataba`.`boxes_catalog` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `HEU_MAJ` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `USR_MAJ` VARCHAR(150) NULL , `MODEL` VARCHAR(50) NOT NULL , `PRODUCTION_PRICE` FLOAT NOT NULL , `INSTALLATION_PRICE` FLOAT NOT NULL , `LOCATION_PRICE` FLOAT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Ajout de données*/
INSERT INTO `boxes_catalog` (`ID`, `HEU_MAJ`, `USR_MAJ`, `MODEL`, `PRODUCTION_PRICE`, `INSTALLATION_PRICE`, `LOCATION_PRICE`) VALUES (NULL, CURRENT_TIMESTAMP, NULL, '5 Clés', '500', '500', '200'), (NULL, CURRENT_TIMESTAMP, NULL, '10 Clés', '700', '1000', '250'), (NULL, CURRENT_TIMESTAMP, NULL, '20 Clés', '900', '1500', '325');



/*==========CATEGORIES==========*/

/*Creation de la table accessories_categories*/
CREATE TABLE `kameobiknqdataba`.`accessories_categories` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `CATEGORY` VARCHAR(50) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Ajout des catégories*/
INSERT INTO `accessories_categories` (`ID`, `CATEGORY`) VALUES (NULL, 'sacoche'), (NULL, 'cadenas'), (NULL, 'casque'), (NULL, 'textiles'), (NULL, 'entretien');

/*Creation de la table accessories*/
CREATE TABLE `kameobiknqdataba`.`accessories_catalog` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `NAME` VARCHAR(100) NOT NULL , `BUYING_PRICE` FLOAT NOT NULL , `PRICE_HTVA` FLOAT NOT NULL , `STOCK` BOOLEAN NOT NULL , `SHOW_ACCESSORIES` BOOLEAN NOT NULL , `DESCRIPTION` TEXT NOT NULL , `ACCESSORIES_CATEGORIES` INT(3)
NOT NULL,`FUNCTION` VARCHAR(200) NULL, PRIMARY KEY (`ID`)) ENGINE = MyISAM;
/*Creation de la clé étrangère reliant accessories a accessories_categories*/
ALTER TABLE accessories_catalog
ADD FOREIGN KEY (ACCESSORIES_CATEGORIES) REFERENCES accessories_categories(ID);

/*==========COMPANIES_CONTACT==========*/

/*Création de la table companies_contact*/
CREATE TABLE `kameobiknq`.`companies_contact` ( `ID` INT(3) NOT NULL AUTO_INCREMENT , `NOM` VARCHAR(250) NOT NULL , `PRENOM` VARCHAR(250) NOT NULL , `EMAIL` VARCHAR(250) NOT NULL , `PHONE` VARCHAR(15) NULL , `ID_COMPANY` INT NOT NULL,`FUNCTION` VARCHAR(250) NULL , `BIKES_STATS` VARCHAR(1) NOT NULL, PRIMARY KEY (`ID`)) ENGINE = MyISAM;
>>>>>>> aecac39fc9e370b7109ada0fe853d1502da6b494
/*Ajout de la clé étrangère reliant la compagnie et le contact*/
ALTER TABLE companies_contact
ADD FOREIGN KEY (ID_COMPANY) REFERENCES companies(ID);

/*Migration des contenus vers la table companies_contact*/
INSERT INTO companies_contact (ID_COMPANY, EMAIL, NOM, PRENOM, PHONE, BIKES_STATS)
SELECT ID, EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, CONTACT_PHONE, AUTOMATIC_STATISTICS FROM `companies`

/*Suppression des colonnes dans la table companies*/
ALTER TABLE `companies`
DROP EMAIL_CONTACT,
DROP NOM_CONTACT,
DROP PRENOM_CONTACT,
DROP CONTACT_PHONE,
DROP AUTOMATIC_STATISTICS

/*==========CUSTOMER_BIKES==========*/

/*Ajout du prix de vente d'un vélo*/
ALTER TABLE `customer_bikes` ADD `SOLD_PRICE` FLOAT NOT NULL AFTER `STAANN`

/*==========COMPANIES_OFFERS==========*/
CREATE TABLE `kameobiknq`.`companies_offers` ( `ID` INT NOT NULL AUTO_INCREMENT , `FILE_NAME` VARCHAR(255) NOT NULL , `COMPANY_ID` INT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;
ALTER TABLE companies_offers
ADD FOREIGN KEY (COMPANY_ID) REFERENCES companies(ID);
INSERT INTO companies_offers (FILE_NAME,COMPANY_ID) VALUES ('AFELIO_2020_2_25_16_15',14);
