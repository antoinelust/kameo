<style type="text/css">
/*
VARIABLES PHP DISPONIBLES
$buyOrLeasing      => "buy"/"leasing"/"both"
$leasingDuration   => durée du leasing
$numberMaintenance => nombre de maintenances prévues sur la durée du leasing
$assurance         => booléen indiquant si une assurance est souscrite
$bikes             => Liste des vélos du client
$boxes             => Liste des boxes du client
$accessories       => Liste des accessoires du client
$others            => Liste des autres frais ajoutés
$company           => Données de la société cliente
$contact           => Données du contact
*/
*{
  font-family: 'Akkurat';
  font-size: 15px;
}
p{
  font-family: 'Akkurat-Light';
  line-height: 22pt;
}
h1{
  font-size: 20px;
  color: #2fa37c;
  font-weight: bold;
}
h3{
  font-size: 17px;
  color: #2fa37c;
  font-weight: 400;
}
h2{
  color: #cc304d;
  font-weight: 400;
  font-size: 18px;
}
.white{
  color: white;
}
.red{
  color: #cc304d;
}
.green{
  color: #2fa37c;
}
.firstPage{
  font-size: 15px;
}
.header{
  color: #808080;
}
.header span{
  margin-top:10mm;
  margin-left:15mm;
  font-size: 15px;
}
.logo{
  width: 400px;
  height:auto;
  margin-bottom: 15mm;
}
.title{
  font-weight: 400;
}
.mainTitle{
  font-size: 50px;
  margin-bottom: 15mm;
}
.secondaryTitle{
  color: white;
  font-size: 35px;
  margin-bottom: 0;
}
.separator{
  color: white;
  height: 0.1mm;
}
.arcamajora{
  font-family: 'ArcaMajora';
}
.inline, .inline *{
  display:inline;
}
.maxWidth{
  width: 100%;
}
.logo-sm{
  width:180px;
  height: auto;
}
.logo-xsm{
  width:130px;
  height: auto;
}
.bold{
  font-weight:bold;
}
.light{
  font-family: 'Akkurat-Light';
  font-weight:400;
}
.normalFont{
  font-family: 'Akkurat';
}
.list .listItem{
  margin-bottom: 5mm;
}
.list .sublist{
  margin-bottom: 2mm;
}
.list .subListItem{
  margin-top: -2mm;
  margin-bottom: 5mm;
  padding-left: 10mm;
  font-family: 'Akkurat-Light';
}
.img-large{
  width: 160mm;
  height: auto;
  max-height: 250mm;
}
.bordered{
  border: 1px, solid, black;
}
.bordered-bottom{
  border-bottom: 1px, solid, black;
}
.bordered-top{
  border-top: 1px, solid, black;
}

.tableBorder {
  border-collapse: collapse;
}

.tableBorder th,.tableBorder td {
  border: 1px solid black;
}
.center{
  text-align: center;
}
.tbody-leftMargin td>*{
  margin-left:3mm;
}

.count-border{
  border: 3px solid #2fa37c;
  border-radius: 50px;
  padding:7mm;
  padding-top: 4mm;
}
.tableMargins td, .tableMargins th {
  padding-top:3mm;
  padding-bottom:3mm;
}

.lMargin{
  margin-left:3mm;
}
</style>

<page class="white firstPage" backcolor="#2fa37c" backtop="10mm" backleft="10mm" backright="10mm">
  <page_footer>
    <img style="margin-top:15px;" src="<?php echo __DIR__ ; ?>/img/logo_black.png" alt="kameo" class="logo-sm">
  </page_footer>
  <div style="text-align: center; margin-bottom:5mm;" >
    <img src="<?php echo __DIR__ ; ?>/img/logo_black_low_opacity.png" alt="logo" class="logo" /><br/>

    <span class="title mainTitle">OFFRE: <br/></span>
    <h2 class="title secondaryTitle">SOLUTION DE MOBILITÉ DOUCE</h2>

  </div>
  <div style="padding-left: 80px; padding-right:80px; margin-bottom:0;">
    <hr class="separator"/>
  </div>
  <table class="maxWidth" style="margin-bottom:10mm; margin-top: 10mm;">
    <tr>
      <td style="text-align:left; margin:0;padding:0; width:50%;">
        <div>
          <?php if($buyOrLeasing =="buy"){ ?><div style="font-size: 25px;">Achat de vélos</div>
          <?php }else if ($buyOrLeasing =="leasing"){?><div style="font-size: 25px;">Location tout inclus VAE</div>
          <?php }else{?><div style="font-size: 25px;">Achat de vélos</div>
            <div style="font-size: 25px;">Location tout inclus VAE</div>
          <?php } ?>
        </div>
      </td>
      <!--<td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
      <span>
      <?php if($buyOrLeasing =="buy"){ ?><span style="font-size: 25px;">Achat de vélos</span><br/>
    <?php }else if ($buyOrLeasing =="leasing"){?><span style="font-size: 25px;">Location tout inclus VAE</span><br/>
  <?php }else{?><span style="font-size: 25px;">Achat de vélos</span><br/>
  <span style="font-size: 25px;">Location tout inclus VAE</span><br/>
<?php } ?>
</span>
</td>-->
</tr>

