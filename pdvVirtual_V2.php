<?php
	//echo fnDebug('true');	
	$hashLocal = mt_rand();
	$tem_prodaux = "";

if($_GET['erro']=='1')
{
   echo 'Cpf Digitado é invalido!'; 
}    

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
    $request = md5( implode( $_POST ) );

    if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
    {
            $msgRetorno = 'Essa página já foi utilizada';
            $msgTipo = 'alert-warning';
    }
    else
    {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
		
		$_SESSION["USU_COD_EMPRESA"] = $cod_empresa;
		$_SESSION["USU_COD_USUARIO"] = $_REQUEST['COD_USUARIO'];
		$_SESSION["USU_COD_UNIVEND"] = $_REQUEST['COD_UNIVEND'];

        if ($opcao != '')
        {

            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
            //mensagem de retorno
            switch ($opcao)
            {
                    case 'CAD':
                            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
                            break;
                    case 'ALT':
                            $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
                            break;
                    case 'EXC':
                            $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
                            break;
                    break;
            }			
            $msgTipo = 'alert-success';	

        }	
    }  
}
	
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);			
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
			
		}else{
			$casasDec = 2;
		}
		
		/*
		$sql = "select  A.*,B.NOM_FANTASI from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '".$cod_empresa."' ";		
		
		//fnEscreve($sql);
		
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
 
		if (isset($arrayQuery)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			
		}
		*/
			
												
	}else {
		$cod_empresa = 0;
		$casasDec = 2;	
		
	}

	// switch ($casasDec) {

	//    	case 3:
	//    		$money = "money3";
	//    	break;

	//    	case 4:
	//    		$money = "money4";
	//    	break;

	//    	case 5:
	//    		$money = "money5";
	//    	break;
	   	
	//    	default:
	//    		$money = "money";
	//    	break;

	//    }
	
	//fnMostraForm();
	//fnEscreve($cod_orcamento);
	
?>

<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />

