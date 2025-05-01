<?php

// ao adicionar legendas nos gráficos:

// - trazer no formulário a flag LOG_LEGENDA e recuperar via request

// - verificar versão do plugin de chart. O compatível é o 2.7.3
// <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script> 


// - carregar biblioteca da legenda via script
// <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

// - carregar plugin em cada instancia do grafico que for usar
// - instanciar legenda em cada dataset do grafico
// - ajustar cor de fundo pra ser a mesma que a cor da borda do grafico

?>
		
		<div class="push"></div>

			<ul class="nav nav-tabs">
			  <li><a data-toggle="tab" href="#Componentes">Componentes</a></li>
			  <li><a data-toggle="tab" href="#Formulario">Formulário</a></li>
			  <li><a data-toggle="tab" href="#Tabelas">Tabelas & Grid</a></li>
			  <li><a data-toggle="tab" href="#Tipografia">Tipografia</a></li>
			  <li><a data-toggle="tab" href="#Extras">Extras</a></li>
			  <li><a data-toggle="tab" href="#Widgets">Widgets</a></li>
			  <li class="active"><a data-toggle="tab" href="#Graficos">Gráficos</a></li>
			</ul>

			<div class="tab-content">
				<!-- aba Componentes -->
				<div id="Componentes" class="tab-pane fade">

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
								  

<style>

.rdo-grp {
  position: absolute;
  top: calc(50% - 10px);
}
.rdo-grp label {
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
  padding: 6px 8px;
  border-radius: 20px;
  float: left;
  transition: all 0.2s ease;
}
.rdo-grp label:hover {
  background: rgba(125,100,247,0.06);
}
.rdo-grp label:not(:last-child) {
  margin-right: 16px;
}
.rdo-grp label span {
  vertical-align: middle;
}
.rdo-grp label span:first-child {
  position: relative;
  display: inline-block;
  vertical-align: middle;
  width: 20px;
  height: 20px;
  background: #e8eaed;
  border-radius: 50%;
  transition: all 0.2s ease;
  margin-right: 8px;
}
.rdo-grp label span:first-child:after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  margin: 2px;
  background: #fff;
  border-radius: 50%;
  transition: all 0.2s ease;
}
.rdo-grp label:hover span:first-child {
  background: #7d64f7;
}
.rdo-grp input {
  display: none;
}
.rdo-grp input:checked + label span:first-child {
  background: #7d64f7;
}
.rdo-grp input:checked + label span:first-child:after {
  transform: scale(0.5);
}