</table>

<hr class="separator"/>
<div>
  <table class="maxWidth" >
    <tr>
      <td style="text-align:left; padding-left:0; margin-left:0; width:50%;">
        <span class="arcamajora" style="color:#efefef; font-size:25px;">KAMEO Bikes sprl</span><br/><br/>
        Rue de la Brasserie, 8<br/>
        4000 Liège<br/>
      </td>
      <td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
        <span class="arcamajora" style="color:#efefef; font-size:25px;"><?php echo $company['COMPANY_NAME']; ?></span><br/><br/>
        <?php echo $company['STREET']; ?><br/><?php echo $company['ZIP_CODE']; ?> <?php echo $company['TOWN']; ?><br/>
      </td>
    </tr>
  </table>
  <table class="maxWidth" style="margin-top:10mm;">
    <tr>
      <td style="text-align:left; padding-left:0; margin-left:0;  width:50%;">
        <div class="arcamajora" style="color:#efefef; font-size:25px;">Julien JAMAR DE BOLSEE</div><br/><br/>
        julien.jamar@kameobikes.com<br/>
        0498 72 75 46
      </td>
      <td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
        <span class="arcamajora" style="color:#efefef; font-size:25px;"><?php echo $contact['PRENOM']; ?> <?php echo strtoupper($contact['NOM']); ?></span><br/><br/>
        <?php echo $contact['EMAIL']; ?><br/>
        <?php if(isset($contact['PHONE'])){echo $contact['PHONE'];} ?>
      </td>
    </tr>
  </table>
</div>
</page>

<page backtop="20mm" backleft="15mm" backright="10mm" backbottom="20mm">
  <page_header class="header">
    <span>OFFRE DE LOCATION TOUT INCLUS VELO</span>
  </page_header>
  <page_footer style="margin-bottom:10mm;">
    <table class="maxWidth">
      <tr>
        <td><img src="<?php echo __DIR__ ; ?>/img/logo_black.png" alt="kameo" class="logo-xsm"></td>
        <td style="font-size:13px;">Kameo bikes SPRL<br/>Rue de la Brasserie, 8<br/>B-4000 Liège<br/>Belgium</td>
        <td style="width:33%; text-align:right; padding-right:0; margin-right:0;"><span>Page [[page_cu]]</span></td>
      </tr>
    </table>
  </page_footer>
  <div>
    <h1>Introduction</h1>
    <p>
      <span class="bold">KAMEO BIKES</span> est une entreprise active dans la mobilité urbaine qui propose,
      aux entreprises, des solutions complètes de mobilité basées sur le vélo.
      Nous sommes convaincus que le vélo est le mode de transport urbain de demain mais,
      surtout d’aujourd’hui, et nous travaillons tous les jours pour le démontrer à nos clients.
    </p>
    <p>
      <span class="bold">NOS SOLUTIONS</span> s’appuient sur 3 PÔLES interdépendants que sont des CYCLES DE QUALITÉ,
      une MAINTENANCE CONTINUE et une GESTION CONNECTÉE des interactions. La maitrise de ces
      3 axes nous permet de garantir une expérience cyclable optimale, quelles que soient les
      circonstances.
    </p>
    <img src="<?php echo __DIR__ ; ?>/img/kameo_scheme.png" alt="kameo-scheme" style="margin-left:25mm; width:400px; height:auto;">
    <p>
      Nous avons le plaisir de vous faire parvenir <span class="bold">notre offre</span> pour l’acquisition sous forme d'une location tout inclus d’un
      vélo ainsi que les services associés. Ces solutions sont entièrement définies par le présent
      document et nos conditions générales.
    </p>
    <p>
      Nous restons à votre disposition pour toute demande d’informations complémentaires.
    </p>
    <img src="<?php echo __DIR__ ; ?>/img/illu1.png" alt="kameo-scheme" style="margin-left:45mm;">

  </div>
