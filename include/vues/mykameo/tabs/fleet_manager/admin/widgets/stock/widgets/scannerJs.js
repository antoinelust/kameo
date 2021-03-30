


$('.stockManagerClick').click(function(){initializeModal()});
var roleButton='';
var typeToBind ='';
var tempCompany ='';
var price='';
var ID='';
var ID_OUT='';
var communication='';
var arrayToBills=[];




///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Action lié au scan de produit pour le referencement 
////////////////////////////////////////////////////////////////


function initializeModal(){


	


	getDataForBills();

	let today = new Date();
	let dateInThreeYears = new Date(today.getYear()+1903,today.getMonth(), today.getDay());
	
	document.getElementById('testDateToday').value = today.toISOString().substr(0, 10);
	document.getElementById('testDateIn3years').value = dateInThreeYears.toISOString().substr(0, 10);


	displayLanguage();
	$('#bindArticleTooOrder').hide();

	roleButton = 'scan';
	document.getElementById('displayScan').style.display='block'
	document.getElementById('displayAdd').style.display='none'
	document.getElementById('displayRemove').style.display='none'
	document.getElementById('displayGeneralBill').style.display='none'
	document.getElementById('resultDisplay').value = '';
	document.getElementById('scanTitle').innerHTML = 'Scannez Votre Produit pour ajouter la référence';
	document.getElementById('resultName').innerHTML = 'Etat du scan : Lancez le scan de code barre en appuyant sur start';
	
	$('#widget-stockScan-form input[name=result]').hide();
	$('#widget-stockScan-form div[name=typeDiv]').hide();
	$('#widget-stockScan-form div[name=brandDiv]').hide();
	$('#widget-stockScan-form div[name=modelDiv]').hide();
	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form div[name=categoryAccessoryDiv]').hide();
	$('#widget-stockScan-form div[name=modelBrandAccessoryDiv]').hide();

	$('#widget-stockScan-form div[name=displayResultDiv]').hide();

}

function resultChanged(){

	$('#widget-stockScan-form div[name=displayResultDiv]').hide();
	$('#widget-stockScan-form div[name=typeDiv]').hide();
	$('#widget-stockScan-form div[name=brandDiv]').hide();
	$('#widget-stockScan-form div[name=modelDiv]').hide();
	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form div[name=typeDiv]').show();
}

$('#widget-stockScan-form select[name=type]').change(function(){
	$('#widget-stockScan-form div[name=brandDiv]').hide();
	$('#widget-stockScan-form div[name=modelDiv]').hide();
	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form div[name=categoryAccessoryDiv]').hide();
	$('#widget-stockScan-form div[name=modelBrandAccessoryDiv]').hide();
	

	if($('#widget-stockScan-form select[name=type]').val()=='bike'){
		$('#widget-stockScan-form input[name=action]').val('addBike')
		getDataBike()
		$('#widget-stockScan-form div[name=brandDiv]').show();
	}
	else{
		$('#widget-stockScan-form input[name=action]').val('addAccessory')
		getDataAccessory()
		$('#widget-stockScan-form div[name=categoryAccessoryDiv]').show();
	}

})

//////////////Select correspondant au type velo 

$('#widget-stockScan-form select[name=brand]').change(function(){

	$('#widget-stockScan-form div[name=modelDiv]').hide();
	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form div[name=modelDiv]').show();
	getModelFromBrand()
})


$('#widget-stockScan-form select[name=model]').change(function(){

	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();

	$('#widget-stockScan-form div[name=sizeDiv]').show();

})


$('#widget-stockScan-form select[name=size]').change(function(){
	$('#widget-stockScan-form div[name=colorDiv]').show();
	$('#widget-stockScan-form button[name=sendValue]').hide();
})

$('#widget-stockScan-form input[name=color]').change(function(){
	$('#widget-stockScan-form button[name=sendValue]').show();
})
///////////////////////////////////////////////////


//////////////////Select correspondant au type accessoire 

$('#widget-stockScan-form select[name=category]').change(function(){

	$('#widget-stockScan-form div[name=modelBrandAccessoryDiv]').show();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	getModelAndBrandAccessory()
})
$('#widget-stockScan-form select[name=accessory]').change(function(){
	$('#widget-stockScan-form button[name=sendValue]').show();
})


////////////////////////////////Recuperation donnes pour les vélo 
function getDataBike(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "loadDataBike"},
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				var i =0;
				$('#widget-stockScan-form select[name=brand]').find('option').remove().end();
				while(i<response.numberBrand){
					if(i==0){
						$('#widget-stockScan-form select[name=brand]').append('<option disabled selected value>Choisissez la marque</option>');
					}
					$('#widget-stockScan-form select[name=brand]').append('<option value="'+response.bike[i].brand+'">'+response.bike[i].brand+'</option>');
					i++;
				}
			}
		}
	});
}

