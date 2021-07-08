var categoriesOptions;


$("#groupedOrdersListing").on("show.bs.modal", function (event){
	$.ajax({
		url: 'api/accessories',
		type: 'get',
		data: { "action": "getCategories"},
		success: function(response){
			response.categories.forEach(function(category){
				categoriesOptions+='<option value="'+category.ID+'">'+traduction["accessoryCategories_"+category.CATEGORY]+'</option>';
			})
		}
	});

	$("#groupedOrdersListingTable").dataTable({
		destroy: true,
		paging : false,
		scrollX : true,
		ajax: {
			url: "api/orders",
			contentType: "application/json",
			type: "GET",
			data: {
				action: "listGroupedOrders",
			},
		},
		sAjaxDataProp: "orders",
		columns: [
			{
				title: "ID",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html('<a href="#" class="text-green" data-target="#groupedOrderManagement" data-toggle="modal" data-action="update" data-id="'+sData+'">'+sData+'</a>');
				},
			},
			{ title: "Nom de la société", data: "COMPANY_NAME"},
			{ title: "Assigné à", data: "EMAIL"},
			{ title: "Nombre de vélos", data: "numberBikes"},
			{ title: "A livrer", data: "numberBikesNotDelivered"},
			{ title: "Nombre d'accessoires", data: "numberAccessories"},
			{ title: "A livrer", data: "numberAccessoriesNotDelivered"},
			{ title: "Nombre de bornes", data: "numberBoxes"},
			{ title: "A livrer", data: "numberBoxesNotDelivered"},
			{
				title: "Progrès",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html(Math.round(100-(oData.numberBikesNotDelivered+oData.numberAccessoriesNotDelivered+oData.numberBoxesNotDelivered)/(oData.numberBikes+oData.numberAccessories+oData.numberBoxes)*100)+' %');
				},
			},
			{ title: "",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html('<a href="#" class="text-green" data-target="#groupedOrderManagement" data-toggle="modal" data-action="update" data-id="'+sData+'">Update</a>');
				},
			}
		],
		order: [
			[9, "desc"]
		],
	});
})


$("#groupedOrderManagement").on("show.bs.modal", function (event) {

	$.ajax({
		url: 'api/companies',
		type: 'get',
		data: { action: "listMinimal"},
		success: function(response){
			$('#groupedOrderManagement select[name=company]').find('option')
			.remove()
			.end();
			response.company.forEach(function(company){
				$('#groupedOrderManagement select[name=company]').append('<option value="'+company.ID+'" >'+company.companyName+'</option>');
			})
			$('#groupedOrderManagement select[name=company]').val('');
		}
	})

	var action = $(event.relatedTarget).data("action");
	var ID = $(event.relatedTarget).data("id");
	if(action == 'retrieve'){
		retrieveGroupedOrder(ID);
		$('#groupedOrderManagement .title').text("Consulter une commande");
		$('#groupedOrderManagement input').attr('disabled', true);
		$('#groupedOrderManagement select').attr('disabled', true);
		$('#groupedOrderManagement [type=submit]').addClass("hidden");
		$('#groupedOrderManagement input[name=ID]').closest('div').removeClass("hidden");
	}else{
		$('#groupedOrderManagement input').attr('disabled', false);
		$('#groupedOrderManagement select').attr('disabled', false);
		$('#groupedOrderManagement [type=submit]').removeClass("hidden");
		$('#groupedOrderManagement input[name=ID]').attr('readonly', true);

		if(action=="add"){
			$('#widget_groupedOrderManagement-form input[name=action]').val("addGroupedOrder");
			$('#groupedOrderManagement .title').text("Ajouter une commande");
			$('#groupedOrderManagement input[name=ID]').closest('div').addClass("hidden");
		}else{
			retrieveGroupedOrder(ID);
			$('#groupedOrderManagement input[name=action]').val("updateGroupedOrder");
			$('#groupedOrderManagement .title').text("Modifier une commande");
			$('#groupedOrderManagement input[name=ID]').closest('div').removeClass("hidden");
		}
	}
})