</style>								  
									
								<div class="push 10"></div>
								
								<div class="col-md-4">
									<label for="inputName" class="control-label required">Tipo do Disparo</label>
									
									<div class="push50"></div>
									
									<div class="rdo-grp">
									  <input id="rdo1" type="radio" name="radio"/>
									  <label for="rdo1"><span></span><span>Ãšnica vez</span></label>
									  <input id="rdo2" type="radio" name="radio"/>
									  <label for="rdo2"><span></span><span>Com repetiÃ§Ã£o</span></label>
									</div>		
			
								</div>

								<div class="push 10"></div>								
								  
								  
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

							<div class="list-group">
								<h1 id="navbar">Gauge</h1>
								<canvas id="foo">guage</canvas>
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
										

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script type="text/javascript">
		$(function () {
			//http://eonasdan.github.io/bootstrap-datetimepicker/
			
			$('.dateFullPicker').datetimepicker().on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			$('.clockPicker').datetimepicker({
				 format: 'LT',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//relacionados
			$('#datetimepicker6').datetimepicker({
				 format: 'DD/MM/YYYY',
				});
			$('#datetimepicker7').datetimepicker({
				format: 'DD/MM/YYYY',
				useCurrent: false //Important! See issue #1075
			});
			
			$("#datetimepicker6").on("dp.change", function (e) {
				$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
			});
			
			$("#datetimepicker7").on("dp.change", function (e) {
				$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
			});

		});
	</script>

	<style type="text/css">
		/*
		-----ESQUEMA ÍCONE NO CAMPO
		*/
      .field-icon {
        float: right;
        right: 10;
        top: -25;
        position: relative;
        z-index: 2;
      }
    </style>
	
	<fieldset>
		<legend>Dados Gerais</legend> 

		<div class="row">

                    <div class="col-md-6">
        <div class="form-group">
        	<label for="inputName" class="control-label required">Nome</label>
          <input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" class="form-control input-sm" placeholder="Nome" required>
          <!-- SPAN QUE VAI CARREGAR O ÍCONE - DENTRO DO FORM GROUP -->
          <a href="javascript:void(0)" onclick="duvida()" style="color: unset; font-size: 12px;">
          	<span toggle="#EXEMPLO" class="fal fa-ellipsis-h-alt field-icon"></span>
          </a>
          <div class="help-block with-errors"></div>
        </div>
      </div>

			<div class="col-md-6">
				<label for="inputName" class="control-label required">SobreNome</label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" id="inputName" placeholder="SobreNome" data-error="Campo obrigatÃ³rio" required>
					<span class="input-group-addon" style="border:none;background-color:unset ">
						<button class="fal fa-info-circle" style="background-color:unset;border:none;font-size: 14px"></button>
					</span>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-md-6">
				<label for="inputName" class="control-label required">Endereço</label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" id="inputName" placeholder="endereco" data-error="Campo obrigatÃ³rio" required>
					<span class="input-group-addon" style="border:none;background-color:unset ">
						<button class="fal fa-angle-double-up" style="background-color:unset;border:none;font-size: 12px"></button>
					</span>
					<div class="help-block with-errors"></div>
				</div>
			</div>
			<div class="col-md-3">
				<label for="inputName" class="control-label">Número</label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" id="inputName" placeholder="numero" data-error="Campo obrigatÃ³rio">
					<span class="input-group-addon" style="border:none;background-color:unset ">
						<button class="fal fa-question" style="background-color:unset;border:none;font-size: 12px"></button>
					</span>
					<div class="help-block with-errors"></div>
				</div>
			</div>
			<div class="col-md-3">
				<label for="inputName" class="control-label">Bairro</label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" id="inputName" placeholder="numero" data-error="Campo obrigatÃ³rio">
					<span class="input-group-addon" style="border:none;background-color:unset ">
						<button onclick="duvida()" class="fal fa-ellipsis-h-alt" style="background-color:unset;border:none;font-size: 12px"></button>
					</span>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>

		<div class="row">


			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName" class="control-label">Data 1 (Picker)</label>
					<input type="text" class="form-control input-sm datePicker" name="data1" id="data1" value="">
				</div>
			</div>	

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName" class="control-label required">Data 2 (Picker Icon)</label>

					<div class='input-group date datePicker'>
						<input type='text' class="form-control input-sm" name="data2" id="data2" value=""/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>

				</div>
			</div>	

			<div class="col-md-3">
				<div class="form-group">
					<label for="inputName" class="control-label">Data 3 (Full Picker)</label>

					<div class='input-group date dateFullPicker'>
						<input type='text' class="form-control input-sm" name="data3" id="data3" value="" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>

				</div>
			</div>	

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName" class="control-label">Data 4 (Clock)</label>

					<div class='input-group date clockPicker'>
						<input type='text' class="form-control input-sm clockPicker" name="data4" id="data4" value="" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-time"></span>
						</span>
					</div>

				</div>
			</div>

			<div class="push10"></div>													

			<div class="col-md-2">
				<div class="form-group">
					<div class='input-group date' id='datetimepicker6'>
						<input type='text' class="form-control input-sm" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>	

			<div class="col-md-2">
				<div class="form-group">
					<div class='input-group date' id='datetimepicker7'>
						<input type='text' class="form-control input-sm" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>

			<div class="col-md-4">

				<select id="example-getting-started" multiple="multiple">
					<option value="cheese">Cheese</option>
					<option value="tomatoes">Tomatoes</option>
					<option value="mozarella">Mozzarella</option>
					<option value="mushrooms">Mushrooms</option>
					<option value="pepperoni">Pepperoni</option>
					<option value="onions">Onions</option>
				</select>

			</div>

			<script type="text/javascript" src="js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
			<link rel="stylesheet" href="js/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" type="text/css"/>

			<!-- http://davidstutz.de/bootstrap-multiselect/#further-examples -->

			<!-- Initialize the plugin: -->
			<script type="text/javascript">
				$(document).ready(function() {
					$('#example-getting-started').multiselect({
						buttonWidth: '100%'
					});
				});
			</script>													

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
	
							<div class="row">
							
								<div class="col-md-4">
									<label for="inputName" class="control-label required">Cliente</label>
									<div class="input-group">
									<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca" style="height:66px;" class="btn btn-primary btn-lg addBox" data-url="action.php?mod=<?php echo fnEncode(1062)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="AtivaÃ§Ã£o de Campanha - Busca Produtos"><i class="fa fa-search" aria-hidden="true" style="padding-top: 5px;" ></i></a>
									</span>
									<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-lg" style="border-radius:0 5px 5px 0;" placeholder="Procurar cliente...">
									<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
									</div>																
								</div>
								
								<div class="col-md-4">
									<label for="inputName" class="control-label required">Produto</label>
									<div class="input-group">
									<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca" style="height:66px;" class="btn btn-primary btn-lg addBox" data-url="action.php?mod=<?php echo fnEncode(1062)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="AtivaÃ§Ã£o de Campanha - Busca Produtos"><i class="fa fa-search" aria-hidden="true" style="padding-top: 5px;" ></i></a>
									</span>
									<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-lg leitura" style="border-radius:0 5px 5px 0;" readonly="readonly" placeholder="Procurar produto...">
									<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
									</div>																
								</div>
								
								<div class="col-md-3">
								
								</div>
											
											
							</div>

							<div class="push10"></div>							

							<div class="form-group">
							  <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass" />
							  <label class="login-field-icon fui-lock" for="login-pass"></label>
							</div>
							
							<div class="push10"></div>							

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
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo1" required >
							 <div class="help-block with-errors"></div>
						</div>
						<div class="col-md-6">
							 <label class="control-label" for="campo2">Coluna 6</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo2" required >
							 <div class="help-block with-errors"></div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							 <label class="control-label" for="campo3">Coluna 4</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo3" />
						</div>
						<div class="col-md-4">
							 <label class="control-label" for="campo4">Coluna 4</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo4" />
						</div>
						<div class="col-md-4">
							 <label class="control-label" for="campo5">Coluna 4</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo5" />
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							 <label class="control-label" for="campo6">Coluna 3</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo6" />
						</div>
						<div class="col-md-3">
							 <label class="control-label" for="campo7">Coluna 3</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo7" />
						</div>
						<div class="col-md-3">
							 <label class="control-label" for="campo8">Coluna 3</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo8" />
						</div>
						<div class="col-md-3">
							 <label class="control-label" for="campo9">Coluna 3</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo9" />
						</div>
					</div>
				
					<div class="row">
						<div class="col-md-2">
							 <label class="control-label" for="campo10">Coluna 2</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo10" />
						</div>
						<div class="col-md-2">
							 <label class="control-label" for="campo11">Coluna 2</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo11" />
						</div>
						<div class="col-md-2">
							 <label class="control-label" for="campo12">Coluna 2</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo12" />
						</div>
						<div class="col-md-2">
							 <label class="control-label" for="campo13">Coluna 2</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo13" />
						</div>
						<div class="col-md-2">
							 <label class="control-label" for="campo14">Coluna 2</label>
							 <input type="text" class="form-control login-field input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo14" />
						</div>
						<div class="col-md-2">
							 <label class="control-label" for="campo15">Coluna 2</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo15" />
						</div>											
						
					</div>
				
					<div class="row">
						<div class="col-md-1">
							 <label class="control-label" for="campo16">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo16" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo17">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo17" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo18">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo18" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo19">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo19" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo20">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo20" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo21">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo21" />
						</div>											
						<div class="col-md-1">
							 <label class="control-label" for="campo22">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo22" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo23">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo23" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo24">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo24" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo25">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo25" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo26">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo26" />
						</div>
						<div class="col-md-1">
							 <label class="control-label" for="campo27">Coluna 1</label>
							 <input type="text" class="form-control input-sm" value="" placeholder="DescriÃ§Ã£o do campo" id="campo27" />
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
													<span aria-hidden="true">Â«</span>
												</a>
											</li>
											<li><a href="#">1</a></li>
											<li class="active"><a href="#">2</a></li>
											<li><a href="#">3</a></li>
											<li>
												<a href="#" aria-label="Next">
													<span aria-hidden="true">Â»</span>
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
			
	
				<!-- aba Widgets -->			
				<div id="Widgets" class="tab-pane fade">
				
				<div class="push50"></div>	
				
				
					<div class="row">
					
						<div class="col-md-2">

      <div class="panelBox borda"><a href="#">
		<div class="addBox">
		
			<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 75px 0 75px 0;"></i>
	
		</div> 
      </a> 
      </div> 


						
						</div>			
						
					
						<div class="col-md-2">



      <div class="panel"><a href="">
        <div class="top primary"><i class="fa fa-database fa-3x iwhite" aria-hidden="true"></i>
          <h6>database</h6>    	     
        </div>
        <div class="bottom">
          <h2>7720</h2>
          <h6>rows</h6>
        </div>
		</a>
      </div>
						
						</div>			
						
					
						<div class="col-md-2">

      <div class="panel">
        <div class="top warning"><i class="fa fa-flag-checkered fa-3x iwhite" aria-hidden="true"></i>
          <h6>warnings</h6>
        </div>
        <div class="bottom">
          <h2>21</h2>
          <h6>service requests</h6>
        </div>
      </div>

						
						</div>							
					
						<div class="col-md-2">


	  
      <div class="panel">
        <div class="top success"><i class="fa fa-terminal fa-3x iwhite" aria-hidden="true"></i>
          <h6>Code Size</h6>
        </div>
        <div class="bottom">
          <h2>2034</h2>
          <h6>loc</h6>
        </div>
      </div>						
						
						
						</div>							
					
						<div class="col-md-2">



	  
      <div class="panel">
        <div class="top danger"><i class="fa fa-comments fa-3x iwhite" aria-hidden="true"></i>
          <h6>Communication</h6>
        </div>
        <div class="bottom">
          <h2>596</h2>
          <h6>comments</h6>
        </div>
      </div>						

						
						</div>							
					
						<div class="col-md-2">



	  
      <div class="panel">
        <div class="top"><i class="fa fa-tachometer fa-3x iwhite" aria-hidden="true"></i>
          <h6>Time Remaining</h6>
        </div>
        <div class="bottom">
          <h2>51</h2>
          <h6>minutes</h6>
        </div>
      </div>
						
						</div>			
						
					</div>	
					
				
				
				<div class="push50"></div>	
				
<style>				

/*
 * Component: Info Box
 * -------------------
 */
.info-box {
  display: block;
  min-height: 90px;  
  width: 100%;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  border-radius: 2px;
  margin-bottom: 15px;
}
.info-box small {
  font-size: 14px;
}
.info-box .progress {
  background: rgba(0, 0, 0, 0.2);
  margin: 5px -10px 5px -10px;
  height: 2px;
}
.info-box .progress,
.info-box .progress .progress-bar {
  border-radius: 0;
}
.info-box .progress .progress-bar {
  background: #fff;
}
.info-box-icon {
  border-top-left-radius: 2px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 2px;
  display: block;
  float: left;
  height: 90px;
  width: 90px;
  text-align: center;
  font-size: 45px;
  padding: 20px 0 0 0;
  color: #fff;  
}
.info-box-icon > img {
  max-width: 100%;
}
.info-box-content {
  padding: 5px 10px;
  margin-left: 90px;
}
.info-box-number {
  display: block;
  font-weight: bold;
  font-size: 18px;
}
.progress-description,
.info-box-text {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.info-box-text {
  text-transform: uppercase;
}
.info-box-more {
  display: block;
}
.progress-description {
  margin: 0;
}

/*
 * Component: Small Box
 * --------------------
 */
.small-box {
  border-radius: 2px;
  position: relative;
  display: block;
  margin-bottom: 20px;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
.small-box > .inner {
  padding: 10px;
}
.small-box > .small-box-footer {
  position: relative;
  text-align: center;
  padding: 3px 0;
  color: #fff;
  color: rgba(255, 255, 255, 0.8);
  display: block;
  z-index: 10;
  background: rgba(0, 0, 0, 0.1);
  text-decoration: none;
}
.small-box > .small-box-footer:hover {
  color: #fff;
  background: rgba(0, 0, 0, 0.15);
}
.small-box h3 {
  font-size: 38px;
  font-weight: bold;
  margin: 0 0 10px 0;
  white-space: nowrap;
  padding: 0;
}
.small-box p {
  font-size: 15px;
}
.small-box p > small {
  display: block;
  color: #f9f9f9;
  font-size: 13px;
  margin-top: 5px;
}
.small-box h3,
.small-box p {
  z-index: 5;
}
.small-box .icon {
  -webkit-transition: all 0.3s linear;
  -o-transition: all 0.3s linear;
  transition: all 0.3s linear;
  position: absolute;
  top: -10px;
  right: 10px;
  z-index: 0;
  font-size: 90px;
  color: rgba(0, 0, 0, 0.15);
}
.small-box:hover {
  text-decoration: none;
  color: #f9f9f9;
}
.small-box:hover .icon {
  font-size: 95px;
}
@media (max-width: 767px) {
  .small-box {
    text-align: center;
  }
  .small-box .icon {
    display: none;
  }
  .small-box p {
    font-size: 12px;
  }
}

/** tile stats **/
.tile-stats {
  position: relative;
  display: block;
  margin-bottom: 12px;
  border: 1px solid #E4E4E4;
  -webkit-border-radius: 5px;
  overflow: hidden;
  padding-bottom: 5px;
  -webkit-background-clip: padding-box;
  -moz-border-radius: 5px;
  -moz-background-clip: padding;
  border-radius: 5px;
  background-clip: padding-box;
  background: #FFF;
  transition: all 300ms ease-in-out; }

.tile-stats:hover .icon i {
  animation-name: tansformAnimation;
  animation-duration: .5s;
  animation-iteration-count: 1;
  color: rgba(58, 58, 58, 0.41);
  animation-timing-function: ease;
  animation-fill-mode: forwards;
  -webkit-animation-name: tansformAnimation;
  -webkit-animation-duration: .5s;
  -webkit-animation-iteration-count: 1;
  -webkit-animation-timing-function: ease;
  -webkit-animation-fill-mode: forwards;
  -moz-animation-name: tansformAnimation;
  -moz-animation-duration: .5s;
  -moz-animation-iteration-count: 1;
  -moz-animation-timing-function: ease;
  -moz-animation-fill-mode: forwards; }

.tile-stats .icon {
  width: 20px;
  height: 20px;
  color: #BAB8B8;
  position: absolute;
  right: 43px;
  top: 42px;
  z-index: 1; }

.tile-stats .icon i {
  margin: 0;
  font-size: 60px;
  line-height: 0;
  vertical-align: bottom;
  padding: 0; }

.tile-stats .count {
  font-size: 38px;
  font-weight: bold;
  line-height: 1.65857; }

.tile-stats .count, .tile-stats h3, .tile-stats p {
  position: relative;
  margin: 0;
  margin-left: 10px;
  z-index: 5;
  padding: 0; }

.tile-stats h3 {
  color: #BAB8B8; }

.tile-stats p {
  margin-top: 5px;
  font-size: 12px; }

.tile-stats > .dash-box-footer {
  position: relative;
  text-align: center;
  margin-top: 5px;
  padding: 3px 0;
  color: #fff;
  color: rgba(255, 255, 255, 0.8);
  display: block;
  z-index: 10;
  background: rgba(0, 0, 0, 0.1);
  text-decoration: none; }

.tile-stats > .dash-box-footer:hover {
  color: #fff;
  background: rgba(0, 0, 0, 0.15); }

.tile-stats > .dash-box-footer:hover {
  color: #fff;
  background: rgba(0, 0, 0, 0.15); }

table.tile_info {
  padding: 10px 15px; }

table.tile_info span.right {
  margin-right: 0;
  float: right;
  position: absolute;
  right: 4%; }

.tile:hover {
  text-decoration: none; }

.tile_header {
  border-bottom: transparent;
  padding: 7px 15px;
  margin-bottom: 15px;
  background: #E7E7E7; }

.tile_head h4 {
  margin-top: 0;
  margin-bottom: 5px; }

.tiles-bottom {
  padding: 5px 10px;
  margin-top: 10px;
  background: rgba(194, 194, 194, 0.3);
  text-align: left; }


</style>				
				
	
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box shadow">
            <span class="info-box-icon bg-primary"><i class="fa fa-envelope-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Messages</span>
              <span class="info-box-number">1,410</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fa fa-flag-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Bookmarks</span>
              <span class="info-box-number">410</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fa fa-files-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Uploads</span>
              <span class="info-box-number">13,648</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fa fa-star-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Likes</span>
              <span class="info-box-number">93,139</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->			
				
				
				
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Bookmarks</span>
              <span class="info-box-number">41,410</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Likes</span>
              <span class="info-box-number">41,410</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Events</span>
              <span class="info-box-number">41,410</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fa fa-comments-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Comments</span>
              <span class="info-box-number">41,410</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
				
				

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="fa fa-comments-o"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="fa fa-thumbs-o-up"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->	



                    <div class="row">
					
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-caret-square-o-right"></i>
                          </div>
                          <div class="count"><i class="fa fa-male"></i> 179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
					  
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-comments-o"></i>
                          </div>
                          <div class="count"><i class="fa fa-female"></i> 179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
					  
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-sort-amount-desc"></i>
                          </div>
                          <div class="count">179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
					  
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-check-square-o"></i>
                          </div>
                          <div class="count">179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
					  
                    </div>
					
<style>

/* WIDGETS */
.widget {
  width: 100%;
  float: left;
  margin: 0px;
  list-style: none;
  text-decoration: none;
  -moz-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
  box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
  color: #FFF;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  padding: 15px 10px;
  margin-bottom: 20px;
  min-height: 120px;
  position: relative;
}
.widget.widget-padding-sm,
.widget.widget-item-icon {
  padding: 10px 0px 5px;
}
.widget.widget-np {
  padding: 0px;
}
.widget.widget-no-subtitle {
  padding-top: 25px;
}
.widget.widget-carousel {
  padding-bottom: 0px;
  padding-top: 10px;
}
.widget.widget-default {
  background: #ffffff;
  background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f5f5f5));
  background: -webkit-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
  background: -o-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
  background: -ms-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
  background: linear-gradient(to bottom, #ffffff 0%, #f5f5f5 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f5f5f5, GradientType=0);
}
.widget.widget-primary {
  background: #33414e;
  background: -moz-linear-gradient(top, #33414e 0%, #29343f 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #33414e), color-stop(100%, #29343f));
  background: -webkit-linear-gradient(top, #33414e 0%, #29343f 100%);
  background: -o-linear-gradient(top, #33414e 0%, #29343f 100%);
  background: -ms-linear-gradient(top, #33414e 0%, #29343f 100%);
  background: linear-gradient(to bottom, #33414e 0%, #29343f 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#33414e, endColorstr=#29343f, GradientType=0);
}
.widget.widget-success {
  background: #95b75d;
  background: -moz-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #95b75d), color-stop(100%, #89ad4d));
  background: -webkit-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
  background: -o-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
  background: -ms-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
  background: linear-gradient(to bottom, #95b75d 0%, #89ad4d 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#95b75d, endColorstr=#89ad4d, GradientType=0);
}
.widget.widget-info {
  background: #3fbae4;
  background: -moz-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3fbae4), color-stop(100%, #29b2e1));
  background: -webkit-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
  background: -o-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
  background: -ms-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
  background: linear-gradient(to bottom, #3fbae4 0%, #29b2e1 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#3fbae4, endColorstr=#29b2e1, GradientType=0);
}
.widget.widget-warning {
  background: #fea223;
  background: -moz-linear-gradient(top, #fea223 0%, #fe970a 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fea223), color-stop(100%, #fe970a));
  background: -webkit-linear-gradient(top, #fea223 0%, #fe970a 100%);
  background: -o-linear-gradient(top, #fea223 0%, #fe970a 100%);
  background: -ms-linear-gradient(top, #fea223 0%, #fe970a 100%);
  background: linear-gradient(to bottom, #fea223 0%, #fe970a 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#fea223, endColorstr=#fe970a, GradientType=0);
}
.widget.widget-danger {
  background: #b64645;
  background: -moz-linear-gradient(top, #b64645 0%, #a43f3e 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #b64645), color-stop(100%, #a43f3e));
  background: -webkit-linear-gradient(top, #b64645 0%, #a43f3e 100%);
  background: -o-linear-gradient(top, #b64645 0%, #a43f3e 100%);
  background: -ms-linear-gradient(top, #b64645 0%, #a43f3e 100%);
  background: linear-gradient(to bottom, #b64645 0%, #a43f3e 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#b64645, endColorstr=#a43f3e, GradientType=0);
}
.widget .widget-title,
.widget .widget-subtitle,
.widget .widget-int,
.widget .widget-big-int {
  width: 100%;
  float: left;
  text-align: center;
}
.widget .widget-title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 5px;
  line-height: 20px;
  text-transform: uppercase;
}
.widget .widget-subtitle {
  font-size: 12px;
  font-weight: 400;
  margin-bottom: 5px;
  line-height: 15px;
  color: #EEE;
}
.widget .widget-int {
  font-size: 32px;
  line-height: 40px;
  font-weight: bold;
  font-family: arial;
}
.widget .widget-big-int {
  font-size: 42px;
  line-height: 45px;
  font-weight: 300;
}
.widget .widget-item-left {
  margin-left: 10px;
  float: left;
  width: 100px;
}
.widget .widget-item-right {
  margin-right: 10px;
  float: right;
  width: 100px;
}
.widget.widget-item-icon .widget-item-left,
.widget.widget-item-icon .widget-item-right {
  width: 70px;
  padding: 20px 0px;
  text-align: center;
}
.widget.widget-item-icon .widget-item-left {
  border-right: 1px solid rgba(0, 0, 0, 0.1);
  margin-right: 10px;
  padding-right: 10px;
}
.widget.widget-item-icon .widget-item-right {
  border-left: 1px solid rgba(0, 0, 0, 0.1);
  margin-left: 10px;
  padding-left: 10px;
}
.widget .widget-item-left .fa,
.widget .widget-item-right .fa,
.widget .widget-item-left .glyphicon,
.widget .widget-item-right .glyphicon {
  font-size: 60px;
}
.widget .widget-data {
  padding-left: 120px;
}
.widget .widget-data-left {
  padding-right: 120px;
}
.widget.widget-item-icon .widget-data {
  padding-left: 90px;
}
.widget.widget-item-icon .widget-data-left {
  padding-right: 90px;
  padding-left: 10px;
}
.widget .widget-data .widget-title,
.widget .widget-data-left .widget-title,
.widget .widget-data .widget-subtitle,
.widget .widget-data-left .widget-subtitle,
.widget .widget-data .widget-int,
.widget .widget-data-left .widget-int,
.widget .widget-data .widget-big-int,
.widget .widget-data-left .widget-big-int {
  text-align: left;
}
.widget .widget-controls a {
  position: absolute;
  width: 30px;
  height: 30px;
  text-align: center;
  line-height: 27px;
  color: #FFF;
  border: 1px solid #FFF;
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
  -webkit-transition: all 200ms ease;
  -moz-transition: all 200ms ease;
  -ms-transition: all 200ms ease;
  -o-transition: all 200ms ease;
  transition: all 200ms ease;
  opacity: 0.4;
  filter: alpha(opacity = 40);
}
.widget .widget-controls a.widget-control-left {
  left: 10px;
  top: 10px;
}
.widget .widget-controls a.widget-control-right {
  right: 10px;
  top: 10px;
}
.widget .widget-controls a:hover {
  opacity: 1;
  filter: alpha(opacity = 100);
}
.widget .widget-buttons {
  float: left;
  width: 100%;
  text-align: center;
  padding-top: 3px;
  margin-top: 5px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}
.widget .widget-buttons a {
  position: relative;
  display: inline-block;
  line-height: 30px;
  font-size: 21px;
}
.widget .widget-buttons .col {
  width: 100%;
  float: left;
}
.widget .widget-buttons.widget-c2 .col {
  width: 50%;
}
.widget .widget-buttons.widget-c3 .col {
  width: 33.333333%;
}
.widget .widget-buttons.widget-c4 .col {
  width: 25%;
}
.widget .widget-buttons.widget-c5 .col {
  width: 20%;
}
.widget.widget-primary .widget-buttons a {
  color: #010101;
  border-color: #010101;
}
.widget.widget-primary .widget-buttons a:hover {
  color: #000000;
}
.widget.widget-success .widget-buttons a {
  color: #51672e;
  border-color: #51672e;
}
.widge.widget-success .widget-buttons a:hover {
  color: #435526;
}
.widget.widget-info .widget-buttons a {
  color: #14708f;
  border-color: #14708f;
}
.widget.widget-info .widget-buttons a:hover {
  color: #115f79;
}
.widget.widget-warning .widget-buttons a {
  color: #a15e01;
  border-color: #a15e01;
}
.widget.widget-warning .widget-buttons a:hover {
  color: #874f01;
}
.widget.widget-danger .widget-buttons a {
  color: #5a2222;
  border-color: #5a2222;
}
.widget.widget-danger .widget-buttons a:hover {
  color: #471b1b;
}
.plugin-clock span {
  -webkit-animation: pulsate 1s ease-out;
  -webkit-animation-iteration-count: infinite;
  -moz-animation: pulsate 1s ease-out;
  -moz-animation-iteration-count: infinite;
  animation: pulsate 1s ease-out;
  animation-iteration-count: infinite;
  opacity: 0.0;
  margin-right: 2px;
}
.widget.widget-default {
  color: #434a54;
}
.widget.widget-default .widget-subtitle {
  color: #434a54;
}
.widget.widget-default .widget-controls a {
  color: #434a54;
  border-color: #434a54;
}

/* TILES */
.tile {
  width: 100%;
  float: left;
  margin: 0px;
  list-style: none;
  text-decoration: none;
  font-size: 38px;
  font-weight: 300;
  color: #FFF;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 20px;
  min-height: 100px;
  position: relative;
  border: 1px solid #D5D5D5;
  text-align: center;
}
.tile.tile-valign {
  line-height: 75px;
}
.tile.tile-default {
  background: #FFF;
  color: #656d78;
}
.tile.tile-default:hover {
  background: #FAFAFA;
}
.tile.tile-primary {
  background: #33414e;
  border-color: #33414e;
}
.tile.tile-primary:hover {
  background: #2f3c48;
}
.tile.tile-success {
  background: #95b75d;
  border-color: #95b75d;
}
.tile.tile-success:hover {
  background: #90b456;
}
.tile.tile-warning {
  background: #fea223;
  border-color: #fea223;
}
.tile.tile-warning:hover {
  background: #fe9e19;
}
.tile.tile-danger {
  background: #b64645;
  border-color: #b64645;
}
.tile.tile-danger:hover {
  background: #af4342;
}
.tile.tile-info {
  background: #3fbae4;
  border-color: #3fbae4;
}
.tile.tile-info:hover {
  background: #36b7e3;
}
.tile:hover {
  text-decoration: none;
  color: #FFF;
}
.tile.tile-default:hover {
  color: #656d78;
}
.tile .fa {
  font-size: 52px;
  line-height: 74px;
}
.tile p {
  font-size: 14px;
  margin: 0px;
}
.tile .informer {
  position: absolute;
  left: 5px;
  top: 5px;
  font-size: 12px;
  color: #FFF;
  line-height: 14px;
}
.tile .informer.informer-default {
  color: #FFF;
}
.tile .informer.informer-primary {
  color: #33414e;
}
.tile .informer.informer-success {
  color: #95b75d;
}
.tile .informer.informer-info {
  color: #3fbae4;
}
.tile .informer.informer-warning {
  color: #fea223;
}
.tile .informer.informer-danger {
  color: #b64645;
}
.tile .informer .fa {
  font-size: 14px;
  line-height: 16px;
}
.tile .informer.dir-tr {
  left: auto;
  right: 5px;
}
.tile .informer.dir-bl {
  top: auto;
  bottom: 5px;
}
.tile .informer.dir-br {
  left: auto;
  top: auto;
  right: 5px;
  bottom: 5px;
}
/* EOF TILES */

</style>
                    <div class="row">
					
                        <div class="col-md-3">
                            
                            <!-- START WIDGET MESSAGES -->
                            <div class="widget widget-default widget-item-icon" onclick="location.href='pages-messages.html';">
                                <div class="widget-item-left">
                                    <span class="fa fa-envelope"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-int num-count">48</div>
                                    <div class="widget-title">New messages</div>
                                    <div class="widget-subtitle">In your mailbox</div>
                                </div>      
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                            
                        </div>
						
                        <div class="col-md-1">
                            
							<label class="switch">
							<input type="checkbox" class="switch" value="1" checked/>
							<span></span>
							</label> 
                            
                        </div>	
						
                        <div class="col-md-2">
       					
							<input type="checkbox" id="chk1" data-group-cls="btn-group-sm" checked>
                            
                        </div>	

						
                    </div>
								
					<div class="push20"></div>	
				
                   <!-- TILES -->                
                    <div class="row">
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-danger tile-valign"><span class="fa fa-laptop"></span></a>                        
                        </div>                    
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-success tile-valign"><span class="fa fa-calendar"></span></a>
                        </div>                                        
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-warning tile-valign">New
                                <div class="informer informer-default dir-bl"><span class="fa fa-globe"></span> Lates Somethink</div>
                            </a>                        
                        </div>
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-info">
                                15
                                <p>September</p>                            
                                <div class="informer informer-default dir-tr"><span class="fa fa-calendar"></span></div>
                            </a>                        
                        </div>
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-default">
                                57%
                                <p>New Visitors</p>                            
                                <div class="informer informer-danger dir-tr"><span class="fa fa-caret-down"></span></div>
                            </a>                        
                        </div>
                        <div class="col-md-2">                        
                            <a href="#" class="tile tile-primary">
                                $25
                                <p>Buy It</p>                            
                                <div class="informer informer-default"><span class="fa fa-shopping-cart"></span></div>
                            </a>                        
                        </div>
                    </div>                       
                    <div class="row">
                        <div class="col-md-3">                        
                            <a href="#" class="tile tile-default">
                                254
                                <p>Sales today</p>
                                <div class="informer informer-primary">08.09.14</div>
                                <div class="informer informer-success dir-tr"><span class="fa fa-caret-up"></span></div>
                            </a>                        
                        </div>
                        <div class="col-md-3">                        
                            <a href="#" class="tile tile-primary">
                                6,432
                                <p>Visitors Today</p>
                                <div class="informer informer-warning"><span class="fa fa-caret-down"></span></div>
                                <div class="informer informer-default dir-tr">10.09.14</div>
                            </a>
                        </div>
                        <div class="col-md-3">                        
                            <a href="#" class="tile tile-success tile-valign">9.5gb
                                <div class="informer informer-default dir-tr"><span class="fa fa-cloud"></span></div>
                                <div class="informer informer-default dir-bl">Free Disk Space</div>
                            </a>                                                    
                        </div>
                        <div class="col-md-3">                        
                            <a href="#" class="tile tile-info tile-valign">
                                1,153
                                <div class="informer informer-default">Registred Users</div>
                                <div class="informer informer-default dir-br"><span class="fa fa-users"></span></div>
                            </a>                            
                        </div>
                    </div>         
                    <!-- END TILES -->
				
				
                    <!-- WIDGETS -->
                    <div class="row">
                        <div class="col-md-3">

                            <div class="widget widget-primary">
                                <div class="widget-title">TOTAL</div>
                                <div class="widget-subtitle">26/08/2014</div>
                                <div class="widget-int">$ <span data-toggle="counter" data-to="1564">1,564</span></div>
                                <div class="widget-controls">
                                    <a href="#" class="widget-control-left"><span class="fa fa-upload"></span></a>
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">

                            <div class="widget widget-success widget-no-subtitle">
                                <div class="widget-big-int">$ <span class="num-count">4,381</span></div>                            
                                <div class="widget-subtitle">Latest transaction</div>
                                <div class="widget-controls">
                                    <a href="#" class="widget-control-left"><span class="fa fa-cloud"></span></a>
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                        

                        </div>
                        <div class="col-md-3">

                            <div class="widget widget-danger widget-padding-sm">
                                <div class="widget-big-int plugin-clock">00:00</div>                            
                                <div class="widget-subtitle plugin-date">Loading...</div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>                            
                                <div class="widget-buttons widget-c3">
                                    <div class="col">
                                        <a href="#"><span class="fa fa-clock-o"></span></a>
                                    </div>
                                    <div class="col">
                                        <a href="#"><span class="fa fa-bell"></span></a>
                                    </div>
                                    <div class="col">
                                        <a href="#"><span class="fa fa-calendar"></span></a>
                                    </div>
                                </div>                            
                            </div>                        

                        </div>
                        <div class="col-md-3">

                            <div class="widget widget-info widget-padding-sm">                            
                                <div class="widget-item-left">
									<!-- espaÃ§o grafico pizza -->
									
                                </div>
                                <div class="widget-data">
                                    <div class="widget-big-int"><span class="num-count">80</span>%</div>
                                    <div class="widget-title">Disk Space</div>
                                    <div class="widget-subtitle">Total free space</div>                                
                                </div>                            
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>                        

                        </div>

                    </div>
                    <div class="row">
					
                        <div class="col-md-3">

                            <div class="widget widget-primary widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-user"></span>
                                </div>
                                <div class="widget-data">
                                    <div class="widget-int num-count">599</div>
                                    <div class="widget-title">Registred users</div>
                                    <div class="widget-subtitle">On our website and app</div>
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="widget widget-success widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-globe"></span>
                                </div>
                                <div class="widget-data">
                                    <div class="widget-int num-count">6,953</div>
                                    <div class="widget-title">Total visitors</div>
                                    <div class="widget-subtitle">That visited our site today</div>
                                </div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                </div>                            
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="widget widget-warning widget-item-icon">
                                <div class="widget-item-right">
                                    <span class="fa fa-envelope"></span>
                                </div>                             
                                <div class="widget-data-left">
                                    <div class="widget-int num-count">418</div>
                                    <div class="widget-title">New messages</div>
                                    <div class="widget-subtitle">In your mailbox</div>
                                </div>                                     
                            </div>

                        </div>

                    </div>
                    <!-- END WIDGETS -->
				
					<div class="push30"></div>	
<style>					

/*state overview*/

.state-overview .symbol, .state-overview .value {
    display: inline-block;
    text-align: center;
}

.state-overview .value  {
    float: right;

}

.state-overview .value h1, .state-overview .value p  {
    margin: 0;
    padding: 0;
    color: #c6cad6;
}

.state-overview .value h1 {
    font-weight: 300;
}

.state-overview .symbol i {
    color: #fff;
    font-size: 50px;
}

.state-overview .symbol {
    width: 40%;
    padding: 25px 15px;
    -webkit-border-radius: 4px 0px 0px 4px;
    border-radius: 4px 0px 0px 4px;
}

.state-overview .value {
    width: 58%;
    padding-top: 21px;
}

.state-overview .terques {
    background: #6ccac9;
}

.state-overview .red {
    background: #ff6c60;
}

.state-overview .yellow {
    background: #f8d347;
}

.state-overview .blue {
    background: #57c8f2;
}




ul.summary-list {
    display: inline-block;
    padding-left:0 ;
    width: 100%;
    margin-bottom: 0;
}

ul.summary-list > li {
    display: inline-block;
    width: 19.5%;
    text-align: center;
}

ul.summary-list > li > a > i {
    display:block;
    font-size: 18px;
    padding-bottom: 5px;
}

ul.summary-list > li > a {
    padding: 10px 0;
    display: inline-block;
    color: #818181;
}

ul.summary-list > li  {
    border-right: 1px solid #eaeaea;
}

ul.summary-list > li:last-child  {
    border-right: none;
}


</style>					
					
					<div class="row state-overview">
						<div class="col-lg-3 col-sm-6">
						  <section class="panel">
							  <div class="symbol terques">
								  <i class="fa fa-user"></i>
							  </div>
							  <div class="value">
								  <h1 class="count">495</h1>
								  <p>New Users</p>
							  </div>
						  </section>
						</div>
						<div class="col-lg-3 col-sm-6">
						  <section class="panel">
							  <div class="symbol red">
								  <i class="fa fa-tags"></i>
							  </div>
							  <div class="value">
								  <h1 class=" count2">947</h1>
								  <p>Sales</p>
							  </div>
						  </section>
						</div>
						<div class="col-lg-3 col-sm-6">
						  <section class="panel">
							  <div class="symbol yellow">
								  <i class="fa fa-shopping-cart"></i>
							  </div>
							  <div class="value">
								  <h1 class=" count3">328</h1>
								  <p>New Order</p>
							  </div>
						  </section>
						</div>
						<div class="col-lg-3 col-sm-6">
						  <section class="panel">
							  <div class="symbol blue">
								  <i class="fa fa-bar-chart-o"></i>
							  </div>
							  <div class="value">
								  <h1 class=" count4">10328</h1>
								  <p>Total Profit</p>
							  </div>
						  </section>
						</div>
					</div>					
					
					
					<div class="push30"></div>	
					
						<div class="row">
                          <div class="col-lg-12">
                              <section class="panel">
                                  <div class="panel-body">
                                      <ul class="summary-list">
                                          <li>
                                              <a href="javascript:;">
                                                  <i class=" fa fa-shopping-cart text-primary"></i>
                                                  1 Purchase
                                              </a>
                                          </li>
                                          <li>
                                              <a href="javascript:;">
                                                  <i class="fa fa-envelope text-info"></i>
                                                  15 Emails
                                              </a>
                                          </li>
                                          <li>
                                              <a href="javascript:;">
                                                  <i class=" fa fa-picture-o text-muted"></i>
                                                  2 Photo Upload
                                              </a>
                                          </li>
                                          <li>
                                              <a href="javascript:;">
                                                  <i class="fa fa-tags text-success"></i>
                                                  19 Sales
                                              </a>
                                          </li>
                                          <li>
                                              <a href="javascript:;">
                                                  <i class="fa fa-microphone text-danger"></i>
                                                  4 Audio
                                              </a>
                                          </li>
                                      </ul>
                                  </div>
                              </section>
                          </div>
                      </div>					
					
					<div class="push50"></div>

					<div class="row">
						<!-- ========================= SECTION CONTENT ========================= -->

<style type="text/css">
	
img {
  max-width: 100%; }

/* ============ text styles and paragraph ==============  */
body, form {
  font-size: 13px; }

.section-intro {
  background-color: #eee;
  padding: 40px; }

.title-intro {
  text-align: center;
  color: #2DA7B0;
  margin: 7px 0; }

/* ================ SECTION FOOTER ==================  */
.section-content {
  padding: 15px 0; }

.title-content {
  border-bottom: 1px solid #ddd;
  color: #999;
  margin-bottom: 30px;
  padding: 7px 0; }

.section-footer {
  padding: 30px 0;
  background: #ddd;
  border-top: 1px solid #ccc; }

/* =================  INFOBOX =================== */
.infobox {
  position: relative;
  min-height: 90px;
  border-radius: 4px;
  margin-bottom: 15px; }

.icon-wrap {
  position: relative;
  display: block;
  height: 90px;
  width: 90px;
  text-align: center;
  font-size: 45px;
  line-height: 90px; }

.text-wrap {
  overflow: hidden; }

/*  --------------- infobox simple --------------- */
.infobox-simple .icon-wrap {
  top: 10px;
  float: left;
  background: rgba(0, 0, 0, 0.2); }
.infobox-simple .text-wrap {
  padding-left: 15px; }
.infobox-simple .title {
  margin-bottom: 5px; }

/*  ---------------  infobox rect --------------- */
.infobox-rect .icon-wrap {
  float: left;
  color: rgba(255, 255, 255, 0.7);
  background: rgba(0, 0, 0, 0.2); }
.infobox-rect .text-wrap {
  padding: 7px 15px; }

/*  --------------- infobox right --------------- */
.infobox-right {
  background-color: #ddd;
  padding: 15px; }
  .infobox-right .icon-wrap {
    float: left;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(0, 0, 0, 0.2); }
  .infobox-right .text-wrap {
    text-align: right; }

/*  --------------- infobox more --------------- */
.infobox-more {
  overflow: hidden;
  background-color: #ddd; }
  .infobox-more .title {
    margin-top: 0;
    font-size: 20px;
    font-weight: bold; }
  .infobox-more p {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px; }
  .infobox-more .text-wrap {
    padding: 15px; }
  .infobox-more .icon-wrap {
    -webkit-transition: all .3s linear;
    -o-transition: all .3s linear;
    transition: all .3s linear;
    position: absolute;
    top: -10px;
    right: 10px;
    z-index: 0;
    font-size: 90px;
    color: rgba(0, 0, 0, 0.15); }
  .infobox-more .infobox-link {
    position: relative;
    text-align: center;
    padding: 3px 0;
    color: #fff;
    color: rgba(255, 255, 255, 0.8);
    display: block;
    background: rgba(0, 0, 0, 0.1);
    text-decoration: none; }
    .infobox-more .infobox-link:hover {
      color: #fff;
      background: rgba(0, 0, 0, 0.15); }

/*  --------------- infobox center --------------- */
.infobox-center {
  margin-top: 15px;
  background-color: #ddd;
  padding: 15px; }
  .infobox-center .icon-wrap {
    left: 50%;
    transform: translateX(-50%);
    top: -30px;
    position: absolute;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(0, 0, 0, 0.2); }
  .infobox-center .text-wrap {
    text-align: center;
    margin-top: 15px; }
  .infobox-center .title {
    margin-bottom: 0px;
    font-size: 20px; }
  .infobox-center p {
    margin-bottom: 0; }

/* =================  DEFINED VARIABLES =================== */
.icon-sm {
  width: 60px;
  height: 60px;
  line-height: 60px;
  font-size: 32px; }

.icon-lg {
  width: 80px;
  height: 80px;
  line-height: 80px;
  font-size: 42px; }

.round-corner {
  border-radius: 15px;
  -moz-border-radius: 15px;
  -webkit-border-radius: 15px; }

.round {
  border-radius: 100%;
  -moz-border-radius: 100%;
  -webkit-border-radius: 100%; }

.bg-green, .bg-red, .bg-blue {
  color: #fff !important; }

.bg-green {
  background-color: #72c02c !important; }

.bg-red {
  background-color: #e74c3c !important; }

.bg-blue {
  background-color: #2DA7B0 !important; }

</style>

						<section class="section-content">
						<div class="container">

						<h3 class="title-content">Infobox simple</h3>
						<div class="row">
							<div class="col-sm-4">
								<figure class="infobox infobox-simple">
									<span class="icon-wrap icon-sm round bg-blue"><i class="fa fa-shopping-cart"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Total sales</h4>
										<p>163,921</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
							<div class="col-sm-4">
								<figure class="infobox infobox-simple">
									<span class="icon-wrap icon-sm round bg-green"><i class="fa fa-users"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Registered users</h4>
										<p>1 424  </p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
						    <div class="col-sm-4">
								<figure class="infobox infobox-simple">
									<span class="icon-wrap icon-sm round bg-red"><i class="fa fa-gears"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Number of something</h4>
										<p>164</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div> <!-- col // -->
						</div><!-- row // -->

						<h3 class="title-content">Infobox rect</h3>
						<div class="row">
							<div class="col-sm-4">
								<figure class="infobox infobox-rect bg-blue">
									<span class="icon-wrap"><i class="fa fa-shopping-cart"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Total sales</h4>
										<p>163,921</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
							<div class="col-sm-4">
								<figure class="infobox infobox-rect bg-green">
									<span class="icon-wrap"><i class="fa fa-users"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Registered users</h4>
										<p>1 424  </p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
						    <div class="col-sm-4">
								<figure class="infobox infobox-rect bg-red">
									<span class="icon-wrap"><i class="fa fa-gears"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Number of something</h4>
										<p>164</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div> <!-- col // -->
						</div><!-- row // -->



						<h3 class="title-content">Infobox right</h3>
						<div class="row">
							<div class="col-sm-4">
								<figure class="infobox infobox-right">
									<span class="icon-wrap  icon-sm round bg-blue"><i class="fa fa-shopping-cart"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Total sales</h4>
										<p>163,921</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
							<div class="col-sm-4">
								<figure class="infobox infobox-right">
									<span class="icon-wrap  icon-sm round bg-green"><i class="fa fa-users"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Registered users</h4>
										<p>1 424 $ </p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
						    <div class="col-sm-4">
								<figure class="infobox infobox-right">
									<span class="icon-wrap icon-sm round bg-red"><i class="fa fa-gears"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">Number of something</h4>
										<p>164</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div> <!-- col // -->
						</div><!-- row // -->


						<h3 class="title-content">Infobox more</h3>
						<div class="row">
							<div class="col-sm-4">
								<figure class="infobox infobox-more bg-blue ">
									<span class="icon-wrap  icon-lg"><i class="fa fa-shopping-cart"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title"> 163,921</h4>
										<p> Total sales</p>
									</figcaption>
									<a href="#" class="infobox-link">More details</a>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
							<div class="col-sm-4">
								<figure class="infobox infobox-more  bg-green">
									<span class="icon-wrap   icon-lg"><i class="fa fa-users"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title"> 1 424</h4>
										<p> Registered users </p>
									</figcaption>
									<a href="#" class="infobox-link">More details</a>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
						    <div class="col-sm-4">
								<figure class="infobox infobox-more bg-red">
									<span class="icon-wrap icon-lg"><i class="fa fa-gears"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">164 </h4>
										<p>Number of something</p>
									</figcaption>
									<a href="#" class="infobox-link">More details</a>
								</figure> <!-- infobox // -->
							</div> <!-- col // -->
						</div><!-- row // -->


						<h3 class="title-content">Infobox center</h3>
						<div class="row">
							<div class="col-sm-4">
								<figure class="infobox infobox-center">
									<span class="icon-wrap  icon-sm round bg-blue"><i class="fa fa-shopping-cart"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title"> 163,921</h4>
										<p>Total sales</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
							<div class="col-sm-4">
								<figure class="infobox infobox-center">
									<span class="icon-wrap  icon-sm round bg-green"><i class="fa fa-users"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title"> 1 424  </h4>
										<p> Registered users </p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div><!-- col // -->
						    <div class="col-sm-4">
								<figure class="infobox infobox-center">
									<span class="icon-wrap icon-sm round bg-red"><i class="fa fa-gears"></i></span>
									<figcaption class="text-wrap">
										<h4 class="title">164 </h4>
										<p>Number of something</p>
									</figcaption>
								</figure> <!-- infobox // -->
							</div> <!-- col // -->
						</div><!-- row // -->

						</div><!-- container //  -->
						</section>
						<!-- ========================= SECTION CONTENT END// ========================= -->
					</div>

				
				</div>
				<!-- fim aba widgets -->
				
				
				<!-- aba Graficos -->			
				<div id="Graficos" class="tab-pane fade in active">
				
					<div class="page-header">
						<h2>Gráficos</h2>
					</div>				
					
					<div class="push20"></div>



	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>    
    <script src="js/plugins/Chart_Js/utils.js"></script>
	<script src="js/pie-chart.js"></script>
	<script src="js/gauge.coffee.js" type="text/javascript"></script>
    	
    <script>
	
		
        $(document).ready( function() {
			
			/* //legendas off
			Chart.defaults.global.legend = {
			enabled: true
			};
			*/

			// Bar chart
			// gentelella
			var ctx = document.getElementById("mybarChart");
			var mybarChart = new Chart(ctx, {
				type: 'bar',
				data: {
				  labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60"],
				  datasets: [{
					label: 'MÃ©dia de idade',
					backgroundColor: "#85C1E9",
					data: [1,2,3,4,5]
				  }]
				},

				options: {
				  scales: {
					yAxes: [{
					  ticks: {
						beginAtZero: true
					  }
					}]
				  },
					animation: {
						onComplete: function(animation) {
											
						}
					}				  
				}
			});
			
			// Bar chart
			var randomScalingFactor = function() {
				return Math.round(Math.random() * 100);
			};

			
			var config = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
						],
						backgroundColor: [
							window.chartColors.red,
							window.chartColors.green,
							window.chartColors.blue,
						],
						label: 'Dataset 1'
					}],
					labels: [
						"Red",
						//"Orange",
						//"Yellow",
						"Green",
						"Blue"
					]
				},
				options: {
					//rotation: 1 * Math.PI,
					//circumference: 1 * Math.PI,
					responsive: true,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: 'Chart.js Doughnut Chart'
					},
					animation: {
						animateScale: true,
						animateRotate: true
					}
				}
			};

			window.onload = function() {
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myDoughnut = new Chart(ctx, config);
			};
					
				

			// Line chart
			var ctx = document.getElementById("lineChart");
			var lineChart = new Chart(ctx, {
			type: 'line',
			data: {
			  labels: ["January", "February", "March", "April", "May", "June", "July"],
			  datasets: [{
				label: "My First dataset",
				backgroundColor: "rgba(38, 185, 154, 0.31)",
				borderColor: "rgba(38, 185, 154, 0.7)",
				pointBorderColor: "rgba(38, 185, 154, 0.7)",
				pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
				pointHoverBackgroundColor: "#fff",
				pointHoverBorderColor: "rgba(220,220,220,1)",
				pointBorderWidth: 1,
				data: [31, 74, 6, 39, 20, 85, 7]
			  }, {
				label: "My Second dataset",
				backgroundColor: "rgba(3, 88, 106, 0.3)",
				borderColor: "rgba(3, 88, 106, 0.70)",
				pointBorderColor: "rgba(3, 88, 106, 0.70)",
				pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
				pointHoverBackgroundColor: "#fff",
				pointHoverBorderColor: "rgba(151,187,205,1)",
				pointBorderWidth: 1,
				data: [82, 23, 66, 9, 99, 4, 2]
			  }]
			},
			});



			// Line chart
			var ctx = document.getElementById("lineChart2");
			var lineChart2 = new Chart(ctx, {
			type: 'line',
			data: {
			  labels: ["January", "February", "March", "April", "May", "June", "July"],
			  datasets: [{
				label: "My Second dataset",
				backgroundColor: "rgba(3, 88, 106, 0.3)",
				borderColor: "rgba(3, 88, 106, 0.70)",
				pointBorderColor: "rgba(3, 88, 106, 0.70)",
				pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
				pointHoverBackgroundColor: "#fff",
				pointHoverBorderColor: "rgba(151,187,205,1)",
				pointBorderWidth: 1,
				data: [8, 23, 66, 9, 99, 4, 2]
			  }]
			},
			});
		
					
			var barChartData = {
				labels: ["January", "February", "March", "April", "May", "June", "July"],
				datasets: [{
					label: 'Dataset 1',
					backgroundColor: window.chartColors.red,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					]
				}, {
					label: 'Dataset 2',
					backgroundColor: window.chartColors.blue,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					]
				}, {
					label: 'Dataset 3',
					backgroundColor: window.chartColors.green,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					]
				}]

			};
			
			
			var ctx2 = document.getElementById("Stacked").getContext("2d");
			window.myBar = new Chart(ctx2, {
				type: 'bar',
				data: barChartData,
				options: {
					title:{
						display:true,
						text:"Chart.js Bar Chart - Stacked"
					},
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});
			
			var opts = {
			  lines: 12, // The number of lines to draw
			  angle: 0.15, // The length of each line
			  lineWidth: 0.44, // The line thickness
			  pointer: {
				length: 0.9, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#000000' // Fill color
			  },
			  colorStart: '#6FADCF',   // Colors
			  colorStop: '#8FC0DA',    // just experiment with them
			  strokeColor: '#E0E0E0',   // to see which ones work best for you
			  generateGradient: true
			};
			var target = document.getElementById('foo'); // your canvas element
			var gauge = new Gauge(target);
			gauge.setOptions(opts); // create sexy gauge!
			gauge.maxValue = 3000; // set max gauge value
			gauge.animationSpeed = 32; // set animation speed (32 is default value)
			gauge.set(1250); // set actual value

        });	
	


    </script>
    <!-- /Chart.js -->


					
								<div class="row">
										
									<div class="form-group text-right col-lg-6">
									
										<canvas id="mybarChart"></canvas>	

									</div>
									
									<div class="form-group text-right col-lg-6">
									
										 <canvas id="chart-area" />

									</div>

									<div class="push50"></div>											
									
									<div class="form-group text-right col-lg-6">
									
										<canvas id="lineChart"></canvas>

									</div>	
									
									<div class="form-group text-right col-lg-6">sgasffsa
									
										<canvas id="lineChart2"></canvas>

									</div>
									
									<div class="push50"></div>
									
										
									<div class="form-group text-right col-lg-6">
									
										<canvas id="Stacked">TESTE</canvas>
										

									</div>
									
									
									<div class="col-md-6 ">
										<div class="content-top">
											<div class="col-md-6 top-content">
												<h5>Tasks</h5>
												<label>8761</label>
											</div>
											<div class="col-md-6">	   
												<div id="demo-pie-1" class="pie-title-center" data-percent="25">
													<span class="pie-value">25%</span>
												</div>
											</div>
											<div class="clearfix"> </div>
										</div>
										<div class="content-top">
											<div class="col-md-6 top-content">
												<h5>Points</h5>
												<label>6295</label>
											</div>
											<div class="col-md-6 top-content">	   
												<div id="demo-pie-2" class="pie-title-center" data-percent="50">
													<span class="pie-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="clearfix"> </div>
										</div>
										<div class="content-top-center">
											<div class="col-md-12 top-content">	   
												<div id="demo-pie-3" class="pie-title-center" data-percent="50">
													<span class="pie-icon"><i class="fa fa-cloud-upload" aria-hidden="true"></i></span>
												</div>
												<div class="push10"></div>
												<div><b>R$ 300,00</b></div>
												<div>Novas Compras</div>
											</div>
											<div class="clearfix"> </div>
										</div>										
									</div>
								</div>	
					
					
					<div class="push50"></div>	
					
								<div class="row">
										
									<div class="form-group text-right col-lg-12">
									
									

<style>
@import url(https://fonts.googleapis.com/css?family=Roboto);

body {
    background-color: #f2f2f2;
    color: #000;
    font-family: 'Roboto', sans-serif;
}

.column-chart {
  position: relative;
  z-index: 20;
  bottom: 0;
  left: 50%;
  width: 100%;
  height: 320px;
  margin-top: 40px;
  margin-left: -50%;
}

@media (min-width: 568px) {
  .column-chart {
    width: 80%;
    margin-left: -40%;
  }
}

@media (min-width: 768px) {
  .column-chart {
    width: 60%;
    margin-left: -30%;
  }
}

@media (min-width: 992px) {
  .column-chart {
    width: 40%;
    margin-left: -20%;
  }
}

@media (min-width: 1024px) {
  .column-chart {
    width: 36%;
    margin-left: -18%;
  }
}

.column-chart:before,
.column-chart:after {
  position: absolute;
  content: '';
  top: 0;
  left: 0;
  width: calc(100% + 30px);
  height: 25%;
  margin-left: -15px;
  border-top: 1px dashed #b4b4b5;
  border-bottom: 1px dashed #b4b4b5;
}

.column-chart:after {
  top: 50%;
}

.column-chart > .legend {
  position: absolute;
  z-index: -1;
  top: 0;
}

.column-chart > .legend.legend-left {
  left: 0;
  width: 25px;
  height: 75%;
  margin-left: -55px;
  border: 1px solid #b4b4b5;
  border-right: none;
}

.column-chart > .legend.legend-left > .legend-title {
  display: block;
  position: absolute;
  top: 50%;
  left: 0;
  width: 65px;
  height: 50px;
  line-height: 50px;
  margin-top: -25px;
  margin-left: -60px;
  font-size: 28px;
  letter-spacing: 1px;
}

.column-chart > .legend.legend-right {
  right: 0;
  width: 100px;
  height: 100%;
  margin-right: -115px;
}

.column-chart > .legend.legend-right > .item {
  position: relative;
  width: 100%;
  height: 25%;
}

.column-chart > .legend.legend-right > .item > h4 {
  display: block;
  position: absolute;
  top: 0;
  right: 0;
  width: 100px;
  height: 40px;
  line-height: 40px;
  margin-top: -20px;
  font-size: 16px;
  text-align: right;
}

.column-chart > .chart {
  position: relative;
  z-index: 20;
  bottom: 0;
  left: 50%;
  width: 98%;
  height: 100%;
  margin-left: -49%;
}

.column-chart > .chart > .item {
  position: relative;
  float: left;
  height: 100%;
}

.column-chart > .chart > .item:before {
  position: absolute;
  z-index: -1;
  content: '';
  bottom: 0;
  left: 50%;
  width: 1px;
  height: calc(100% + 15px);
  border-right: 1px dashed #b4b4b5;
}

.column-chart > .chart > .item > .bar {
  position: absolute;
  bottom: 0;
  left: 3px;
  width: 94%;
  height: 100%;
}

.column-chart > .chart > .item > .bar > span.percent {
  display: block;
  position: absolute;
  z-index: 25;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 26px;
  line-height: 26px;
  color: #fff;
  background-color: #3e50b4;
  font-size: 14px;
  font-weight: 700;
  text-align: center;
  letter-spacing: 1px;
}

.column-chart > .chart > .item > .bar > .item-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 20%;
  color: #fff;
  background-color: #ff4081;
}

.column-chart > .chart > .item > .bar > .item-progress > .title {
  position: absolute;
  top: calc(50% - 13px);
  left: 50%;
  font-size: 14px;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 2px;
  -moz-transform: translateX(-50%) translateY(-50%) rotate(-90deg);
  -webkit-transform: translateX(-50%) translateY(-50%) rotate(-90deg);
  transform: translateX(-50%) translateY(-50%) rotate(-90deg);
}

@media (min-width: 360px) {
  .column-chart > .chart > .item > .bar > .item-progress > .title {
    font-size: 16px;
  }
}

@media (min-width: 480px) {
  .column-chart > .chart > .item > .bar > .item-progress > .title {
    font-size: 18px;
  }
}
</style>

	<div class="row">
        <div class="col-md-6 borda">
            <div class="text-center text-uppercase">
                <h2>Pesquisa Marka</h2>
            </div>
            <!-- //.text-center -->
            
            <div class="column-chart">
                <div class="legend legend-left hidden-xs">
                    <h3 class="legend-title">NPS</h3>
                </div>
                <!-- //.legend -->
            
                <div class="legend legend-right hidden-xs">
                    <div class="item">
                        <h4>Promotores</h4>
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <h4>Neutros</h4>
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <h4>Detratores</h4>
                    </div>
                    <!-- //.item -->
            
                </div>
                <!-- //.legend -->
            
                <div class="chart clearfix">
                    <div class="item">
                        <div class="bar">
                            <span class="percent">85%</span>
            
                            <div class="item-progress" data-percent="85">
                                <span class="title">Content</span>
                            </div>
                            <!-- //.item-progress -->
                        </div>
                        <!-- //.bar -->
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <div class="bar">
                            <span class="percent">65%</span>
            
                            <div class="item-progress" data-percent="65">
                                <span class="title">Links</span>
                            </div>
                            <!-- //.item-progress -->
                        </div>
                        <!-- //.bar -->
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <div class="bar">
                            <span class="percent">55%</span>
            
                            <div class="item-progress" data-percent="55">
                                <span class="title">Trust</span>
                            </div>
                            <!-- //.item-progress -->
                        </div>
                        <!-- //.bar -->
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <div class="bar">
                            <span class="percent">70%</span>
            
                            <div class="item-progress" data-percent="70">
                                <span class="title">Engagement</span>
                            </div>
                            <!-- //.item-progress -->
                        </div>
                        <!-- //.bar -->
                    </div>
                    <!-- //.item -->
            
                    <div class="item">
                        <div class="bar">
                            <span class="percent">90%</span>
            
                            <div class="item-progress" data-percent="90">
                                <span class="title">Authority</span>
                            </div>
                            <!-- //.item-progress -->
                        </div>
                        <!-- //.bar -->
                    </div>
                    <!-- //.item -->
                </div>
                <!-- //.chart -->
            </div>
            <!-- //.column-chart -->
        </div>
        <!-- //.col-md-6 -->
		
		
        <div class="col-md-6 borda">
            <div class="text-center text-uppercase">
                <h2>Vertical Progress Bar</h2>
            </div>
            <!-- //.text-center -->
		
			<div class="progress vertical bottom">
			  <div class="progress-bar progress-bar-success" role="progressbar" data-transitiongoal="40"></div>
			</div>
			<div class="progress vertical bottom">
			  <div class="progress-bar progress-bar-info" role="progressbar" data-transitiongoal="60"></div>
			</div>
			<div class="progress vertical bottom">
			  <div class="progress-bar progress-bar-warning" role="progressbar" data-transitiongoal="80"></div>
			</div>
			<div class="progress vertical bottom">
			  <div class="progress-bar progress-bar-danger" role="progressbar" data-transitiongoal="100"></div>
			</div>

			<!--<script type="text/javascript" src="js/plugins/bootstrap-progressbar.min.js"></script>-->
			
			<script>
			$(document).ready(function() {
			  //$('.progress .progress-bar').progressbar();
			});
			</script>	

	
        </div>
        <!-- //.col-md-6 -->
		
    </div>
    <!-- //.row -->


</div>
<!-- //.container -->


<script>
$(document).ready(function(){
    columnChart();
    
    function columnChart(){
        var item = $('.chart', '.column-chart').find('.item'),
        itemWidth = 100 / item.length;
        item.css('width', itemWidth + '%');
        
        $('.column-chart').find('.item-progress').each(function(){
            var itemProgress = $(this),
            itemProgressHeight = $(this).parent().height() * ($(this).data('percent') / 100);
            itemProgress.css('height', itemProgressHeight);
        });
    };
});
</script>
									

									</div>
									
								</div>	
					
					
					<div class="push50"></div>						
					
				
				</div>
				<!-- fim aba graficos -->
				
			</div>
			
						
		<div class="push100"></div>	
		
		<a href="#0" class="cd-top">Top</a> 
		 
		</div>
		<!-- end container -->   


		<script type="text/javascript">
	
		$(document).ready(function() {
			
			$(':checkbox').checkboxpicker({
			  html: true,
			  offLabel: '<span class="glyphicon glyphicon-remove">',
			  onLabel: '<span class="glyphicon glyphicon-ok">'
			});
				
			$('#chk1').on('change', function() {
			alert("checado...");
			});
			
			
            $('#demo-pie-1').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-2').pieChart({
                barColor: '#fbb03b',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
			
            $('#demo-pie-3').pieChart({
                barColor: '#00cc00',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });			
		});	
		
	</script>
	
	<script src="js/plugins/bootstrap-checkbox.min.js"></script>
        <script>
            function duvida(){
                document.getElementsByClassName('fal fa-ellipsis-h-alt');
                alert("Campo de Bairro");
    }
        </script>
	
	