function getModelFromBrand(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "loadModelBrand", "brand":$('#widget-stockScan-form select[name=brand]').val()},
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				var i =0;
				$('#widget-stockScan-form select[name=model]').find('option').remove().end();
				while(i<response.numberModel){
					if(i==0){
						$('#widget-stockScan-form select[name=model]').append('<option disabled selected value>Choisissez la marque</option>');
					}
					$('#widget-stockScan-form select[name=model]').append('<option value="'+response.bike[i].id+'">'+response.bike[i].model+'-'+response.bike[i].frame_type+'</option>');
					i++;
				}
			}
		}
	});
}
////////////////////////////////////////////////////////////////

///////////////////////////Recuperation des données pour les  accessoires 

function getDataAccessory(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "loadCategory"},
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				var i =0;
				$('#widget-stockScan-form select[name=category]').find('option').remove().end();
				while(i<response.numberCategory){
					if(i==0){
						$('#widget-stockScan-form select[name=category]').append('<option disabled selected value>Choisissez la catégorie </option>');
					}
					$('#widget-stockScan-form select[name=category]').append('<option value="'+response.bike[i].id+'">'+response.bike[i].category+'</option>');
					i++;
				}
			}
		}
	});
}

function getModelAndBrandAccessory(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "loadModelBrandCategory", "idCategory": $('#widget-stockScan-form select[name=category]').val() },
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				var i =0;
				$('#widget-stockScan-form select[name=accessory]').find('option').remove().end();
				while(i<response.numberModelBrand){
					if(i==0){
						$('#widget-stockScan-form select[name=accessory]').append('<option disabled selected value>Choisissez la Mraque et le modèle correspondant </option>');
					}
					$('#widget-stockScan-form select[name=accessory]').append('<option value="'+response.bike[i].id+'">'+response.bike[i].brand +'-'+response.bike[i].model+'</option>');
					i++;
				}
			}
		}
	});
}
///////////////Recupere les données lié au code barre déja repertorié

function displayDataBarcode(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "getDataFromBarcode","barcode" : document.getElementById('resultDisplay').value},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {
				$('#widget-stockScan-form div[name=typeDiv]').hide();
				$('#widget-stockScan-form div[name=displayResultDiv]').show();

				$('#widget-stockScan-form div[name=typeResultDiv]').show();
				$('#widget-stockScan-form input[name=typeResult]').val(response.type);

				if(response.type=='BIKE'){

					$('#widget-stockScan-form div[name=brandResultDiv]').show();
					$('#widget-stockScan-form input[name=brandResult]').val(response.brand);

					$('#widget-stockScan-form div[name=modelResultDiv]').show();
					$('#widget-stockScan-form input[name=modelResult]').val(response.model);

					$('#widget-stockScan-form div[name=sizeResultDiv]').show();
					$('#widget-stockScan-form input[name=sizeResult]').val(response.size);

					$('#widget-stockScan-form div[name=colorResultDiv]').show();
					$('#widget-stockScan-form input[name=colorResult]').val(response.color);

					$('#widget-stockScan-form div[name=categoryResultDiv]').hide();
					
				}
				else{
					$('#widget-stockScan-form div[name=brandResultDiv]').show();
					$('#widget-stockScan-form input[name=brandResult]').val(response.brand);

					$('#widget-stockScan-form div[name=modelResultDiv]').show();
					$('#widget-stockScan-form input[name=modelResult]').val(response.model);

					$('#widget-stockScan-form div[name=categoryResultDiv]').show();
					$('#widget-stockScan-form input[name=categoryResult]').val(response.category);

					$('#widget-stockScan-form div[name=sizeResultDiv]').hide();
					$('#widget-stockScan-form div[name=colorResultDiv]').hide();
				}
			}	
		}
	});
}

///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Fin Action lié au scan de produit pour le referencement 
////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Action lié au scan de produit pour l'ajout de stock 
////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////

$('#listOrder').change(function(){
	$('#bindArticleTooOrder').show();
})


////////////////////////Liaison de l'article au code barre scannez pour l'ajoit de stock