$('#groupedOrderManagement select[name=company]').change(function(){
	$.ajax({
		url: 'api/companies',
		type: 'get',
		data: { action: "listUsers", 'companyID': $(this).val()},
		success: function(users){
			$('#groupedOrderManagement select[name=email]').find('option').remove();
			users.forEach(function(user){
				$('#groupedOrderManagement select[name=email]').append('<option value="'+user.EMAIL+'">'+user.PRENOM+' '+user.NOM+'</option>');
			})
			$('#groupedOrderManagement select[name=email]').val("");
		}
	})
})


function retrieveGroupedOrder(ID){
	$('#widget_groupedOrderManagement-form').trigger("reset");
	$('#widget_groupedOrderManagement-form .bikeNumberTable tbody').html("");
	$('#widget_groupedOrderManagement-form .accessoriesTable tbody').html("");
	$('#widget_groupedOrderManagement-form .boxesTable tbody').html("");
	$('#widget_groupedOrderManagement-form .bikesNumber').html(0);
	$('#widget_groupedOrderManagement-form .accessoriesNumber').html(0);
	$('#widget_groupedOrderManagement-form .boxesNumber').html(0);
	$('#groupedOrderManagement input[name=ID]').val(ID);
	$.ajax({
		url: 'api/orders',
		type: 'get',
		data: { action: "retrieveGroupedOrder", 'ID': ID},
		success: function(response){
			response.bikes.forEach(function(bike){
				if(bike.TYPE=='leasing'){
					var units='€/mois';
				}else{
					var units='€';
				}
				if(bike.ESTIMATED_DELIVERY_DATE == null){
					bike.ESTIMATED_DELIVERY_DATE = "N/A";
				}else{
					bike.ESTIMATED_DELIVERY_DATE=bike.ESTIMATED_DELIVERY_DATE.shortDate();
				}

				var row='<tr><td>'+bike.ID+'</td><td>'+bike.BRAND+'</td><td>'+bike.MODEL+'</td><td>'+bike.SIZE+'</td><td>'+bike.TYPE+'</td><td class="amount"><span>'+bike.LEASING_PRICE+'</span> '+units+'</td><td>'+bike.ESTIMATED_DELIVERY_DATE+'</td><td>'+bike.STATUS+'</td><td class="IDstockLinked">'+((bike.BIKE_ID == null) ? '<span class="text-red">N/A</span>' : bike.BIKE_ID)+'</td>';
				if(bike.TYPE=='selling'){
					if(bike.BIKE_ID != null && bike.STATUS=='confirmed'){
						var checkboxGenerateBill='<input type="checkbox" name="generateBillAccessory[]">';
					}else{
						var checkboxGenerateBill='<input type="checkbox" name="generateBillAccessory[]" disabled>';
					}
					row = row + '<td><button class="button small red button-3d rounded icon-right deleteBikeOrder" type="button" data-id="'+bike.ID+'">-</button></td><td class="generateBillGroupedOrderBike">'+checkboxGenerateBill+'</td>'
				}else{
					if(bike.BIKE_ID != null && bike.STATUS=='confirmed'){
						var confirmOrder='<a href="#" data-target="#confirmOrder" data-toggle="modal" class="button small green button-3d rounded icon-right confirmBikeOrder" data-id="'+bike.ID+'">+</a>';
					}else{
						var confirmOrder='';
					}
					row = row + '<td><button class="button small red button-3d rounded icon-right deleteBikeOrder" type="button" data-id="'+bike.ID+'">-</button></td><td>'+confirmOrder+'</td>'
				}

				$('#groupedOrderManagement .bikeNumberTable').find('tbody')
				.append(row);
			})

			$('#groupedOrderManagement .deleteBikeOrder').off();
			$('#groupedOrderManagement .deleteBikeOrder').click(function(){
				var ID = $(this).data('id');
				$.ajax({
					url: 'api/orders',
					type: 'post',
					data: { action: "deleteBikeOrder", 'ID': ID},
					success: function(response){
						$.notify({
						 message: "Commande supprimée"
						}, {
						 type: 'success'
						});
						retrieveGroupedOrder($('#groupedOrderManagement input[name=ID]').val());
						$("#groupedOrdersListingTable").dataTable().api().ajax.reload();
					}
				});
			});


			$('#groupedOrderManagement .confirmBikeOrder').off();
			$('#groupedOrderManagement .confirmBikeOrder').click(function(){
				var ID = $(this).data('id');
				$.ajax({
					url: 'api/orders',
					type: 'get',
					data: { action: "retrieve", 'ID': ID},
					success: function(response){
						if(response.order.type=="leasing"){
							var dateNow = new Date();
							var dateInThreeYears = new Date();
							dateInThreeYears.setFullYear(dateInThreeYears.getFullYear()+3);
							$('#confirmOrder input[name=contractStart]').val(get_date_string(dateNow));
							$('#confirmOrder input[name=contractEnd]').val(get_date_string(dateInThreeYears));
							$('#confirmOrder input[name=itemType]').val('bike');
							$('#confirmOrder input[name=itemID]').val(ID);
						}else{
							$.notify({
							 message: "Veuillez générer une facture de vente"
							}, {
							 type: 'danger'
							});
						}
					}
				});
			});


			response.accessories.forEach(function(accessory){
				if(accessory.TYPE=='leasing'){
					var units='€/mois';
				}else{
					var units='€';
				}
				if(accessory.ESTIMATED_DELIVERY_DATE==null){
					estimatedDeliveryDate='N/A';
				}else {
					estimatedDeliveryDate=accessory.ESTIMATED_DELIVERY_DATE.shortDate();
				}

				var row='<tr><td><a href="#" class="text-green" data-target="#accessoryOrderManagement" data-toggle="modal" data-action="update" data-id="'+accessory.ID+'">'+accessory.ID+'</a></td><td>'+traduction['accessoryCategories_'+accessory.CATEGORY]+'</td><td>'+accessory.BRAND+' '+accessory.MODEL+'</td><td>'+accessory.TYPE+'</td><td class="amount"><span>'+Math.round(accessory.PRICE_HTVA*100)/100+'</span> '+units+'</td><td>'+estimatedDeliveryDate+'</td><td>'+accessory.STATUS+'</td><td class="IDstockLinked">'+((accessory.ACCESSORY_ID == null) ? '<span class="text-red">N/A</span>' : accessory.ACCESSORY_ID)+'</td>';
				if(accessory.TYPE=='selling'){
					if(accessory.ACCESSORY_ID != null && accessory.STATUS=='confirmed'){
						var checkboxGenerateBill='<input type="checkbox" name="generateBillAccessory[]">';
					}else{
						var checkboxGenerateBill='<input type="checkbox" name="generateBillAccessory[]" disabled>';
					}
					row = row + '<td><button class="button small red button-3d rounded icon-right deleteAccessoryOrder" type="button" data-id="'+accessory.ID+'">-</button></td><td class="generateBillGroupedOrderAccessory">'+checkboxGenerateBill+'</td>'
				}else{
					if(accessory.ACCESSORY_ID != null && accessory.STATUS=='confirmed'){
						var confirmOrder='<a href="#" data-target="#confirmOrder" data-toggle="modal" class="button small green button-3d rounded icon-right confirmAccessoryOrder" data-id="'+accessory.ID+'">+</a>';
					}else{
						var confirmOrder='';
					}
					row = row + '<td><td><button class="button small red button-3d rounded icon-right deleteAccessoryOrder" type="button" data-id="'+accessory.ID+'">-</button> '+confirmOrder+'</td>'
				}
				$('#groupedOrderManagement .accessoriesTable').find('tbody').append(row);

			});

			$('.deleteAccessoryOrder').off();
			$('.deleteAccessoryOrder').click(function(){
				var ID = $(this).data('id');
				$.ajax({
					url: 'api/orders',
					type: 'post',
					data: { action: "deleteAccessoryOrder", 'ID': ID},
					success: function(response){
						$.notify({
						 message: "Commande supprimée"
						}, {
						 type: 'success'
						});
						retrieveGroupedOrder($('#groupedOrderManagement input[name=ID]').val());
						$("#groupedOrdersListingTable").dataTable().api().ajax.reload();
					}
				});
			});

			$('#groupedOrderManagement .confirmAccessoryOrder').off();
			$('#groupedOrderManagement .confirmAccessoryOrder').click(function(){
				var ID = $(this).data('id');
				$.ajax({
					url: 'api/accessories',
					type: 'get',
					data: { action: "getOrderDetailAcessory", 'ID': ID},
					success: function(response){
						if(response.TYPE=="leasing"){
							var dateNow = new Date();
							var dateInThreeYears = new Date();
							dateInThreeYears.setFullYear(dateInThreeYears.getFullYear()+3);
							$('#confirmOrder input[name=contractStart]').val(get_date_string(dateNow));
							$('#confirmOrder input[name=contractEnd]').val(get_date_string(dateInThreeYears));
							$('#confirmOrder input[name=itemType]').val('accessory');
							$('#confirmOrder input[name=itemID]').val(ID);
						}else{
							$.notify({
							 message: "Veuillez générer une facture de vente"
							}, {
							 type: 'danger'
							});
						}
					}
				});
			});


			response.boxes.forEach(function(box){
				if(box.ESTIMATED_DELIVERY_DATE==null){
					box.ESTIMATED_DELIVERY_DATE='N/A';
				}
				$('#groupedOrderManagement .boxesTable').find('tbody')
				.append('<tr><td>'+box.ID+'</td><td>'+box.MODEL+'</td><td>'+box.INSTALLATION_PRICE+' €</td><td>'+box.MONTHLY_PRICE+' €/mois</td><td>'+box.ESTIMATED_DELIVERY_DATE.shortDate()+'</td><td>'+box.STATUS+'</td><td><button class="button small red button-3d rounded icon-right deleteBoxOrder" type="button" data-id="'+box.ID+'">-</button></td>');
			})

			$('.deleteBoxOrder').off();
			$('.deleteBoxOrder').click(function(){
				var ID = $(this).data('id');
				$.ajax({
					url: 'api/boxes',
					type: 'post',
					data: { action: "deleteBoxOrder", 'ID': ID},
					success: function(response){
						$.notify({
						 message: "Commande supprimée"
						}, {
						 type: 'success'
						});
						retrieveGroupedOrder($('#groupedOrderManagement input[name=ID]').val());
						$("#groupedOrdersListingTable").dataTable().api().ajax.reload();
					}
				});
			});





			$('#groupedOrderManagement select[name=company]').val(response.COMPANY_ID);
			$.ajax({
				url: 'api/companies',
				type: 'get',
				data: { action: "listUsers", 'companyID': response.COMPANY_ID},
				success: function(users){
					$('#groupedOrderManagement select[name=email]').find('option').remove();
					users.forEach(function(user){
						$('#groupedOrderManagement select[name=email]').append('<option value="'+user.EMAIL+'">'+user.PRENOM+' '+user.NOM+'</option>');
					})
					$('#groupedOrderManagement select[name=email]').val(response.EMAIL);
				}
			})

			$('#groupedOrderManagement .bikesNumber').html(response.bikes.length);
			$('#groupedOrderManagement .accessoriesNumber').html(response.accessories.length);
		}
	})
}


