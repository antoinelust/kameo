$( ".fleetmanager" ).click(function() {
	$.ajax({
		url: 'apis/Kameo/initialize_counters.php',
		type: 'post',
		data: { "email": email, "type": "groupedOrders"},
		success: function(response){
			if(response.response == 'error') {
				console.log(response.message);
			}
			if(response.response == 'success'){
				document.getElementById('counterGroupedCommands').innerHTML = "<span class=\"text-green\" data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersNumber+"</span>";
			}
		}
	})
})


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
					$(nTd).html('<a href="#" class="text-green" data-target="#groupedOrderManagement" data-toggle="modal" data-action="retrieve" data-id="'+sData+'">'+sData+'</a>');
				},
			},
			{ title: "Nom de la société", data: "COMPANY_NAME"},
			{ title: "Assigné à", data: "EMAIL"},
			{ title: "Nombre de vélos", data: "numberBikes"},
			{ title: "Encore à délivrer", data: "numberBikesNotDelivered"},
			{ title: "Nombre d'accessoires", data: "numberAccessories"},
			{ title: "Encore à délivrer", data: "numberAccessoriesNotDelivered"},
			{
				title: "Progrès",
				data: "ID",
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
					$(nTd).html(Math.round(100-(oData.numberBikesNotDelivered+oData.numberAccessoriesNotDelivered)/(oData.numberBikes+oData.numberAccessories)*100)+' %');
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
			[7, "desc"]
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
	$('#widget_groupedOrderManagement-form .bikesNumber').html(0);
	$('#widget_groupedOrderManagement-form .accessoriesNumber').html(0);
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
				$('#groupedOrderManagement .bikeNumberTable').find('tbody')
				.append('<tr><td>'+bike.ID+'</td><td>'+bike.BRAND+'</td><td>'+bike.MODEL+'</td><td>'+bike.SIZE+'</td><td>'+bike.TYPE+'</td><td>'+bike.LEASING_PRICE+' '+units+'</td><td>'+bike.ESTIMATED_DELIVERY_DATE+'</td><td>'+bike.STATUS+'</td><td><button class="button small red button-3d rounded icon-right deleteBikeOrder" type="button" data-id="'+bike.ID+'">-</button></td>');
			})

			$('.deleteBikeOrder').off();
			$('.deleteBikeOrder').click(function(){
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


			response.accessories.forEach(function(accessory){
				if(accessory.TYPE=='leasing'){
					var units='€/mois';
				}else{
					var units='€';
				}
				if(accessory.ESTIMATED_DELIVERY_DATE==null){
					accessory.ESTIMATED_DELIVERY_DATE='N/A';
				}
				$('#groupedOrderManagement .accessoriesTable').find('tbody')
				.append('<tr><td>'+accessory.ID+'</td><td>'+traduction['accessoryCategories_'+accessory.CATEGORY]+'</td><td>'+accessory.BRAND+' '+accessory.MODEL+'</td><td>'+accessory.TYPE+'</td><td>'+Math.round(accessory.PRICE_HTVA*100)/100+' '+units+'</td><td>'+accessory.ESTIMATED_DELIVERY_DATE.shortDate()+'</td><td>'+accessory.STATUS+'</td><td><button class="button small red button-3d rounded icon-right deleteAccessoryOrder" type="button" data-id="'+accessory.ID+'">-</button></td>');
			})

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
	bikesNumber = $('#groupedOrderManagement .bikeNumberTable tbody tr').length
	$('#groupedOrderManagement').find('.bikesNumber').html(bikesNumber);

})

$('#groupedOrderManagement .accessories .glyphicon-minus').unbind();
$('#groupedOrderManagement .accessories .glyphicon-minus').click(function(){
	$('#groupedOrderManagement .accessoriesTable tbody .accessoryRow:last-child').remove();
	accessoriesNumber = $('#groupedOrderManagement .bikeNumberTable tbody tr').length
	$('#groupedOrderManagement').find('.accessoriesNumber').html(accessoriesNumber);
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
	<td class="contractType"><select name="contractType[]" class="form-control required"><option value="leasing">Leasing</option><option value="achat">Vente</option></select></td>
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
		if(contractType=="achat"){
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
})