</page>
<page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
  <h2>Objet</h2>
  <p>
    Le présent document est, avec ses annexes, l’offre détaillée de KAMEO Bikes SPRL (appelé KAMEO dans la
    suite du document) pour <?php echo $company['COMPANY_NAME']; ?> pour l’acquisition
    <?php if($buyOrLeasing =="leasing" || $buyOrLeasing =="both"){echo "sous forme de location tout inclus";} ?>
    de
    <?php
    $nbVelos = $bikesNumber;
    $txtVelo = "vélo";
    if($nbVelos > 1) {
      $txtVelo = "vélos";
    }
    echo $nbVelos . ' ' . $txtVelo . ' et des services de support liés';
    ?>
    .
  </p>
  <h2>Documents de référence et contacts</h2>
  <p>
    Cette offre est basée sur les échanges entre, M./Mme. <?php echo $contact['NOM']; ?> de <?php echo $company['COMPANY_NAME'] ?> et M. Jamar de KAMEO.
  </p>
  <p>
    La solution proposée est cependant définie entièrement et uniquement par ce document et ses annexes
    ainsi que les conditions générales de vente/location de KAMEO.
  </p>
  <h2>Scope de l’offre</h2>
  <p>
    L’offre inclus l’ensemble des éléments repris ci-dessous.
  </p>
  <div class="list">
    <?php if ($buyOrLeasing =="leasing" || $buyOrLeasing =="both") {
      echo "<div class='listItem'>• Location de " . $nbVelos . " " . $txtVelo ."</div>";
    } ?>

    <?php if ($buyOrLeasing =="buy") {
      echo "<div class='listItem'>• Achat de " . $nbVelos . " " . $txtVelo."</div>";
    } ?>

    <?php if (count($boxes) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>• Location de " . $boxesNumber . " boxe(s) de gestion des clés de vélos: </div>"; ?>
        <div class='subList'>
          <?php
          foreach ($boxes as $box) {
            echo "<div class='subListItem'>• Box " . $box['MODEL'] . "<span class='green'> x".$box['count']."</span></div>";
          }?>
        </div>
      </div>
    <?php } ?>
    <?php if (count($accessories) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>• Achat de " . count($accessories)  . " accessoire(s): </div>" ; ?>
        <div class='subList'>
          <?php
          foreach ($accessories as $accessory) {
            echo "<div class='subListItem'>• " . $accessory['NAME'] . "</div>";
          }?>
        </div>
      </div>
    <?php  } ?>

    <?php if (is_array($others) && count($others) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>• Autres : </div>" ; ?>
        <div class='subList'>
          <?php
          foreach ($others as $other) {
            echo "<div class='subListItem'>• " . $other['othersDescription'] . "</div>";
          } ?>
        </div>
      </div>
    <?php  } ?>
  </div>
</page>
<page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">

    <h1>1. Location tout inclus</h1>
    <p>
      La location est un contrat comprenant à la fois la mise à disposition d’un produit et des services liés à ce
      produit. Pendant toute la durée de la période de location, le produit appartient à KAMEO Bikes. A l’échéance
      de la période de location, une possibilité d'achat du vélo peut être réçue par <?php echo $company['COMPANY_NAME']; ?> de la part de Kameo Bikes afin d’acquérir définitivement le
      produit. Les services peuvent toujours être contractés en supplément.
    </p>
    <p>Dans le cadre de cette offre, la location englobe les éléments suivants :</p>
    <div class="list">
      <?php if ($buyOrLeasing == "leasing" || $buyOrLeasing == "both") { ?>
      <div class="listItem">• PRODUIT</div>
      <div class="subList">
        <div class="subListItem">
          • <?php echo $nbVelos . ' '. $txtVelo; ?>
        </div>
      </div>
      <?php } ?>
      <?php if($assurance == true ||  $numberMaintenance >= 0){ ?>
      <div class="listItem">• SERVICES</div>
      <div class="subList">
        <?php if($assurance == true){echo '<div class="subListItem">• Assurance</div>';} ?>
        <?php if($numberMaintenance >= 0){echo '<div class="subListItem">• Maintenance</div>';} ?>
      </div>
    <?php } ?>
      <?php if ($buyOrLeasing == "leasing" || $buyOrLeasing == "both") { ?>
      <div class="listItem">• POSSIBILITE D ACHAT</div>
      <div class="subList">
        <div class="subListItem">• A calculer selon la durée de location</div>
      </div>
      <?php } ?>
    </div>
    <img src="<?php echo __DIR__ ; ?>/img/leasing_schema.png" alt="" style="width:600px; height: auto;" />


</page>

<?php
//vélos
if (count($bikes) > 0) {
  foreach ($bikes as $bike) { ?>
    <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
      <?php echo <<<BIKETITLE
      <table class="maxWidth">
        <tbody>
          <tr>
            <td style="width:70%;"><span><h2>Le vélo: {$bike['BRAND']} {$bike['MODEL']}</h2></span></td>
            <td class="green" style:"width:30%; text-align:center;"><div class="count-border" style="width:auto;"><span style="font-size:30px;">x </span><span style="font-size:25mm;">{$bike['count']}</span></div></td>
          </tr>
        </tbody>
      </table>
BIKETITLE;
      $temp = $bike['BRAND'] . '_' . $bike['MODEL'] . '_' . $bike['FRAME_TYPE'];
      $temp = strtolower(str_replace(' ','-',$temp));
      $bikeImg =  __DIR__ .'/../../../images_bikes/'.$temp.'.jpg' ;
      ?>
      <table class="maxWidth tableBorder tableMargins" style="margin-top:10mm; margin-bottom:10mm;">
        <thead>
          <tr>
            <th style="width:25%"><span class="lMargin">Marque</span></th>
            <th style="width:25%"><span class="lMargin">Modèle</span></th>
            <th style="width:25%"><span class="lMargin">Utilisation</span></th>
            <th style="width:25%"><span class="lMargin">Électrique</span></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width:25%"><span class="lMargin"><?php echo $bike['BRAND'] ?></span></td>
            <td style="width:25%"><span class="lMargin"><?php echo $bike['MODEL'] ?></span></td>
            <td style="width:25%"><span class="lMargin"><?php echo $bike['UTILISATION'] ?></span></td>
            <td style="width:25%"><?php if($bike['ELECTRIC'] == "Y"){ echo "<span class='green lMargin'>Oui</span>";}else{echo "<span class='red lMargin'>Non</span>";}   ?></td>
          </tr>
        </tbody>
      </table>
      <div><img src="<?php echo $bikeImg ?>" alt="velo.jpg" class="img-large" /></div>
      <div><a href="<?php echo $bike['LINK'] ?>">Lien du vélo</a></div>
    </page>
  <?php }
}
?>

