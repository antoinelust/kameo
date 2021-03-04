


$('.stockManagerClick').click(function(){initializeModal()});

function initializeModal(){
	var roleButton = 'scan';
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
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "check","barcode" : $('#widget-stockScan-form input[name=result]').val()},
		success: function(response){
			if(response.response == 'error') {
				console.log('error')
			}
			if(response.response == 'success') {
				resultChanged()
			}
			if(response.response == 'present') {
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
		}
	});
}
//////////////////////////////////////////////////////////////////////////

///////////////Recupere les données lié au code barre déja repertorié

function displayDataBarcode(){
	$.ajax({
		url: 'apis/Kameo/scannerForm.php',
		type: 'get',
		data: {"action": "getDataFromBarcode","barcode" : $('#widget-stockScan-form input[name=result]').val()},
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
			}

			const sourceSelectPanel = document.getElementById('sourceSelectPanel')
			sourceSelectPanel.style.display = 'block'
		}

		document.getElementById('startButton').addEventListener('click', () => {
			decodeContiniously(codeReader, selectedDeviceId);
		})
		document.getElementById('stopButton').addEventListener('click', () => {
			codeReader.reset()
		})
		
	})
	.catch((err) => {
		console.error(err);
	})
})

function decodeContiniously(codeReader, selectedDeviceId){
	document.getElementById('resultName').innerHTML = 'Etat du scan : Recherche de code Barre en cours';
	$('#widget-stockScan-form input[name=result]').hide();

	codeReader.decodeFromInputVideoDeviceContinuously(selectedDeviceId, 'video', (result, err) => {
		if (result) {
			document.getElementById('resultName').innerHTML = 'Etat du scan :Code Barre Trouvé';
			$('#widget-stockScan-form input[name=result]').show();
			document.getElementById('result').value = result.text
			checkPresent()

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

	}
	else if(type == 'add'){
		
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