function bindArticle(){
	document.getElementById('listOrderDiv').style.display='none';
	$('#bindArticleTooOrder').hide();
	document.getElementById('resultDisplay').value='';
	
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "changeContractType", "id": $('#listOrder').val() , "typeArticle" : typeToBind},
		success: function(response){
			if(response.response == 'error') {
				console.log('test');
			}
			if(response.response == 'success'){
				$.notify(
				{
					message:'Cette commandea bien été mis à jour',
				},
				{
					type: "success",
				}
				);
			}
		}
	});
}


///////////////Récupere les vélos de contract type 'order'
function displaySelectToAddStock(type){
	if(roleButton=='add'){
		if(type=='BIKE'){
			typeToBind = 'BIKE';
			$.ajax({
				url: 'apis/Kameo/scannerForm.php',
				type: 'get',
				data: {"action": "loadBikeOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'order'},
				success: function(response){
					if(response.response == 'error') {
						console.log('error');
					}
					if(response.response == 'success'){


						if(response.numberBikeOrder>0){

							var i =0;

							document.getElementById('listOrderDiv').style.display='block'
							$('#listOrder').find('option').remove().end();
							while(i<response.numberBikeOrder){
								if(i==0){
									$('#listOrder').append('<option disabled selected value>Choisissez la vélo lié à cette commande </option>');
								}
								$('#listOrder').append('<option value="'+response.bike[i].bike+'">'+response.bike[i].bike+'-'+response.brand +'-'+response.model+'-'+response.frame_type +':'+response.bike[i].estimate_date+'</option>');
								i++;

							}
						}
						else {
							$.notify(
							{
								message:'Aucune article de ce type n\'a été commandé ou en stock',
							},
							{
								type: "danger",
							}
							);
						}
					}
				}
			});
		}
		else if (type='ACCESSORY'){
			typeToBind = 'ACCESSORY';
			$.ajax({
				url: 'apis/Kameo/scannerForm.php',
				type: 'get',
				data: {"action": "loadAccessoryOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'order'},
				success: function(response){
					if(response.response == 'error') {
						console.log('error');
					}
					if(response.response == 'success'){

						if(response.numberAccessoryOrder>0){

							var i =0;
							
							document.getElementById('listOrderDiv').style.display='block'
							$('#listOrder').find('option').remove().end();
							while(i<response.numberAccessoryOrder){
								if(i==0){
									$('#listOrder').append('<option disabled selected value>Choisissez l\accesoire lié à cette commande </option>');
								}
								$('#listOrder').append('<option value="'+response.bike[i].accessory+'">'+response.bike[i].accessory+'-'+response.brand +'-'+response.model+':'+response.category+'</option>');
								i++;
							}
						}
						else {
							$.notify(
							{
								message:'Aucune article de ce type n\'a été commandé ou en stock',
							},
							{
								type: "danger",
							}
							);
						}
					}
				}
			});
		}
	}
	else if (roleButton=='remove'){
		$('#widget-stockScanRemove-form div[name=displayPendingDiv]').hide();
		$('#widget-stockScanRemove-form button[name=sendValueToRemove]').hide();
		var contract='';
		if(type=='BIKE'){
			typeToBind = 'BIKE';
			$.ajax({
				url: 'apis/Kameo/scannerForm.php',
				type: 'get',
				data: {"action": "loadBikeOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'stock'},
				success: function(response){
					if(response.response == 'error') {
						console.log('error');
					}
					if(response.response == 'success'){
						if(response.numberBikeOrder>0){

							var i =0;
							$('#widget-stockScanRemove-form div[name=listOrderTypeDiv]').show();
							$('#widget-stockScanRemove-form select[name=listOrderType]').find('option').remove().end();
							while(i<response.numberBikeOrder){
								if(i==0){
									$('#widget-stockScanRemove-form select[name=listOrderType]').append('<option disabled selected value>Choisissez la vélo lié à cette commande </option>');
								}
								if (response.bike[i].contract == 'pending_delivery') {
									contract = '<span class="text-green">'+response.bike[i].contract+'</span>';
								}else{
									contract = '<span class="text-red">'+response.bike[i].contract+'</span>';
								}

								$('#widget-stockScanRemove-form select[name=listOrderType]').append('<option value="'+response.bike[i].bike+'" data-contract="'+response.bike[i].contract+'" data-type="'+response.type+'">'+response.bike[i].bike+'-'+response.brand +'-'+response.model+'-'+response.frame_type +':'+response.bike[i].estimate_date +' : - '+contract+'</option>');
								i++;
							}
						}
						else {
							$.notify(
							{
								message:'Aucune article de ce type n\'a été commandé ou en stock',
							},
							{
								type: "danger",
							}
							);
						}
					}
				}
			});
		}
		else if (type='ACCESSORY'){
			typeToBind = 'ACCESSORY';
			$.ajax({
				url: 'apis/Kameo/scannerForm.php',
				type: 'get',
				data: {"action": "loadAccessoryOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'stock'},
				success: function(response){
					if(response.response == 'error') {
						console.log('error');
					}
					if(response.response == 'success'){

						if(response.numberAccessoryOrder>0){

							var i =0;
							
							$('#widget-stockScanRemove-form div[name=listOrderTypeDiv]').show();
							$('#widget-stockScanRemove-form select[name=listOrderType]').find('option').remove().end();
							while(i<response.numberAccessoryOrder){
								if(i==0){
									$('#widget-stockScanRemove-form select[name=listOrderType]').append('<option disabled selected value>Choisissez l\'accesoire lié à cette commande </option>');
								}

								if (response.bike[i].contract == 'pending_delivery') {
									contract = '<span class="text-green">'+response.bike[i].contract+'</span>';
								}else{
									contract = '<span class="text-red">'+response.bike[i].contract+'</span>';
								}
								$('#widget-stockScanRemove-form select[name=listOrderType]').append('<option value="'+response.bike[i].accessory+'" data-contract="'+response.bike[i].contract+'">'+response.bike[i].accessory+'-'+response.brand +'-'+response.model+':'+response.category+' : - '+contract+'</option>');
								i++;
							}
						}
						else {
							$.notify(
							{
								message:'Aucune article de ce type n\'a été commandé ou en stock',
							},
							{
								type: "danger",
							}
							);
						}
					}
				}
			});
		}
	}
}


///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Fin Action lié au scan de produit pour l'ajout de stock 
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Action lié au scan de produit pour la sortie de stock 
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

function hideAllFromRemove(){
	$('#widget-stockScanRemove-form div[name=displayPendingDiv]').hide();
	$('#widget-stockScanRemove-form input[name=result]').hide();
	$('#widget-stockScanRemove-form div[name=typeOutputDiv]').hide();
	$('#widget-stockScanRemove-form div[name=listOrderTypeDiv]').hide();
	$('#widget-stockScanRemove-form div[name=orderClientDiv]').hide();
	$('#widget-stockScanRemove-form div[name=orderCompanyDiv]').hide();
	$('#widget-stockScanRemove-form button[name=sendValueToRemove]').hide();
	$('#widget-stockScanRemove-form select[name=typeOutput]').attr("disabled", false);
	
}


$('#widget-stockScanRemove-form select[name=listOrderType]').change(function(){
	$('#widget-stockScanRemove-form select[name=typeOutput]').val('0');
	$('#widget-stockScanRemove-form select[name=orderCompany]').val('0');
	$('#widget-stockScanRemove-form select[name=typeOutput]').attr("disabled", false);
	$('#widget-stockScanRemove-form div[name=displayPendingDiv]').hide();
	$('#widget-stockScanRemove-form div[name=typeOutputDiv]').hide();
	$('#widget-stockScanRemove-form div[name=orderClientDiv]').hide();
	$('#widget-stockScanRemove-form div[name=orderCompanyDiv]').hide();
	$('#widget-stockScanRemove-form button[name=sendValueToRemove]').hide();
	if($('#widget-stockScanRemove-form select[name=listOrderType]').find(':selected').data('contract')=='pending_delivery'){
		displayResultPendingDelivery();
		$('#widget-stockScanRemove-form input[name=action]').val('leasingStockPending');
	}
	else {
		$('#widget-stockScanRemove-form div[name=typeOutputDiv]').show();
	}	
})

$('#widget-stockScanRemove-form select[name=typeOutput]').change(function(){
	$('#widget-stockScanRemove-form select[name=orderCompany]').val('0');
	$('#widget-stockScanRemove-form div[name=orderCompanyDiv]').hide();
	$('#widget-stockScanRemove-form div[name=orderClientDiv]').hide();
	$('#widget-stockScanRemove-form button[name=sendValueToRemove]').hide();
	$('#widget-stockScanRemove-form div[name=orderCompanyDiv]').show();
})

$('#widget-stockScanRemove-form select[name=orderCompany]').change(function(){
	tempCompany = $('#widget-stockScanRemove-form select[name=orderCompany]').find(':selected').data('inter');
	
	$('#widget-stockScanRemove-form input[name=companyInternalReference]').val(tempCompany);
	changeCompanyAndClient()
	$('#widget-stockScanRemove-form div[name=orderClientDiv]').show();
	$('#widget-stockScanRemove-form button[name=sendValueToRemove]').show();
	
	
	if($('#widget-stockScanRemove-form select[name=typeOutput]').val('selling')){
		$('#widget-stockScanRemove-form input[name=action]').val('selling');
	}
	else{
		$('#widget-stockScanRemove-form input[name=action]').val('leasingStockPending');
	}

	$('#widget-stockScanRemove-form input[name=typeArticle]').val(typeToBind);
})

function changeCompanyAndClient(){
	$.ajax({
		url: 'apis/Kameo/get_users_listing.php',
		type: 'post',
		data: { "companyID": $('#widget-stockScanRemove-form select[name=orderCompany]').val()},
		success: function(response){
			if(response.response == 'error') {
				console.log(response.message);
			}
			if(response.response == 'success'){
				$('#widget-stockScanRemove-form select[name=orderClient]')
				.find('option')
				.remove()
				.end()
				;

				var i=0;
				var toSelect = null;
				while (i < response.usersNumber){
					if(i==0){
						$('#widget-stockScanRemove-form select[name=orderClient]').append('<option disabled selected value>Choisissez l\'employé </option>');
					}
					$('#widget-stockScanRemove-form select[name=orderClient]').append("<option value="+response.users[i].email+">"+response.users[i].name+" - "+response.users[i].firstName+"<br>");
					i++;
				}
			}
		}
	});
}
function displayResultPendingDelivery(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "getDataFromOrderPendingDelivery","id" :$('#widget-stockScanRemove-form select[name=listOrderType]').val(),"typeArticle": typeToBind},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {

				$('#widget-stockScanRemove-form div[name=displayPendingDiv]').show();
				$('#widget-stockScanRemove-form input[name=typePendingArticle]').val(typeToBind);
				$('#widget-stockScanRemove-form button[name=sendValueToRemove]').show();
				$('#widget-stockScanRemove-form div[name=typeOutputDiv]').show();
				$('#widget-stockScanRemove-form select[name=typeOutput]').val('leasing');
				$('#widget-stockScanRemove-form select[name=typeOutput]').attr("disabled", true);

				if(typeToBind=='BIKE'){

					$('#widget-stockScanRemove-form input[name=companyPending]').val(response.company);
					$('#widget-stockScanRemove-form input[name=clientPending]').val(response.name + ' - ' + response.firstname);
				}
				else{
					// pas d'accessoire en pending delivery pour l'instant
				}
			}	
		}
	});
}

