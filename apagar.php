<!DOCTYPE html>
<html lang="pt">
    <head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

	<title>Navbar Template for Bootstrap</title>

	
	<!--
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap.cerulean.min.css" rel="stylesheet">
	<link href="css/bootstrap.flatly.min.css" rel="stylesheet">
	<link href="css/bootstrap.cosmo.min.css" rel="stylesheet">
	<link href="css/bootstrap.paper.min.css" rel="stylesheet">
	<link href="css/bootstrap.sandstone.min.css" rel="stylesheet">
	<link href="css/bootstrap.slate.min.css" rel="stylesheet">
	<link href="css/bootstrap.spacelab.min.css" rel="stylesheet">
	<link href="css/bootstrap.superhero.min.css" rel="stylesheet">
	<link href="css/bootstrap.united.min.css" rel="stylesheet">
	<link href="css/bootstrap.yeti.min.css" rel="stylesheet">
	-->	
	
        <?php 
            include 'cssLib.php';
            
           ?>
	

	
	
	
    
	
	<!--
	<script type="text/javascript">
				
		
	</script>	
	-->
	
    </head>

    <body>
	
	<!-- top nav bar -->	
	<nav class="navbar navbar-default navbar-top menuCentral" style="border-radius: 0;">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#">webtools</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
		  <ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-picture"></span> Skin <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li class="dropdown-header">Básicas</li>
				<li><a href="#" data-theme="clean" class="theme-link">Clean</a></li>
				<li><a href="#" data-theme="default" class="theme-link">Default Flat</a></li>
				<li role="separator" class="divider"></li>
				<li class="dropdown-header">Temas</li>
				<li><a href="#" data-theme="cerulean" class="theme-link">Cerulean</a></li>
				<li><a href="#" data-theme="cosmo" class="theme-link">Cosmo</a></li>
				<li><a href="#" data-theme="paper" class="theme-link">Paper</a></li>
				<li><a href="#" data-theme="sandstone" class="theme-link">Sandstone</a></li>
				<li><a href="#" data-theme="spacelab" class="theme-link">Space Lab</a></li>
				<li><a href="#" data-theme="superhero" class="theme-link">Super Hero </a></li>
				<li><a href="#" data-theme="united" class="theme-link">United</a></li>
				<li><a href="#" data-theme="yeti" class="theme-link">Yeti</a></li>
			  </ul>
			</li>			
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-cubes"></span> Sistemas <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="#">Administrativo</a></li>
				<li><a href="#">Portal Cliente</a></li>
				<li role="separator" class="divider"></li>
			  </ul>
			</li>
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Usuário <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="#"><span class="glyphicon glyphicon-cog"></span>&nbsp; Perfil</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-star"></span>&nbsp; Favoritos</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-bell"></span>&nbsp; Notificações</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#"><span class="glyphicon glyphicon-off"></span>&nbsp; Log Off</a></li>
			  </ul>
			</li>
			<li><a href="#"><span class="glyphicon glyphicon-off"></span></a></li>
			
			</li>
			
		  </ul>
		</div><!--/.nav-collapse -->
	  </div>
	</nav> 
	<!-- end top nav bar -->	
	
	<!-- left nav bar -->
	<div id="menuLateral">
		<div class="navbar-default navbar-fixed-left">
			<a id="btnMenu" class="navbar-brand" href="#menu" data-toggle="tooltip" data-placement="right" title="Menu">
				<i class="fa fa-bars" aria-hidden="true"></i>
				<div class="menuLateralText">Menu</div>
			</a>
			<a class="active navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Página Home">
				<i class="fa fa-home" aria-hidden="true"></i>
				<div class="menuLateralText">Página Home</div>
			</a>
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Pasta">
				<i class="fa fa-briefcase" aria-hidden="true"></i>
				<div class="menuLateralText">Pasta</div>
			</a>
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Calendário">
				<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
				<div class="menuLateralText">Calendário</div>
			</a>
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Gráficos">
				<i class="fa fa-line-chart" aria-hidden="true"></i>
				<div class="menuLateralText">Gráficos</div>
			</a>                 
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Construções">
				<i class="fa fa-building-o" aria-hidden="true"></i>
				<div class="menuLateralText">Construções</div>
			</a>
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Crédito">
				<i class="fa fa-credit-card-alt" aria-hidden="true"></i>
				<div class="menuLateralText">Crédito</div>
			</a>
			<a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="right" title="Configurações">
				<i class="fa fa-cogs" aria-hidden="true"></i>
				<div class="menuLateralText">Configurações</div>
			</a>
		</div>
		
	</div>
	<!-- end left nav bar -->
	
	<!-- menu -->
	<nav id="menu" class="navbar-default">
		<ul>
			<li><a href="#">Home</a></li>
			<li><span>Sobre nós</span>
				<ul>
					<li><a href="#about/history">História</a></li>
					<li><span>Nosso Time View Informática</span>
						<ul>
						<li><a href="#about/team/management">Gerencimento</a></li>
						<li><a href="#about/team/sales">Vendas</a></li>
						<li><a href="#about/team/development">Desenvolvimento</a></li>
						</ul>
					</li>
					<li><a href="#about/address">Nosso endereço</a></li>
				</ul>
			</li>
			<li><a href="#contact">Contato</a></li>
		</ul>
	</nav>
	<!-- end menu -->

    <div class="outContainer">
	
    <div class="container">
    <div class="push50"></div>

		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#Componentes">Componentes</a></li>
		  <li><a data-toggle="tab" href="#Formulario">Formulário</a></li>
		  <li><a data-toggle="tab" href="#Tabelas">Tabelas & Grid</a></li>
		  <li><a data-toggle="tab" href="#Tipografia">Tipografia</a></li>
		  <li><a data-toggle="tab" href="#Extras">Extras</a></li>
		</ul>

		<div class="tab-content">
			<!-- aba Componentes -->
			<div id="Componentes" class="tab-pane fade in active">

				<div class="row">			
					<div class="page-header">
						<h2>Navbar</h2>
					</div>			
								
					<nav class="navbar navbar-default">
					  <div class="container-fluid">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						  <a class="navbar-brand" href="#">Brand</a>
						</div>

						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						  <ul class="nav navbar-nav">
							<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
							<li><a href="#">Link</a></li>
							<li class="dropdown">
							  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
							  <ul class="dropdown-menu" role="menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li class="divider"></li>
								<li><a href="#">Separated link</a></li>
								<li class="divider"></li>
								<li><a href="#">One more separated link</a></li>
							  </ul>
							</li>
						  </ul>
						  <form class="navbar-form navbar-left" role="search">
							<div class="form-group">
							  <input type="text" class="form-control" placeholder="Search">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						  </form>
						  <ul class="nav navbar-nav navbar-right">
							<li><a href="#">Link</a></li>
						  </ul>
						</div>
					  </div>
					</nav>			
					
					
					<nav class="navbar navbar-inverse">
					  <div class="container-fluid">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						  <a class="navbar-brand" href="#">Brand</a>
						</div>

						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
						  <ul class="nav navbar-nav">
							<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
							<li><a href="#">Link</a></li>
							<li class="dropdown">
							  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
							  <ul class="dropdown-menu" role="menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li class="divider"></li>
								<li><a href="#">Separated link</a></li>
								<li class="divider"></li>
								<li><a href="#">One more separated link</a></li>
							  </ul>
							</li>
						  </ul>
						  <form class="navbar-form navbar-left" role="search">
							<div class="form-group">
							  <input type="text" class="form-control" placeholder="Search">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						  </form>
						  <ul class="nav navbar-nav navbar-right">
							<li><a href="#">Link</a></li>
						  </ul>
						</div>
					  </div>
					</nav>

				</div>
		
				<div class="row">
				
					<div class="col-md-6">	
						
						<div class="page-header">
						  <h2>Alerts</h2>
						</div>
						  
						<div class="alert alert-success" role="alert">
						  <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
						</div>
						
						<div class="alert alert-info" role="alert">
						  <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
						</div>
						
						<div class="alert alert-warning" role="alert">
						  <strong>Warning!</strong> Better check yourself, you're <a href="#" class="alert-link">not looking too good</a>.
						</div>
						
						<div class="alert alert-danger" role="alert">
						  <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
						</div>
						
						<div class="alert alert-warning alert-dismissible" role="alert">
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						  <strong>Warning!</strong> Better check yourself, you're not looking too good.
						</div>

						<div class="page-header">
						<h2>Log in</h2>
						</div>

						<div class="login-form">
						<div class="form-group">
						  <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo120" />
						  <label class="login-field-icon fui-user" for="campo1"></label>
						</div>

						<div class="form-group">
						  <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass" />
						  <label class="login-field-icon fui-lock" for="login-pass"></label>
						</div>

						<a class="btn btn-primary btn-lg btn-block" href="#">Log in</a>
						<a class="login-link" href="#">Lost your password?</a>
						</div>				

					</div>	

					<div class="col-md-6">	
						
						<div class="page-header">
							<h2>Forms</h2>
						</div>			

						<div class="well bs-component">
						  <form class="form-horizontal">
							<fieldset>
							  <legend>Legend</legend>
							  <div class="form-group">
								<label for="inputEmail" class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
								  <input type="text" class="form-control" id="inputEmail" placeholder="Email">
								</div>
							  </div>
							  <div class="form-group">
								<label for="inputPassword" class="col-lg-2 control-label">Password</label>
								<div class="col-lg-10">
								  <input type="password" class="form-control" id="inputPassword" placeholder="Password">
								  <div class="checkbox">
									<label>
									  <input type="checkbox"> Checkbox
									</label>
								  </div>
								</div>
							  </div>
							  <div class="form-group">
								<label for="textArea" class="col-lg-2 control-label">Textarea</label>
								<div class="col-lg-10">
								  <textarea class="form-control" rows="3" id="textArea"></textarea>
								  <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
								</div>
							  </div>
							  <div class="form-group">
								<label class="col-lg-2 control-label">Radios</label>
								<div class="col-lg-10">
								  <div class="radio">
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
									  Option one is this
									</label>
								  </div>
								  <div class="radio">
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
									  Option two can be something else
									</label>
								  </div>
								</div>
							  </div>
							  <div class="form-group">
								<label for="select" class="col-lg-2 control-label">Selects</label>
								<div class="col-lg-10">
								  <select class="form-control" id="select">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								  </select>
								  <br>
								  <select multiple="" class="form-control">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								  </select>
								</div>
							  </div>
							  <div class="form-group">
								<div class="col-lg-10 col-lg-offset-2">
								  <button type="reset" class="btn btn-default">Cancel</button>
								  <button type="submit" class="btn btn-primary">Submit</button>
								</div>
							  </div>
							</fieldset>
						  </form>
						</div>
							
					</div>	

				</div>
						
				<div class="row">
				
					<div class="col-md-12">	
					
					<div class="page-header">
						<h2>Link Buttons</h2>
					</div>			
					
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Button</th>
									<th>Large Button</th>
									<th>Small Button</th>
									<th>Disabled Button</th>
									<th>Button with Icon</th>
									<th>Block Button</th>
									<th>Link Groups</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<a class="btn btn-default" href="#">Default</a>
									</td>
									<td>
										<a class="btn btn-lg btn-default" href="#">Default</a>
									</td>
									<td>
										<a class="btn btn-sm btn-default" href="#">Default</a>
									</td>
									<td>
										<a class="btn disabled btn-default" href="#">Default</a>
									</td>
									<td>
										<a class="btn btn-default" href="#"> <i class="fa fa-cog"></i>
											Default
										</a>
									</td>
									<td>
										<a class="btn btn-block btn-default" href="#">Default</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-default">Default</a>
										  <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li class="disabled"><a href="#">Separated link</a></li>
										  </ul>
										</div>							
									</td>
								</tr>
								<tr>
									<td>
										<a class="btn btn-primary" href="#">Primary</a>
									</td>
									<td>
										<a class="btn btn-primary btn-lg" href="#">Primary</a>
									</td>
									<td>
										<a class="btn btn-primary btn-sm" href="#">Primary</a>
									</td>
									<td>
										<a class="btn btn-primary disabled" href="#">Primary</a>
									</td>
									<td>
										<a class="btn btn-primary" href="#"> <i class="fa fa-shopping-cart fa fa-white"></i>
											Primary
										</a>
									</td>
									<td>
										<a class="btn btn-primary btn-block" href="#">Primary</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-primary">Primary</a>
										  <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li><a href="#">Separated link</a></li>
										  </ul>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<a class="btn btn-info" href="#">Info</a>
									</td>
									<td>
										<a class="btn btn-info btn-lg" href="#">Info</a>
									</td>
									<td>
										<a class="btn btn-info btn-sm" href="#">Info</a>
									</td>
									<td>
										<a class="btn btn-info disabled" href="#">Info</a>
									</td>
									<td>
										<a class="btn btn-info" href="#">
											<i class="fa fa-exclamation-sign fa fa-white"></i>
											Info
										</a>
									</td>
									<td>
										<a class="btn btn-info btn-block" href="#">Info</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-info">Info</a>
										  <a href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li><a href="#">Separated link</a></li>
										  </ul>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<a class="btn btn-success" href="#">Success</a>
									</td>
									<td>
										<a class="btn btn-success btn-lg" href="#">Success</a>
									</td>
									<td>
										<a class="btn btn-success btn-sm" href="#">Success</a>
									</td>
									<td>
										<a class="btn btn-success disabled" href="#">Success</a>
									</td>
									<td>
										<a class="btn btn-success" href="#">
											<i class="fa fa-check fa fa-white"></i>
											Success
										</a>
									</td>
									<td>
										<a class="btn btn-success btn-block" href="#">Success</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-success">Success</a>
										  <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li class="disabled"><a href="#">Separated link</a></li>
										  </ul>
										</div>							
									</td>
								</tr>
								<tr>
									<td>
										<a class="btn btn-warning" href="#">Warning</a>
									</td>
									<td>
										<a class="btn btn-warning btn-lg" href="#">Warning</a>
									</td>
									<td>
										<a class="btn btn-warning btn-sm" href="#">Warning</a>
									</td>
									<td>
										<a class="btn btn-warning disabled" href="#">Warning</a>
									</td>
									<td>
										<a class="btn btn-warning" href="#">
											<i class="fa fa-warning-sign fa fa-white"></i>
											Warning
										</a>
									</td>
									<td>
										<a class="btn btn-warning btn-block" href="#">Warning</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-warning">Warning</a>
										  <a href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li class="disabled"><a href="#">Separated link</a></li>
										  </ul>
										</div>							
									</td>
								</tr>
								<tr>
									<td>
										<a class="btn btn-danger" href="#">Danger</a>
									</td>
									<td>
										<a class="btn btn-danger btn-lg" href="#">Danger</a>
									</td>
									<td>
										<a class="btn btn-danger btn-sm" href="#">Danger</a>
									</td>
									<td>
										<a class="btn btn-danger disabled" href="#">Danger</a>
									</td>
									<td>
										<a class="btn btn-danger" href="#">
											<i class="fa fa-remove fa fa-white"></i>
											Danger
										</a>
									</td>
									<td>
										<a class="btn btn-danger btn-block" href="#">Danger</a>
									</td>
									<td>
										<div class="btn-group">
										  <a href="#" class="btn btn-danger">Danger</a>
										  <a href="#" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
										  <ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li class="divider"></li>
											<li class="disabled"><a href="#">Separated link</a></li>
										  </ul>
										</div>							
									</td>
								</tr>						
							</tbody>
						</table>
						
					</div>
					
				</div>


				
				
				<div class="row">
				
					<div class="col-md-5">	
						
						<div class="page-header">
							<h2>Button Groups</h2>
						</div>			
						
						<div class="col-md-9">
							<p class="lead text-muted">Regular</p>
							
							<div class="btn-group" role="group" aria-label="Basic example">
							  <button type="button" class="btn btn-default">Left</button>
							  <button type="button" class="btn btn-default">Middle</button>
							  <button type="button" class="btn btn-default">Right</button>
							</div>
							
							<div class="push10"></div>	
							
							<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group with nested dropdown">
							  <a href="#" class="btn btn-default" role="button">Left</a>
							  <a href="#" class="btn btn-default" role="button">Middle</a>
							  <div class="btn-group" role="group">
								<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								  Dropdown <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
								  <li><a href="#">Action</a></li>
								  <li><a href="#">Another action</a></li>
								  <li><a href="#">Something else here</a></li>
								  <li role="separator" class="divider"></li>
								  <li><a href="#">Separated link</a></li>
								</ul>
							  </div>
							</div>
							
							<div class="push10"></div>	

							<div class="bs-component" style="margin-bottom: 15px;">
							  <div class="btn-toolbar">
								<div class="btn-group">
								  <a href="#" class="btn btn-default">1</a>
								  <a href="#" class="btn btn-default">2</a>
								  <a href="#" class="btn btn-default">3</a>
								  <a href="#" class="btn btn-default">4</a>
								</div>

								<div class="btn-group">
								  <a href="#" class="btn btn-default">5</a>
								  <a href="#" class="btn btn-default">6</a>
								  <a href="#" class="btn btn-default">7</a>
								</div>

								<div class="push10"></div>	
								
								<div class="btn-group">
								  <a href="#" class="btn btn-default"><span class="glyphicon glyphicon-star"></a>
								  <div class="btn-group">
									<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									  Dropdown
									  <span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
									  <li><a href="#">Dropdown link</a></li>
									  <li><a href="#">Dropdown link</a></li>
									  <li><a href="#">Dropdown link</a></li>
									 </ul>
								  </div>
								</div>
							  </div>
							</div>
							
							<div class="btn-group btn-group-lg" role="group" aria-label="Large button group">
							  <button type="button" class="btn btn-default">Left</button>
							  <button type="button" class="btn btn-default">Middle</button>
							  <button type="button" class="btn btn-default">Right</button>
							</div>
							
							<div class="push10"></div>	
							
							<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
							  <button type="button" class="btn btn-default">Left</button>
							  <button type="button" class="btn btn-default">Middle</button>
							  <button type="button" class="btn btn-default">Right</button>
							</div>
							
							<div class="push10"></div>	
							
							<div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group">
							  <button type="button" class="btn btn-default">Left</button>
							  <button type="button" class="btn btn-default">Middle</button>
							  <button type="button" class="btn btn-default">Right</button>
							</div>
							
						</div>
					
						<div class="col-md-3">
							<p class="lead text-muted">Vertical</p>
						
							<div class="btn-group-vertical" role="group" aria-label="Vertical button group">
							  <button type="button" class="btn btn-default">Button</button>
							  <button type="button" class="btn btn-default">Button</button>
							  <div class="btn-group" role="group">
								<button id="btnGroupVerticalDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  Dropdown
								  <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
								  <li><a href="#">Dropdown link</a></li>
								  <li><a href="#">Dropdown link</a></li>
								</ul>
							  </div>
							  <button type="button" class="btn btn-default">Button</button>
							  <button type="button" class="btn btn-default">Button</button>
							  <div class="btn-group" role="group">
								<button id="btnGroupVerticalDrop2" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  Dropdown
								  <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
								  <li><a href="#">Dropdown link</a></li>
								  <li><a href="#">Dropdown link</a></li>
								</ul>
							  </div>
							  <div class="btn-group" role="group">
								<button id="btnGroupVerticalDrop3" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  Dropdown
								  <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
								  <li><a href="#">Dropdown link</a></li>
								  <li><a href="#">Dropdown link</a></li>
								</ul>
							  </div>
							  <div class="btn-group" role="group">
								<button id="btnGroupVerticalDrop4" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  Dropdown
								  <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop4">
								  <li><a href="#">Dropdown link</a></li>
								  <li><a href="#">Dropdown link</a></li>
								</ul>
							  </div>
							</div>
				  
						</div>			
							
					</div>			
					
					<div class="col-md-4">	
					
						<div class="page-header">
							<h2>Icons Buttons</h2>
						</div>			
					
						<div class="btn-toolbar" role="toolbar">
						  <button type="button" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						</div>
						
						<div class="push10"></div>	
					
						<div class="btn-toolbar" role="toolbar">
						  <button type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						  <button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star</button>
						</div>
					
						<div class="push10"></div>	
						
						<div class="btn-toolbar" role="toolbar">
						  <div class="btn-group">
							<button type="button" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Center Align"><span class="glyphicon glyphicon-align-center" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Right Align"><span class="glyphicon glyphicon-align-right" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Justify"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></button>
						  </div>
						</div>	
					
						<div class="page-header">
							<h2>Blocks Checks / Radio</h2>
						</div>
						
						<div class="btn-group btn-group-xs effect-fall in" data-toggle="buttons" data-effect="fall" style="transition: all 0.7s ease-in-out;">
							<label class="btn btn-primary">
								<input type="checkbox"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></label>
							<label class="btn btn-primary">
								<input type="checkbox"><span class="glyphicon glyphicon-align-center" aria-hidden="true"></span></label>
							<label class="btn btn-primary">
								<input type="checkbox"><span class="glyphicon glyphicon-align-right" aria-hidden="true"></label>
							<label class="btn btn-primary active">
								<input type="checkbox"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></label>
						</div>
						
						<div class="btn-group btn-group-xs effect-fall in" data-toggle="buttons" data-effect="fall" style="transition: all 0.7s ease-in-out;">
							<label class="btn btn-warning active">
								<input type="radio" name="options" id="option1"><i class="glyphicon glyphicon-ok"></i></label>
							<label class="btn btn-success">
								<input type="radio" name="options" id="option2"><i class="glyphicon glyphicon-remove"></i></label>
							<label class="btn btn-info">
								<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-thumbs-down"></i></label>
							<label class="btn btn-danger">
								<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-thumbs-up"></i></label>
							<label class="btn btn-default">
								<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-refresh"></i></label>
						</div>		
				   
						<div class="btn-group btn-group-xs">
							<button type="button" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Center Align"><span class="glyphicon glyphicon-align-center" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Right Align"><span class="glyphicon glyphicon-align-right" aria-hidden="true"></span></button>
							<button type="button" class="btn btn-default" aria-label="Justify"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></button>
						</div>						
					
					</div>			
					
					<div class="col-md-3">	
					
						<div class="page-header">
							<h2>Labeled Buttons</h2>
						</div>			
						
						<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Success</button>
						<button type="button" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</button>			

						<div class="push10"></div>	

						<button type="button" class="btn btn-labeled btn-warning"><span class="btn-label"><i class="glyphicon glyphicon-bookmark"></i></span>Bookmark</button>
						<button type="button" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-camera"></i></span>Camera</button>			

						<div class="push10"></div>	
						
						<button type="button" class="btn btn-labeled btn-info"><span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>Refresh</button>
					
						<div class="push10"></div>	

						<button type="button" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-arrow-left"></i></span>Left</button>
						<button type="button" class="btn btn-labeled btn-default">Right<span class="btn-label btn-label-right"><i class="glyphicon glyphicon-arrow-right"></i></span></button>
						
					</div>			
					
				</div>




				<div class="row">
				
					<div class="col-md-4">
						<div class="page-header">
						  <h2>Inputs</h2>
						</div>
				  
						<form class="bs-example-form" data-example-id="simple-input-groups">
							<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">@</span>
							<input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
							</div>
							<br>
							<div class="input-group">
							<input type="text" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2">
							<span class="input-group-addon" id="basic-addon2">@example.com</span>
							</div>
							<br>
							<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
							<span class="input-group-addon">.00</span>
							</div>
						</form>

						<div class="push20"></div>
						  
						<form class="bs-example-form" data-example-id="input-group-sizing">
							<div class="input-group input-group-lg">
							  <span class="input-group-addon" id="sizing-addon1">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
							</div>
							<br>
							<div class="input-group">
							  <span class="input-group-addon" id="sizing-addon2">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon2">
							</div>
							<br>
							<div class="input-group input-group-sm">
							  <span class="input-group-addon" id="sizing-addon3">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon3">
							</div>

							<div class="push15"></div> 	

							<select class="form-control select select-primary" data-toggle="select">
								<option value="0">Choose hero</option>
								<option value="1">Spider Man</option>
								<option value="2">Wolverine</option>
								<option value="3">Captain America</option>
								<option value="4" selected>X-Men</option>
								<option value="5">Crocodile</option>
							</select>
								  
							<div class="push15"></div> 	

							<p class="lead text-muted">Tags</p>
							<div class="tagsinput-primary">
								<input name="tagsinput" class="tagsinput" data-role="tagsinput" value="School, Teacher, Colleague" />
							</div>

						</form>				
							
					</div>	

					<div class="col-md-4">	
						<div class="page-header">
							<h2>Input States</h2>
						</div>			
					
						<form class="bs-component">
							<div class="form-group">
							  <label class="control-label" for="focusedInput">Focused input</label>
							  <input class="form-control" id="focusedInput" type="text" value="This is focused...">
							</div>

							<div class="form-group">
							  <label class="control-label" for="disabledInput">Disabled input</label>
							  <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
							</div>

							<div class="form-group has-warning">
							  <label class="control-label" for="inputWarning">Input warning</label>
							  <input type="text" class="form-control" id="inputWarning">
							</div>

							<div class="form-group has-error">
							  <label class="control-label" for="inputError">Input error</label>
							  <input type="text" class="form-control" id="inputError">
							</div>

							<div class="form-group has-success">
							  <label class="control-label" for="inputSuccess">Input success</label>
							  <input type="text" class="form-control" id="inputSuccess">
							</div>
						</form>
					
					</div>
					
					<div class="col-md-4">
						
						<div class="page-header">
						<h2>Checks / Radios /  Addons</h2>
						</div>

						<form class="bs-example-form" data-example-id="input-group-with-checkbox-radio">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-addon">
									<input type="checkbox" aria-label="Checkbox for following text input">
									</span>
									<input type="text" class="form-control" aria-label="Text input with checkbox">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-addon">
									<input type="radio" aria-label="Radio button for following text input">
									</span>
									<input type="text" class="form-control" aria-label="Text input with radio button">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
							</div><!-- /.row -->
						</form>

						<div class="push10"></div> 	

						<form class="bs-example-form" data-example-id="input-group-with-button">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
									</span>
									<input type="text" class="form-control" placeholder="Search for...">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
								<div class="col-lg-6">
									<div class="input-group">
									<input type="text" class="form-control" placeholder="Search for...">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
									</span>
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
							</div><!-- /.row -->
						</form>

						<div class="push10"></div> 	

						<form class="bs-example-form" data-example-id="input-group-dropdowns">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
										<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
										<ul class="dropdown-menu">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li><a href="#">Something else here</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="#">Separated link</a></li>
										</ul>
										</div><!-- /btn-group -->
										<input type="text" class="form-control" aria-label="Text input with dropdown button">
										</div><!-- /input-group -->
									</div><!-- /.col-lg-6 -->

								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" class="form-control" aria-label="Text input with dropdown button">
										<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li><a href="#">Something else here</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="#">Separated link</a></li>
										</ul>
										</div><!-- /btn-group -->
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->

						</div><!-- /.row -->
						</form>

						<div class="push10"></div> 

						<div class="col-xs-6">
							<label class="checkbox" for="checkbox1">
							<input type="checkbox" value="" id="checkbox1" data-toggle="checkbox">
							Unchecked
							</label>
							<label class="checkbox" for="checkbox2">
							<input type="checkbox" checked="checked" value="" id="checkbox2" data-toggle="checkbox" checked="">
							Checked
							</label>
							<label class="checkbox" for="checkbox3">
							<input type="checkbox" value="" id="checkbox3" data-toggle="checkbox" disabled="">
							Disabled unchecked
							</label>
							<label class="checkbox" for="checkbox4">
							<input type="checkbox" checked="checked" value="" id="checkbox4" data-toggle="checkbox" disabled="" checked="">
							Disabled checked
							</label>
						</div>
						<!-- /checkboxes col-xs-6 -->

						<div class="col-xs-6">
							<label class="radio">
							<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" data-toggle="radio">
							Radio is off
							</label>
							<label class="radio">
							<input type="radio" name="optionsRadios" id="optionsRadios2" value="option1" data-toggle="radio" checked="">
							Radio is on
							</label>

							<label class="radio">
							<input type="radio" name="optionsRadiosDisabled" id="optionsRadios3" value="option2" data-toggle="radio" disabled="">
							Disabled radio is off
							</label>
							<label class="radio">
							<input type="radio" name="optionsRadiosDisabled" id="optionsRadios4" value="option2" data-toggle="radio" checked="" disabled="">
							Disabled radio is on
							</label>
						</div>
						<!-- /radios col-xs-6 -->

						<div class="push10"></div> 	

						<h2 id="breadcrumbs">Breadcrumbs</h2>

						<ol class="breadcrumb">
						<li class="active">Home</li>
						</ol>
						<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li class="active">Library</li>
						</ol>
						<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li><a href="#">Library</a></li>
						<li class="active">Data</li>
						</ol>			  

					</div>	
		
				</div>




				<div class="row">
				
					<div class="col-md-4">
						<div class="page-header">
						  <h2>Inputs</h2>
						</div>
				  
						<form class="bs-example-form" data-example-id="simple-input-groups">
							<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">@</span>
							<input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
							</div>
							<br>
							<div class="input-group">
							<input type="text" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2">
							<span class="input-group-addon" id="basic-addon2">@example.com</span>
							</div>
							<br>
							<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
							<span class="input-group-addon">.00</span>
							</div>
						</form>

						<div class="push20"></div>
						  
						<form class="bs-example-form" data-example-id="input-group-sizing">
							<div class="input-group input-group-lg">
							  <span class="input-group-addon" id="sizing-addon1">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
							</div>
							<br>
							<div class="input-group">
							  <span class="input-group-addon" id="sizing-addon2">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon2">
							</div>
							<br>
							<div class="input-group input-group-sm">
							  <span class="input-group-addon" id="sizing-addon3">@</span>
							  <input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon3">
							</div>

							<div class="push15"></div> 	

							<select class="form-control select select-primary" data-toggle="select">
								<option value="0">Choose hero</option>
								<option value="1">Spider Man</option>
								<option value="2">Wolverine</option>
								<option value="3">Captain America</option>
								<option value="4" selected>X-Men</option>
								<option value="5">Crocodile</option>
							</select>
								  
							<div class="push15"></div> 	

							<p class="lead text-muted">Tags</p>
							<div class="tagsinput-primary">
								<input name="tagsinput" class="tagsinput" data-role="tagsinput" value="School, Teacher, Colleague" />
							</div>

						</form>				
							
					</div>	

					<div class="col-md-4">	
						<div class="page-header">
							<h2>Input States</h2>
						</div>			
					
						<form class="bs-component">
							<div class="form-group">
							  <label class="control-label" for="focusedInput">Focused input</label>
							  <input class="form-control" id="focusedInput" type="text" value="This is focused...">
							</div>

							<div class="form-group">
							  <label class="control-label" for="disabledInput">Disabled input</label>
							  <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
							</div>

							<div class="form-group has-warning">
							  <label class="control-label" for="inputWarning">Input warning</label>
							  <input type="text" class="form-control" id="inputWarning">
							</div>

							<div class="form-group has-error">
							  <label class="control-label" for="inputError">Input error</label>
							  <input type="text" class="form-control" id="inputError">
							</div>

							<div class="form-group has-success">
							  <label class="control-label" for="inputSuccess">Input success</label>
							  <input type="text" class="form-control" id="inputSuccess">
							</div>
						</form>
					
					</div>
					
					<div class="col-md-4">
						
						<div class="page-header">
						<h2>Checks / Radios /  Addons</h2>
						</div>

						<form class="bs-example-form" data-example-id="input-group-with-checkbox-radio">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-addon">
									<input type="checkbox" aria-label="Checkbox for following text input">
									</span>
									<input type="text" class="form-control" aria-label="Text input with checkbox">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-addon">
									<input type="radio" aria-label="Radio button for following text input">
									</span>
									<input type="text" class="form-control" aria-label="Text input with radio button">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
							</div><!-- /.row -->
						</form>

						<div class="push10"></div> 	

						<form class="bs-example-form" data-example-id="input-group-with-button">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
									</span>
									<input type="text" class="form-control" placeholder="Search for...">
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
								
								<div class="col-lg-6">
									<div class="input-group">
									<input type="text" class="form-control" placeholder="Search for...">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
									</span>
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
							</div><!-- /.row -->
						</form>

						<div class="push10"></div> 	

						<form class="bs-example-form" data-example-id="input-group-dropdowns">
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
										<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
										<ul class="dropdown-menu">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li><a href="#">Something else here</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="#">Separated link</a></li>
										</ul>
										</div><!-- /btn-group -->
										<input type="text" class="form-control" aria-label="Text input with dropdown button">
										</div><!-- /input-group -->
									</div><!-- /.col-lg-6 -->

								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" class="form-control" aria-label="Text input with dropdown button">
										<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li><a href="#">Something else here</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="#">Separated link</a></li>
										</ul>
										</div><!-- /btn-group -->
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->

						</div><!-- /.row -->
						</form>

						<div class="push10"></div> 

						<div class="col-xs-6">
							<label class="checkbox" for="checkbox1">
							<input type="checkbox" value="" id="checkbox1" data-toggle="checkbox">
							Unchecked
							</label>
							<label class="checkbox" for="checkbox2">
							<input type="checkbox" checked="checked" value="" id="checkbox2" data-toggle="checkbox" checked="">
							Checked
							</label>
							<label class="checkbox" for="checkbox3">
							<input type="checkbox" value="" id="checkbox3" data-toggle="checkbox" disabled="">
							Disabled unchecked
							</label>
							<label class="checkbox" for="checkbox4">
							<input type="checkbox" checked="checked" value="" id="checkbox4" data-toggle="checkbox" disabled="" checked="">
							Disabled checked
							</label>
						</div>
						<!-- /checkboxes col-xs-6 -->

						<div class="col-xs-6">
							<label class="radio">
							<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" data-toggle="radio">
							Radio is off
							</label>
							<label class="radio">
							<input type="radio" name="optionsRadios" id="optionsRadios2" value="option1" data-toggle="radio" checked="">
							Radio is on
							</label>

							<label class="radio">
							<input type="radio" name="optionsRadiosDisabled" id="optionsRadios3" value="option2" data-toggle="radio" disabled="">
							Disabled radio is off
							</label>
							<label class="radio">
							<input type="radio" name="optionsRadiosDisabled" id="optionsRadios4" value="option2" data-toggle="radio" checked="" disabled="">
							Disabled radio is on
							</label>
						</div>
						<!-- /radios col-xs-6 -->

						<div class="push10"></div> 	

						<h2 id="breadcrumbs">Breadcrumbs</h2>

						<ol class="breadcrumb">
						<li class="active">Home</li>
						</ol>
						<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li class="active">Library</li>
						</ol>
						<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li><a href="#">Library</a></li>
						<li class="active">Data</li>
						</ol>			  

					</div>	
		
				</div>
				


				<div class="row">
				
					<div class="col-md-4">
						<div class="page-header">
						<h2>Lists Groups</h2>
						</div>

						<ul class="list-group">
						<li class="list-group-item active">
						<span class="badge">14</span>
						Cras justo odio
						</li>
						<li class="list-group-item">
						<span class="badge">2</span>
						Dapibus ac facilisis in
						</li>
						<li class="list-group-item">
						<span class="badge">1</span>
						Morbi leo risus
						</li>
						</ul>			

						<div class="push10"></div> 	

						<div class="list-group">
						<button type="button" class="list-group-item">Button items</button>
						<button type="button" class="list-group-item">Button items</button>
						<button type="button" class="list-group-item disabled">Button items</button>
						</div>			

						<div class="list-group">
						<li class="list-group-item list-group-item-success">Dapibus ac facilisis in</li>
						<li class="list-group-item list-group-item-info">Cras sit amet nibh libero</li>
						<a href="#" class="list-group-item list-group-item-warning">Porta ac consectetur ac</a>
						<a href="#" class="list-group-item list-group-item-danger">Vestibulum at eros</a>
						</div>

						<div class="list-group">
						<a href="#" class="list-group-item active">
						<h4 class="list-group-item-heading">List group item heading</h4>
						<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
						</a>
						<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading">List group item heading</h4>
						<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
						</a>
						<a href="#" class="list-group-item">
						<h4 class="list-group-item-heading">List group item heading</h4>
						<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
						</a>
						</div>	

					</div>	


					<div class="col-md-4">
						<div class="page-header">
						<h2>Panels</h2>
						</div>

						<div class="panel panel-default">
						<div class="panel-heading">
						<h3 class="panel-title">Panel title</h3>
						</div>
						<div class="panel-body">
						Panel content
						</div>
						<div class="panel-footer">Panel footer</div>
						</div>

						<div class="push10"></div> 	

						<div class="panel panel-primary">
						<div class="panel-heading">
						<h3 class="panel-title">Panel title</h3>
						</div>
						<div class="panel-body">
						Panel content
						</div>
						</div>			

						<div class="panel panel-info">
						<div class="panel-heading">
						<h3 class="panel-title">Panel title</h3>
						</div>
						<div class="panel-body">
						Panel content
						</div>
						</div>

						<div class="panel panel-default">
						<!-- Default panel contents -->
						<div class="panel-heading">Panel heading</div>
						<div class="panel-body">
						<p>Some default panel content here. Nulla vitae elit libero, a pharetra augue. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						</div>

						<table class="table table-striped table-hover ">
						<thead>
						<tr>
						<th>#</th>
						<th>Column heading</th>
						<th>Column heading</th>
						<th>Column heading</th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td>1</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr>
						<td>2</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr class="info">
						<td>3</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr class="success">
						<td>4</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr class="danger">
						<td>5</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr class="warning">
						<td>6</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						<tr class="active">
						<td>7</td>
						<td>Column content</td>
						<td>Column content</td>
						<td>Column content</td>
						</tr>
						</tbody>
						</table> 
						</div>	

					</div>
			
					<div class="col-md-4">
						<div class="page-header">
						  <h2>Progres Bar</h2>
						</div>
						
						<div class="progress">
						  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
							<span class="sr-only">60% Complete</span>
						  </div>
						</div>

						<div class="progress">
						  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
							60%
						  </div>
						</div>
						
						<div class="progress">
						  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
							60%<span class="sr-only">40% Complete (success)</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
							60%<span class="sr-only">20% Complete</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
						   60% <span class="sr-only">60% Complete (warning)</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
						   60% <span class="sr-only">80% Complete (danger)</span>
						  </div>
						</div>

						<div class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
							<span class="sr-only">40% Complete (success)</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
							<span class="sr-only">20% Complete</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
							<span class="sr-only">60% Complete (warning)</span>
						  </div>
						</div>
						<div class="progress">
						  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
							<span class="sr-only">80% Complete (danger)</span>
						  </div>
						</div>
						
						<p class="lead text-muted">Animated</p>
						<div class="progress">
						  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">45% Complete</span></div>
						</div>
						<button type="button" class="btn btn-default bs-docs-activate-animated-progressbar" data-toggle="button" aria-pressed="false" autocomplete="off">Toggle animation</button>
							

						<div class="page-header">
						  <h2 id="navbar">Tooltips</h2>
						</div>							
								
						<div class="bs-component">
						  <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="" data-original-title="Tooltip on left">Left</button>

						  <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">Top</button>

						  <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Bottom</button>

						  <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="" data-original-title="Tooltip on right">Right</button>
						</div>
						

						<div class="page-header">
						  <h1 id="navbar">Popovers</h1>
						</div>	
						
						<button class="btn btn-primary edit popOverCb" id="showPopover2">Popover Web Ui Html</button>						

						<div id="popup-content" style="display:none;">
						
							<form class="form-inline">
							  <div class="form-group pull-left">
								<input type="text" class="form-control input-sm" style="margin-right: 5px;" id="inputPassword2" placeholder="Password">
							  </div>
							  <button type="button" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-ok"></i></button>
							</form>	
							
							<br/>

							<div class="btn-group btn-group-xs effect-fall in" data-toggle="buttons" data-effect="fall" style="transition: all 0.7s ease-in-out;">
								<label class="btn btn-primary">
									<input type="checkbox"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></label>
								<label class="btn btn-primary">
									<input type="checkbox"><span class="glyphicon glyphicon-align-center" aria-hidden="true"></span></label>
								<label class="btn btn-primary">
									<input type="checkbox"><span class="glyphicon glyphicon-align-right" aria-hidden="true"></label>
								<label class="btn btn-primary active">
									<input type="checkbox"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></label>
							</div>
							
							<div class="btn-group btn-group-xs effect-fall in" data-toggle="buttons" data-effect="fall" style="transition: all 0.7s ease-in-out;">
								<label class="btn btn-warning active">
									<input type="radio" name="options" id="option1"><i class="glyphicon glyphicon-ok"></i></label>
								<label class="btn btn-success">
									<input type="radio" name="options" id="option2"><i class="glyphicon glyphicon-remove"></i></label>
								<label class="btn btn-info">
									<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-thumbs-down"></i></label>
								<label class="btn btn-danger">
									<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-thumbs-up"></i></label>
								<label class="btn btn-default">
									<input type="radio" name="options" id="option3"><i class="glyphicon glyphicon-refresh"></i></label>
							</div>		
					   
							<div class="btn-group btn-group-xs">
								<button type="button" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></button>
								<button type="button" class="btn btn-default" aria-label="Center Align"><span class="glyphicon glyphicon-align-center" aria-hidden="true"></span></button>
								<button type="button" class="btn btn-default" aria-label="Right Align"><span class="glyphicon glyphicon-align-right" aria-hidden="true"></span></button>
								<button type="button" class="btn btn-default" aria-label="Justify"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></button>
							</div>							
							
						</div>
						
							
					</div>

					
				</div>				
				
				
			
			</div>
			<!-- fim aba Componentes -->
			
			<!-- aba Formulario -->			
			<div id="Formulario" class="tab-pane fade">
			
				<div class="push50"></div> 
				
				<div class="row">				
				
					<div class="col-md12 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-bordered">
							<div class="portlet-title">
								<div class="caption">
									<i class="glyphicon glyphicon-calendar"></i>
									<span class="caption-subject"> Nome da Tela</span>
								</div>
								<div class="actions">
									<a href="javascript:;" class="btn">
										<i class="glyphicon glyphicon-pencil"></i>
										Edit 
									</a>
									<a href="javascript:;" class="btn">
										<i class="glyphicon glyphicon-paperclip"></i>
										Add
									</a>
									<a href="javascript:;" class="btn btn-circle">
										<i class="glyphicon glyphicon-resize-full"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
							
								<div class="login-form">
								
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="appCode/">
									<!-- https://1000hz.github.io/bootstrap-validator/#validator-examples -->
									
									<fieldset>
										<legend>Dados Gerais</legend> 
										
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome</label>
															<input type="text" class="form-control input-sm" id="inputName" placeholder="Nome" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">SobreNome</label>
															<input type="text" class="form-control input-sm" id="inputName" placeholder="SobreNome" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Endereço</label>
															<input type="text" class="form-control input-sm" id="inputName" placeholder="endereco" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Número</label>
															<input type="text" class="form-control input-sm" id="inputName" placeholder="numero" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Bairro</label>
															<input type="text" class="form-control input-sm" id="inputName" placeholder="Bairro" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
												</div>
																			
									</fieldset>									
									
									<div class="push10"></div>
									<hr>	
									<div class="form-group text-right col-lg-12">
										
										  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
										  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
										  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
										  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
										
									</div>
									
									<input type="hidden" name="opcao" id="opcao" value="">		
									<input type="hidden" name="Hhabilitado" id="Hhabilitado" value="N">		
									
									<div class="push5"></div> 
									
									</form>
									
									<div class="push50"></div>
									
									<div class="col-lg-12">

										<div class="no-more-tables">
	
											<table class="table table-bordered table-striped table-hover">
											  <thead>
												<tr>
												  <th></th>
												  <th>First Name</th>
												  <th>Last Name</th>
												  <th>Username</th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
												  <th scope="row"><input type="radio"></th>
												  <td>Mark</td>
												  <td>Otto</td>
												  <td>@mdo</td>
												</tr>
												<tr>
												  <th scope="row"><input type="radio"></th>
												  <td>Jacob</td>
												  <td>Thornton</td>
												  <td>@fat</td>
												</tr>
												<tr>
												  <th scope="row"><input type="radio"></th>
												  <td>Larry</td>
												  <td>the Bird</td>
												  <td>@twitter</td>
												</tr>
											  </tbody>
											</table>

										</div>
										
									</div>										
								
								<div class="push10"></div>
								
								</div>								
							
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>					
					
					<div class="push20"></div> 
					
					<div class="well bs-component">
					
						<div class="page-header">
						<h2>Nome da Tela</h2>
						</div>

						<div class="login-form">
						<div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo111" />
                                </div>
                                <div class="col-md-6">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo112" />
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo113" />
                                </div>
                                <div class="col-md-4">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo114" />
                                </div>
                                <div class="col-md-4">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo115" />
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo116" />
                                </div>
                                <div class="col-md-3">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo117" />
                                </div>
                                <div class="col-md-3">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo118" />
                                </div>
                                <div class="col-md-3">
                                     <label class="control-label" for="campo1">Focused input</label>
                                     <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="campo119" />
                                </div>
                            </div>
						</div>

						<div class="form-group">
						  <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass" />
						  <label class="login-field-icon fui-lock" for="login-pass"></label>
						</div>

						<a class="btn btn-primary btn-lg btn-block" href="#">Log in</a>
						<a class="login-link" href="#">Lost your password?</a>
						</div>	
					  
					</div>

					
				</div>
			
			</div>
			<!-- fim aba Formulario -->			
			
			<!-- aba Tabelas -->			
			<div id="Tabelas" class="tab-pane fade">
			
				<div class="page-header">
				<h2>Grid</h2>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<label class="control-label" for="campo1">Coluna 6</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo1" required >
						 <div class="help-block with-errors"></div>
					</div>
					<div class="col-md-6">
						 <label class="control-label" for="campo2">Coluna 6</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo2" required >
						 <div class="help-block with-errors"></div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						 <label class="control-label" for="campo3">Coluna 4</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo3" />
					</div>
					<div class="col-md-4">
						 <label class="control-label" for="campo4">Coluna 4</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo4" />
					</div>
					<div class="col-md-4">
						 <label class="control-label" for="campo5">Coluna 4</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo5" />
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						 <label class="control-label" for="campo6">Coluna 3</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo6" />
					</div>
					<div class="col-md-3">
						 <label class="control-label" for="campo7">Coluna 3</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo7" />
					</div>
					<div class="col-md-3">
						 <label class="control-label" for="campo8">Coluna 3</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo8" />
					</div>
					<div class="col-md-3">
						 <label class="control-label" for="campo9">Coluna 3</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo9" />
					</div>
				</div>
			
				<div class="row">
					<div class="col-md-2">
						 <label class="control-label" for="campo10">Coluna 2</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo10" />
					</div>
					<div class="col-md-2">
						 <label class="control-label" for="campo11">Coluna 2</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo11" />
					</div>
					<div class="col-md-2">
						 <label class="control-label" for="campo12">Coluna 2</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo12" />
					</div>
					<div class="col-md-2">
						 <label class="control-label" for="campo13">Coluna 2</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo13" />
					</div>
					<div class="col-md-2">
						 <label class="control-label" for="campo14">Coluna 2</label>
						 <input type="text" class="form-control login-field input-sm" value="" placeholder="Descrição do campo" id="campo14" />
					</div>
					<div class="col-md-2">
						 <label class="control-label" for="campo15">Coluna 2</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo15" />
					</div>											
					
				</div>
			
				<div class="row">
					<div class="col-md-1">
						 <label class="control-label" for="campo16">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo16" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo17">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo17" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo18">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo18" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo19">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo19" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo20">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo20" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo21">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo21" />
					</div>											
					<div class="col-md-1">
						 <label class="control-label" for="campo22">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo22" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo23">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo23" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo24">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo24" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo25">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo25" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo26">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo26" />
					</div>
					<div class="col-md-1">
						 <label class="control-label" for="campo27">Coluna 1</label>
						 <input type="text" class="form-control input-sm" value="" placeholder="Descrição do campo" id="campo27" />
					</div>
					
				</div>			

				
				<div class="row">
			
				<div class="page-header">
				<h2>Tabelas</h2>
				</div>	
				
					<div class="col-lg-6">
					
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-bordered">
							<div class="portlet-body">
									
								<table class="table">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>First Name</th>
									  <th>Last Name</th>
									  <th>Username</th>
									</tr>
								  </thead>
								  <tbody>
									<tr>
									  <th scope="row">1</th>
									  <td>Mark</td>
									  <td>Otto</td>
									  <td>@mdo</td>
									</tr>
									<tr>
									  <th scope="row">2</th>
									  <td>Jacob</td>
									  <td>Thornton</td>
									  <td>@fat</td>
									</tr>
									<tr>
									  <th scope="row">3</th>
									  <td>Larry</td>
									  <td>the Bird</td>
									  <td>@twitter</td>
									</tr>
								  </tbody>
								</table>					
							
							</div>
						</div>
						<!-- END Portlet PORTLET-->	
				
					</div>
					
					<div class="col-lg-6">
					
						<div class="portlet portlet-bordered">
							<div class="portlet-body">

								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>First Name</th>
									  <th>Last Name</th>
									  <th>Username</th>
									</tr>
								  </thead>
								  <tbody>
									<tr>
									  <th scope="row">1</th>
									  <td>Mark</td>
									  <td>Otto</td>
									  <td>@mdo</td>
									</tr>
									<tr>
									  <th scope="row">2</th>
									  <td>Jacob</td>
									  <td>Thornton</td>
									  <td>@fat</td>
									</tr>
									<tr>
									  <th scope="row">3</th>
									  <td>Larry</td>
									  <td>the Bird</td>
									  <td>@twitter</td>
									</tr>
								  </tbody>
								</table>					
								
							</div>
						</div>
						<!-- END Portlet PORTLET-->	
				
					</div>
					
					<div class="push20"></div>
					
					<div class="col-lg-6">

						<div class="portlet portlet-bordered">
							<div class="portlet-body">
					
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>First Name</th>
									  <th>Last Name</th>
									  <th>Username</th>
									</tr>
								  </thead>
								  <tbody>
									<tr>
									  <th scope="row">1</th>
									  <td>Mark</td>
									  <td>Otto</td>
									  <td>@mdo</td>
									</tr>
									<tr>
									  <th scope="row">2</th>
									  <td>Jacob</td>
									  <td>Thornton</td>
									  <td>@fat</td>
									</tr>
									<tr>
									  <th scope="row">3</th>
									  <td>Larry</td>
									  <td>the Bird</td>
									  <td>@twitter</td>
									</tr>
								  </tbody>
								</table>	
								
							</div>
						</div>
						<!-- END Portlet PORTLET-->	
					
					</div>
					
					<div class="col-lg-6">
					
						<div class="portlet portlet-bordered">
							<div class="portlet-body">
						
								<table class="table table-bordered">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>First Name</th>
									  <th>Last Name</th>
									  <th>Username</th>
									</tr>
								  </thead>
								  <tbody>
									<tr>
									  <th scope="row">1</th>
									  <td>Mark</td>
									  <td>Otto</td>
									  <td>@mdo</td>
									</tr>
									<tr>
									  <th scope="row">2</th>
									  <td>Jacob</td>
									  <td>Thornton</td>
									  <td>@fat</td>
									</tr>
									<tr>
									  <th scope="row">3</th>
									  <td>Larry</td>
									  <td>the Bird</td>
									  <td>@twitter</td>
									</tr>
								  </tbody>
								</table>

							</div>
						</div>
						<!-- END Portlet PORTLET-->							
		
					</div>
					
					<div class="push20"></div>
					
					<div class="col-lg-12">
					
						<div class="no-more-tables">
						<div class="portlet portlet-bordered">
							<div class="portlet-body">
						
								<table class="table table-bordered table-striped table-hover">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>First Name</th>
									  <th>Last Name</th>
									  <th>Username</th>
									</tr>
								  </thead>
								  <tbody>
									<tr>
									  <th scope="row">1</th>
									  <td>Mark</td>
									  <td>Otto</td>
									  <td>@mdo</td>
									</tr>
									<tr>
									  <th scope="row">2</th>
									  <td>Jacob</td>
									  <td>Thornton</td>
									  <td>@fat</td>
									</tr>
									<tr>
									  <th scope="row">3</th>
									  <td>Larry</td>
									  <td>the Bird</td>
									  <td>@twitter</td>
									</tr>
								  </tbody>
								</table>

							</div>
						</div>
						<!-- END Portlet PORTLET-->	
						</div>
						
					</div>						
								
					
					
				</div>
				
			
			</div>
			<!-- fim aba Tabelas -->			
			
			<!-- aba Tipografia -->			
			<div id="Tipografia" class="tab-pane fade">

				<div class="row">
					<div class="page-header">
					<h2>Tipografy</h2>
					</div>

					<div class="col-lg-4">
					<div class="bs-component">
					<h1>Heading 1</h1>
					<h2>Heading 2</h2>
					<h3>Heading 3</h3>
					<h4>Heading 4</h4>
					<h5>Heading 5</h5>
					<h6>Heading 6</h6>
					<p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
					</div>
					</div>
					<div class="col-lg-4">
					<div class="bs-component">
					<h2>Example body text</h2>
					<p>Nullam quis risus eget <a href="http://bootswatch.com/flatly/#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
					<p><small>This line of text is meant to be treated as fine print.</small></p>
					<p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
					<p>The following snippet of text is <em>rendered as italicized text</em>.</p>
					<p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
					</div>

					</div>
					<div class="col-lg-4">
					<div class="bs-component">
					<h2>Emphasis classes</h2>
					<p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
					<p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
					<p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
					<p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
					<p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
					<p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
					</div>

					</div>	  
				</div><!-- end row -->
				
				
				<div class="row">
						
					<div class="page-header">
					  <h2>Helpers Classes</h2>
					</div>
					
					<div class="col-md-4">
						
						<p class="lead text-muted">Text</p>				
												
						<table class="table table-bordered">
							<thead>
								<tr>
								<th>Class</th>
								<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td>.text-muted</td>
								<td><span class="text-muted">This text is styled with class &#8220;text-muted&#8221;</span></td>
								</tr>
								<tr>
								<td>.text-primary</td>
								<td><span class="text-primary">This text is styled with class &#8220;text-primary&#8221;</span></td>
								</tr>
								<tr>
								<td>.text-success</td>
								<td><span class="text-success">This text is styled with class &#8220;text-success&#8221;</span></td>
								</tr>
								<tr>
								<td>.text-info</td>
								<td><span class="text-info">This text is styled with class &#8220;text-info&#8221;</span></td>
								</tr>
								<tr>
								<td>.text-warning</td>
								<td><span class="text-warning">This text is styled with class &#8220;text-warning&#8221;</span></td>
								</tr>
								<tr>
								<td>.text-danger</td>
								<td><span class="text-danger">This text is styled with class &#8220;text-danger&#8221;</span></td>
								</tr>
								
								<tr>
								<td>.lead</td>
								<td><span class="lead">Makes a paragraph stand out</span></td>
								</tr>
								<tr>
								<td>.small</td>
								<td><span class="small">Indicates smaller text (set to 85% of the size of the parent)</span></td>
								</tr>
								<tr>
								<td>.text-left</td>
								<td><span class="text-left">Indicates left-aligned text</span></td>
								</tr>
								<tr>
								<td>.text-right</td>
								<td><span class="text-right">Indicates right-aligned text</span></td>
								</tr>
								<tr>
								<td>.text-justify</td>
								<td><span class="text-justify">Indicates justified text</span></td>
								</tr>
								<tr>
								<td>.text-nowrap</td>
								<td><span class="text-nowrap">Indicates no wrap text</span></td>
								</tr>								
								<tr>
								<td>.text-lowercase</td>
								<td><span class="text-lowercase">Indicates lowercased text</span></td>
								</tr>
								<tr>
								<td>.text-uppercase</td>
								<td><span class="text-uppercase">Indicates uppercased text</span></td>
								</tr>
								<tr>
								<td>.text-capitalize</td>
								<td><span class="text-capitalize">Displays the text inside an <abbr> element in a slightly smaller font size</span></td>
								</tr>
								<tr>
								<td>.list-unstyled</td>
								<td><span class="list-unstyled">Removes the default list-style and left margin on list items. </span></td>
								</tr>
								<tr>
								<td>.list-inline</td>
								<td><span class="list-inline">Places all list items on a single line</span></td>
								</tr>
								<tr>
								<td>.dl-horizontal</td>
								<td><span class="dl-horizontal">Lines up the terms (<dt>) and descriptions (<dd>)</span></td>
								</tr>
								<tr>
								<td>.pre-scrollable</td>
								<td><span class="pre-scrollable">Makes a <pre> element scrollable</span></td>
								</tr>
							</tbody>
						</table>
				

					</div>
					
					<div class="col-md-4">
						
						<p class="lead text-muted">Background</p>

						<table class="table table-bordered">
							<thead>
								<tr>
								<th>Class</th>
								<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td>.bg-primary</td>
								<td class="bg-primary">This table cell is styled with class &#8220;bg-primary&#8221;</td>								
								</tr>
								<tr>
								<td>.bg-success</td>
								<td class="bg-success">This table cell is styled with class &#8220;bg-success&#8221;</td>								
								</tr>
								<tr>
								<td>.bg-info</td>
								<td class="bg-info">This table cell is styled with class &#8220;bg-info&#8221;</td>								
								</tr>
								<tr>
								<td>.bg-warning</td>
								<td class="bg-warning">This table cell is styled with class &#8220;bg-warning&#8221;</td>								
								</tr>
								<tr>
								<td>.bg-danger</td>
								<td class="bg-danger">This table cell is styled with class &#8220;bg-danger&#8221;</td>								
								</tr>
							</tbody>
						</table>

					</div>
					
					
					<div class="col-md-4">	

						<p class="lead text-muted">Other</p>						
						
						<table class="table table-bordered">
							<thead>
								<tr>
								<th>Class</th>
								<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td>.pull-left</td>
								<td>Floats an element to the left</td>
								</tr>
								<tr>
								<td>.pull-right</td>
								<td>Floats an element to the right</td>
								</tr>
								<tr>
								<td>.center-block</td>
								<td>Sets an element to display:block and center</td>
								</tr>
								<tr>
								<td>.clearfix</td>
								<td>Clears floats</td>
								</tr>
								<tr>
								<td>.show</td>
								<td>Forces an element to be shown</td>
								</tr>
								<tr>
								<td>.hidden</td>
								<td>Forces an element to be hidden</td>
								</tr>
								<tr>
								<td>.sr-only</td>
								<td>Hides an element to all devices except screen readers</td>
								</tr>
								<tr>
								<td>.sr-only-focusable</td>
								<td>Combine with .sr-only to show the element again when it is focused (e.g. by a keyboard-only user)</td>
								</tr>
								<tr>
								<td>.text-hide</td>
								<td>Helps replace an element&#8217;s text content with a background image</td>
								</tr>
								<tr>
								<td>.close</td>
								<td>Indicates a close icon</td>
								</tr>
								<tr>
								<td>.caret</td>
								<td>Indicates dropdown functionality (will reverse automatically in dropup menus)</td>								
								</tr>
							</tbody>
						</table>				

					</div>	
					
				</div><!-- end row -->
				
				
				
				
				<div class="row">
				
					<div class="page-header">
					<h2>Thumbnails</h2>
					</div>

					<div class="col-xs-6 col-md-3">
						<a href="#" class="thumbnail">
						  <img src="http://placehold.it/235x180" alt="Generic placeholder thumbnail">
						</a>
					</div>
					
					<div class="col-xs-6 col-md-3">
						<a href="#" class="thumbnail">
						  <img src="http://placehold.it/235x180" alt="Generic placeholder thumbnail">
						</a>
					</div>
					
					<div class="col-xs-6 col-md-3">
						<a href="#" class="thumbnail">
						  <img src="http://placehold.it/235x180" alt="Generic placeholder thumbnail">
						</a>
					</div>
					
					<div class="col-xs-6 col-md-3">
						<a href="#" class="thumbnail">
						  <img src="http://placehold.it/235x180" alt="Generic placeholder thumbnail">
						</a>
					</div>

					<div class="push20"></div> 	


				</div><!-- end row -->	

					<div class="row">

					<div class="page-header">
					<h2>Thumbnails Custom</h2>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
						  <img src="http://placehold.it/327x200" alt="Generic placeholder thumbnail">
						<div class="caption">
						<h3>Thumbnail label</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
						</div>
						</div>
					</div>
					
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
						  <img src="http://placehold.it/327x200" alt="Generic placeholder thumbnail">
						<div class="caption">
						<h3>Thumbnail label</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
						</div>
						</div>
					</div>
					
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
						  <img src="http://placehold.it/327x200" alt="Generic placeholder thumbnail">
						<div class="caption">
						<h3>Thumbnail label</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
						</div>
						</div>
					</div>

				</div><!-- end row --> 


				<div class="row">

					<div class="page-header">
					<h2>Media</h2>
					</div>

					<div class="media">
						<div class="media-left">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
						<div class="media-body">
						<h4 class="media-heading">Media heading</h4>
						Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
						</div>
					</div>
					
					<div class="media">
						<div class="media-left">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
						<div class="media-body">
						<h4 class="media-heading">Media heading</h4>
						Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
						<div class="media">
						<div class="media-left">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
						<div class="media-body">
						<h4 class="media-heading">Nested media heading</h4>
						Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
						</div>
						</div>
						</div>
					</div>
					
					<div class="media">
						<div class="media-body">
						<h4 class="media-heading">Media heading</h4>
						Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
						</div>
						<div class="media-right">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
					</div>
					
					<div class="media">
						<div class="media-left">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
						<div class="media-body">
						<h4 class="media-heading">Media heading</h4>
						Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
						</div>
						<div class="media-right">
						<a href="#">
						  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
						</a>
						</div>
					</div>

					<div class="push30"></div>

					<div class="page-header">
					<h2>Media List</h2>
					</div>

					<ul class="media-list">
						<li class="media">
							<div class="media-left">
							<a href="#">
							  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
							</a>
							</div>
							
							<div class="media-body">
							<h4 class="media-heading">Media heading</h4>
							<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
							<!-- Nested media object -->
							<div class="media">
							<div class="media-left">
							<a href="#">
							  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
							</a>
							</div>
							<div class="media-body">
							<h4 class="media-heading">Nested media heading</h4>
							Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
							<!-- Nested media object -->
							<div class="media">
							<div class="media-left">
							<a href="#">
							  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
							</a>
							</div>
							<div class="media-body">
							<h4 class="media-heading">Nested media heading</h4>
							Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
							</div>
							</div>
							</div>
							</div>
							<!-- Nested media object -->
							<div class="media">
							<div class="media-left">
							<a href="#">
							  <img src="http://placehold.it/64x64" alt="Generic placeholder thumbnail">
							</a>
							</div>
							<div class="media-body">
							<h4 class="media-heading">Nested media heading</h4>
							Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
							</div>
							</div>
							</div>
							
						</li>
					</ul>			

				</div><!-- end row -->
				
				
			</div>
			<!-- fim aba Tipografia -->			
			
			
			<!-- aba Extras -->			
			<div id="Extras" class="tab-pane fade">
			
			<div class="push50"></div>	

				<div class="row">
					
					<div class="col-md-6 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-bordered">
							<div class="portlet-title">
								<div class="caption caption-red">
									<i class="glyphicon glyphicon-cog"></i>
									<span class="caption-subject text-uppercase"> Portlet</span>
									<span class="caption-helper">weekly stats...</span>
								</div>
								<div class="actions">
									<a href="javascript:;" class="btn btn-red btn-circle active">
										<i class="glyphicon glyphicon-paperclip"></i>
									</a>
									<a href="javascript:;" class="btn btn-red btn-circle">
										<i class="glyphicon glyphicon-pencil"></i>
									</a>
									<a href="javascript:;" class="btn btn-red btn-circle">
										<i class="glyphicon glyphicon-trash"></i>
									</a>
									<a href="javascript:;" class="btn btn-red btn-circle">
										<i class="glyphicon glyphicon-search"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
								<h4>Heading Text</h4>
								<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>

					<div class="col-md-6 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-gray portlet-bordered">
							<div class="portlet-title">
								<div class="caption caption-purple">
									<i class="glyphicon glyphicon-cog"></i>
									<span class="caption-subject text-uppercase"> Pagination</span>
								</div>
								<div class="actions">
									<ul class="pagination">
										<li>
											<a href="#" aria-label="Previous">
												<span aria-hidden="true">«</span>
											</a>
										</li>
										<li><a href="#">1</a></li>
										<li class="active"><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li>
											<a href="#" aria-label="Next">
												<span aria-hidden="true">»</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="portlet-body">
								<h4>Heading Text</h4>
								<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>	
		
					<div class="col-md-6 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-bordered">
							<div class="portlet-title">
								<div class="caption">
									<i class="glyphicon glyphicon-search"></i>
									<span class="caption-subject text-uppercase"> Form Input</span>
									<span class="caption-helper">more samples...</span>
								</div>
								<div class="inputs">
									<div class="portlet-input input-inline input-medium">
										<div class="input-group">
											<input type="text" class="form-control input-circle-left" placeholder="search...">
											<span class="input-group-btn">
											<button class="btn btn-circle-right btn-default" type="submit">Go!</button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="portlet-body">
								<h4>Heading Text</h4>
								<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>

					<div class="col-md-6">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet portlet-bordered">
							<div class="portlet-title">
								<div class="caption caption-red">
									<i class="glyphicon glyphicon-pushpin"></i>
									<span class="caption-subject bold font-yellow-crusta uppercase">
									Tabs </span>
									<span class="caption-helper">more samples...</span>
								</div>
								<ul class="nav nav-tabs">
									<li>
										<a href="#portlet_tab3" data-toggle="tab">
										Tab 3 </a>
									</li>
									<li>
										<a href="#portlet_tab2" data-toggle="tab">
										Tab 2 </a>
									</li>
									<li class="active">
										<a href="#portlet_tab1" data-toggle="tab">
										Tab 1 </a>
									</li>
								</ul>
							</div>
							<div class="portlet-body">
								<div class="tab-content">
									<div class="tab-pane active" id="portlet_tab1">
										<h4>Tab 1 Content</h4>
										<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
									</div>
									<div class="tab-pane" id="portlet_tab2">
										<h4>Tab 2 Content</h4>
										<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
									</div>
									<div class="tab-pane" id="portlet_tab3">
										<h4>Tab 3 Content</h4>
										<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
										</div>
								</div>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>					
					
					<div class="push20"></div>
					
					<div class="col-md-6 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet ">
							<div class="portlet-title">
								<div class="caption">
									<i class="glyphicon glyphicon-calendar"></i>
									<span class="caption-subject text-uppercase"> Portlet</span>
									<span class="caption-helper">weekly stats...</span>
								</div>
								<div class="actions">
									<a href="javascript:;" class="btn">
										<i class="glyphicon glyphicon-pencil"></i>
										Edit 
									</a>
									<a href="javascript:;" class="btn">
										<i class="glyphicon glyphicon-paperclip"></i>
										Add
									</a>
									<a href="javascript:;" class="btn btn-circle">
										<i class="glyphicon glyphicon-resize-full"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
								<h4>Heading Text</h4>
								<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>

					<div class="col-md-6 margin-bottom-30">
						<!-- BEGIN Portlet PORTLET-->
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption caption-green">
									<i class="glyphicon glyphicon-knight"></i>
									<span class="caption-subject text-uppercase"> Portlet</span>
									<span class="caption-helper">monthly stats...</span>
								</div>
								<div class="actions">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn grey-salsa active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn grey-salsa">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn grey-salsa">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div>
								</div>
							</div>
							<div class="portlet-body">
								<h4>Heading Text</h4>
								<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							</div>
						</div>
						<!-- END Portlet PORTLET-->
					</div>
					
					
				</div>			
					

				<div class="row">
				
					<div class="col-md-6">
						<div class="page-header">
						  <h2>Jumbotron</h2>
						</div>

						<div class="jumbotron">
						  <h1>Hello, world!</h1>
						  <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
						  <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
						</div>			  

					</div>			
					
				</div>			
			
			</div>
			<!-- fim aba extras -->			

			
		</div>
		
					
	<div class="push100"></div>	
	
	<a href="#0" class="cd-top">Top</a> 
	 
    </div>
	<!-- end container -->    
	
	</div>
	<!-- end outContainer -->
	
	<!-- plugins -->
	<?php
           include 'jsLIb.php';
        ?>

    </body>
	
</html>