$('#groupedOrderManagement .bikes .glyphicon-minus').unbind();
$('#groupedOrderManagement .bikes .glyphicon-minus').click(function(){
	$('#groupedOrderManagement .bikeNumberTable tbody .bikeRow:last-child').remove();
	bikesNumber = $('#groupedOrderManagement .bikeNumberTable tbody tr').length;
	$('#groupedOrderManagement').find('.bikesNumber').html(bikesNumber);

})

$('#groupedOrderManagement .accessories .glyphicon-minus').unbind();
$('#groupedOrderManagement .accessories .glyphicon-minus').click(function(){
	$('#groupedOrderManagement .accessoriesTable tbody .accessoryRow:last-child').remove();
	accessoriesNumber = $('#groupedOrderManagement .accessoriesTable tbody tr').length;
	$('#groupedOrderManagement').find('.accessoriesNumber').html(accessoriesNumber);
})

$('#groupedOrderManagement .boxes .glyphicon-minus').unbind();
$('#groupedOrderManagement .boxes .glyphicon-minus').click(function(){
	$('#groupedOrderManagement .boxesTable tbody .boxRow:last-child').remove();
	boxesNumber = $('#groupedOrderManagement .boxesTable tbody tr').length;
	$('#groupedOrderManagement').find('.boxesNumber').html(boxesNumber);
})