function getCompanies() {
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "listCompanies"},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {
				var i =0;
				$('#widget-stockScanRemove-form select[name=orderCompany]').find('option').remove().end();
				$('#widget-generateBill-form select[name=companyBills]').find('option').remove().end();
				while(i<response.numberCompanies){
					if(i==0){
						$('#widget-stockScanRemove-form select[name=orderCompany]').append('<option disabled selected value="0">Choisissez la société </option>');
						$('#widget-generateBill-form select[name=companyBills]').append('<option disabled selected value="0">Choisissez la société </option>');

					}

					$('#widget-stockScanRemove-form select[name=orderCompany]').append('<option value="'+response.companies[i].id+'" data-inter="'+response.companies[i].internalRef+'" data-name="'+response.companies[i].name+'">'+response.companies[i].name+'</option>');
					$('#widget-generateBill-form select[name=companyBills]').append('<option value="'+response.companies[i].id+'" data-inter="'+response.companies[i].internalRef+'" data-name="'+response.companies[i].name+'">'+response.companies[i].name+'</option>');
					i++;
				}
			}	
		}
	});
}


///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Fin Action lié au scan de produit pour la sortie de stock 
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Action utilisé pour tous les type de scan 
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

////////////////////Verifie si le code barre est déja repertorié 
function checkPresent(){
	document.getElementById('listOrderDiv').style.display='none'
	$('#bindArticleTooOrder').hide();

	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "check","barcode" :document.getElementById('resultDisplay').value},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {
				if(roleButton=='scan'){
					resultChanged()
				}
				else if(roleButton=='add' || roleButton=='remove' ){
					$.notify(
					{
						message:'Référence Inconnu, veuillez d\'abord l\'ajouter. Dirigez vous vers l\'onglet \"Scanner nouveau produit\" ',
					},
					{
						type: "warning",
					}
					);
				}
			}
			if(response.response == 'present') {
				if(roleButton=='scan'){
					$.notify(
					{
						message:'Article déja repertorié sur la BDD',
					},
					{
						type: "info",
					}
					);
					displayDataBarcode()
				}
				else if(roleButton=='add'){
					displaySelectToAddStock(response.type)
				}
				else if(roleButton=='remove'){
					
					displaySelectToAddStock(response.type)
				}
				else if(roleButton=='generateBill'){
					
					displayToAddBills(response.type,response.id)
				}
			}
		}
	});

}
//////////////////////////////////////////////////////////////////////////