<?php
//boxes
if (count($boxes) > 0) {
  foreach ($boxes as $box) { ?>
    <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">

      <?php echo "
      <table class='maxWidth'>
        <tbody>
          <tr>
            <td style='width:70%;'><span><h2>La Box {$box['MODEL']}</h2></span></td>
            <td class='green' style:'width:30%; text-align:center;'><div class='count-border' style='width:auto;'><span style='font-size:30px;'>x </span><span style='font-size:25mm;'>{$box['count']}</span></div></td>
          </tr>
        </tbody>
      </table>" ?>
      <?php
      $temp = explode(' ',$box['MODEL'])[0];
      $boxImg =  __DIR__ .'/../../../images_bikes/'.$temp.'keys.png' ;
      ?>
      <img src="<?php echo $boxImg ?>" alt="box.png" class="img-large" />
      <p>Facilitant grandement la gestion de la flotte, notre box
        <?php echo $box['MODEL']?> offre un confort certain. Une simple réservation sur <a href="www.kameobikes.com">MyKameo</a>,
        et vous receverez un code de dévérouillage pour récupérer la clé de votre vélo !
      </p>
    </page>
  <?php }
}
?>
<?php
//maintenance
if($numberMaintenance > 0){ ?>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <h2>Maintenance</h2>
    <p>
      La clé d’une expérience de mobilité réussie est d’avoir en permanence des vélos dans un état irréprochable.
      KAMEO Bikes part du principe que pour prendre du plaisir sur votre vélo, celui-ci doit rouler sans souci
      mécanique.<br/>
      <span class="bold">
        Les entretiens seront organisés sur le site de l’entreprise entre 8h et 18h lors d’une date annoncée avec
        un délai de minimum 2 semaines.
      </span>
    </p>
    <h3 style="margin: 0;">Entretiens</h3>
    <p style="margin-bottom: 10px;">
      Un premier entretien est effectué après 3 mois. Ensuite, une révision annuelle est organisée en fonction de
      la durée de location. L’entretien annuel est conçu pour assurer une remise en état de votre vélo. Il correspond
      aux changements de pièces et réglages de l'ensemble des organes de votre vélo.
    </p>
    <table class="maxWidth bordered">
      <tbody>
        <tr>
          <td class="bordered-bottom" style="width:100%;font-size: 18px; font-weight: bold; padding-bottom: 3mm;">
            POINTS VÉRIFIÉS LOS D'UN ENTRETIEN
          </td>
        </tr>
        <tr>
          <td style="width:100%; margin-top: 3mm;" class="list">
            <div class="subListItem" style="margin-top: 3mm;">• Nettoyage du vélo</div>
            <div class="subListItem">• Etat général du vélo</div>
            <div class="subListItem">• Pression et état des pneus</div>
            <div class="subListItem">• Fonctionnement des freins</div>
            <div class="subListItem">• Fonctionnement du changement de vitesse</div>
            <div class="subListItem">• Etat des roulements</div>
            <div class="subListItem">• Vérification des jeux et serrages</div>
            <div class="subListItem">• Vérification de la tension des rayons</div>
            <div class="subListItem">• Vérification des connexions électriques</div>
            <div class="subListItem">• Huilage de la chaine et des parties en roulement</div>
            <div class="subListItem">• Vérification des points de sécurité et des lampes</div>
          </td>
        </tr>
        <tr>
          <td class="bordered-top bordered-Bottom"style="width:100%;font-size: 18px; font-weight: bold; padding-bottom: 3mm;">
            PIÈCES DE RECHANGE COMPRISES LORS LA LOCATION
          </td>
        </tr>
        <tr>
          <td class="list"style="width:100%;">
            <div class="subListItem" style="margin-top: 3mm;">• Pneus</div>
            <div class="subListItem">• Plaquettes de freins</div>
            <div class="subListItem">• Transmission (chaine, cassette et plateau)</div>
            <div class="subListItem">• Poignées</div>
          </td>
        </tr>
      </tbody>
    </table>
  </page>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <p>
      Les éléments suivants ne sont pas couverts par l’entretien :
    </p>
    <div class="list">
      <div class="subListItem">• Pièces de rechanges non comprises dans la liste précédente ;</div>
      <div class="subListItem">• Réparation des dommages causés par une utilisation impropre, négligence, lors d’une compétition, collision, accidents ou les chutes, le vandalisme ou toute autre cause que l'usure normale ;</div>
      <div class="subListItem">• Entretien et réparation de composants, de fonctions optionnelles et d'accessoires qui n'étaient pas fournis et montés à la livraison du vélo.</div>
    </div>
    <h3>Intervention sur demande</h3>
    <p>
      Un vélo ne fonctionne plus correctement ou une pièce est cassée ? Vous souhaitez réaliser un check complet
      de la flotte avant une activité de la cellule environnement ?
    </p>
    <p>
      Pas de problème, nous pouvons planifier un entretien supplémentaire. La facturation de celui-ci dépend
      alors de la nature de la demande.
    </p>
    <p class="bold">
      SI LA DEMANDE RELÈVE D’UNE MALFAÇON DU VÉLO, KAMEO BIKES PREND À SA CHARGE
      LE COÛT DE L’ENTRETIEN ET LES PIÈCES.
    </p>
    <p>Pour toute autre raison, KAMEO Bikes facturera l’intervention de la manière suivante :</p>
    <div class="list">
      <div class="listItem">• 75€ pour le déplacement et la première heure de travail</div>
      <div class="listItem">
        <div>• Main d’œuvre : (après les 60 min de base)</div>
        <div class="subList" style="margin-top: 4mm;">
          <div class="subListItem">o 50€/h pour le travail effectué par notre technicien de 8h à 18h du lundi au vendredi</div>
          <div class="subListItem">o 85€/h en dehors de cette tranche horaire.</div>
        </div>
      </div>
      <div class="listItem">• Les pièces de rechange au prix du marché</div>
    </div>
  </page>
<?php } ?>

