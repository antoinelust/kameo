<style type="text/css">

*{
  font-family: 'helvetica';
  font-size: 15px;
}
.white{
  color: white;
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
  display:inline; !important
}
.maxWidth{
  width: 100%;
}
.logo-sm{
  width:180px;
  height: auto;
}








</style>

<page class="white" backcolor="#2fa37c" backtop="10mm" backleft="10mm" backright="10mm">

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
      <td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
        <span>
          <?php if($buyOrLeasing =="buy"){ ?><span style="font-size: 25px;">Achat de vélos</span><br/>
          <?php }else if ($buyOrLeasing =="leasing"){?><span style="font-size: 25px;">Leasing VAE</span><br/>
          <?php }else{?><span style="font-size: 25px;">Achat de vélos</span><br/>
                        <span style="font-size: 25px;">Leasing VAE</span><br/>
          <?php } ?>
        </span>
      </td>
    </tr>

</table>
  <!--<div class="white inline">
    <div class="inline" style="width: 49%; margin:0; padding:0;">
      <span class="arcamajora" style="color:#efefef; font-size:25px;">KAMEO Bikes sprl</span>
      Boulevard de la sauvenière 118
      4000 Liège
    </div>
    <div class="inline" style=" width:49%; margin:0; padding:0; text-align:left;">
      <span class="arcamajora inline" style="color:#efefef; font-size:25px;">KAMEO Bikes sprl</span>
      Boulevard de la sauvenière 118
      4000 Liège
    </div>
  </div>-->

    <hr class="separator"/>

  <table class="maxWidth" style="margin-bottom:10mm; margin-top: 10mm;">
    <tr>
        <td style="text-align:left; padding-left:0; margin-left:0; width:50%;">
          <span class="arcamajora" style="color:#efefef; font-size:25px;">KAMEO Bikes sprl</span><br/><br/>
          Boulevard de la sauvenière 118<br/>
          4000 Liège<br/>
        </td>
        <td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
          <span class="arcamajora" style="color:#efefef; font-size:25px;">Société db</span><br/><br/>
          adresse<br/>
        </td>
    </tr>
</table>
<table class="maxWidth">
  <tr>
      <td style="text-align:left; padding-left:0; margin-left:0; padding-top:4mm; width:50%;">
        <div class="arcamajora" style="color:#efefef; font-size:25px;">Julien JAMAR DE BOLSEE</div><br/><br/>
        julien.jamar@kameobikes.com<br/>
        0498 72 75 46
      </td>
      <td style="text-align:right; padding-right:0; margin-right:0;  width:50%;">
        <span class="arcamajora" style="color:#efefef; font-size:25px;">CONTACT SOCIETE</span><br/><br/>
        email contact<br/>
        téléphone société<br/>
      </td>
  </tr>
</table>
<img style="margin-top:15px;" src="<?php echo __DIR__ ; ?>/img/logo_black.png" alt="kameo" class="logo-sm">

</page>