$('#groupedOrderManagement .bikes .glyphicon-plus').unbind();
$('#groupedOrderManagement .bikes .glyphicon-plus').click(function(){
	bikesNumber = $("#groupedOrderManagement").find('.bikesNumber').html()*1+1;
	$('#groupedOrderManagement').find('.bikesNumber').html(bikesNumber);
	$('#groupedOrderManagement .bikeNumberTable').find('tbody')
	.append(`<tr class="bikeRow form-group">
	<td class="bLabel">Vélo n°`+bikesNumber+`</td>
	<td class="brand">
		<select name="brand[]">
			<option value='ahooga'>Ahooga</option>\
			<option value'benno'>Benno</option>\
			<option value'bzen'>BZEN</option>\
			<option value'conway'>Conway</option>\
			<option value'Douze Cycle'>Douze Cycle</option>\
			<option value'hnf'>HNF Nicolai</option>\
			<option value'kayza'>Kayza</option>\
			<option value'Moustache Bikes'>Moustache Bikes</option>\
			<option value'Orbea'>Orbea</option>\
			<option value'other'>Other</option>\
			<option value'victoria'>Victoria</option>\
		</select>
	</td>
	<td class="model"><select name="catalogID[]" class="form-control required"></select></td>
	<td class="size"><select name="size[]" class="form-control required"></select></td>
	<td class="contractType"><select name="contractType[]" class="form-control required"><option value="leasing">Leasing</option><option value="selling">Vente</option></select></td>
	<td class="amount"><input type="amount" step="0.01" class="form-control required" name="bikeAmount[]"></td>
	<td class="estimatedDeliveryDate"><input type="date" class="form-control required" name='estimatedDeliveryDate[]'></td>
	<td class="status">
		<select name="status[]" class="form-control required">
			<option value="new">Nouvelle Commande</option>
			<option value="confirmed">Commande confirmée</option>
			<option value="done">Commande délivrée</option>
		</select>
	</td>
	</tr>`);
	$('#groupedOrderManagement .bikeNumberTable tbody tr:last-child select').val("");

	$('#groupedOrderManagement .bikeNumberTable .brand select').change(function(){
		$modelSelect=$(this).closest('tr').find('.model select');

		$.ajax({
			url: 'api/portfolioBikes',
			type: 'get',
			data: { action: "listModelsFromBrand", 'brand': $(this).val()},
			success: function(response){
				$modelSelect.find('option')
				.remove()
				.end();
				response.forEach(function(bike){
					$modelSelect.append('<option value="'+bike.ID+'" data-retailprice="'+bike.PRICE_HTVA+'">'+bike.MODEL+' - '+bike.FRAME_TYPE+' - '+bike.SEASON+'</option>');
				})
				$modelSelect.val('');
			}
		})
	})

	$('#groupedOrderManagement .bikeNumberTable .model select, #groupedOrderManagement .bikeNumberTable .contractType select').off();

	$('#groupedOrderManagement .bikeNumberTable .model select').change(function(){
		$size=$(this).closest('tr').find('.size select');

		$.ajax({
			url: 'api/portfolioBikes',
			type: 'get',
			data: { action: "listSizesFromModel", 'ID': $(this).val()},
			success: function(response){
				$size.find('option')
				.remove()
				.end();
				response.forEach(function(size){
					$size.append('<option value="'+size+'">'+size+'</option>');
				})
				$size.val('');
			}
		})
	})
	$('#groupedOrderManagement .bikeNumberTable .model select, #groupedOrderManagement .bikeNumberTable .contractType select').change(function(){
		$amount=$(this).closest('tr').find('.amount input');
		var retailPrice=$(this).closest('tr').find('.model select').children("option:selected").data('retailprice');
		var contractType=$(this).closest('tr').find('.contractType select').val();
		if(contractType=="selling"){
			$amount.val(retailPrice);
		}else{
			get_leasing_price(retailPrice, $('#groupedOrderManagement select[name=company]').val()).done(function(response){
				$amount.val(response.leasingPrice);
			})
		}
	})
})


