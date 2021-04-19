<div class="tab-pane" id="fleetmanager"> <!-- TAB4: FLEET MANAGET -->
	<div class="row">
        <?php

				if(get_user_permissions("fleetManager", $token)){
					echo '<h4>Votre flotte</h4><br><br>';
				}
        if(get_user_permissions("fleetManager", $token)){
            echo '
              <div class="col-md-4">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#BikesListing" class="clientBikesManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-bicycle"></i>
                    </a>
                  </div>
                  <div class="counter bold" id="counterBike" style="color:#3cb395"></div>
                  <p>'.L::widgetTitle_bikeManager.'</p>
                </div>
              </div>
              <div class="seperator seperator-small visible-xs"><br/><br/></div>';
        }
        if(get_user_permissions("fleetManager", $token)){
            echo '
              <div class="col-md-4">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#usersListing" class="usersManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-users"></i>
                    </a>
                  </div>
                  <div class="counter bold" id="counterUsers" style="color:#3cb395"></div>
									<p>'.L::widgetTitle_userManager.'</p>
                </div>
              </div>';

        }
        if(get_user_permissions("fleetManager", $token)){
					if($user_data['CAFETARIA']=='Y'){
            echo '
              <div class="col-md-4">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#ordersListingFleet" class="commandFleetManagerClick" href="#" >
                      <i style="opacity:0.15" class="fa fa-users"></i>
                    </a>
                  </div>
                  <div class="counter bold" id="counterOrdersFleet" style="color:#3cb395"></div>
                  <p>'.L::widgetTitle_ordersManager.'</p>
                </div>
              </div>
              <div class="seperator seperator-small visible-xs"><br/><br/></div>';
					}
        }
				if(get_user_permissions("fleetManager", $token)){
					if($user_data['LOCKING']=='Y'){
						echo '
						<div class="col-md-4 " id="boxView">
	            <div class="icon-box medium fancy">
	              <div class="icon bold" data-animation="pulse infinite">
	                <a data-toggle="modal" data-target="#boxesListing" href="#" class="boxViewClick">
	                  <i style="opacity:0.15" class="fa fa-cube"></i>
	                </a>
	              </div>
	              <div class="counter bold" id="counterBoxesFleet" style="color:#3cb395"></div>
	              <p>'.L::widgetTitle_boxesManager.'</p>
	            </div>
	          </div>';
						}
        }
				if(get_user_permissions("fleetManager", $token)){
					if($user_data['BOOKING']=='Y'){
            echo '
              <div class="col-md-4">
                <div class="icon-box medium fancy">
                  <div class="icon bold" data-animation="pulse infinite">
                    <a data-toggle="modal" data-target="#ReservationsListing" href="#">
                      <i style="opacity:0.15" class="fa fa-calendar-plus-o reservationlisting"></i>
                    </a>
                  </div>
                  <div class="counter bold" id="counterBookings" style="color:#3cb395"></div>
									<p>'.L::widgetTitle_bookingManager.'</p>
                </div>
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
          <div class="col-md-4">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#conditionListing" id="settings" href="#" >
                  <i style="opacity:0.15" class="fa fa-cog"></i>
                </a>
              </div>
                  <div class="counter bold" id="counterConditions" style="color:#3cb395"></div>
              <p>'.L::widgetTitle_settingsManager.'</p>
            </div>
          </div>
        </div>
				</div>
        <div class="separator"></div>';
			}
    }?>
		</div>

		<?php
		if(get_user_permissions(['bikesStock', "admin"], $token)){
			echo '<h4 class="administrationKameo">Administration Kameo</h4>
			<br/><br/>
			<div class="row">';
		}


    if(get_user_permissions("admin", $token)){
          echo '<div class="col-md-4 " id="clientManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#companyListing" href="#" class="clientManagerClick" >
                  <i style="opacity:0.15" class="fa fa-users"></i>
                </a>
              </div>
              <div class="counter bold" id="counterClients" style="color:#3cb395"></div>
              <p>Clients et prospects</p>
            </div>
          </div>
          <div class="col-md-4 " id="orderManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#ordersListing" href="#" class="ordersManagerClick" >
                  <i style="opacity:0.15" class="fa fa-users"></i>
                </a>
              </div>
              <div class="counter bold" id="counterOrdersAdmin" style="color:#3cb395"></div>
              <p>Commandes</p>
            </div>
          </div>
					<div class="col-md-4 " id="chatsManagement" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#chatsListing" href="#" class="chatsManagerClick">
									<i style="opacity:0.15" class="fa fa-comment"></i>
								</a>
							</div>
							<div class="counter bold" id="counterChat" style="color:#3cb395"></div>
							<p>Chat</p>
						</div>
					</div>
          <div class="col-md-4 " id="portfolioManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick">
                  <i style="opacity:0.15" class="fa fa-book"></i>
                </a>
              </div>
              <div class="counter bold" id="counterBikePortfolio" style="color:#3cb395"></div>
              <p>Catalogue vélos</p>
            </div>
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
							<div class="counter bold" id="counterBikeAdmin"></div>
							<p>Stock vélo</p>
						</div>
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
							<div class="counter bold" id="counterMaintenance" style="color:#3cb395"></div>
							<p>Vue sur les entretiens</p>
						</div>
					</div>
          <div class="col-md-4 " id="portfolioAccessoriesManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#portfolioAccessoriesManager" href="#" class="portfolioAccessoriesManagerClick">
                  <i style="opacity:0.15" class="fa fa-book"></i>
                </a>
              </div>
              <div class="counter bold" id="counterAccessoriesPortfolio" style="color:#3cb395"></div>
              <p>Catalogue accessoires</p>
            </div>
          </div>
					<div class="col-md-4 " id="stockAccessories" style="height:164px">
						<div class="icon-box medium fancy">
							<div class="icon bold" data-animation="pulse infinite">
								<a data-toggle="modal" data-target="#stockAccessoriesListing" href="#" class="stockAccessoriesClick">
									<i style="opacity:0.15" class="fa fa-briefcase"></i>
								</a>
							</div>
							<div class="counter bold" id="counterStockAccessoriesCounter"></div>
							<p>Stock accessoires</p>
						</div>
					</div>
          <div class="col-md-4 " id="orderAccessories" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#orderAccessoriesListing" href="#" class="orderAccessoriesClick">
                  <i style="opacity:0.15" class="fa fa-users"></i>
                </a>
              </div>
              <div class="counter bold" id="counterOrderAccessoriesCounter"></div>
              <p>Commande accessoires</p>
            </div>
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
              <p>Gérer les Bornes</p>
            </div>
          </div>
					<div class="row"></div>
          <div class="col-md-4 " id="tasksManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#tasksListing" href="#" class="tasksManagerClick">
                  <i style="opacity:0.15" class="fa fa-tasks"></i>
                </a>
              </div>
              <div class="counter bold" id="counterTasks" style="color:#3cb395"></div>
              <p>Gérer les Actions</p>
            </div>
          </div>';
				}
				if(get_user_permissions("cashflow", $token)){

          echo '<div class="col-md-4 " id="cashFlowManagement" style="height:164px">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#cashListing" href="#" id="offerManagerClick">
                  <i style="opacity:0.15" class="fa fa-money"></i>
                </a>
              </div>
              <div class="counter bold" id="cashFlowSpan" style="color:#3cb395"></div>
              <p>Vue sur le cash-flow</p>
            </div>
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
						<div class="counter bold" id="counterFeedbacks" style="color:#3cb395"></div>
						<p>Vue sur les feedbacks</p>
					</div>
				</div>';
			}if(get_user_permissions("dashboard", $token)){

				echo '<div class="col-md-4 " id="dashBoardManagement" style="height:164px">
					<div class="icon-box medium fancy">
						<div class="icon bold" data-animation="pulse infinite">
							<a data-toggle="modal" class="dashboardManagementClick" data-target="#dashboard" href="#" >
								<i style="opacity:0.15" class="fa fa-dashboard"></i>
							</a>
						</div>
						<div class="counter bold" id="errorCounter" style="color:#3cb395"></div>
						<p>Dashboard</p>
					</div>
				</div>';
    }

		if(get_user_permissions(["bikesStock", "admin"], $token)){
			echo '</div>';
		}

    if(get_user_permissions("bills", $token)){
        echo '<h4 class="billsTitle ">Factures</h4><br><br>
        <div class="row">
          <div class="col-md-4" style="height:164px" id="billsManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#billingListing" href="#" class="billsManagerClick">
                  <i style="opacity:0.15" class="fa fa-folder-open-o"></i>
                </a>
              </div>
              <div class="counter bold" id=\'counterBills\' style="color:#3cb395"></div>
              <p>Aperçu des factures</p>
            </div>
          </div>
				</div>';
    }

    //changer tt ici en dessous et creer modal dans fleet pour interface de stock
    if(get_user_permissions("stock", $token)){
        echo '<h4 class="StockTitle ">Stock</h4><br><br>
        <div class="row">
          <div class="col-md-4" style="height:164px" id="stockManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#scanBarcodeModal" href="#" class="stockManagerClick">
                  <i style="opacity:0.15" class="fa fa-barcode"></i>
                </a>
              </div>
              <p>Scanner Stock</p>
            </div>
          </div>
          <div class="col-md-4" style="height:164px" id="preOrderCSVManagement">
            <div class="icon-box medium fancy">
              <div class="icon bold" data-animation="pulse infinite">
                <a data-toggle="modal" data-target="#preOrderCSVModal" href="#" class="preOrderCSVManagerClick">
                  <i style="opacity:0.15" class="fa fa-file-excel-o"></i>
                </a>
              </div>
              <p>Chargement des Accessoire sous CSV</p>
            </div>
          </div>
        </div>';
    }
    ?>
	</div>