<style>
.widget .widget-title {
    font-size: 14px;
}
.widget .widget-int {
    font-size: 20px;
	padding: 0 0 10px 0;
}
.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon {
    font-size: 35px;
}


	/*-- bloco saldos --*/
	
	.blkSaldo {
		margin-top: 1.5em;
	}
	.blkSaldo-left{
		background:#1B4F72;
		background-image: url(../images/lighten.png);
		text-align:center;
		padding: 15px 0 0 0px;
		 border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;
		border-top-left-radius: 0.3em;
		-o-border-top-left-radius: 0.3em;
		-moz-border-top-left-radius: 0.3em;
		
	}
	.blkSaldo-middle{
		background:#2874A6;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-right{
		background:#cc324b;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-lost{
		background:#3498DB;
		background-image:url('../images/lighten.png');
		border-radius:0;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
		border-top-right-radius: 0.3em;
		-o-border-top-right-radius: 0.3em;
		-moz-border-top-right-radius: 0.3em;
		-webkit-border-top-right-radius: 0.3em;

	}
	
	.blkSaldo-left span{
		display: block;
		font-size: 15px;
		font-weight: 400;
		color: #fff;
		background-color: #1B4F72;
		padding: 8px 0;
		margin-top: 15px;
		border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;

	}
	span.resgatado {
		background-color: #2874A6;
		border-radius:0;
	}
	span.liberar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
	}
	span.expirar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
	}
	.blkSaldo img {
		text-align: center;
		margin: 0 auto;
	}
	/*-- bloco saldo --*/
	
	/*-- choosen --*/

	#sexo_chosen, #COD_ATENDENTE_chosen {
		font-size: 18px;
	}
	
	#sexo_chosen > a, #COD_ATENDENTE_chosen > a {
		height: 66px;
		padding: 18px 27px;		
	}
	
	#COD_UNIVEND_chosen {
		font-size: 15px;
	}
	
	#COD_UNIVEND_chosen > a {
		height: 45px;
		padding: 5px 15px;	
	}

	#COD_USUARIO_chosen {
		font-size: 15px;
	}
	
	#COD_USUARIO_chosen > a {
		height: 45px;
		padding: 5px 15px;		
	}

	#COD_FORMAPA_chosen {
		font-size: 15px;
	}
	
	#COD_FORMAPA_chosen > a {
		height: 45px;
		padding: 5px 15px;		
	}	
	
	.chosen-container{
		width:100% !important;
	}
	
	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}
	
	.chosen-container-single .chosen-single div b {
		background: url(css/chosen-sprite.png) no-repeat 0 7px;
	}	

	/*-- choosen --*/
	
		
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

	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>
					
					<?php 
					$formBack = "1758";
					include "atalhosPortlet.php"; 
					?>	
					
				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>					
					
					<div class="push30"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						<div class="row" id="bloco">
						
							<?php 
							if (($_SESSION["SYS_COD_EMPRESA"] != $cod_empresa) && $cod_empresa != $_SESSION["USU_COD_EMPRESA"] ) { 
							?>

								<div class="col-md-3"></div>	

								<div class="col-md-6">
								
														
									<div class="alert alert-warning top30" role="alert" id="msgRetorno">
										<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										Você está em <b>modo administrador</b>. <br/> Escolha uma <b>unidade</b> e <b>vendedor</b> para continuar.
									</div>
								
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades de Venda</label>
											
												<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<option value="">&nbsp;</option>
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
													  {					
													  if($qrListaUnive['LOG_ESTATUS'] == 'N'){ $disabled = "disabled"; }else{ $disabled = " "; }															
														echo"
															  <option value='".$qrListaUnive['COD_UNIVEND']."'".$disabled.">".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
															"; 
														  }	
													?>								
												</select>
												<?php //fnEscreve($sql); ?>		
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="push10"></div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Vendedores</label>
											
												<select data-placeholder="Selecione uma unidade para acesso" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;" tabindex="1">								
													<option value="">&nbsp;</option>
												</select>
												<?php //fnEscreve($sql); ?>		
											<div class="help-block with-errors"></div>
										</div>
									
									</div>
									
								</div>

								<div class="col-md-3"></div>							
					
							<?php } else {

							if ($_SESSION["SYS_COD_EMPRESA"] == $cod_empresa) { 							
							
								$sql1 = 'SELECT COD_UNIVEND FROM  USUARIOS WHERE COD_EMPRESA = '.$cod_empresa.' and  cod_usuario='.$_SESSION["SYS_COD_USUARIO"] ;
								//fnEscreve($sql1);
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());																
								$qrListaUniveUsu = mysqli_fetch_assoc($arrayQuery);
								$unidades_usuario = $qrListaUniveUsu['COD_UNIVEND'];
								$sqlUnidades = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN ($unidades_usuario) ORDER BY NOM_FANTASI ";
							} else {
								$sqlUnidades = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";	
							}	
							?>					
							
								<div class="col-md-3"></div>

								<?php
									switch ($cod_empresa) {
										case 121: //águia postos
										case 91: //renaza 
										case 143: //águia postos
										case 176: // posto amigao
										case 178: // central
										case 190: // viplac
											$mostrac10 = "style='display: block;'";
											$disabled = "";
											$cartaoRequired = 'true';
										break;

										default:
											$mostrac10 = "style='display: none;'";
											$disabled = "disabled";
											$cartaoeRquired = 'false';
										break;
									}
								?>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidades de Venda</label>
										
											<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option value="">&nbsp;</option>
												<?php												
												$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlUnidades) or die(mysqli_error());																
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
												  {			
												  if($qrListaUnive['LOG_ESTATUS'] == 'N'){ $disabled = "disabled"; }else{ $disabled = " "; }																
													echo"
														  <option value='".$qrListaUnive['COD_UNIVEND']."'".$disabled.">".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
														"; 
													  }	
												?>								
											</select>
											<?php //fnEscreve($sql); ?>		
										<div class="help-block with-errors"></div>
									</div>
								</div>	
								<div class="col-md-3"></div>
								
								<div class="push10"></div>
								
								<div class="col-md-3"></div>

								<div class="col-md-6">
										

									
									<div class="row">
										
										<?php

											$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

											$arrayCampos = mysqli_query($connAdm->connAdm(),$sqlCampos);

											// echo($sqlCampos);

											$lastField = "";

											$qrCampos = mysqli_fetch_assoc($arrayCampos);

											switch ($qrCampos[COD_CHAVECO]) {

												case 2:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Cartão</label>
																<input type="text" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<input type="hidden" class="campo1" value="">
														<input type="hidden" class="campo3" value="">
														<input type="hidden" class="campo4" value="">

													<?php

												break;

												case 3:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Celular</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<input type="hidden" class="campo1" value="">
														<input type="hidden" class="campo3" value="">
														<input type="hidden" class="campo4" value="">

													<?php
													
												break;

												case 4:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Código Externo</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<input type="hidden" class="campo1" value="">
														<input type="hidden" class="campo3" value="">
														<input type="hidden" class="campo4" value="">

													<?php
													
												break;

												case 5:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">CPF/CNPJ</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Cartão</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" data-error="ou este" maxlenght="10" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<input type="hidden" class="campo3" value="">
														<input type="hidden" class="campo4" value="">

													<?php
													
												break;

												case 6:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">CPF/CNPJ</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Nascimento</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2 data" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">Celular</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo3 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label>&nbsp;</label>
																<label for="inputName" class="control-label required">Email</label>
																<input type="email" class="form-control input-hg input-sm campo4" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

													<?php
													
												break;							
												
												default:

													?>
														<div class="col-md-12 col-xs-12">
															<div class="form-group">
																<label for="inputName" class="control-label required">CPF/CNPJ</label>
																<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<input type="hidden" class="campo2" value="">
														<input type="hidden" class="campo3" value="">
														<input type="hidden" class="campo4" value="">

													<?php

												break;

											}

										?>

									</div>	

								

								</div>	


								<div class="push30"></div>

								<div class="col-md-3 col-md-offset-3">
								
									<!-- <input type="hidden" class="form-control input-lg text-center cpfcnpj" name="c10" id="c10" value="0" placeholder="Informe seu CPF/CNPJ" required>												 -->
									<button type="button" name="ZERO" id="ZERO" class="btn btn-info btn-lg btn-block getBtn " tabindex="5"><i class="fal fa-user-times" aria-hidden="true"></i>&nbsp; Compra Avulsa</button>
									
								</div>

								<div class="col-md-3">
										<button type="submit" name="PESQUISA" id="PESQUISA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar Cliente</button>
								</div>

								<div class="col-md-3"></div>								
								
							<?php } ?>	
							
						</div>
						
						<div class="push5" id="loadPage"></div>
						<div class="push100"></div>
						
							<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
							<input type="hidden" name="REFRESH_PRODUTOS" id="REFRESH_PRODUTOS" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
							
						</form>										
						
					</div>
					<!-- fim Portlet -->
					
				</div>
				
			</div>					

	<!-- modal -->									
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script type="text/javascript">	

		let campo = "",
			validado = "";
	
		$(document).ready(function(){
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$("#COD_USUARIO").change(function(){           
				$("#formulario").unbind().submit();
			});

			// $('.money3').mask("#.##0,000", {reverse: true});
			// $('.money4').mask("#.##0,0000", {reverse: true});
			// $('.money5').mask("#.##0,00000", {reverse: true});
						
		});	
		
		$("body").on("click","#FINALIZA", function(){
			$.ajax({
				type: "GET",
				url: "ajxValidaResgatePdv.do",
				data: $('#formulario').serialize(),
				success:function(retorno){

					if(retorno == 52){

						$.ajax({
							type: "GET",
							url: "ajxBlocoFinaliza.do?opcao=LGPD",
							data: $('#formulario').serialize(),
							beforeSend:function(){
								$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$("#bloco").html(data); 
								$("#COD_UNIVEND").chosen();
								$("#COD_UNIVEND").chosen({allow_single_deselect:true});
								$("#COD_FORMAPA").chosen();
								$("#COD_FORMAPA").chosen({allow_single_deselect:true});					
								$("#COD_USUARIO").chosen();
								$("#COD_USUARIO").chosen({allow_single_deselect:true});					
							},
							error:function(){
								$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
							}
						});

					}else{

						$.alert({
							title: 'Atenção!',
							content: retorno,
						});

					}

					// console.log(retorno);

				},
				error:function(){
					$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		});			
		
		$("body").on("change","#COD_UNIVEND", function(){
			$.ajax({
				type: "GET",
				url: "ajxPdvVirtual.do",
				data: {opcao: "vendedores", cod_univend: $('#COD_UNIVEND').val(), cod_empresa: <?php echo $cod_empresa; ?>},
				beforeSend:function(){
					$('#loadPage').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$('#loadPage').html("");
					$('#COD_USUARIO').html(data);
					 $('#COD_USUARIO').trigger("chosen:updated");
				},
				error:function(){
				}
			});
		});
		

		$("#ZERO").click(function(){
			if($('#COD_UNIVEND').val().trim() == ""){
				$.alert({
					title: 'Atenção!',
					content: 'Selecione uma unidade de venda!',
				});
				return false;
				
			}else{
			$("#c1, #c6").val('');
				$.ajax({
					type: "GET",
					url: "ajxBlocoCompra.do?opcao=LGPD",
					data: $('#formulario').serialize(),
					beforeSend:function(){
						$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						//console.log(data);
						$("#bloco").html(data); 
						$("#COD_USUARIO").chosen();
						$("#COD_USUARIO").chosen({allow_single_deselect:true});						
						$("#COD_UNIVEND").chosen();
						$("#COD_UNIVEND").chosen({allow_single_deselect:true});
						$("#COD_FORMAPA").chosen();
						$("#COD_FORMAPA").chosen({allow_single_deselect:true});					
					},
					error:function(){
						$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}
		});	

		$("body").on("click","#ATUALIZA", function(e){
			if(validaDadosObrigatorios()){
				$.ajax({
					type: "GET",
					url: "ajxBlocoCompra_V2.do",
					data: $('#formulario').serialize(),
					beforeSend:function(){
						$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						//console.log(data);
						$("#bloco").html(data); 
						$("#COD_USUARIO").chosen();
						$("#COD_USUARIO").chosen({allow_single_deselect:true});						
						$("#COD_UNIVEND").chosen();
						$("#COD_UNIVEND").chosen({allow_single_deselect:true});
						$("#COD_FORMAPA").chosen();
						$("#COD_FORMAPA").chosen({allow_single_deselect:true});					
					},
					error:function(){
						$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});	
			}else{
				$.alert({
					title: 'Atenção!',
					content: 'Informe campos obrigatórios!',
				});
			}			
			
		});

		function validaDadosObrigatorios(){

			validado = true;

			console.log('entrou func');

			$("[required]").each(function() {

				campo = $(this).attr('id');

				console.log($("#"+campo).attr("id")+" : "+$("#"+campo).val().trim());

				if($("#"+campo).val().trim() == ""){

					validado = false;

				}

			});	

			return validado;

		}

		function validarPesquisar(){
			if($('#COD_UNIVEND').val().trim() == ""){
				$.alert({
					title: 'Atenção!',
					content: 'Informe campos obrigatórios!',
				});	

				return false;
			}else{
				return true;
			}
		}			
		
		$("body").on("click","#PESQUISA", function(){
			if(validarPesquisar()){
				consultaCliente();			
			}
		});
		
		
		// $("body").on("click","#HOME, #HOME2", function(){
		// 	$.ajax({
		// 		type: "GET",
		// 		url: "ajxBlocoPesquisa.do",
		// 		data: $('#formulario').serialize(),
		// 		beforeSend:function(){
		// 			$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
		// 		},
		// 		success:function(data){
		// 			$("#bloco").html(data); 
		// 			$("#COD_UNIVEND").chosen();
		// 			$("#COD_UNIVEND").chosen({allow_single_deselect:true});
		// 		},
		// 		error:function(){
		// 			$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
		// 		}
		// 	});
		// });

		$("body").on("change","#VAL_RESGATE", function(){
			var val_disponivel = converterFloatValueToCalc($("#VAL_DISPONIVEL").val());
			var val_resgate = converterFloatValueToCalc($(this).val());
			
			if(val_resgate > val_disponivel){
				$.alert({
					title: 'Atenção!',
					content: 'Valor digitado é maior que o saldo resgate!',
				});	
				$(this).val('0,00');
			}else{
				if($(this).val().trim() == ""){
					$(this).val("0,00");
				}
				
				var total_de_produtos = converterFloatValueToCalc($('#total_de_produtos').text());
				var valor = total_de_produtos - val_resgate;
				$('#total_da_venda, .total_da_venda').unmask();
				$('#total_da_venda').text(valor.toFixed(2));	
				$('.total_da_venda').val(valor.toFixed(2));	
				$('#total_da_venda, .total_da_venda').mask("#.##0,00", {reverse: true});	
							
			}
		});

		// ajax consulta

		function consultaCliente(){
			$.ajax({
				type: "POST",
				url: "ajxBlocoCadastro_V2.do",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#bloco").html(data);
					$("#sexo").chosen();
					$("#sexo").chosen({allow_single_deselect:true});
				},
				error:function(){
					$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}	

		//ajax compras 
		
		function deleteProd(idOrc, idItem){
			RefreshProdutosExc(<?php echo $cod_empresa; ?>, idOrc, 'EXC', idItem);
		}
		
		function RefreshProdutos(idEmp, idOrc, tipo) {
			//alert("-> "+idOrc);
			$.ajax({
				type: "GET",
				url: "ajxListaOrcamento.php?CASAS_DEC=<?=$casasDec?>",
				data: { ajx1:idEmp, ajx2:idOrc, ajx3:tipo},
				beforeSend:function(){
					$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_Produtos").html(data); 
					
					var val_totprodu = converterFloatValueToCalc($('#VAL_TOTPRODU').val());
					
					$('#total_de_produtos, .total_de_produtos').unmask();
					$('#total_de_produtos').text(val_totprodu.toFixed(2));
					$('.total_de_produtos').val(val_totprodu.toFixed(2));					
					$('#total_de_produtos, .total_de_produtos').mask("#.##0,00", {reverse: true});

					var val_desconto = converterFloatValueToCalc($('#VAL_DESCONTO').val());
								
					if(val_totprodu >= val_desconto){
						val_totprodu = val_totprodu - val_desconto;
					}else{
						$('#VAL_DESCONTO').val('0,00');
						$.confirm({
							icon: 'fa fa-warning',
							title: 'Atenção',
							content: 'Valor total dos produtos menor que valor de desconto. Desconto será zerado.',
							type: 'orange',
							typeAnimated: true,
							buttons: {
								ok: function () {
								}
							}							
						});						
					}
					
					$('#total_da_venda, .total_da_venda').unmask();
					$('#total_da_venda').text(val_totprodu.toFixed(2));	
					$('.total_da_venda').val(val_totprodu.toFixed(2));
					$('#total_da_venda, .total_da_venda').mask("#.##0,00", {reverse: true});	
									
				},
				error:function(){
					$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}	
		
		function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
			$.ajax({
				type: "GET",
				url: "ajxListaOrcamento.php",
				data: { ajx1:idEmp, ajx2:idOrc, ajx3:tipo, ajx4: idItem },
				beforeSend:function(){
					$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					//console.log(data);
					$("#div_Produtos").html(data);
					
					var val_totprodu = converterFloatValueToCalc($('#VAL_TOTPRODU').val());
					
					$('#total_de_produtos, .total_de_produtos').unmask();
					$('#total_de_produtos').text(val_totprodu.toFixed(2));	
					$('.total_de_produtos').val(val_totprodu.toFixed(2));
					$('#total_de_produtos, .total_de_produtos').mask("#.##0,00", {reverse: true});	
					
					var val_desconto = converterFloatValueToCalc($('#VAL_DESCONTO').val());
								
					if(val_totprodu >= val_desconto){
						val_totprodu = val_totprodu - val_desconto;
					}else{
						$('#VAL_DESCONTO').val('0,00');
						$.confirm({
							icon: 'fa fa-warning',
							title: 'Atenção',
							content: 'Valor total dos produtos menor que valor de desconto. Desconto será zerado.',
							type: 'orange',
							typeAnimated: true,
							buttons: {
								ok: function () {
								}
							}							
						});						
					}					
										
					$('#total_da_venda, .total_da_venda').unmask();
					$('#total_da_venda').text(val_totprodu.toFixed(2));	
					$('.total_da_venda').val(val_totprodu.toFixed(2));
					$('#total_da_venda, .total_da_venda').mask("#.##0,00", {reverse: true});
						
				},
				error:function(){
					$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
	</script>	