$('#groupedOrderManagement .accessories .glyphicon-plus').unbind();
$('#groupedOrderManagement .accessories .glyphicon-plus').click(function(){
	accessoriesNumber = $("#groupedOrderManagement").find('.accessoriesNumber').html()*1+1;
	$('#groupedOrderManagement').find('.accessoriesNumber').html(accessoriesNumber);
	$('#groupedOrderManagement .accessoriesTable').find('tbody')
	.append(`<tr class="accessoryRow form-group">
	<td class="bLabel">Accessoire n°`+accessoriesNumber+`</td>
	<td class="category"><select>`+categoriesOptions+`</select></td>
	<td class="model"><select name="accessoryCatalogID[]" class="form-control required"></select></td>
	<td class="contractType"><select name="accessoryContractType[]" class="form-control required"><option value="leasing">Leasing</option><option value="selling">Vente</option></select></td>
	<td class="accessoryAmount"><input type="amount" step="0.01" class="form-control required" name='accessoryAmount[]'></td>
	<td class="accessoryEstimatedDeliveryDate"><input type="date" class="form-control required" name='accessoryEstimatedDeliveryDate[]'></td>
	<td class="status">
		<select name="accessoryStatus[]" class="form-control required">
			<option value="new">Nouvelle Commande</option>
			<option value="confirmed">Commande confirmée</option>
			<option value="done">Commande délivrée</option>
		</select>
	</td>
	</tr>`);
	$('#groupedOrderManagement .accessoriesTable tbody tr:last-child select').val("");

	$('#groupedOrderManagement .accessoriesTable .category select').change(function(){
		$modelSelect=$(this).closest('tr').find('.model select');

		$.ajax({
			url: 'api/accessories',
			type: 'get',
			data: { action: "getModelsCategory", 'category': $(this).val()},
			success: function(response){
				$modelSelect.find('option')
				.remove()
				.end();
				response.models.forEach(function(accessory){
					$modelSelect.append('<option value="'+accessory.ID+'" data-retailprice="'+accessory.PRICE_HTVA+'">'+accessory.BRAND+' - '+accessory.MODEL+'</option>');
				})
				$modelSelect.val('');
			}
		})
	})


	$('#groupedOrderManagement .accessoriesTable .model select, #groupedOrderManagement .accessoriesTable .contractType select').change(function(){
		$amount=$(this).closest('tr').find('.accessoryAmount input');
		var retailPrice=$(this).closest('tr').find('.model select').children("option:selected").data('retailprice');
		var contractType=$(this).closest('tr').find('.contractType select').val();
		if(contractType=="selling"){
			$amount.val(Math.round(retailPrice*100)/100);
		}else{
			$amount.val(Math.round((retailPrice*1.25/36)*100)/100);
		}
	})
});




