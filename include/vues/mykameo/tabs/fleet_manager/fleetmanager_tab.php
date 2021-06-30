<div class="tab-pane" id="fleetmanager"> <!-- TAB4: FLEET MANAGET -->
	<div class="row">
        <?php

				if(get_user_permissions("fleetManager", $token)){
					echo '<h4>Votre flotte</h4><br><br>';
				}
        if(get_user_permissions("fleetManager", $token)){
            echo '
              <div class="col-md-4" style="height:164px">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#BikesListing" class="clientBikesManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-bicycle"></i>
                    </a>
                  </div>
									<div id="counterBike" style="color:#3cb395; font-size: 4em"></div>
                </div>
								<strong style="text-align: left">'.L::widgetTitle_bikeManager.'</strong>
              </div>
              <div class="seperator seperator-small visible-xs"><br/><br/></div>';
        }
        if(get_user_permissions("fleetManager", $token)){
            echo '
              <div class="col-md-4" style="height:164px">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#usersListing" class="usersManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-users"></i>
                    </a>
                  </div>
									<div id="counterUsers" style="color:#3cb395; font-size: 4em"></div>
                </div>
								<strong style="text-align: left">'.L::widgetTitle_userManager.'</strong>
              </div>';

        }
        if(get_user_permissions("fleetManager", $token)){
					if($user_data['CAFETARIA']=='Y'){
            echo '
              <div class="col-md-4" style="height:164px">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#ordersListingFleet" class="commandFleetManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-users"></i>
                    </a>
                  </div>
									<div id="counterOrdersFleet" style="color:#3cb395; font-size: 4em"></div>
                </div>
								<strong style="text-align: left">'.L::widgetTitle_ordersManager.'</strong>
              </div>
              <div class="seperator seperator-small visible-xs"><br/><br/></div>';
					}
        }
				if(get_user_permissions("fleetManager", $token)){
					if($user_data['LOCKING']=='Y'){
						echo '
						<div class="col-md-4 " id="boxView" style="height:164px">
	            <div class="icon-box medium fancy">
	              <div class="icon bold" data-animation="pulse infinite">
	                <a data-toggle="modal" data-target="#boxesListing" href="#" class="boxViewClick">
	                  <i style="opacity:0.15" class="fa fa-cube"></i>
	                </a>
	              </div>
								<div id="counterBoxesFleet" style="color:#3cb395; font-size: 4em"></div>
	            </div>
							<strong style="text-align: left">'.L::widgetTitle_boxesManager.'</strong>
	          </div>';
						}
        }
				if(get_user_permissions("fleetManager", $token)){
					if($user_data['BOOKING']=='Y'){
            echo '
              <div class="col-md-4" style="height:164px">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#ReservationsListing" href="#">
                      <i style="opacity:0.15" class="fa fa-calendar-plus-o reservationlisting"></i>
                    </a>
                  </div>
									<div id="counterBookings" style="color:#3cb395; font-size: 4em"></div>
                </div>
								<strong style="text-align: left">'.L::widgetTitle_bookingManager.'</strong>
              </div>';
						}
        }
				?>
	</div>
	<div class="row">
    <?php
    if(get_user_permissions("fleetManager", $token)){
			if($user_data['BOOKING']=='Y'){
				echo '<div class="separator"></div>';
        echo '
				<div class="col-md-12">
        <h4>Réglages</h4>
        <br/><br/>
        <div class="row">
          <div class="col-md-4" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#conditionListing" id="settings" href="#" >
                  <i style="opacity:0.15" class="fa fa-cog"></i>
                </a>
              </div>
								<div id="counterConditions" style="color:#3cb395; font-size: 4em"></div>
            </div>
						<strong style="text-align: left">'.L::widgetTitle_settingsManager.'</strong>
          </div>
        </div>
				</div>
        <div class="separator"></div>';
			}
    }?>
		</div>

				<?php
				if(get_user_permissions(['bikesStock', "admin"], $token)){
					echo '<h4 class="administrationKameo text-green">Administration Kameo</h4>
					<br/><br/>
					<div class="row">';
				}


    		if(get_user_permissions("admin", $token)){
          echo '
					<div class="col-md-4 " id="orderAccessories" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#groupedOrdersListing" href="#">
									<i style="opacity:0.15" class="fa fa-users"></i>
								</a>
							</div>
							<div id="counterGroupedCommands" style="font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Commandes groupées</strong>
					</div>
          <div class="col-md-4 " id="orderManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#ordersListing" href="#" class="ordersManagerClick" >
                  <i style="opacity:0.15" class="fa fa-users"></i>
                </a>
              </div>
							<div id="counterOrdersAdmin" style="font-size: 4em"></div>
            </div>
						<strong style="text-align: left">Commandes Vélos</strong>
          </div>
					<div class="col-md-4 " id="orderAccessories" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#orderAccessoriesListing" href="#" class="orderAccessoriesClick">
									<i style="opacity:0.15" class="fa fa-users"></i>
								</a>
							</div>
							<div id="counterOrderAccessoriesCounter" style="font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Commande accessoires</strong>
					</div>
					<div class="col-md-4 " id="chatsManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#chatsListing" href="#" class="chatsManagerClick">
									<i style="opacity:0.15" class="fa fa-comment"></i>
								</a>
							</div>
							<div id="counterChat" style="font-size: 4em"></div>
						</div>
						<strong style="text-align: left; margin-left:25px">Chat</strong>
					</div>
          <div class="col-md-4 " id="portfolioManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick">
                  <i style="opacity:0.15" class="fa fa-book"></i>
                </a>
              </div>
							<div id="counterBikePortfolio" style="color:#3cb395; font-size: 4em"></div>
            </div>
						<strong style="text-align: left">Catalogue vélos</strong>
          </div>';
				}
				if(get_user_permissions(['bikesStock', "admin"], $token)){
					echo '<div class="col-md-4 " id="bikesManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#BikesListingAdmin" href="#" class="bikeManagerClick">
									<i style="opacity:0.15" class="fa fa-bicycle"></i>
								</a>
							</div>
							<div id="counterBikeAdmin" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Stock vélo</strong>
					</div>';
				}
				if(get_user_permissions("admin", $token)){
					echo '<div class="col-md-4 " id="maintenanceManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#maintenanceListing" href="#" class="maintenanceManagementClick">
									<i style="opacity:0.15" class="fa fa-wrench"></i>
								</a>
							</div>
							<div id="counterMaintenance" style="font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Vue sur les entretiens</strong>
					</div>
					<div class="col-md-4" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#planningsListing" href="#">
									<i style="opacity:0.15" class="fa fa-map"></i>
								</a>
							</div>
							<div id="counterPlannings" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Vue sur les plannings</strong>
					</div>
          <div class="col-md-4 " id="portfolioAccessoriesManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#portfolioAccessoriesManager" href="#" class="portfolioAccessoriesManagerClick">
                  <i style="opacity:0.15" class="fa fa-book"></i>
                </a>
              </div>
							<div id="counterAccessoriesPortfolio" style="font-size: 4em"></div>
            </div>
						<strong style="text-align: left">Catalogue accessoires</strong>
          </div>
					<div class="col-md-4 " id="stockAccessories" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#stockAccessoriesListing" href="#" class="stockAccessoriesClick">
									<i style="opacity:0.15" class="fa fa-briefcase"></i>
								</a>
							</div>
							<div id="counterStockAccessoriesCounter" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Stock accessoires</strong>
					</div>
					<div class="row"></div>
          <div class="col-md-4 " id="boxesManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#boxesListingAdmin" href="#" class="boxManagerClick">
                  <i style="opacity:0.15" class="fa fa-cube"></i>
                </a>
              </div>
              <div class="counter bold" id="counterBoxes" style="color:#3cb395"></div>
            </div>
						<strong style="text-align: left">Gérer les Bornes</strong>
          </div>
					<div class="row"></div>
					<div class="col-md-4 " id="clientManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#companyListing" href="#" class="clientManagerClick" >
									<i style="opacity:0.15" class="fa fa-users"></i>
								</a>
							</div>
							<div id="counterClients" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Clients et prospects</strong>
					</div>';
				}
				if(get_user_permissions("cashflow", $token)){

          echo '
					<div class="col-md-4 " id="cashFlowManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#cashListing" href="#" id="offerManagerClick">
									<i style="opacity:0.15" class="fa fa-money"></i>
								</a>
							</div>
							<div id="cashFlowSpan" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Vue sur le cash-flow</strong>
          </div>';
				}
				if(get_user_permissions(['bikesStock', "admin"], $token)){

					echo '
					<div class="col-md-4" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#statisticsListing" href="#">
									<i style="opacity:0.15" class="fa fa-arrow-up"></i>
								</a>
							</div>
							<div id="statisticsCounter" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Statistiques</strong>
					</div>';

				}
				if(get_user_permissions("admin", $token)){

					echo '<div class="col-md-4 " id="feedbacksManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#feedbacksListing" href="#" class="feedbackManagerClick">
									<i style="opacity:0.15" class="fa fa-comments"></i>
								</a>
							</div>
							<div id="counterFeedbacks" style="color:#3cb395; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Vue sur les feedbacks</strong>
					</div>
					<div class="col-md-4 " id="tasksManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#tasksListing" href="#" class="tasksManagerClick">
									<i style="opacity:0.15" class="fa fa-tasks"></i>
								</a>
							</div>
							<div id="counterTasks" style="color:#d80000; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Gérer les Actions</strong>
					</div>';
				}if(get_user_permissions("dashboard", $token)){

					echo '<div class="col-md-4 " id="dashBoardManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" class="dashboardManagementClick" data-target="#dashboard" href="#" >
									<i style="opacity:0.15" class="fa fa-dashboard"></i>
								</a>
							</div>
							<div id="errorCounter" style="color:#d80000; font-size: 4em"></div>
						</div>
						<strong style="text-align: left">Dashboard</strong>
					</div>';
	    	}

		if(get_user_permissions(["bikesStock", "admin"], $token)){
			echo '</div>';
		}

    if(get_user_permissions("bills", $token)){
        echo '<h4 class="billsTitle text-green">Factures</h4><br><br>
        <div class="row">
          <div class="col-md-4" style="height:164px" id="billsManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#billingListing" href="#" class="billsManagerClick">
                  <i style="opacity:0.15" class="fa fa-folder-open-o"></i>
                </a>
              </div>
							<div id="counterBills" style="font-size: 4em"></div>
            </div>
						<strong style="text-align: left">'.L::widgetTitle_billsManager.'</strong>
          </div>
				</div>';
    }

    //changer tt ici en dessous et creer modal dans fleet pour interface de stock
    if(get_user_permissions("stock", $token)){
        echo '<h4 class="StockTitle text-green">Stock</h4><br><br>
        <div class="row">
          <div class="col-md-4" style="height:164px" id="stockManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#scanBarcodeModal" href="#" class="stockManagerClick">
                  <i style="opacity:0.15" class="fa fa-barcode"></i>
                </a>
              </div>
            </div>
						<strong style="text-align: left">Scanner Stock</strong>
          </div>
          <div class="col-md-4" style="height:164px" id="preOrderCSVManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#preOrderCSVModal" href="#" class="preOrderCSVManagerClick">
                  <i style="opacity:0.15" class="fa fa-file-excel-o"></i>
                </a>
              </div>
            </div>
						<strong style="text-align: left">Chargement des Accessoire sous CSV</strong>
          </div>
        </div>';
    }
    ?>
	</div>

	<script type='text/javascript'>

	$.ajax({
		url: "apis/Kameo/initialize_counters.php",
		type: "get",
		success: function (response) {
			if(typeof response.accessoriesNumber != 'undefined'){
				$("#counterStockAccessoriesCounter").html('<span style="margin-left:20px; color:#3cb395">'+response.accessoriesNumber+'</span>');
			}
			if(typeof response.bikeNumber != 'undefined'){
				$("#counterBikeAdmin").html('<span style="color: #3cb395; margin-left:20px">'+response.bikeNumber+'</span><span style="color: #d80000; margin-left:0px">/'+response.bikeOrdersLate+'</span>');
			}
			if(typeof response.boxesNumberTotal != 'undefined'){
				$("#counterBoxes").html('<span style="margin-left:20px">'+response.boxesNumberTotal+'</span>');
			}
			if(typeof response.cashFlow != 'undefined'){
				if (response.sumContractsCurrent > 0) {
					$("#cashFlowSpan").html('<span style="color: #3cb395; margin-left:20px">'+Math.round(response.cashFlow.sumContractsCurrent)+'€</span>');
				} else {
					$("#cashFlowSpan").html('<span style="color: #d80000; margin-left:20px">'+Math.round(response.cashFlow.sumContractsCurrent)+'€</span>');
				}
			}
			if(typeof response.messagesNumberUnread != 'undefined'){
				$("#counterChat").html('<span style="margin-left:20px; color: #3cb395">'+response.messagesNumberUnread+'</span>');
			}
			if(typeof response.companiesNumberClientOrProspect != 'undefined'){
				$("#counterClients").html('<span style="margin-left:20px">'+response.companiesNumberClientOrProspect+'</span>');
			}
			if(typeof response.feedbacksNumber != 'undefined'){
				$("#counterFeedbacks").html('<span style="margin-left:20px">'+response.feedbacksNumber+'</span>');
			}
			if(typeof response.groupedOrdersNumber != 'undefined'){
				$("#counterGroupedCommands").html('<span style="margin-left:20px; color:#3cb395">'+response.groupedOrdersNumber+'</span>');
			}
			if(typeof response.maintenances != 'undefined'){
				$("#counterMaintenance").html('<span style="color: #d80000; margin-left:20px">'+response.maintenances.maintenancesNumberAuto+'</span><span style="color: #3cb395; margin-left:0px">/'+response.maintenances.maitenancesConfirmed+'</span>');
			}
			if(typeof response.ordersAccessoryNumber != 'undefined'){
				$("#counterOrderAccessoriesCounter").html('<span style="margin-left:20px; color:#3cb395">'+response.ordersAccessoryNumber+'</span>');
			}
			if(typeof response.bikesOrdersNumber != 'undefined'){
				$("#counterOrdersAdmin").html('<span style="margin-left:20px; color: #3cb395">'+response.bikesOrdersNumber+'</span>');
			}
			if(typeof response.stillToDo != 'undefined'){
				$("#counterPlannings").html('<span style="color: orange; margin-left:20px">'+response.stillToDo+'</span>');
			}
			if(typeof response.bikeNumberPortfolio != 'undefined'){
				$("#counterBikePortfolio").html('<span style="color: #3cb395; margin-left:20px">'+response.bikeNumberPortfolio+'</span>');
			}
			if(typeof response.accessoriesNumberPortfolio != 'undefined'){
				$("#counterAccessoriesPortfolio").html('<span style="color: #3cb395; margin-left:20px">'+response.accessoriesNumberPortfolio+'</span>');
			}
			if(typeof response.actionNumberNotDone != 'undefined'){
				if(response.actionNumberNotDone == 0){
					$("#counterTasks").html('<span style="color: #3cb395; margin-left:20px">0</span>');
				}else{
					$("#counterTasks").html('<span style="color: #d80000; margin-left:20px">'+response.actionNumberNotDone+'</span>');
				}
			}
			if(typeof response.statistics != 'undefined'){
				if (response.statistics.progressCA > 0) {
					$("#statisticsCounter").html('<span style="color: #3cb395; margin-left:20px">'+Math.round(response.statistics.progressCA*100)+'%</span>');
				}else {
					$("#statisticsCounter").html('<span style="color: #d80000; margin-left:20px">'+Math.round(response.statistics.progressCA*100)+'%</span>');
				}
			}

			if(typeof response.billsNumber != 'undefined'){
				if (response.billsNumber == 0) {
					$("#counterBills").html('<span style="color: #3cb395; margin-left:20px">0</span>');
				}else {
					$("#counterBills").html('<span style="color: #d80000; margin-left:20px">'+response.billsNumber+'</span>');
				}
			}


			if(typeof response.bikeNumberClient != 'undefined'){
				$("#counterBike").html('<span style="margin-left:20px">'+response.bikeNumberClient+'</span>');
			}

			if(typeof response.fleetBoxesNumber != 'undefined'){
				$("#counterBoxesFleet").html('<span style="margin-left:20px">'+response.fleetBoxesNumber+'</span>');
			}

			if(typeof response.fleetOrdersNumber != 'undefined'){
				$("#counterOrdersFleet").html('<span style="margin-left:20px">'+response.fleetOrdersNumber+'</span>');
			}

			if(typeof response.bookingNumber != 'undefined'){
				$("#counterBookings").html('<span style="margin-left:20px">'+response.bookingNumber+'</span>');
			}

			if(typeof response.usersNumber != 'undefined'){
				$("#counterUsers").html('<span style="margin-left:20px">'+response.usersNumber+'</span>');
			}

			if(typeof response.conditionsNumber != 'undefined'){
				$("#counterConditions").html('<span style="margin-left:20px">'+response.conditionsNumber+'</span>');
			}
		}
	})

	</script>
