<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

?>


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">		
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>


					<div class="push50"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
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
																<input type="text" class="form-control input-sm" name="nome" id="nome" placeholder="Nome" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">SobreNome</label>
																<input type="text" class="form-control input-sm" name="sobrenome" id="sobrenome" placeholder="Sobre nome" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>

													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Endereço</label>
																<input type="text" class="form-control input-sm" name="endereco" id="endereco" placeholder="Endereco" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Número</label>
																<input type="text" class="form-control input-sm" name="numero" id="numero" placeholder="Número" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Bairro</label>
																<input type="text" class="form-control input-sm" name="bairro" id="bairro" placeholder="Bairro" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>
																				
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label required">Empresas</label>
																	<select data-placeholder="Selecione uma empresa" name="id_cli" id="id_cli" class="chosen-select-deselect" required>
																	<option value="0"></option>
																		<option value="1">teste</option> 
																	</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Sistemas</label>
																	<select class="selectpicker" data-style="input-sm">
																	  <option>Mustard</option>
																	  <option>Ketchup</option>
																	  <option>Relish</option>
																	</select>
																	<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Sistemas</label>
																	<select class="selectpicker" data-style="btn-primary input-sm" required>
																	  <option>Mustard</option>
																	  <option>Ketchup</option>
																	  <option>Relish</option>
																	</select>
																	<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Empresas</label><br/>
																	<select class="selectpicker" multiple id="combo2" data-style="input-sm" style="width: 100%;" required>
																	<option value="0"></option>
																		<option value="1">teste</option> 
																		<option value="2">teste 2</option> 
																		<option value="3">teste 3</option> 
																	</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>
					
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Empresas</label>
																	<select data-placeholder="Selecione um cliente" name="id_cli" id="id_cli" multiple class="chosen-select-deselect" style="width:100%;" tabindex="1">
																	<option value="0"></option>
																		<option value="1">teste</option> 
																		<option value="2">teste 2</option> 
																		<option value="3">teste 3</option> 
																	</select>
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
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										

	<style>
	
	.circle {
	  display: block;
	  border-radius: 50%;
	  height: 75px;
	  width: 75px;
	  margin: auto; 
	  padding: 24px 0;  
	}

	.circle span {
	  font-size:20px;
	  color: #ffffff;
	  font-weight: bold;
	}

	.circle2 {
	  display: block;
	  border-radius: 50%;
	  height: 75px;
	  width: 75px;
	  margin: auto; 
	  padding: 28px 0;  
	}

	.circle2 span {
	  font-size:17px;
	  color: #ffffff;
	  font-weight: bold;
	}

	.corBase {background: #F8F9F9;}
	
	.cor1 {background: #EC7063;}
	.cor2 {background: #F4D03F;}
	.cor3 {background: #58D68D;}
	.cor4 {background: #5DADE2;}
	
	.fCor1 {color: #EC7063;}
	.fCor2 {color: #F4D03F;}
	.fCor3 {color: #58D68D;}
	.fCor4 {color: #5DADE2;}

	.cor1on {background: #CB4335; font-size:18px !important;}
	.cor2on {background: #D4AC0D; font-size:18px !important;}
	.cor3on {background: #239B56; font-size:18px !important;}
	.cor4on {background: #2874A6; font-size:18px !important;}

	.bar {
		font-size: 16px;
		line-height: 50px;
		height:50px;
		border-radius: 5px;
		color: #ffffff;
		font-weight: bold;
		text-align: left;
		margin: left;
	}

	.f30 {
		font-size: 30px;
		font-weight: bold;
	}

	.bar span{
	  background: rgba(255,255,255,0.3);
	  padding: 6px 9px;
	  border-radius: 4px;
	  margin-left: 15px;
	  font-size:18px;
	}

.tooltip.top .tooltip-inner {
	color: #3c3c3c;
	width: 120px;
	height: 40px;
	font-size: 16px;
	background-color:white;
	opacity: 1!important;
	filter: alpha(opacity=100)!important;
	-webkit-box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
	-moz-box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
	box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
}
.tooltip.top .tooltip-arrow {
	border-top-color: white;
	opacity: 1!important;
	filter: alpha(opacity=100)!important;
}

.tooltip.in {
	opacity: 0.97!important;
	filter: alpha(opacity=97)!important;
}

	</style>										
										

	<a class="btn btn-large btn-success" href="#" id='demo'>Tour Demo</a>
	
	<div class="push20"></div>
	
    <p class="text-center"><button id="startTourBtn" class="btn btn-large btn-primary">Take a tour</button></p>
	
	<div class="push50"></div>
										
										<div class="row text-center">
											
											<div class="text-center col-lg-1"></div>
											<div class="text-center col-lg-10">
																				
												<div class="push50"></div>
											
												<table class="table table-striped">
												  <thead>
													<tr>
													  <th></th>
													  <th class="text-center f18">TIPO DE CLIENTE</th>
													  <th class="text-center f18">CONSUMO</th>
													  <th class="text-center f18">RENTABILIDADE</th>
													  <th class="text-center f18">CONCENTRAÇÃO</th>
													</tr>
												  </thead>
												  <tbody>
												  
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i < 1; $i++) {
															echo "<i class='fas fa-male fa-2x fCor1' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push10"></div>
													  <span class="f18 fCor1"><b>CASUAIS</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor1"><b>R$ 85,02</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor1"><b>1x</b></span>
													  </td>
													  <td class="bootstro" id="bar1"
														data-bootstro-title="I am simple" 
														data-bootstro-content="Call bootstro.start('.bootstro') or just <b>bootstro.start()</b>"
														data-bootstro-step='1'													  
													  >
														<div class="push15"></div>
														<div class="bar cor1 bootstro" id="bar1" style="width: -webkit-calc(90%);" data-toggle="tooltip" data-placement="top" data-original-title="50.412"><span>50%</span>&nbsp; 50.412 </div>
													  </td>
													</tr>
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i <2; $i++) {
															echo "<i class='fas fa-male fa-2x fCor2' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor2"><b>FREQUENTES</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor2"><b>R$ 218,18</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor2"><b> 3x </b></span>
													  </td>
													  <td class="bootstro" id="bar2"
														data-bootstro-title="I am simple 2" 
														data-bootstro-content="Call bootstro.start('.bootstro') or just <b>bootstro.start()</b>"
														data-bootstro-step='2'													  
													  >
														<div class="push15"></div>
														<div class="bar cor2" style="width: -webkit-calc(70%);" data-toggle="tooltip" data-placement="top" data-original-title="30.247"><span>30%</span>&nbsp;  30.247 </div>
													  </td>
													</tr>
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i < 3; $i++) {
															echo "<i class='fas fa-male fa-2x fCor3' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor3"><b>FIÉIS</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor3"><b>R$ 528,51</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor3"><b>5x</b></span>
													  </td>
													  <td class="bootstro"
														data-bootstro-title="I am simple 3" 
														data-bootstro-content="Call bootstro.start('.bootstro') or just <b>bootstro.start()</b>"
														data-bootstro-step='3'													  
													  >
														<div class="push15"></div>
														<div class="bar cor3" style="width: -webkit-calc(55%);" data-toggle="tooltip" data-placement="top" data-original-title="15.123"><span>15%</span>&nbsp; 15.123</div>
													  </td>
													</tr>	
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center">
													  <div class="push5"></div>
													  <?php 
														for ($i=0; $i < 10; $i++) {
															echo "<i class='fas fa-male fa-2x fCor4' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor4"><b>FÃS</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor4"><b>R$ 1,170,82</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor4"><b>10x</b></span>
													  </td>
													  <td>
														<div class="push15"></div>
														<div class="bar cor4" style="width: -webkit-calc(35%);" data-toggle="tooltip" data-placement="top" data-original-title="5.041"><span>5%</span>&nbsp; 5.041</div>
													  </td>
													</tr>
													
												  </tbody>
												</table>								
												
											</div>
											<div class="text-center col-lg-1"></div>
											
										</div>
										
										<div class="push10"></div>
										
										<div class="row text-center">
											
											<div class="text-center col-lg-1"></div>
											<div class="text-center col-lg-10">
																				
												<div class="push50"></div>
											
												<table class="table ">
												  <thead>
													<tr>
													  <th></th>
													  <th class="text-center f18">TIPO DE CLIENTE</th>
													  <th class="text-center f18">CONSUMO</th>
													  <th class="text-center f18">RENTABILIDADE</th>
													  <th class="text-center f18">CONCENTRAÇÃO</th>
													</tr>
												  </thead>
												  <tbody>
												  
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center corBase">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i < 1; $i++) {
															echo "<i class='fas fa-male fa-2x fCor1' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push10"></div>
													  <span class="f18 fCor1"><b>CASUAIS</b></span>
													  <div class="push3"></div>
													  <span class="f13 fCor1"><b>1 a 2 compras no período</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor1"><b>R$ 85,02</b></span>
													  </td>
													  <td class="text-center corBase">
													  <div class="push15"></div>
													  <span class="f30 fCor1"><b>1x</b></span>
													  </td>
													  <td>
														<div class="push15"></div>
														<div class="bar cor1" style="width: -webkit-calc(90%);" data-toggle="tooltip" data-placement="top" data-original-title="50.412"><span>50%</span>&nbsp; 50.412 </div>
													  </td>
													</tr>
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center corBase">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i <2; $i++) {
															echo "<i class='fas fa-male fa-2x fCor2' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor2"><b>FREQUENTES</b></span>
													  <div class="push3"></div>
													  <span class="f13 fCor2"><b>3 a 4 compras no período</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor2"><b>R$ 218,18</b></span>
													  </td>
													  <td class="text-center corBase">
													  <div class="push15"></div>
													  <span class="f30 fCor2"><b> 3x </b></span>
													  </td>
													  <td>
														<div class="push15"></div>
														<div class="bar cor2" style="width: -webkit-calc(70%);" data-toggle="tooltip" data-placement="top" data-original-title="30.247"><span>30%</span>&nbsp;  30.247 </div>
													  </td>
													</tr>
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center corBase">
													  <div class="push10"></div>
													  <?php 
														for ($i=0; $i < 3; $i++) {
															echo "<i class='fas fa-male fa-2x fCor3' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor3"><b>FIÉIS</b></span>
													  <div class="push3"></div>
													  <span class="f13 fCor3"><b>5 a 9 compras no período</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push15"></div>
													  <span class="f30 fCor3"><b>R$ 528,51</b></span>
													  </td>
													  <td class="text-center corBase">
													  <div class="push15"></div>
													  <span class="f30 fCor3"><b>5x</b></span>
													  </td>
													  <td>
														<div class="push15"></div>
														<div class="bar cor3" style="width: -webkit-calc(55%);" data-toggle="tooltip" data-placement="top" data-original-title="15.123"><span>15%</span>&nbsp; 15.123</div>
													  </td>
													</tr>	
													
													<tr>
													  <th scope="row">&nbsp;</th>
													  <td class="text-center corBase">
													  <div class="push5"></div>
													  <?php 
														for ($i=0; $i < 10; $i++) {
															echo "<i class='fas fa-male fa-2x fCor4' style='margin: 0 3px 0 0;'></i>";
														}													  
													  ?>
													  <div class="push5"></div>
													  <span class="f18 fCor4"><b>FÃS</b></span>
													  <div class="push3"></div>
													  <span class="f13 fCor4"><b>10 ou mais compras no período</b></span>
													  </td>
													  <td class="text-center">
													  <div class="push20"></div>
													  <span class="f26b fCor4"><b>R$ 1,170,82</b></span>
													  </td>
													  <td class="text-center corBase">
													  <div class="push15"></div>
													  <span class="f30 fCor4"><b>10x</b></span>
													  </td>
													  <td>
														<div class="push15"></div>
														<div class="bar cor4" style="width: -webkit-calc(35%);" data-toggle="tooltip" data-placement="top" data-original-title="5.041"><span>5%</span>&nbsp; 5.041</div>
													  </td>
													</tr>
													
												  </tbody>
												</table>								
												
											</div>
											<div class="text-center col-lg-1"></div>
											
										</div>
										
										<div class="push50"></div>										
										
										
										
										
										
										<div class="push50"></div>
										
										<form method="post" id="frmLogin" name="frmLogin" action="https://mail2easypro.com/authenticate" target="_blank">
										
										<input type="hidden" name="hash" id="hash"  value="" />
										<input id="username" type="hidden" value="mkt@markafidelizacao.com.br" name="username" placeholder="Seu e-mail" class="field-text">
										<input id="password" type="hidden" value="olecram1974" name="password" placeholder="Sua senha" class="field-text">
									    <button type="submit" name="CAD" id="CAD" class="btn btn-sm btn-primary"> Logar na mail2easypro </button>
										
										</form>																			
																		
										<div class="push20"></div>
										
										<form method="post" id="frmLogin" name="frmLogin" action="http://mail.markafidelizacao.com.br/console/login.aspx" target="_blank">
										<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="09F20EE6" />
										<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEdAARdK6+CsXhaz3dX+4/xbk+qkUTCVNkB13WsYfQPuBFZzIb+Ceo2kFCl17Di8Kx70hDwxjiFIrURJcCXsKttJOkNSsNVRGumxFm1TlnjSvu5mxugIC4=" />
					
										<input id="USER" type="hidden" value="m87540" name="USER" placeholder="Seu e-mail" class="field-text">
										<input id="PASSWORD" type="hidden" value="Mk87540@" name="PASSWORD" placeholder="Sua senha" class="field-text">
									    <button type="submit" name="CAD" id="CAD" class="btn btn-sm btn-primary"> Logar na Mail Up </button>
										
										</form>		

										<div class="push20"></div>
										
										<form id="login-form" method="post" action="https://account.sendinblue.com/users/make-login" class="login-form" target="_blank">
											<input type="hidden" class="form-control" name="email" autocomplete="off" placeholder="Endereço de e-mail" value="ricardolara.ti@gmail.com" />
											<input type="hidden" class="form-control" name="pass" autocomplete="off" placeholder="Senha" value="teste123456" />
											<input type="hidden" name="next" value="" >
										    <button type="submit" name="CAD" id="CAD" class="btn btn-sm btn-primary"> Logar na Send in Blue </button>
										
										</form>
																			
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th class="bg-primary" colspan="100">&nbsp;</th>
													</tr>
												  </thead>
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Nome</th>
													  <th class="bg-primary">Sobrenome</th>
													  <th class="bg-primary">Usuario</th>
													  <th class="bg-primary">e-Mail</th>
													</tr>
												  </thead>
												  <tbody>
													<?php 
													$x = 1;
													while($x <= 5) {
													?>	
													<tr>
													  <th scope="row"><input type="radio" name="radio1" onclick="retornaForm(<?php echo $x ?>)"></th>
													  <td>Nome 	<?php echo $x ?></td>
													  <td>Sobrenome <?php echo $x ?> </td>
													  <td>@user<?php echo $x ?></td>
													  <td>email<?php echo $x ?>@teste.com</td>
													</tr>
													<input type="hidden" id="ret_nome<?php echo $x ?>" value="Retorno Nome<?php echo $x ?>">
													<input type="hidden" id="ret_sobrenome<?php echo $x ?>" value="Retorno Sobrenome<?php echo $x ?>">
													<input type="hidden" id="ret_endereco<?php echo $x ?>" value="Retorno Endereço<?php echo $x ?>">
													<?php
													$x++;
													} 
													?>	
												  </tbody>
												</table>
												
												</form>

											</div>
											
										</div>										

										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												<h3>Geração de Senha Nexx</h3> 
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Cliente</th>
													  <th>Senha</th>
													  <th>Senha Cripto</th>
													</tr>
												  </thead>
												  <tbody>

													<?php
														$sql = "SELECT a.cod_cliente,b.senha
																FROM clientes a , nexx.cnvassociado b
																 WHERE 
																 a.Id_associado=b.id_associado AND 
																 a.cod_empresa=124 AND 
																 a.DES_SENHAUS IS NULL limit 10;	 ";
																 
														$arrayQuery = mysqli_query(connTemp(124,""),trim($sql)) or die(mysqli_error());
													
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														  {	
													  
															$cod_cliente = $qrLista['cod_cliente'];
															$senha = $qrLista['senha'];
															$senha_nova = fnEncode($qrLista['senha']);
														
															/*	
															$sql2 = "UPDATE clientes SET 
																	des_senhaus='$senha_nova'
																	WHERE cod_cliente=$cod_cliente AND 
																		  cod_empresa=124													  
																	";

															mysqli_query(connTemp(124,""),trim($sql2));													  
															*/
															
														  ?>
															
															<tr>
															  <th scope="row"><input type="radio" name="radio1" onclick="retornaForm(<?php echo $x ?>)"></th>
															  <td><?php echo $qrLista['cod_cliente']; ?></td>
															  <td><?php echo $qrLista['senha']; ?> </td>
															  <td><?php echo fnEncode($qrLista['senha']); ?> </td>
															  <td></td>
															</tr>
																
																
														  <?php	
															  }											
													?>	

												  </tbody>
												</table>
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
					
					
	<link rel="stylesheet" href="js/plugins/tour/bootstro.css">		
	<script src="js/plugins/tour/bootstro.min.js"></script>
	
	<link rel="stylesheet" href="js/plugins/tour/bootstro.css">		
	<script src="js/plugins/tour/hopscotch.min.js"></script>
	
	<script type="text/javascript">

		// Define the tour!
		var tour = {
		id: "hello-hopscotch",
		steps: [
		  {
			title: "My Header",
			content: "This is the header of my page.",
			target: document.querySelector("#bar1"),
			placement: "right"
		  },
		  {
			title: "My content",
			content: "Here is where I put my content.",
			target: document.querySelector("#bar2"),
			placement: "bottom"
		  }
		]
		};

		// Start the tour!
		hopscotch.startTour(tour);
			
	
	
		$(document).ready(function(){

		    $("#demo").click(function(){
		        bootstro.start(".bootstro", {
		            onComplete : function(params)
		            {
		                alert("Reached end of introduction with total " + (params.idx + 1)+ " slides");
		            }
					//,
		            //onExit : function(params)
		            //{
		            //    alert("Introduction stopped at slide #" + (params.idx + 1));
		            //},
		        });    
		    });	
			
			//tooltip
			$('[data-toggle="tooltip"]').tooltip(); 

			//pop over
			$('.popOverCb').webuiPopover({
					title: 'Popover Web UI - Combo',
					placement:'top',
					width:290,
					height:130,
					closeable:true,
					url:'#popup-content'
			});	
			
			$('.popOverCb2').webuiPopover({
					title: 'Popover Web UI - Combo',
					placement:'top',
					width:290,
					height:130,
					closeable:true,
					url:'#popup-content2'
			});	

		});

		$('#combo2').addClass('col-lg-12').selectpicker('setStyle');		
		
		function retornaForm(index){
			$("#formulario #nome").val($("#ret_nome"+index).val());
			$("#formulario #sobrenome").val($("#ret_sobrenome"+index).val());
			$("#formulario #endereco").val($("#ret_endereco"+index).val());
			//$("#id_cli").val(eval('document.getElementById("FORMLISTA").R_id_cli' + index + '.value')).trigger("liszt:updated");
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	