$('#groupedOrderManagement .boxes .glyphicon-plus').unbind();
$('#groupedOrderManagement .boxes .glyphicon-plus').click(function(){
	boxesNumber = $("#groupedOrderManagement").find('.boxesNumber').html()*1+1;
	$('#groupedOrderManagement').find('.boxesNumber').html(boxesNumber);

	$('#groupedOrderManagement .boxesTable').find('tbody')
	.append(`<tr class="boxRow form-group">
	<td class="bLabel">Box n°`+boxesNumber+`</td>
	<td class="model"><select name='boxModel[]'><option value='5keys'>5 clés</option><option value='10keys'>10 clés</option><option value='20keys'>20 clés</option><option value='40keys'>40 clés</option></select></td>
	<td class="boxInstallationPrice"><input type='amount' step='0.01' name="boxInstallationPrice[]" class="form-control required"></td>
	<td class="boxMonthlyPrice"><input type='amount' step='0.01' name="boxMonthlyPrice[]" class="form-control required"></td>
	<td class="boxEstimatedDeliveryDate"><input type="date" class="form-control required" name='boxEstimatedDeliveryDate[]'></td>
	<td class="boxStatus">
		<select name="boxStatus[]" class="form-control required">
			<option value="new">Nouvelle Commande</option>
			<option value="confirmed">Commande confirmée</option>
			<option value="done">Commande délivrée</option>
		</select>
	</td>
	</tr>`);

	$row=$('#groupedOrderManagement .boxesTable tbody tr:last-child');
	$row.find('select').val('');


	$row.find('.model select').change(function(){
		$boxInstallationPrice=$(this).closest('tr').find('.boxInstallationPrice input');
		$boxMonthlyPrice=$(this).closest('tr').find('.boxMonthlyPrice input');

		$.ajax({
			url: 'api/boxes',
			type: 'get',
			data: { action: "getPrice", 'model': $(this).val()},
			success: function(response){
				$boxInstallationPrice.find('option')
				.remove()
				.end();
				$boxMonthlyPrice.find('option')
				.remove()
				.end();
				$boxInstallationPrice.val(response.installationPrice);
				$boxMonthlyPrice.val(response.monthlyPrice);
			}
		})
	})
})


