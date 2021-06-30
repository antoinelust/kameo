$( ".orderAccessoriesClick" ).click(function(){

	$("#displayorderAcessory").dataTable({
		scrollX: true,
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
		columnDefs: [{ width: "0%", targets: 0 }],
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
			},
			{ title: "",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html('<a href="#" class="text-green" data-target="#accessoryOrderManagement" data-toggle="modal" data-action="update" data-id="'+sData+'">Update</a>');
				},
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
		getOrderDetailAcessory(ID);
		$('#accessoryOrderManagement input').attr('disabled', false);
		$('#accessoryOrderManagement select').attr('disabled', false);
		$('#accessoryOrderManagement [type=submit]').removeClass("hidden");
		$('#accessoryOrderManagement input[name=id]').closest('div').removeClass("hidden");
		$('#accessoryOrderManagement input[name=id]').attr('readonly', true);
		$('#accessoryOrderManagement input[name=grouped_id]').attr('readonly', true);
		$('#accessoryOrderManagement select[name=company]').attr('readonly', true);
		$('#accessoryOrderManagement input[name=action]').val("updateOrderDetailAcessory");
		$('#accessoryOrderManagement .title').text("Modifier une commande");
	}
});


function getOrderDetailAcessory(ID){
	$.ajax({
		url: 'api/accessories',
		type: 'get',
		data: {"action": "getOrderDetailAcessory", "ID":ID},
		success: function(response){
			console.log("test2");
			if($("#widget-orderAcessory-form select[name=company] option").length == 0){
				$("#widget-orderAcessory-form select[name=company]")
					.find("option")
					.remove()
					.end();
				$.ajax({
					url: "api/companies",
					type: "get",
					data: { type: "*", action: 'listMinimal' },
					success: function (companies) {
						if (companies.response == "success") {
							for (var i = 0; i < companies.company.length; i++) {
								$("#widget-orderAcessory-form select[name=company]").append(
									'<option value="' +
										companies.company[i].ID +
										'">' +
										companies.company[i].companyName +
										"</option>"
								);
							}
							$("#widget-orderAcessory-form [name=company]").val(response.COMPANY_ID);
						}
					}
				})
			}else{
				$("#widget-orderAcessory-form [name=company]").val(response.COMPANY_ID);
			}

			if(response.ACCESSORY_ID==null){
				$("#widget-orderAcessory-form p[name=linkOrderAccessoryToStock]").addClass("hidden");
				$("#widget-orderAcessory-form select[name=linkOrderAccessoryToStock]").parent().removeClass("hidden");

				$.ajax({
					url: 'api/accessories',
					type: 'get',
					data: {action: "getStockAccessoryNotLinkedToOrder", catalogID:response.catalogID},
					success: function(accessories){
						$("#widget-orderAcessory-form select[name=linkOrderAccessoryToStock]")
						.find("option")
						.remove()
						.end();
						accessories.forEach(function(accessory){
							$("#widget-orderAcessory-form select[name=linkOrderAccessoryToStock]").append("<option value='"+accessory.ID+"'>"+accessory.ID+" - "+accessory.CONTRACT_TYPE+"</option>");
						})
						$("#widget-orderAcessory-form select[name=linkOrderAccessoryToStock]").val("");
					}
				})
			}else{
				$("#widget-orderAcessory-form p[name=linkOrderAccessoryToStock]").removeClass("hidden");
				$("#widget-orderAcessory-form p[name=linkOrderAccessoryToStock] span").html(response.ACCESSORY_ID);
				$("#widget-orderAcessory-form select[name=linkOrderAccessoryToStock]").parent().addClass("hidden");
			}

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