<?php
//assurance
if ($assurance == true) { ?>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <h2>Assurance</h2>
    <?php
    $assuImg1 =  __DIR__ .'/../../../images/aedes.png';
    $assuImg2 =  __DIR__ .'/../../../images/DEDALE.jpg';
    ?>
    <p>
      KAMEO Bikes collabore avec Aedes et Dedales afin de vous offrir l’assurance Omnium la plus complète et la
      plus flexible actuellement disponible sur le marché : La P-Vélo
    </p>
    <table class="maxWidth">
      <tbody>
        <tr>
          <td style="width:50%;"><img src="<?php echo $assuImg1; ?>" alt="Aedes.jpg"></td>
          <td style="width:50%; text-align:right;"><img src="<?php echo $assuImg2; ?>" alt="Dedale.jpg" style="height:150px; width:auto;"></td>
        </tr>
      </tbody>
    </table>
    <div>
      <div class="bold">L’omnium P-Vélo a les caractéristiques suivantes :</div>
      <div class="list" style="margin-top: 5mm;">
        <div class="subListItem">• En cas de perte totale ou vol complet, le vélo est remboursé TVAC la première année. A partir du 13eme mois, il y a une dégressivité mensuelle du remboursement du vélo de 1% par mois ;</div>
        <div class="subListItem bold">• Franchise de 150€ ;</div>
        <div class="subListItem">• Aedes impose l’achat d’un cadenas d’une valeur d’achat de minimum 60€ pour tout qui souscrit une Omnium Vélo et qui souhaite être couvert contre le vol. </div>
        <div class="subListItem">• La couverture est valable en Belgique comme à l’étranger ;</div>
        <div class="subListItem">• L’assurance comprend <span class="bold">2 dépannages</span> d’un même vélo dans la même année. S’il devait y en avoir plus, ceux-ci seront facturés suivant la police Aedes en annexe.</div>
      </div>
    </div>
    <div>
      <div class="bold">Les risques non-couverts sont les suivants :</div>
      <div class="list" style="margin-top: 5mm;">
        <div class="subListItem">• Un sinistre provoqué intentionnellement par l'assuré ;</div>
        <div class="subListItem">• Le vol du vélo s'il n'est pas attaché à un point fixe par un cadenas d'une valeur de 60€ min ;</div>
        <div class="subListItem">• La compétition.</div>
      </div>
    </div>
    <div class="bold red">
      Si vous respectez les 3 points précédents la seule chose à faire pour être couvert est donc ATTACHER
      votre vélo à UN POINT FIXE avec le CADENAS PROPOSÉ PAR KAMEO. Dans un garage privé et fermé,
      c’est un cas particulier, il n’est pas nécessaire d’attacher le vélo.
    </div>
    <p>Informations et conditions complètes sur <a href="https://www.aedessa.be/assurances/velo" class="bold" style="color:black; ">https://www.aedessa.be/assurances/velo</a></p>
    <p>
      L’utilisateur s’engage à respecter toutes les conditions de l’assurance afin d’en bénéficier et à utiliser le vélo
      en bon père de famille. Dans le cas contraire, l’ensemble des frais causés par sa négligence seront à sa
      charge.
    </p>
    <div>Si vous avez des questions supplémentaires, n’hésitez pas à nous contacter !</div>
  </page>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <h3>Procédure en cas de vol</h3>
    <p>
      <span class="bold">Les démarches suivantes sont obligatoires.</span> En cas de non-respect des délais ou de non-réalisation
      d’une des démarches, la police d’assurance n’est pas applicable.
    </p>
    <div class="list">
      <div class="subListItem">• Informer KAMEO Bikes (dans le cas d’une location) ou votre assureur (pour une assurance personnelle) dans les 24h suivant la découverte du vol ;</div>
      <div class="subListItem">• Porter plainte à la police dans les 24h suivant la découverte du vol ;</div>
      <div class="subListItem">• Envoyer la déclaration de vol à KAMEO Bikes / votre assureur dès qu’elle est en votre possession ;</div>
      <div class="subListItem">• KAMEO Bikes s’occupe du reste.</div>
    </div>
    <h3>Procédure en cas de besoin d’assistance</h3>
    <p>Si vous ne savez pas continuer à rouler :</p>
    <div class="list">
      <div class="subListItem">• Téléphoner à Aedes, le numéro d’appel se trouve sur le sticker KAMEO sur le cadre de votre vélo ( 02 642 45 03 ) ;</div>
      <div class="subListItem">• Prendre un maximum de photo de votre problème ;</div>
      <div class="subListItem">• Contacter KAMEO Bikes via l’adresse suivante <a href="mailto:sav@kameobikes.com" class="bold" style="color:black">sav@kameobikes.com</a> décrire votre problème et joindre vos photos.</div>
    </div>
    <h3>Quelques exemples</h3>
    <table class="maxWidth tableBorder">
      <tbody>
        <tr>
          <td style="width:50%; height: 10mm;">
            <div style="margin-left:3mm;">Vélo fixé sur un parking vélo en rue avec le cadenas proposé par KAMEO</div>
          </td>
          <td style="width:50%; height: 10mm; text-align:center;"><span class="green">COUVERT</span></td>
        </tr>
        <tr>
          <td style="width:50%; height: 10mm;">
            <div style="margin-left:3mm;">Vélo fixé sur un parking vélo en rue avec un cadenas acheté 59 € de 6h à 22h</div>
          </td>
          <td style="width:50%; height: 10mm; text-align:center;"><span class="red">PAS COUVERT</span></td>
        </tr>
        <tr>
          <td style="width:50%; height: 10mm;">
            <div style="margin-left:3mm;">Vélo à l’intérieur de votre domicile avec ou sans cadenas</div>
          </td>
          <td style="width:50%; height: 10mm; text-align:center;"><span class="green">COUVERT</span></td>
        </tr>
        <tr>
          <td style="width:50%; height: 10mm;">
            <div style="margin-left:3mm;">Vélo laissé en rue sans cadenas</div>
          </td>
          <td style="width:50%; height: 10mm; text-align:center;"><span class="red">PAS COUVERT</span></td>
        </tr>
        <tr>
          <td style="width:50%; height: 10mm;">
            <div style="margin-left:3mm;">Vélo cadenassé à lui-même sans fixation à un point fixe, en rue, avec le cadenas fourni par KAMEO </div>
          </td>
          <td style="width:50%; height: 10mm; text-align:center;"><span class="red">PAS COUVERT</span></td>
        </tr>
      </tbody>
    </table>
  </page>
<?php } ?>