$('.generateBillGroupedOrder').click(function(){
	var i=0;
	var total=0;
	var j=0;

	var data=[];

	$(".generateBillGroupedOrderAccessory")
		.find("input:checked")
		.closest('tr')
		.map(function(){
			i++;
			total=total+parseInt($(this).find('.amount span').html());
			data.push({ name: "accessoryID[]", value: $(this).find('.IDstockLinked').html() });
			data.push({ name: "accessoryFinalPrice[]", value: $(this).find('.amount span').html() });
		})

	$(".generateBillGroupedOrderBike")
		.find("input:checked")
		.closest('tr')
		.map(function(){
			j++;
			total=total+parseInt($(this).find('.amount span').html());
			data.push({ name: "bikeID[]", value: $(this).find('.IDstockLinked').html() });
			data.push({ name: "bikeFinalPrice[]", value: $(this).find('.amount span').html() });
		})

	data.push({ name: "bikesNumber", value: j });
	data.push({ name: "accessoriesNumber", value: i });
	data.push({ name: "companyID", value: $('#groupedOrderManagement select[name=company]').val() });
	data.push({ name: "billingGroup", value: "1" });
	data.push({ name: "beneficiaryCompany", value: "KAMEO" });
	data.push({ name: "type", value: "achat" });
	data.push({ name: "individualBillingName", value: $('#groupedOrderManagement select[name=email]').val() });
	data.push({ name: "widget-addBill-form-amountHTVA", value: total });
	data.push({ name: "widget-addBill-form-amountTVAC", value: total*1.21 });
	data.push({ name: "comingFrom", value: "groupedOrders" });

	$.ajax({
		url: 'apis/Kameo/add_bill.php',
		type: 'POST',
		data: data,
		success: function(response){
			retrieveGroupedOrder($('#groupedOrderManagement input[name=ID]').val());
			$("#groupedOrdersListingTable").dataTable().api().ajax.reload();
			$.notify({
			 message: "Facture générée et commandes mises à jour"
			}, {
			 type: 'success'
			});

		}
	});
})
