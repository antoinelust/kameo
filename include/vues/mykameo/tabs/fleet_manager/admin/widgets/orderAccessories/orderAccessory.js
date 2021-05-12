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


$( ".orderAccessoriesClick" ).click(function() {

	$("#widget-orderAcessory-form select[name=company]")
    .find("option")
    .remove()
    .end();
		$.ajax({
	    url: "api/companies",
	    type: "get",
	    data: { type: "*", action: 'listMinimal' },
	    success: function (response) {
	      if (response.response == "success") {
	        for (var i = 0; i < response.company.length; i++) {
						$("#widget-orderAcessory-form select[name=company]").append(
							'<option value="' +
								response.company[i].ID +
								'">' +
								response.company[i].companyName +
								"</option>"
						);
					}
				}
			}
		})



	$("#displayorderAcessory").dataTable({
		"scrollXInner": true,
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
			{ title: "ID",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html('<a href="#" class="text-green" data-target="#accessoryOrderManagement" data-toggle="modal" data-action="retrieve" data-id="'+sData+'">'+sData+'</a>');
				},
			},
			{ title: "Société", data: "COMPANY_NAME" },
			{ title: "Assigné à", data: "EMAIL" },
			{ title: "Type", data: "TYPE" },
			{ title: "Montant", data: "PRICE_HTVA"},
			{
				title: "Categorie",
				data: "CATEGORY",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html(traduction["accessoryCategories_"+sData]);
				},
			},
			{ title: "Marque", data: "BRAND"},
			{ title: "Modèle", data: "MODEL"},
			{
				title: "Statut de la commande",
				data: "STATUS",
			}
		],
		order: [
			[0, "asc"]
		],
	});
});

$("#accessoryOrderManagement").on("show.bs.modal", function (event) {
	var ID = $(event.relatedTarget).data("id");
	var action = $(event.relatedTarget).data("action");
	if(action == 'retrieve'){
		getOrderDetailAcessory(ID);
		$('#accessoryOrderManagement .title').text("Consulter une commande");
		$('#accessoryOrderManagement input').attr('disabled', true);
		$('#accessoryOrderManagement select').attr('disabled', true);
		$('#accessoryOrderManagement [type=submit]').addClass("hidden");
		$('#accessoryOrderManagement input[name=ID]').closest('div').removeClass("hidden");
	}else{
		$('#accessoryOrderManagement input').attr('disabled', false);
		$('#accessoryOrderManagement select').attr('disabled', false);
		$('#accessoryOrderManagement [type=submit]').removeClass("hidden");
		$('#accessoryOrderManagement input[name=ID]').attr('readonly', true);
		getOrderDetailAcessory(ID);
		$('#accessoryOrderManagement input[name=action]').val("update");
		$('#accessoryOrderManagement .title').text("Modifier une commande");
		$('#accessoryOrderManagement input[name=ID]').closest('div').removeClass("hidden");
	}
});


function getOrderDetailAcessory(ID){
	$.ajax({
		url: 'api/accessories',
		type: 'get',
		data: {"action": "getOrderDetailAcessory", "ID":ID},
		success: function(response){
			$("#widget-orderAcessory-form [name=company]").val(response.COMPANY_ID);
			$("#widget-orderAcessory-form [name=emailUser]").val(response.EMAIL);
			$("#widget-orderAcessory-form [name=contractType]").val(response.TYPE);
			$("#widget-orderAcessory-form [name=priceHTVA]").val(response.PRICE_HTVA);
			$("#widget-orderAcessory-form [name=description]").val(response.DESCRIPTION);
			$("#widget-orderAcessory-form [name=id]").val(ID);
			$("#widget-orderAcessory-form [name=grouped_id]").val(response.GROUP_ID);
			$("#widget-orderAcessory-form select[name=status]").val(response.STATUS);

			var categoryID=response.categoryID;
			var catalogID=response.catalogID;

			$.ajax({
				url: 'api/accessories',
				type: 'get',
				data: {"action": "listCategories"},
				success: function(response){
					$('#widget-orderAcessory-form select[name=category]').find('option').remove();
					response.categories.forEach(function(category){
						$('#widget-orderAcessory-form select[name=category]').append("<option value='"+category.ID+"'>"+traduction['accessoryCategories_'+category.CATEGORY]+"</option>");
					});
					$('#widget-orderAcessory-form select[name=category]').val(categoryID);

					$.ajax({
						url: 'api/accessories',
						type: 'get',
						data: {"action": "getModelsCategory", 'category': categoryID},
						success: function(response){
							$('#widget-orderAcessory-form select[name=model]').find('option').remove();
							response.models.forEach(function(model){
								$('#widget-orderAcessory-form select[name=model]').append("<option value='"+model.ID+"'>"+model.BRAND+" - "+model.MODEL+"</option>");
							});
							$('#widget-orderAcessory-form select[name=model]').val(catalogID);
						}
					});
				}
			});


		}
	});
}
