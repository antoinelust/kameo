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
h2{
  color: #cc304d;
  font-weight: 400;
  font-size: 18px;
}
.white{
  color: white;
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
          <?php }else if ($buyOrLeasing =="leasing"){?><div style="font-size: 25px;">Leasing VAE</div>
          <?php }else{?><div style="font-size: 25px;">Achat de vélos</div>
            <div style="font-size: 25px;">Leasing VAE</div>
          <?php } ?>
        </div>
      </td>
      <!--<td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
      <span>
      <?php if($buyOrLeasing =="buy"){ ?><span style="font-size: 25px;">Achat de vélos</span><br/>
    <?php }else if ($buyOrLeasing =="leasing"){?><span style="font-size: 25px;">Leasing VAE</span><br/>
  <?php }else{?><span style="font-size: 25px;">Achat de vélos</span><br/>
  <span style="font-size: 25px;">Leasing VAE</span><br/>
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
        Boulevard de la sauvenière 118<br/>
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
        <span class="arcamajora" style="color:#efefef; font-size:25px;"><?php echo $contact['firstName']; ?> <?php echo strtoupper($contact['lastName']); ?></span><br/><br/>
        <?php echo $contact['email']; ?><br/>
        <?php if(isset($contact['phone'])){echo $contact['phone'];} ?>
      </td>
    </tr>
  </table>
</div>
</page>

<page backtop="20mm" backleft="15mm" backright="10mm" backbottom="20mm">
  <page_header class="header">
    <span>OFFRE DE LEASING VELO</span>
  </page_header>
  <page_footer style="margin-bottom:10mm;">
    <table class="maxWidth">
      <tr>
        <td><img src="<?php echo __DIR__ ; ?>/img/logo_black.png" alt="kameo" class="logo-xsm"></td>
        <td style="font-size:13px;">Kameo bikes SPRL<br/>Boulevard de la Sauvenière, 118<br/>B-4000 Liège<br/>Belgium</td>
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
      Nous avons le plaisir de vous faire parvenir <span class="bold">notre offre</span> pour l’acquisition sous forme de leasing d’un
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
    <?php if($buyOrLeasing =="leasing" || $buyOrLeasing =="both"){echo "sous forme de leasing";} ?>
    de
    <?php
    $nbVelos = count($bikes);
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
    Cette offre est basée sur les échanges entre, M./Mme. <?php echo $contact['lastName']; ?> de <?php echo $company['COMPANY_NAME'] ?> et M. Jamar de KAMEO.
  </p>
  <p>
    La solution proposée est cependant définie entièrement et uniquement par ce document et ses annexes
    ainsi que les conditions générales de vente/leasing de KAMEO.
  </p>
  <h2>Scope de l’offre</h2>
  <p>
    L’offre inclus l’ensemble des éléments repris ci-dessous.
  </p>
  <div class="list">
    <?php if ($buyOrLeasing =="leasing" || $buyOrLeasing =="both") {
      echo "<div class='listItem'>- Leasing de " . $nbVelos . " " . $txtVelo ."</div>";
    } ?>

    <?php if ($buyOrLeasing =="buy") {
      echo "<div class='listItem'>- Achat de " . $nbVelos . " " . $txtVelo."</div>";
    } ?>

    <?php if (count($boxes) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>- Location de " . count($boxes) . " boxe(s) de gestion des clés de vélos: </div>"; ?>
        <div class='subList'>
          <?php
          foreach ($boxes as $box) {
            echo "<div class='subListItem'>* Box " . $box['MODEL'] . "</div>";
          }?>
        </div>
      </div>
    <?php } ?>
    <?php if (count($accessories) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>- Achat de " . count($accessories)  . "accessoire(s): </div>" ; ?>
        <div class='subList'>
          <?php
          foreach ($accessories as $accessory) {
            echo "<div class='subListItem'>* " . $accessory['NAME'] . "</div>";
          }?>
        </div>
      </div>
    <?php  } ?>

    <?php if (count($others) > 0) { ?>
      <div>
        <?php
        echo "<div class='listItem'>- Autres : </div>" ; ?>
        <div class='subList'>
          <?php
          foreach ($others as $other) {
            echo "<div class='subListItem'>* " . $other['othersDescription'] . "</div>";
          } ?>
        </div>
      </div>
    <?php  } ?>
  </div>
</page>
<page pageset="old" backtop="30mm" backleft="15mm" backright="10mm" backbottom="20mm">
  <?php if ($buyOrLeasing == "leasing" || $buyOrLeasing == "both") { ?>
    <h1>1. Leasing</h1>
    <p>
      Le leasing est un contrat comprenant à la fois la mise à disposition d’un produit et des services liés à ce
      produit. Pendant toute la durée de la période de leasing, le produit appartient à KAMEO Bikes. A l’échéance
      de la période de leasing, une option d’achat peut être levée par <?php echo $company['COMPANY_NAME']; ?> afin d’acquérir définitivement le
      produit. Les services peuvent toujours être contractés en supplément.
    </p>
    <p>Dans le cadre de cette offre, le leasing englobe les éléments suivants :</p>
    <div class="list">
      <div class="listItem">• PRODUIT</div>
      <div class="subList">
        <div class="subListItem">
          • <?php echo $nbVelos . ' '. $txtVelo; ?>
        </div>
      </div>
      <div class="listItem">• SERVICES</div>
      <div class="subList">
        <?php if($assurance == true){echo '<div class="subListItem">• Assurance</div>';} ?>
        <?php if($numberMaintenance >= 0){echo '<div class="subListItem">• Maintenance</div>';} ?>
      </div>
      <div class="listItem">• OPTION D ACHAT</div>
        <div class="subList">
          <div class="subListItem">• A calculer selon la durée du leasing</div>
        </div>
    </div>
    <img src="<?php echo __DIR__ ; ?>/img/leasing_schema.png" alt="" style="width:600px; height: auto;" />
  <?php } ?>

</page>
