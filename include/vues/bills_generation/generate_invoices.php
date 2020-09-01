<!DOCTYPE html>
<html lang="fr">
<?php
ob_start();
if(!isset($_SESSION))
	session_start();

$token=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL; //@TODO: replaced by a token to check if connected

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';    
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
$token = getBearerToken();
    
echo '<script type="text/javascript" src="../../js/language2.js">
  displayLanguage();
</script>';

include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
echo '<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">';
include $_SERVER['DOCUMENT_ROOT'].'/include/topbar.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/header.php';
    
if($token==NULL){ //Not connected
  include 'include/vues/login_form/main.php'; //@TODO: REFACTOR
}else{ //Connected
  //@TODO: Replace email chech with authentication token    
  $sql = "SELECT NOM, PRENOM, PHONE, ADRESS, CITY, POSTAL_CODE, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, EMAIL from customer_referential WHERE TOKEN='$token' LIMIT 1";
  if ($conn->query($sql) === FALSE)
    die;
  $user_data = mysqli_fetch_assoc(mysqli_query($conn, $sql));?>
  <section class="content">
  <div class="container">
    <div class="row">
      <!-- MAIN CONTENT -->
      <div class="post-content float-right col-md-9">
        <div class="post-item">
          <div class="post-content-details">
            <div class="heading heading text-left m-b-20">
              <div class="row" style="position: relative;">
                <h2 class="col-sm-8">MY KAMEO</h2>
                  <div class="col-sm-12">
                      <h4 class="text-green">Génération des factures de location Long-terme</h4>                                      
                      <span class="leasingListing"></span>
                  </div>
              </div>
            </div>
            <br/>
            <br/>
          </div>
        </div>
      </div>
      <!-- END: MAIN CONTENT -->
  </div>
</div>
</section>


<?php } ?>

<div class="loader"><!-- Place at bottom of page --></div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>

</div>
<!-- END: WRAPPER -->

<!-- Theme Base, Components and Settings -->
<script src="../../../js/theme-functions.js"></script>

</body>
<?php
$conn->close();
ob_end_flush();
?>
</html>