///////////////Fonctionnalité activant le début du scan de code barre

window.addEventListener('load', function () {
	let selectedDeviceId;
	const codeReader = new ZXing.BrowserBarcodeReader()

	
	codeReader.getVideoInputDevices()
	.then((videoInputDevices) =>{
		const sourceSelect = document.getElementById('sourceSelect');

		selectedDeviceId = videoInputDevices[0].deviceId;
		if (videoInputDevices.length > 1) {
			videoInputDevices.forEach((element) => {
				const sourceOption = document.createElement('option')
				sourceOption.text = element.label
				sourceOption.value = element.deviceId
				sourceSelect.appendChild(sourceOption)
			})

			sourceSelect.onchange = () => {
				selectedDeviceId = sourceSelect.value;
				document.getElementById('resultName').innerHTML = 'Etat du scan : Changement de camera en cours';
				codeReader.reset();
				decodeContiniously(codeReader, selectedDeviceId);
				document.getElementById('resultName').innerHTML = 'Etat du scan : Recherche de code Barre en cours';
			}

			const sourceSelectPanel = document.getElementById('sourceSelectPanel')
			sourceSelectPanel.style.display = 'block'
		}

		document.getElementById('startButton').addEventListener('click', () => {
			decodeContiniously(codeReader, selectedDeviceId);
		})
		document.getElementById('stopButton').addEventListener('click', () => {
			codeReader.reset()
			document.getElementById('resultName').innerHTML = 'Etat du scan : Recherche de code Barre en cours';
			hideAllFromRemove(); 

		})
		
	})
	.catch((err) => {
		console.error(err);
	})
})

