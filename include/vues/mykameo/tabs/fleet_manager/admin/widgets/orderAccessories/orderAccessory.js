$( ".fleetmanager" ).click(function() {
	$.ajax({
		url: 'apis/Kameo/initialize_counters.php',
		type: 'post',
		data: { "email": email, "type": "ordersAccessoryAdmin"},
		success: function(response){
			if(response.response == 'error') {
				console.log(response.message);
			}
			if(response.response == 'success'){
				document.getElementById('counterOrderAccessoriesCounter').innerHTML = "<span class=\"text-green\" data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersAccessoryNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersAccessoryNumber+"</span>";
			}
		}
	})
})


$(".orderAccessoriesClick" ).click(function() {
	$("#displayorderAcessory").dataTable({
		destroy: true,
		ajax: {
			url: "apis/Kameo/accessories/accessories.php",
			contentType: "application/json",
			type: "GET",
			data: {
				action: "listOrderAcessories",
			},
		},
		sAjaxDataProp: "",
		columnDefs: [{ width: "100%", targets: 0 }],
		columns: [
		{ title: "ID",data: "ID",},
		{ title: "Société", data: "COMPANY_NAME" },
		{ title: "Type", data: "TYPE" },
		{ title: "Marque du vélo", data: "BRAND"},
		{ title: "Model du vélo", data: "MODEL"},
		{ title: "Categorie", data: "CATEGORY" },
		{ title: "Etat de la commande", data: "CONTRACT_TYPE" ,
		fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
			if(sData=='NONE'){
				$(nTd).html('<label>En attente de traitement</label>');
			}
			if(sData==null){
				$(nTd).html('<label>En attente de traitement</label>');
			}
			if(sData=='order'){
				$(nTd).html('<label>En attente de livraison de nos fournisseurs</label>');
			}
			if(sData=='pending_delivery'){
				$(nTd).html('<label>En attente d\'expedition chez le client</label>');	
			}
			if(sData=='leasing' || sData=='selling'){
				$(nTd).html('<label>Commande envoyé chez le client</label>');
			}
		} 
	},
	{ title: "", data: "ID" ,fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
		$(nTd).html('<a href="#" class="text-green confirmOrderAccessory" data-target="#confirmOrderAccessory" data-toggle="modal" name="linkConfirmOrderAccessory"  data-correspondent="'+oData.ID+'">Update</a>');
	} 
},
],
order: [
[0, "asc"]
],
paging : false,
searching : true
});
});

$('#displayorderAcessory').on( 'draw.dt', function () {
	$('#displayorderAcessory .confirmOrderAccessory').off();
	$('#displayorderAcessory .confirmOrderAccessory').click(function(){
		getOrderDetailAcessory($(this).data('correspondent'));
	})
})
function getOrderDetailAcessory(ID){
	$.ajax({
		url: 'apis/Kameo/accessories/accessories.php',
		type: 'get',
		data: {"action": "getOrderDetailAcessory", "ID":ID},
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				$("#widget-orderAcessory-form [name=company]").val(response[0].COMPANY_NAME);
				$("#widget-orderAcessory-form [name=emailUser]").val(response[0].EMAIL);
				$("#widget-orderAcessory-form [name=contractType]").val(response[0].TYPE);
				$("#widget-orderAcessory-form [name=priceHTVA]").val(response[0].PRICE_HTVA);
				$("#widget-orderAcessory-form [name=description]").val(response[0].DESCRIPTION);
				$("#widget-orderAcessory-form [name=bike]").val(response[0].BRAND + ' - ' +response[0].MODEL);
				$("#widget-orderAcessory-form [name=id]").val(ID);
			}
		}
	});
	$.ajax({
		url: 'apis/Kameo/accessories/accessories.php',
		type: 'get',
		data: {"action": "getContractFromAccessory", "OrderId":ID},
		success: function(response){
			if(response.response == 'error') {
				console.log('error');
			}
			if(response.response == 'success'){
				if(response.orderAccessory!=null){
					$("#widget-orderAcessory-form [name=accessory]").find('option')
					.remove()
					.end();
					$("#widget-orderAcessory-form [name=accessory]").append('<option value="'+response.orderAccessory[0].ID+'">'+response.orderAccessory[0].ID+' : '+response.orderAccessory[0].BRAND +' - '+response.orderAccessory[0].MODEL+'</option>');
					$("#widget-orderAcessory-form [name=accessory]").attr("readonly",true);
					$("#widget-orderAcessory-form [name=submitBtn]").parent().fadeOut();
				}
				else{
					$.ajax({
						url: 'apis/Kameo/accessories/accessories.php',
						type: 'get',
						data: {"action": "getOrderAccessory", "ID":ID},
						success: function(response){
							$("#widget-orderAcessory-form [name=accessory]").find('option')
							.remove()
							.end();
							if(response.response == 'error') {
								console.log('error');
							}
							if(response.response == 'success'){
								var i=0;

								response.orderAccessory.forEach(function(bike, index){
									$("#widget-orderAcessory-form [name=accessory]").append('<option value="'+bike['ID']+'">'+bike['ID']+' : '+bike['BRAND'] +' - '+bike['MODEL']+'</option>');
								});

								$("#widget-orderAcessory-form [name=accessory]").attr("readonly",false);
								$("#widget-orderAcessory-form [name=submitBtn]").parent().fadeIn();
							}
						}
					});
				}
			}
		}
	});

}
