


$('.stockManagerClick').click(function(){initializeModal()});
var roleButton='';
var typeToBind ='';

function initializeModal(){
	$('#bindArticleTooOrder').hide();

	roleButton = 'scan';
	document.getElementById('displayScan').style.display='block'
	document.getElementById('displayAdd').style.display='none'
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



////////////////////////////////Recuperation donnes pour les vélo 
function getDataBike(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "loadDataBike"},
		success: function(response){
			if(response.response == 'error') {
				console.log('test');
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
				console.log('test');
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
				console.log('test');
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
				console.log('test');
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


/////////////////////////////////////////////////////////////////


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
				else if(roleButton=='add'){
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
			}
		}
	});
}
//////////////////////////////////////////////////////////////////////////

///////////////Récupere les vélos de contract type 'order'
function displaySelectToAddStock(type){
	if(type=='BIKE'){
		typeToBind = 'BIKE';
		$.ajax({
			url: 'apis/Kameo/scannerForm.php',
			type: 'get',
			data: {"action": "loadBikeOrder","barcode" :document.getElementById('resultDisplay').value,"id":$('#listOrder').val()},
			success: function(response){
				if(response.response == 'error') {
					console.log('error');
				}
				if(response.response == 'success'){
					

					if(response.numberBikeOrder>0){
						document.getElementById('listOrderDiv').style.display='block'
						var i =0;
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
							message:'Aucune commande de ce type n\'a été effectuée',
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
			data: {"action": "loadAccessoryOrder","barcode" :document.getElementById('resultDisplay').value,"id":$('#listOrder').val()},
			success: function(response){
				if(response.response == 'error') {
					console.log('error');
				}
				if(response.response == 'success'){

					if(response.numberAccessoryOrder>0){
						document.getElementById('listOrderDiv').style.display='block'
						var i =0;
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
							message:'Aucune commande de ce type n\'a été effectuée',
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
/////////////////////////////////////////////////

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
/////////////////////////Responsable du changement de fonctionnalité lié au scan 

function changeDisplay(type)
{

	roleButton = type;

	if(type == 'scan'){
		$('#bindArticleTooOrder').hide();
		document.getElementById('displayScan').style.display='block'
		document.getElementById('displayAdd').style.display='none'
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
		document.getElementById('scanTitle').innerHTML = 'Scannez Votre Produit pour l\'ajouter au stock';
		document.getElementById('resultDisplay').value = '';

	}
	else if(type == 'remove'){
		
	}
	else if(type == 'bills'){
		
	}
}

/////////////////////////////////////////////////

/*function validForm(){
	if(roleButton == 'scan'){

	}
	else if(roleButton == 'add'){
		
	}
	else if(roleButton == 'remove'){
		
	}
	else if(roleButton == 'bills'){
		
	}
}*/