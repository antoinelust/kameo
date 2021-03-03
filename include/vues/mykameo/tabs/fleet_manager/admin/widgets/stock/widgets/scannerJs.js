


$('.stockManagerClick').click(function(){initializeModal()});

function initializeModal(){

	console.log('tet')
	var roleButton = 'scan';
	document.getElementById('result').value = 'test';
	$('#widget-stockScan-form div[name=typeDiv]').hide();
	$('#widget-stockScan-form div[name=brandDiv]').hide();
	$('#widget-stockScan-form div[name=modelDiv]').hide();
	$('#widget-stockScan-form div[name=sizeDiv]').hide();
	$('#widget-stockScan-form div[name=colorDiv]').hide();
	$('#widget-stockScan-form button[name=sendValue]').hide();
	$('#widget-stockScan-form div[name=categoryAccessoryDiv]').hide();
	$('#widget-stockScan-form div[name=modelBrandAccessoryDiv]').hide();
}

function resultChanged(){
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

$('#widget-stockScan-form select[name=color]').change(function(){
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
				console.log('testOk')
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
					message: 'Code barre déja présent dans la BDD',
				},
				{
					type: "info",
				}
				);
			}
		}
	});
}
/////////////////////////////////////////////////




window.addEventListener('load', function () {
	let selectedDeviceId;
	const codeReader = new ZXing.BrowserBarcodeReader()

	console.log('ZXing code reader initialized')
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
			codeReader.decodeOnceFromVideoDevice(selectedDeviceId, 'video').then((result) => {
				document.getElementById('result').value = result.text
				checkPresent()
			}).catch((err) => {
				console.error(err)
			})
		})
		
	})
	.catch((err) => {
		console.error(err);
	})
})


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