function decodeContiniously(codeReader, selectedDeviceId){
	document.getElementById('resultName').innerHTML = 'Etat du scan : Recherche de code Barre en cours';
	codeReader.decodeFromInputVideoDeviceContinuously(selectedDeviceId, 'video', (result, err) => {
		if (result) {
			document.getElementById('resultName').innerHTML = 'Etat du scan :Code Barre Trouvé';
			document.getElementById('resultDisplay').value = result.text
			$('#widget-stockScan-form input[name=result]').val(result.text);
			checkPresent()
			document.getElementById('resultName').innerHTML = 'Etat du scan : Recherche de code Barre en cours';

		}if (err) {
			if (err instanceof ZXing.FormatException) {
				console.log('A code was found, but it was in a invalid format.')
			}
		}
	})
}

///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////Fin Action utilisé pour tous les type de scan 
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////




function create_Facture(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action":'getPrice', "id":$('#widget-stockScanRemove-form select[name=listOrderType]').val(),"typeArticle":typeToBind},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {

				price = response.price;
				let dateToday = new Date().toISOString().substr(0, 10);
				let today = new Date();
				let datelimit = new Date(today.getYear()+1903,today.getMonth(), today.getDay()).toISOString().substr(0, 10);
				
				$.ajax({
					url: 'apis/Kameo/add_bill.php',
					type: 'post',
					data: {"beneficiaryCompany":'KAMEO',
					"widget-addBill-form-email":email,
					"scan":'test',
					"ID":ID, 
					"type":'selling',
					"ID_OUT": ID_OUT,
					"widget-addBill-form-date":dateToday,
					"widget-addBill-form-datelimite": datelimit,
					"widget-addBill-form-companyOther":'',
					"widget-addBill-form-amountHTVA":'',
					"widget-addBill-form-amountTVAC":'',
					"communication":communication,
					"company":$('#widget-stockScanRemove-form select[name=orderCompany]').find(':selected').data('name'),
					"billType":'automatic',
					"date":dateToday , 
					"companyInternalReference":tempCompany,
					"typeArticle":typeToBind, 
					"articleId":$('#widget-stockScanRemove-form select[name=listOrderType]').val(),
					"articlePrice":price},
					success: function(response){
						if(response.response == 'error') {
							console.log('error')
						}
						if(response.response == 'success') {
							hideAllFromRemove(); 
							document.getElementById("widget-stockScanRemove-form").reset();
						}	
					}
				});
			}	
		}
	});	
}
function getDataForBills(){
	$.ajax({
		url: 'apis/Kameo/get_bills_listing.php',
		type: 'post',
		data: { "email": email, "company": '*', "sent": '*', "paid": '*', "direction": '*'},
		success: function(response){
			if(response.response == 'error') {
				console.log(response.message);
			}
			if(response.response == 'success'){

				ID = parseInt(response.IDMaxBillingOut) +1;
				ID_OUT=parseInt(response.IDMaxBilling) +1;
				communication = response.communication;
			}
		}
	});
}
/////////////////////////Responsable du changement de fonctionnalité lié au scan 

