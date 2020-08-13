<!DOCTYPE html>
<html lang="fr">
<?php
	include '../../include/head.php';
?>
<style>
	.collapse-title {
	  position: relative;
	  flex-grow: 1;
	  display: inline-block;
	  margin: auto; /* Important */ 
	}
	  
	.collapse-title::after {
		content: "\f107";
		color: #333;
		right: 0;
		position: absolute;
		font-family: "FontAwesome"
	}

	.collapse-title[aria-expanded="true"]::after {
		content: "\f106";
	}
</style>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include '../../include/topbar.php'; ?>
		<?php include '../../include/header.php'; ?>
		<section class="p-b-150">
			<div class="container" style="min-width: 80%;">
				<div class="row" style="display: flex; flex-wrap: wrap">
					<nav id="sidebar" class="col-md-3 nav nav-tabs" style="background-color: #F0F0F0; border-radius: 10px; padding: 15px;">
						<div class="sidebar-header text-center">
							<h3>Documentation</h3>
						</div>
						<ul class="list-unstyled components" role="menu">
							<p class="text-center">KameoBikes API V1</p>
							<li>
								<a href="#generaMenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">General</a>
								<ul class="collapse list-unstyled in" id="generaMenu">
									<li class="active">
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#description_tab" data-toggle="tab">Description</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#authentication_tab" data-toggle="tab">Authentication</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#permissions_tab" data-toggle="tab">Permissions</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="#dataMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Data</a>
								<ul class="collapse list-unstyled" id="dataMenu">
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#parameters_tab" data-toggle="tab">Parameters</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#responses_tab" data-toggle="tab">Responses</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#errors_tab" data-toggle="tab">Errors</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="#endpointSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Endpoints</a>
								<ul class="collapse list-unstyled" id="endpointSubmenu">
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#chats_tab" data-toggle="tab">Chats</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#companies_tab" data-toggle="tab">Companies</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#notifications_tab" data-toggle="tab">Notifications</a>
									</li>
									<li>
										<a class="nav-item nav-link" style="color: #888; margin-left: 15px;" href="#orders_tab" data-toggle="tab">Orders</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
					<div class="col-md-9">
						<div class="tab-content" style="padding-left: 20px;">
						  <div class="tab-pane fade active in" id="description_tab">
							<h3 class="text-center">Description</h3>
							<br>
							<p>Welcome to the KameoBikes JSON REST API V1 documentation. You'll find every needed informations to use our API via the menu on the left and you can view the current status of your developer account in the <b>authentication</b> section.</p>
							<div class="alert alert-danger text-center" role="alert" style="border-radius: 10px;">
							  <b>Our API is currently in Beta, therefore, every endpoint as well as this documentation is likely to be modified and nothing should currently be considered stable.</b>
							</div>
							<p>Every endpoint of our API are on the <i>https://kameobikes.com/api</i> domain and each of them is corresponding to a type of ressource, on which you can perform different actions, according to your account/token permissions.</p>
							<p>If in doubt, everything should always be considered as case-sensitive everywhere.</p>
							<p>You are currently <b>not allowed</b> to use our API in <b>any distributed product</b> whatsoever without our prior written consent, and therefore, it is strongly discouraged to publish any application containing one of our tokens.</p>
							<p>All the requests must use HTTPS and <i>Get/Post/Put/Delete</i> methods.</p>
						  </div>
						  <div class="tab-pane fade" id="authentication_tab">
							<h3 class="text-center">Authentication</h3>
							<br>
							<p>Our APIs uses a token but are currently not Oauth compatible. You can find your developer token and informations at the bottom of this page.</p>
							<p>Developer tokens can be re-generated if needed but should be considered <b>as personal as passwords</b>.</p>
							<p>To authenticate yourself, a token should be included in every requests made to our APIs, using the <i>Authorization</i> request header with the value <i>Bearer &lt;TOKEN&gt;</i>, where <i>&ltTOKEN&gt</i> is an access token. If you're not successfully authenticated, every API will return an HTTP error code as well as a JSON error description in the body. Details about errors can be found in the <i>Responses >> Errors</i> section of this documentation.</p>
							<div style="background-color: #F0F0F0; padding: 20px; margin: 20px; max-width: 50%;">
								<p>Your developer informations :</p>
								<p class="text-center" style="padding: 5px; background-color: rgba(255,0,0,0.25); border-radius: 10px;">The developer functionalities are currently not activated on your account. You can contact us at support@kameobikes.com for more informations.</p>
								<label for="dev-token">Your Dev Token&nbsp</label>
								<input name="dev-token" value="***************" disabled>
								<input type="button" value="Show"> <?php /* Il faut récupérer le dev-token depuis la database et l'afficher ici lors de l'appui sur le bouton */ ?>
								<br><br>
								<p>
									Token permissions: <span>none</span>
									<br>
									Token maximum rate: <span>0 query/h</span>
								</p>
								<?php /* Il faut re-générer un dev-token lors de l'appui sur ce bouton et le laisser masqué */ ?>
								<div class="text-center"><input type="button" value="Re-Generate"></div>
							</div>
						  </div>
						  <div class="tab-pane fade" id="permissions_tab">
							<h3 class="text-center">Permissions</h3>
							<br>
							<p>Each token and/or account is associated to a group of permissions allowing you to perform different actions. You <b>cannot</b> grant yourself permissions that you currently do not have. In case you'll need new permissions for you application, contact us at <i>support@kameobikes.com</i>.</p>
							<h4>Permissions list</h4>
							<table class="table">
								<thead>
									<tr>
										<th scope="col">Permission</th>
										<th scope="col">Endpoint(s)</th>
										<th scope="col">Description</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>order</td>
										<td>/orders, /chats</td>
										<td>This permission allow you to order a bike, manage your orders, ask questions on an order, etc.</td>
									</tr>
									<tr>
										<td>search</td>
										<td></td>
										<td>This permission is currently not implemented.</td>
									</tr>
									<tr>
										<td>fleetManager</td>
										<td></td>
										<td>This permission is currently not implemented.</td>
									</tr>
								</tbody>
							</table>
						  </div>
						  <div class="tab-pane fade" id="parameters_tab">
							  <h3 class="text-center">Parameters</h3>
								<br>
							  <p>Parameters given to an endpoint must be values of simple types (int, string, etc.)</p><p><b>GET</b> requests parameters are passed using a "<i>?</i>" as follows:</p>
							  <div style="background-color: #F0F0F0; width: 100%; padding: 20px; border-radius: 1px; display: inline-block;">
								<p style="display: inline;">https://example.com/api/<i>&lt;endpoint&gt;</i>?<i>&lt;parameter&gt;</i>=<i>&lt;value&gt;</i>&<i>&lt;parameter2&gt;</i>=<i>&lt;value2&gt;</i>
								</p>
							  </div>
									<p><br>Where <i>&lt;endpoint&gt;</i> is the name of the endpoint, <i>&lt;parameter&gt;</i> is the name of a parameter given to the endpoint and <i>&lt;value&gt;</i> is the value of the corresponding parameter.</p>
							  <p><b>POST</b> requests parameters are passed as <i>application/x-www-form-urlencoded</i> or plain data basic value/pair.</p>
							  <p>As a general rule of thumb, every endpoint needs at least a value for the <i>action</i> parameter, used to specify which action you want to perform on the requested ressource, but other parameters may sometimes be ommited.<br>
							  Further details about any specific endpoint parameter and/or type is available in the corresponding section of this documentation.</p>
						  </div>
						  <div class="tab-pane fade" id="responses_tab">
							  <h3 class="text-center">Responses</h3>
								<br>
							  <div class="alert alert-danger text-center" role="alert" style="border-radius: 10px;">
								<b>The HTTP status 200 OK on a reply should never be considered as a guarantee that the request was successful. In order to ensure a correct processing, one must check the content of the response field in the body of the reply.</b>
							  </div>
							  <p>This api will return data in JSON format. If the request is correct, the standard success response will have the <b>200 OK</b> HTTP code and will usually be of the form:<br>
							  <span style="background-color: #F0F0F0; padding:20px; width: 50%; border-radius: 1px; display: inline-block;">
							  {<br>
							  &nbsp;&nbsp;"response":"success",<br>
							  &nbsp;&nbsp;"itemsCount":2,<br>
							  &nbsp;&nbsp;"items": [<br>
							  &nbsp;&nbsp;&nbsp;&nbsp;{ ID: 1, name: "item1", property: "value" }<br>
							  &nbsp;&nbsp;&nbsp;&nbsp;{ ID: 2, name: "item2", property: "value" }<br>
							  &nbsp;&nbsp;]<br>
							  }
							  </span>
							  </p>
							  <p>This structure may vary in some cases. Please refer to the corresponding documentation of an endpoint for more informations.</p>
						  </div>
						  <div class="tab-pane fade" id="errors_tab">
							  <h3 class="text-center">Errors</h3>
									<br>
							  <p>In the event of an error occuring during the processing of a query, different HTTP error codes and messages can be returned to indicated what happened. The following table provides the common error codes and a description of how to interpret them:</p>
							  <table class="table">
								<thead>
									<tr class="d-flex">
										<th scope="col">HTTP status</th>
										<th scope="col">Body: error</th>
										<th scope="col">Body: error_message</th>
										<th scope="col">Recoverability</th>
										<th scope="col">Description</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>400&nbsp;Bad&nbsp;Request</td>
										<td>malformed_syntax</td>
										<td></td>
										<td>Recoverable</td>
										<td>This error indicate that one or several parameters are empty, missing or malformed, more informations can be found in the error_message field of the body. You should modify your request prior to retrying.</td>
									</tr>
									<tr>
										<td>401&nbsp;Unauthorized</td>
										<td>invalid_token</td>
										<td>The access token is invalid</td>
										<td>Recoverable</td>
										<td>This error either means that you forgot to provide your token to the api or that it isn't correct. You should modify your authenticate method prior to retrying.</td>
									</tr>
									<tr>
										<td>403&nbsp;Forbidden</td>
										<td>insufficient_privileges</td>
										<td>Your access token doesn't allow you to perfom this action</td>
										<td>Unrecoverable</td>
										<td>This error indicate that your request has been understood but that you don't have the required permission(s) to perform the requested action or to access the endpoint. You should not retry the same action and if you think that this is not a normal behavior, you can contact us at <i>support@kameobikes.com</i> .</td>
									</tr>
									<tr>
										<td>404&nbsp;Not&nbsp;Found</td>
										<td>not_found</td>
										<td>The requested endpoint cannot be found</td>
										<td>Unrecoverable</td>
										<td>This error indicate that your request has been sent to a non existing endpoint.</td>
									</tr>
									<tr>
										<td>405&nbsp;Method&nbsp;Not&nbsp;Allowed</td>
										<td>unallowed_method</td>
										<td>This method is not allowed on this endpoint</td>
										<td>Unrecoverable</td>
										<td>This error means that the requested action as not been found or is unacceptable on the current endpoint. You should not retry a similar query without changing the <i>action</i> parameter.</td>
									</tr>
									<tr>
										<td>500&nbsp;Internal&nbsp;Server&nbsp;Error</td>
										<td>internal_error</td>
										<td></td>
										<td>Unrecoverable</td>
										<td>This error means that the server as panicked during execution and was unable to perform the requested action. It is likely that nothing can be done to recover from this error but more informations can be found in the error_message field of the body.</td>
									</tr>
								</tbody>
							</table>
							<p>The <i>Recoverability</i> field in this table indicates whether you may be able to get a successful endpoint response by modifying your request or not.</p>
							<p>More detailed informations about the action-specific error messages on each endpoint can be found in the associated sections of this documentation in tables like this one.</p>
						  </div>
						  <div class="tab-pane fade" id="chats_tab">
							  <h3 class="text-center">Chats</h3>
										<br>
							  <p>This endpoint controls Kameo's instant message chats.</p>
							  <p>URL: <a href="http://kameobikes/api/chats">http://kameobikes/api/chats</a><br></p>
							  <div style="width:80%; background-color: #F0F0F0; border-radius: 5px; border: 1px solid black; padding: 5px; padding-right: 15px; display: flex;"><span style="margin-right: 20px; background-color: rgba(255,0,0,0.2); padding: 5px; border-radius: 5px;">GET</span><a class="collapse-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" href="#/">retrieveMessages<span style="display: inline-block; position: absolute; right: 20px;">/api/chats?action=retrieveMessages</span></a>
							  </div>
							  <div id="collapseOne" class="collapse" style="width:80%; border: 1px solid black; padding: 10px; border-width: 0px 1px 1px 1px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
								  <div class="card-body">
									<h4>Description</h4>
									<br>
									<p>This request allow you to retrieve the message(s) that you sent or that were sent to you through one of our webchats.</p>
									<br>
									<h4>Permission(s)</h4>
									<br>
									<p>The <b>order</b> permission is needed to perform this request.</p>
									<br>
									<h4>Parameter(s)</h4>
									<br>
									  <table class="table">
										<thead>
											<tr class="d-flex">
												<th scope="col">Parameter</th>
												<th scope="col">Type</th>
												<th scope="col">Required</th>
												<th scope="col">Value(s)</th>
												<th scope="col">Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>type</td>
												<td>string</td>
												<td>false</td>
												<td>order</td>
												<td>The type of the message, corresponding to a chat. If this parameter is ommited, every message will be retrieved.</td>
											</tr>
										</tbody>
									</table>
									<div style="background-color: #F0F0F0; width: 100%; padding: 20px; border-radius: 1px; display: inline-block;">
										<p style="display: inline;">curl -X GET "https://kameobikes.com/api/chats?action=retrieveMessages&type=order" --header "Authorization: Bearer <i>&lt;token&gt;</i>"</p>
									</div>
									<br><br>
									<h4>Response</h4>
									<br>
									<div style="background-color: #F0F0F0; width: 100%; padding: 20px; border-radius: 1px; display: inline-block;">
									<p style="display: inline;">
									  {<br>
									  &nbsp;&nbsp;"response":"success",<br>
									  &nbsp;&nbsp;"messagesNumber":2,<br>
									  &nbsp;&nbsp;"messages": [<br>
									  &nbsp;&nbsp;&nbsp;&nbsp;{ emailUser: "john@example.com", name: "Doe", firstName: "John", emailDestinary: "support@kameobikes.com", message: "Hi", messageDate:"25/01", messageHour: "10:23" },<br>
									  &nbsp;&nbsp;&nbsp;&nbsp;{ emailUser: "admin@kameobikes.com", name: "Admin", firstName: "Admin", emailDestinary: "john@example.com", message: "Hello, how can I help you ?", messageDate:"25/01", messageHour: "10:25" }<br>
									  &nbsp;&nbsp;]<br>
									  }
									  </p>
									</div>
									<br><br>
									<h4>Error(s)</h4>
									<br>
									<table class="table">
										<thead>
											<tr class="d-flex">
												<th scope="col">HTTP status</th>
												<th scope="col">Body: error</th>
												<th scope="col">Body: error_message</th>
												<th scope="col">Recoverability</th>
												<th scope="col">Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>500&nbsp;Internal&nbsp;Server&nbsp;Error</td>
												<td>internal_error</td>
												<td>Unable to retrieve messages</td>
												<td>Unrecoverable</td>
												<td>The server was not able to retrieve messages in database.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<br>
							<div style="width:80%; background-color: #F0F0F0; border-radius: 5px; border: 1px solid black; padding: 5px; padding-right: 15px; display: flex;"><span style="margin-right: 20px; background-color: rgba(255,0,0,0.2); padding: 5px; border-radius: 5px;">POST</span><a class="collapse-title" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" href="#/">sendMessage<span style="display: inline-block; position: absolute; right: 20px;">/api/chats</span></a>
							  </div>
							  <div id="collapseTwo" class="collapse" style="width:80%; border: 1px solid black; padding: 10px; border-width: 0px 1px 1px 1px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
								  <div class="card-body">
									<h4>Description</h4>
									<br>
									<p>This request allow you to send a message to KameoBikes through one of our webchat.</p>
									<br>
									<h4>Permission(s)</h4>
									<br>
									<p>The <b>order</b> permission is needed to perform this request.</p>
									<br>
									<h4>Parameter(s)</h4>
									<br>
									  <table class="table">
										<thead>
											<tr class="d-flex">
												<th scope="col">Parameter</th>
												<th scope="col">Type</th>
												<th scope="col">Required</th>
												<th scope="col">Value(s)</th>
												<th scope="col">Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>type</td>
												<td>string</td>
												<td>true</td>
												<td>order</td>
												<td>The type of the message, corresponding to a chat.</td>
											</tr>
											<tr>
												<td>message</td>
												<td>string</td>
												<td>true</td>
												<td></td>
												<td>The content of the message to send.</td>
											</tr>
										</tbody>
									</table>
									<div style="background-color: #F0F0F0; width: 100%; padding: 20px; border-radius: 1px; display: inline-block;">
										<p style="display: inline;">curl -X POST "https://kameobikes.com/api/chats" --header "Authorization: Bearer <i>&lt;token&gt;</i>" -d action=sendMessage -d message="My message!" -d type=order</p>
									</div>
									<br><br>
									<h4>Response</h4>
									<br>
									<div style="background-color: #F0F0F0; width: 100%; padding: 20px; border-radius: 1px; display: inline-block;">
									<p style="display: inline;">
									  {&nbsp;&nbsp;"response":"success"&nbsp;&nbsp;}
									</p>
									</div>
									<br><br>
									<h4>Error(s)</h4>
									<br>
									<table class="table">
										<thead>
											<tr class="d-flex">
												<th scope="col">HTTP status</th>
												<th scope="col">Body: error</th>
												<th scope="col">Body: error_message</th>
												<th scope="col">Recoverability</th>
												<th scope="col">Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>400&nbsp;Bad&nbsp;Request</td>
												<td>malformed_syntax</td>
												<td>The type of the message must be set</td>
												<td>Recoverable</td>
												<td>This error indicate that the parameter <i>type</i> has to be defined.</td>
											</tr>
											<tr>
												<td>400&nbsp;Bad&nbsp;Request</td>
												<td>malformed_syntax</td>
												<td>You cannot send an empty message</td>
												<td>Recoverable</td>
												<td>This error indicate that the parameter <i>message</i> has to be defined.</td>
											</tr>
											<tr>
												<td>500&nbsp;Internal&nbsp;Server&nbsp;Error</td>
												<td>internal_error</td>
												<td>Unable to send your message</td>
												<td>Unrecoverable</td>
												<td>The server was not able to record your message in database.</td>
											</tr>
											<tr>
												<td>500&nbsp;Internal&nbsp;Server&nbsp;Error</td>
												<td>internal_error</td>
												<td>Unable to send notification of your message, it has been canceled</td>
												<td>Unrecoverable</td>
												<td>This error indicate that the server was able to record the message but that it failed to send a notification to the recipient, and therefore, canceled everything to prevent this message from never being read.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane fade" id="companies_tab">
							<h3 class="text-center">Companies</h3>
							<br>
							<div class="alert alert-danger text-center" role="alert" style="border-radius: 10px;">
							  <b>The routing to this endpoint is currently not implemented.</b>
							</div>
						  </div>
						  <div class="tab-pane fade" id="notifications_tab">
						    <h3 class="text-center">Notifications</h3>
							<br>
							<div class="alert alert-danger text-center" role="alert" style="border-radius: 10px;">
							  <b>The routing to this endpoint is currently not implemented.</b>
							</div>
						  </div>
						  <div class="tab-pane fade" id="orders_tab">
						    <h3 class="text-center">Orders</h3>
							<br>
							<div class="alert alert-danger text-center" role="alert" style="border-radius: 10px;">
							  <b>The routing to this endpoint is currently not implemented.</b>
							</div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<script>
				/*Prevent bug with multiple active classes at the same time in the menu*/
				$(".nav-item").on('click', function(event){
					$tab = $(this).attr("href");
					$("div.in").not($tab).not(this).removeClass("in").delay(500).queue(function() {
						$(".nav-item").attr("aria-expanded","false").dequeue();
						$(".active").not($tab).not(this).removeClass("active").dequeue();
						$($tab).addClass("in").addClass("active").dequeue();
						$(this).attr("aria-expanded","true").dequeue();
					});
				});
			</script>
		</section>
		<!-- END:  404 PAGE -->
		<?php include '../../include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->
	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>
	<!-- Language management -->
	<script type="text/javascript" src="/js/language.js"></script>
</body>
</html>
