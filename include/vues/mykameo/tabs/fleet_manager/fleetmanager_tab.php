<div class="tab-pane" id="fleetmanager"> <!-- TAB4: FLEET MANAGET -->
	<h4 class="fr">Votre flotte</h4>
	<br/><br/>
	<div class="row">
	  <div class="col-md-4">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#BikesListing" class="clientBikesManagerClick" href="#" >
			  <i class="fa fa-bicycle"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterBike" style="color:#3cb395"></div>
		  <p>Nombre de vélos</p>
		</div>
	  </div>
	  <div class="seperator seperator-small visible-xs"><br/><br/></div>
	  <div class="col-md-4">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#usersListing" class="usersManagerClick" href="#" >
			  <i class="fa fa-users"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterUsers" style="color:#3cb395"></div>
		  <p>Nombre d'utilisateurs</p>
		</div>
	  </div>
	  <div class="seperator seperator-small visible-xs"><br/><br/></div>
	  <div class="col-md-4">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#ReservationsListing" href="#">
			  <i class="fa fa-calendar-plus-o reservationlisting"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterBookings" style="color:#3cb395"></div>
		  <p>Nombre de réservations sur le mois passé</p>
		</div>
	  </div>
	</div>
	<div class="separator"></div>
	<h4 class="fr">Réglages</h4>
	<h4 class="en">Settings</h4>
	<h4 class="en">Settings</h4>
	<br/><br/>
	<div class="row">
	  <div class="col-md-4">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#conditionListing" id="settings" href="#" >
			  <i class="fa fa-cog"></i>
			</a>
		  </div>
		  <div class="counter bold" style="color:#3cb395"></div>
		  <p>Modifier les réglages</p>
		</div>
	  </div>
	</div>
	<div class="separator"></div>
	<h4 class="fr hidden administrationKameo">Administration Kameo</h4>
	<h4 class="en hidden administrationKameo">Kameo administration</h4>
	<h4 class="en hidden administrationKameo">Kameo administration</h4>
	<br/><br/>
	<div class="row">
	  <div class="col-md-4 hidden" id="clientManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#companyListing" href="#" class="clientManagerClick" >
			  <i class="fa fa-users"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterClients" style="color:#3cb395"></div>
		  <p>Gérer les clients</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="orderManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#ordersListing" href="#" class="ordersManagerClick" >
			  <i class="fa fa-users"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterOrders" style="color:#3cb395"></div>
		  <p>Gérer les commandes</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="portfolioManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick">
			  <i class="fa fa-book"></i>
			</a>
		  </div>
		  <div class="counter bold" id='counterBikePortfolio' style="color:#3cb395"></div>
		  <p>Gérer le catalogue</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="bikesManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#BikesListingAdmin" href="#" class="bikeManagerClick">
			  <i class="fa fa-bicycle"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterBikeAdmin" style="color:#3cb395"></div>
		  <p>Gérer les vélos</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="boxesManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#boxesListing" href="#" class="boxManagerClick">
			  <i class="fa fa-cube"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterBoxes" style="color:#3cb395"></div>
		  <p>Gérer les Bornes</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="tasksManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#tasksListing" href="#" class="tasksManagerClick">
			  <i class="fa fa-tasks"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterTasks" style="color:#3cb395"></div>
		  <p>Gérer les Actions</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="cashFlowManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#cashListing" href="#" id="offerManagerClick">
			  <i class="fa fa-money"></i>
			</a>
		  </div>
		  <div class="counter bold" id="cashFlowSpan" style="color:#3cb395"></div>
		  <p>Vue sur le cash-flow</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="feedbacksManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#feedbacksListing" href="#" class="feedbackManagerClick">
			  <i class="fa fa-comments"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterFeedbacks" style="color:#3cb395"></div>
		  <p>Vue sur les feedbacks</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="maintenanceManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#maintenanceListing" href="#" class="maintenanceManagementClick">
			  <i class="fa fa-wrench"></i>
			</a>
		  </div>
		  <div class="counter bold" id="counterMaintenance" style="color:#3cb395"></div>
		  <div class="counter bold" id="counterMaintenance2" style="color:#3cb395"></div>
		  <p>Vue sur les entretiens</p>
		</div>
	  </div>
	  <div class="col-md-4 hidden" id="dashBoardManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" class="dashboardManagementClick" data-target="#dashboard" href="#" >
			  <i class="fa fa-dashboard"></i>
			</a>
		  </div>
		  <div class="counter bold" id='errorCounter' style="color:#3cb395"></div>
		  <p>Dashboard</p>
		</div>
	  </div>
	</div>
	<div class="separator kameo"></div>
	<h4 class="fr billsTitle hidden">Factures</h4>
	<h4 class="en billsTitle hidden">Billing</h4>
	<h4 class="nl billsTitle hidden">Billing</h4><br/><br />
	<div class="row">
	  <div class="col-md-4 hidden" id="billsManagement">
		<div class="icon-box medium fancy">
		  <div class="icon bold" data-animation="pulse infinite">
			<a data-toggle="modal" data-target="#billingListing" href="#" class="billsManagerClick">
			  <i class="fa fa-folder-open-o"></i>
			</a>
		  </div>
		  <div class="counter bold" id='counterBills' style="color:#3cb395"></div>
		  <p>Aperçu des factures</p>
		</div>
	  </div>
	</div>
	<div class="col-md-12" id="progress-bar-bookings"></div>
</div>