function changeDisplay(type)
{

	roleButton = type;

	if(type == 'scan'){
		$('#bindArticleTooOrder').hide();
		document.getElementById('displayScan').style.display='block'
		document.getElementById('displayAdd').style.display='none'
		document.getElementById('displayRemove').style.display='none'
		document.getElementById('displayGeneralBill').style.display='none'
		document.getElementById('resultDisplay').value = '';
		document.getElementById('scanTitle').innerHTML = 'Scannez Votre Produit pour ajouter la référence';
		initializeModal();
	}
	else if(type == 'add'){
		$('#bindArticleTooOrder').hide();
		$('#listOrder').find('option').remove().end();
		document.getElementById('listOrderDiv').style.display='none'
		document.getElementById('displayAdd').style.display='block'
		document.getElementById('displayScan').style.display='none'
		document.getElementById('displayRemove').style.display='none'
		document.getElementById('scanTitle').innerHTML = 'Scannez Votre Produit pour l\'ajouter au stock';
		document.getElementById('resultDisplay').value = '';
		document.getElementById('displayGeneralBill').style.display='none'
	}
	else if(type == 'remove'){
		hideAllFromRemove();
		getCompanies();
		/*$('#bindArticleTooOrder').hide();
		$('#listOrder').find('option').remove().end();
		document.getElementById('listOrderDiv').style.display='none'*/
		document.getElementById('displayAdd').style.display='none'
		document.getElementById('displayScan').style.display='none'
		document.getElementById('displayRemove').style.display='block'
		document.getElementById('scanTitle').innerHTML = 'Scannez Votre Produit pour l\'enlevé du stock';
		document.getElementById('resultDisplay').value = '';
		document.getElementById('displayGeneralBill').style.display='none'
	}
	else if(type == 'generateBill'){
		document.getElementById('generateBillDiv').style.display='none';
		$('#widget-generateBill-form div[name=bikeBillsDiv]').hide();
		getCompanies();
		document.getElementById('displayGeneralBill').style.display='block'
		document.getElementById('displayAdd').style.display='none'
		document.getElementById('displayScan').style.display='none'
		document.getElementById('displayRemove').style.display='none'
		document.getElementById('scanTitle').innerHTML = 'Configurez Votre Facture combiné';
		document.getElementById('resultDisplay').value = '';

	}
	else if(type == 'bills'){
		
	}
}