<?php
  //accessoires
  if (count($accessories) > 0) { ?>
    <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
      <h1>2. Accessoires</h1>
      <p>
        Les vélos sont équipés mais les cyclistes doivent l’être également ! Il est important d’avoir un bon cadenas
        pour sécuriser le vélo à un point fixe et une sacoche pour transporter ses affaires sans transpirer. La
        sécurité des cyclistes est également à assurer via un casque répondant aux normes d’usage et
        rétroéclairé. Une chasuble permet de garantir la visibilité vis-à-vis des autres usagers de la route !
      </p>
      <table class="maxWidth tableBorder">
        <thead style="font-size: 18px;">
          <tr>
            <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">ACCESSOIRE</div></th>
            <!--<th style="width:25%; height: 10mm;"><div style="margin:3mm;" class="bold">VISUEL</div></th> -->
            <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">CARACTÉRISTIQUES</div></th>
            <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">QUANTITÉ</div></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($accessories as $accessory) { ?>
            <tr>
              <td style="width:33%;" class="center"><?php echo $accessory['NAME']; ?></td>
              <!--<td style="width:25%;" class="center">A venir ...</td> -->
              <td style="width:33%;" class="center"><?php echo $accessory['DESCRIPTION']; ?></td>
              <td style="width:33%;" class="center"><?php echo $accessory['count']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </page>
  <?php } ?>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <?php if ($buyOrLeasing =="leasing" || $buyOrLeasing == "both") {
      $both = "";
      if($buyOrLeasing == "both"){$both = " et achat";}
      echo "<h2>En location sur {$leasingDuration} mois{$both}: </h2>";
    } else{
      echo "<h2>En achat: </h2>";
    } ?>

    <table class="maxWidth tableBorder">
      <thead>
        <tr style="font-size: 18px;">
          <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">POSTE</div></th>
          <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">DESCRIPTIF</div></th>
          <th style="width:33%; height: 10mm;"><div style="margin:3mm;" class="bold">BUDGET</div></th>
        </tr>
      </thead>
      <tbody class="tbody-leftMargin">
        <?php if (($buyOrLeasing =="leasing" || $buyOrLeasing == "both") && count($bikes) > 0) { ?>
          <tr>
            <td style="width:33%;"><div class="green bold" style="padding-top:3mm; padding-bottom:3mm; margin-left:3mm;">Vélo: Location <?php echo $leasingDuration ; ?> mois</div> </td>
            <td style="width:33%;">
              <?php foreach ($bikes as $bike) {
                echo "<div style='margin-left:3mm;'>{$bike['BRAND']} {$bike['MODEL']}  <span class='green bold'> x{$bike['count']}</span></div><br/>";
              } ?>
            </td>
            <td style="width:33%; padding-top:3mm; padding-bottom:3mm;">
              <?php foreach ($bikes as $bike) {
                if($bike['LEASING_PRICE'] > $bike['FINAL_LEASING_PRICE']){
                    echo "<div style='margin-left:3mm;'>Location:<br/><del>{$bike['LEASING_PRICE']} € HTVA/mois</del><br /> {$bike['FINAL_LEASING_PRICE']} € HTVA/mois <span class='green bold'> x{$bike['count']}</span></div><br/>";
                }else if($bike['LEASING_PRICE'] < $bike['FINAL_LEASING_PRICE']){
                    echo "<div style='margin-left:3mm;'>Location<br/>{$bike['FINAL_LEASING_PRICE']} € HTVA/mois  <span class='green bold'> x{$bike['count']}</span></div><br/>";
                }
                else{
                    echo "<div style='margin-left:3mm;'>Location<br/>{$bike['LEASING_PRICE']} € HTVA/mois  <span class='green bold'> x{$bike['count']}</span></div><br/>";
                }
              } ?>
            </td>
          </tr>
        <?php } if(($buyOrLeasing =="both" || $buyOrLeasing == "buy") && count($bikes) > 0) { ?>
          <tr>
            <td style="width:33%;"><div class="green bold" style="padding-top:3mm; padding-bottom:3mm; margin-left:3mm;">Vélo: Achat</div> </td>
            <td style="width:33%;">
              <?php foreach ($bikes as $bike) {
                echo "<div style='margin-left:3mm;'>{$bike['BRAND']} {$bike['MODEL']}  <span class='green bold'> x{$bike['count']}</span></div><br/>";
              } ?>
            </td>
            <td style="width:33%; padding-top:3mm; padding-bottom:3mm;">
              <?php foreach ($bikes as $bike) {
                echo "<div style='margin-left:3mm;'>{$bike['PRICE_HTVA']} € HTVA  <span class='green bold'> x{$bike['count']}</span></div><br/>";
              } ?>
            </td>
          </tr>
        <?php } ?>

        <?php if (count($boxes) > 0) { ?>
          <tr>
            <td style="width:33%;"><div class="green bold" style="padding-top:3mm; padding-bottom:3mm; margin-left:3mm;">Boxes: Installation + Location <?php echo $leasingDuration ; ?> mois</div> </td>
            <td style="width:33%;">
              <?php foreach ($boxes as $box) {
                echo "<div style='margin-left:3mm;'>Box {$box['MODEL']}  <span class='green bold'> x{$box['count']}</span></div><br/>";
              } ?>
            </td>
            <td style="width:33%; padding-top:3mm; padding-bottom:3mm;">
              <?php foreach ($boxes as $box) {
                if($box['INSTALLATION_PRICE'] != $box['FINAL_INSTALLATION_PRICE']){
                    echo "<div style='margin-left:3mm;'>Installation:<br/> <del>{$box['INSTALLATION_PRICE']} € HTVA</del><br/> {$box['FINAL_INSTALLATION_PRICE']} € HTVA <span class='green bold'> x{$box['count']}</span><br/><br/>";
                }else{
                    echo "<div style='margin-left:3mm;'>Installation:<br/> {$box['INSTALLATION_PRICE']} € HTVA <span class='green bold'> x{$box['count']}</span><br/><br/>";
                }
                if($box['LOCATION_PRICE'] != $box['FINAL_LOCATION_PRICE']){
                    echo  " Location:<br/> <del>{$box['LOCATION_PRICE']} € HTVA/mois</del><br/> {$box['FINAL_LOCATION_PRICE']} € HTVA/mois  <span class='green bold'> x{$box['count']}</span></div><br/>";
                }else{
                    echo  " Location:<br/> {$box['LOCATION_PRICE']} € HTVA/mois  <span class='green bold'> x{$box['count']}</span></div><br/>";
                }
                
              } ?>
            </td>
          </tr>
        <?php } ?>

        <?php if (count($accessories) > 0) { ?>
          <tr>
            <td style="width:33%;"><div class="green bold" style="padding-top:3mm; padding-bottom:3mm; margin-left:3mm;">Accessoires(achat) </div></td>
            <td style="width:33%;">
              <?php foreach ($accessories as $accessory) {
                echo "<div style='margin-left:3mm;'>{$accessory['NAME']} <span class='green bold'> x{$box['count']}</span></div><br/>";
              } ?>
            </td>
            <td style="width:33%; padding-top:3mm; padding-bottom:3mm;">
              <?php foreach ($accessories as $accessory) {
                echo "<div style='margin-left:3mm;'>Prix: {$accessory['BUYING_PRICE']} € HTVA <span class='green bold'> x{$box['count']}</span></div><br/>";
              } ?>
            </td>
          </tr>
        <?php } ?>
        <?php if (count($others) > 0) { ?>
          <tr>
            <td style="width:33%;"><div class="green bold" style="padding-top:3mm; padding-bottom:3mm; margin-left:3mm;">Autres </div></td>
            <td style="width:33%;">
              <?php foreach ($others as $other) {
                echo "<div style='margin-left:3mm;'>{$other['othersDescription']}</div><br/>";
              } ?>
            </td>
            <td style="width:33%; padding-top:3mm; padding-bottom:3mm;">
              <?php foreach ($others as $other) {
                if($other['othersSellingPriceFinal']!=$other['othersSellingPrice']){
                    echo "<div style='margin-left:3mm;'>Prix:<br/> <del>{$other['othersSellingPrice']} € HTVA</del><br/>{$other['othersSellingPriceFinal']} € HTVA</div><br/>";
                }else{
                    echo "<div style='margin-left:3mm;'>Prix:<br/> {$other['othersSellingPriceFinal']} € HTVA</div><br/>";
                }
              } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </page>

  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <?php $titleNumber = 2;
    if (count($accessories) > 0) { $titleNumber = 3; } ?>
    <h1><?php echo $titleNumber; ?>. Conditions de vente</h1>
    <h2>Prix</h2>
    <div class="light">Les prix sont entendus HTVA.</div>
    <h2>Livraison</h2>
    <table class="maxWidth">
      <tbody>
        <tr>
          <td style="width:50%;" class="light">Livraison directement chez vous soit :</td>
          <td style="width:50%;" class="light"><?php echo $company['STREET'] . '<br/><br/>' . $company['ZIP_CODE'] . ' '. $company['TOWN']?></td>
        </tr>
      </tbody>
    </table>
    <h2>Délais</h2>
    <div class="light">
      <?php
          foreach ($delais as $delai) {
            echo "• ".$delai."<br/>";
          }
       ?>
    </div>
    <h2>Validité de l’offre</h2>
    <div class="light">
      <?php echo $offerValidity; ?>
    </div>
    <h2>Garantie</h2>
    <div class="light">KAMEO Bikes offre une garantie conforme à celle de la marque. Soit 2 ans sur le cadre et les composants.</div>
    <h2>Facturation</h2>
    <div class="light">
      La facturation s’effectuera de façon mensuelle chaque 1er du mois pour les locations et lors de la livraison pour les achats.<br/>
      Cette offre est sujette aux conditions générales de vente et de location de KAMEO Bikes SPRL.
    </div>
    <h2>Conditions de paiement</h2>
    <div class="light">30 jours à partir de la date de la facture. Prélèvement automatique par domiciliation.</div>
  </page>
  <page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <h2>Rupture du contrat – possibilié d’achat</h2>
    <div class="light">
      En cas de rupture unilatérale du contrat de location de la part du client, les indemnités suivantes seront dues :<br/>
      <table class="maxWidth tableBorder tableMargins">
        <thead>
          <tr>
            <th style="width:50%;" class="bold">DURÉE ÉCOULÉE DE LOCATION</th>
            <th style="width:50%;" class="bold">Indemnité de rupture</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width:50%;" class="light">1-12 mois</td>
            <td style="width:50%;" class="light">12 mois</td>
          </tr>
          <tr>
            <td style="width:50%;" class="light">12-24 mois</td>
            <td style="width:50%;" class="light">6 mois</td>
          </tr>
          <tr>
            <td style="width:50%;" class="light">24-36 mois</td>
            <td style="width:50%;" class="light">3 mois</td>
          </tr>
          <tr>
            <td style="width:50%;" class="bold">A l’échéance </td>
            <td style="width:50%;" class="bold">Possibilité d’achat</td>
          </tr>
          <tr>
            <td style="width:50%;" class="light">36 mois</td>
            <td style="width:50%;" class="light">A la valeur de marché du vélo, environ 16% du prix initial dans la plupart des cas</td>
          </tr>
        </tbody>
      </table>
    </div>
  </page>
  <page backcolor="#2fa37c" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
    <page_footer>
      <div style="text-align:center" class="white">
        <img src="<?php echo __DIR__ ; ?>/img/logo_black_low_opacity.png" alt="logo" class="logo" /><br/>
      </div>
        <div class="light white" style="margin-bottom:10mm; text-align:center;">
            <span class="bold">KAMEO Bikes SPRL <br/></span>
            Rue de la Brasserie, 8<br/>
            4000 Liège <br/>
            BE 0681.879.712
          </div>

          <div class="light white" style="margin-bottom:10mm; text-align:center;">
            <span class="bold">Julien JAMAR DE BOLSEE <br/></span>
            julien.jamar@kameobikes.com <br/>
            0498 72 75 46 <br/>
          </div>

    </page_footer>
  </page>