function displayToAddBills(type, id){
	$('#widget-generateBill-form div[name=bikeBillsDiv]').show();
	document.getElementById('generateBillDiv').style.display='block';
	if(type=='BIKE'){
		typeToBind = 'BIKE';
		$.ajax({
			url: 'apis/Kameo/scannerForm.php',
			type: 'get',
			data: {"action": "loadBikeOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'stock'},
			success: function(response){
				if(response.response == 'error') {
					console.log('error');
				}
				if(response.response == 'success'){
					if(response.numberBikeOrder>0){
						var i =0;
						$('#widget-generateBill-form select[name=bikeBills]').find('option').remove().end();
						while(i<response.numberBikeOrder){
							if(i==0){
								$('#widget-generateBill-form select[name=bikeBills]').append('<option disabled selected value>Choisissez la vélo lié à cette commande </option>');
							}
							$('#widget-generateBill-form select[name=bikeBills]').append('<option value="'+response.bike[i].bike+'" data-price="'+response.bike[i].bikePrice+'" data-contract="'+response.bike[i].contract+'" data-type="'+response.type+'">'+response.bike[i].bike+'-'+response.brand +'-'+response.model+'-'+response.frame_type +':'+response.bike[i].estimate_date +' : - '+response.bike[i].contract+'</option>');
							i++;
						}
					}
					else {
						$.notify(
						{
							message:'Aucune article de ce type n\'a été commandé ou en stock',
						},
						{
							type: "danger",
						}
						);
					}
				}
			}
		});
	}
	else if (type='ACCESSORY'){
		typeToBind = 'ACCESSORY';
		$.ajax({
			url: 'apis/Kameo/scannerForm.php',
			type: 'get',
			data: {"action": "loadAccessoryOrder","barcode" :document.getElementById('resultDisplay').value,"typeContract":'stock'},
			success: function(response){
				if(response.response == 'error') {
					console.log('error');
				}
				if(response.response == 'success'){

					if(response.numberAccessoryOrder>0){

						var i =0;

						
						$('#widget-generateBill-form select[name=bikeBills]').find('option').remove().end();
						while(i<response.numberAccessoryOrder){
							if(i==0){
								$('#widget-generateBill-form select[name=bikeBills]').append('<option disabled selected value>Choisissez l\'accesoire lié à cette commande </option>');
							}
							$('#widget-generateBill-form select[name=bikeBills]').append('<option value="'+response.bike[i].accessory+'" data-contract="'+response.bike[i].contract+'">'+response.bike[i].accessory+'-'+response.brand +'-'+response.model+':'+response.category+' : - '+response.bike[i].contract+'</option>');
							i++;
						}
					}
					else {
						$.notify(
						{
							message:'Aucune article de ce type n\'a été commandé ou en stock',
						},
						{
							type: "danger",
						}
						);
					}
				}
			}
		});
	}
}
$('#widget-generateBill-form select[name=bikeBills]').change(function(){
	//add Text 
	var article='';
	var temparr=[];
	temparr[0]=$('#widget-generateBill-form select[name=bikeBills]').val();
	if(typeToBind=='BIKE'){
		temparr[1]=$('#widget-generateBill-form select[name=bikeBills]').find(':selected').data('price');
		article='bikeSell';
		temparr[2]=article;
	}
	else {
		$.ajax({
			url: 'apis/Kameo/scannerForm.php',
			type: 'get',
			data: {"action":'getPrice', "id":$('#widget-generateBill-form select[name=bikeBills]').val(),"typeArticle":typeToBind},
			success: function(response){
				if(response.response == 'error') {
					console.log('error')
				}
				if(response.response == 'success') {
					price = response.price;
				}
			}
		});
		temparr[1]=price;
		article='accessorySell';
		temparr[2]=article;
	}
	temparr[3]=typeToBind;
	arrayToBills.push(temparr);
	if(arrayToBills.length==1){
		$('#widget-generateBill-form label[name=listArticleBills]').text('List des Articles :');
	}
	$('#widget-generateBill-form label[name=listArticleBills]').text($('#widget-generateBill-form label[name=listArticleBills]').text()+"\n"+arrayToBills.length+" type de l'article : " +typeToBind+" Id : " +temparr[0] + " au prix HTVA de " +temparr[1]);


	$('#widget-generateBill-form div[name=bikeBillsDiv]').hide();
	document.getElementById('resultDisplay').value = '';
})

function generateBill(){

	let dateToday = new Date().toISOString().substr(0, 10);
	let today = new Date();
	let dateThree = new Date(today.getYear()+1903,today.getMonth(), today.getDay()).toISOString().substr(0, 10);

	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action":'changeMultipleArticles',"companyInternalReference":$('#widget-generateBill-form select[name=companyBills]').find(':selected').data('inter'),"companyPending":$('#widget-generateBill-form select[name=companyBills]').val(),"testDateToday":dateToday,"testDateIn3years":dateThree,"bikeArrayId":arrayToBills,"articleNumbers":arrayToBills.length},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {
				console.log('test');
				$.ajax({
					url: 'apis/Kameo/add_bill.php',
					type: 'post',
					data: {"beneficiaryCompany":'KAMEO',
					"widget-addBill-form-email":email,
					"scan":'billsGeneral',
					"ID":ID, 
					"type":'selling',
					"ID_OUT": ID_OUT,
					"widget-addBill-form-date":dateToday,
					"widget-addBill-form-datelimite": dateThree,
					"widget-addBill-form-companyOther":'',
					"widget-addBill-form-amountHTVA":'',
					"widget-addBill-form-amountTVAC":'',
					"communication":communication,
					"company":$('#widget-generateBill-form select[name=companyBills]').find(':selected').data('name'),
					"billType":'automatic',
					"date":dateToday , 
					"companyInternalReference":$('#widget-generateBill-form select[name=companyBills]').find(':selected').data('inter'),
					"bikeArrayId":arrayToBills,
					"articleNumbers":arrayToBills.length},
					success: function(response){
						if(response.response == 'error') {
							console.log('error')
						}
						if(response.response == 'success') {
							$('#widget-generateBill-form div[name=bikeBillsDiv]').hide();
							getCompanies()
							document.getElementById('generateBillDiv').style.display='none';
							$('#widget-generateBill-form label[name=listArticleBills]').text('List des Articles :');
							document.getElementById('resultDisplay').value = '';
							document.getElementById("widget-generateBill-form").reset();
						}	
					}
				});
			}
		}
	});
}